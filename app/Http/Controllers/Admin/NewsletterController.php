<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = Newsletter::with('preferences');
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('statut')) {
            $statut = $request->get('statut');
            if ($statut === 'actif') {
                $query->where('actif', true);
            } elseif ($statut === 'inactif') {
                $query->where('actif', false);
            }
        }
        
        $newsletters = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistiques
        $stats = [
            'total' => Newsletter::count(),
            'actifs' => Newsletter::where('actif', true)->count(),
            'inactifs' => Newsletter::where('actif', false)->count(),
            'confirmes' => Newsletter::whereNotNull('confirme_a')->count(),
        ];
        
        return view('admin.newsletter.index', compact('newsletters', 'stats'));
    }

    public function show(Newsletter $newsletter)
    {
        $newsletter->load('preferences');
        return view('admin.newsletter.show', compact('newsletter'));
    }
    
    public function toggle(Newsletter $newsletter)
    {
        $newsletter->update(['actif' => !$newsletter->actif]);
        
        $status = $newsletter->actif ? 'activé' : 'désactivé';
        return redirect()->back()
                        ->with('success', "Abonné {$status} avec succès.");
    }
    
    public function export(Request $request)
    {
        $query = Newsletter::with('preferences');
        
        // Appliquer les mêmes filtres que l'index
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('nom', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('statut')) {
            $statut = $request->get('statut');
            if ($statut === 'actif') {
                $query->where('actif', true);
            } elseif ($statut === 'inactif') {
                $query->where('actif', false);
            }
        }
        
        $newsletters = $query->get();
        
        $csv = "Email,Nom,Statut,Date d'inscription,Publications,Actualités,Projets\n";
        
        foreach ($newsletters as $newsletter) {
            $preferences = $newsletter->preferences->pluck('type')->toArray();
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $newsletter->email,
                $newsletter->nom ?? '',
                $newsletter->actif ? 'Actif' : 'Inactif',
                $newsletter->created_at->format('d/m/Y'),
                in_array('publications', $preferences) ? 'Oui' : 'Non',
                in_array('actualites', $preferences) ? 'Oui' : 'Non',
                in_array('projets', $preferences) ? 'Oui' : 'Non'
            );
        }
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="newsletters_' . date('Y-m-d') . '.csv"');
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->preferences()->delete();
        $newsletter->delete();
        
        return redirect()->route('admin.newsletter.index')
                        ->with('success', 'Abonné supprimé avec succès.');
    }
}
