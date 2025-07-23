<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Models\NewsletterPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    /**
     * Inscription à la newsletter
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'nom' => 'nullable|string|max:255',
            'preferences' => 'required|array|min:1',
            'preferences.*' => 'in:publications,actualites,projets',
            'redirect_url' => 'nullable|url'
        ]);

        try {
            // Créer ou récupérer l'abonné
            $newsletter = Newsletter::firstOrCreate(
                ['email' => $request->email],
                [
                    'nom' => $request->nom,
                    'token' => Str::random(64),
                    'actif' => true,
                    'confirme_a' => now()
                ]
            );

            // Mettre à jour les préférences
            NewsletterPreference::where('newsletter_id', $newsletter->id)->delete();
            
            foreach ($request->preferences as $preference) {
                NewsletterPreference::create([
                    'newsletter_id' => $newsletter->id,
                    'type' => $preference,
                    'actif' => true
                ]);
            }

            // Déterminer l'URL de redirection
            $redirectUrl = $request->redirect_url ?: url()->previous();
            
            return redirect()->to($redirectUrl)->with('success', 'Inscription réussie ! Vous recevrez désormais nos newsletters selon vos préférences.');

        } catch (\Exception $e) {
            $redirectUrl = $request->redirect_url ?: url()->previous();
            return redirect()->to($redirectUrl)->with('error', 'Une erreur est survenue lors de l\'inscription.');
        }
    }

    /**
     * Afficher la page de gestion des préférences
     */
    public function preferences($token)
    {
        $newsletter = Newsletter::where('token', $token)->firstOrFail();
        
        $preferences = $newsletter->preferences()
            ->where('actif', true)
            ->pluck('type')
            ->toArray();

        return view('newsletter.preferences', compact('newsletter', 'preferences'));
    }

    /**
     * Mettre à jour les préférences
     */
    public function updatePreferences(Request $request, $token)
    {
        $request->validate([
            'preferences' => 'nullable|array',
            'preferences.*' => 'in:publications,actualites,projets'
        ]);

        $newsletter = Newsletter::where('token', $token)->firstOrFail();

        try {
            // Supprimer toutes les préférences actuelles
            NewsletterPreference::where('newsletter_id', $newsletter->id)->delete();
            
            // Créer les nouvelles préférences
            if ($request->has('preferences')) {
                foreach ($request->preferences as $preference) {
                    NewsletterPreference::create([
                        'newsletter_id' => $newsletter->id,
                        'type' => $preference,
                        'actif' => true
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Vos préférences ont été mises à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    /**
     * Se désabonner complètement
     */
    public function unsubscribe($token)
    {
        $newsletter = Newsletter::where('token', $token)->firstOrFail();
        
        $newsletter->update(['actif' => false]);
        NewsletterPreference::where('newsletter_id', $newsletter->id)->delete();

        return redirect()->route('site.home')->with('success', 'Vous avez été désabonné avec succès.');
    }
}
