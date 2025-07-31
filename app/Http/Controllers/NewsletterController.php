<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Services\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->newsletterService = $newsletterService;
    }

    /**
     * Inscription à la newsletter
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'nom' => 'nullable|string|max:255',
            'preferences' => 'required|array|min:1',
            'preferences.*' => 'in:actualites,publications,rapports,evenements',
            'redirect_url' => 'nullable|url'
        ]);

        try {
            // Préparer les préférences
            $preferences = [];
            foreach(['actualites', 'publications', 'rapports', 'evenements'] as $type) {
                $preferences[$type] = in_array($type, $request->preferences);
            }

            // Créer ou récupérer l'abonné
            $newsletter = Newsletter::firstOrCreate(
                ['email' => $request->email],
                [
                    'nom' => $request->nom,
                    'token' => Str::random(64),
                    'actif' => true,
                    'confirme_a' => now(),
                    'preferences' => $preferences
                ]
            );

            // Si l'abonné existe déjà, mettre à jour ses préférences
            if (!$newsletter->wasRecentlyCreated) {
                $newsletter->updatePreferences($preferences);
                $newsletter->update(['actif' => true]);
            }

            // Envoyer l'email de bienvenue pour les nouveaux abonnés
            if ($newsletter->wasRecentlyCreated) {
                $this->newsletterService->sendWelcomeEmail($newsletter);
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
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return view('newsletter.preferences-error', [
                'error' => 'Token invalide ou abonnement non trouvé.'
            ]);
        }

        return view('newsletter.preferences', compact('newsletter'));
    }

    /**
     * Mettre à jour les préférences
     */
    public function updatePreferences(Request $request, $token)
    {
        $request->validate([
            'nom' => 'nullable|string|max:255',
            'preferences' => 'array',
            'preferences.*' => 'boolean'
        ]);

        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return redirect()->back()->with('error', 'Token invalide.');
        }

        try {
            // Mettre à jour le nom si fourni
            if ($request->has('nom')) {
                $newsletter->update([
                    'nom' => $request->nom ?: null
                ]);
            }

            $preferences = [
                'actualites' => $request->has('preferences.actualites'),
                'publications' => $request->has('preferences.publications'),
                'rapports' => $request->has('preferences.rapports'),
                'evenements' => $request->has('preferences.evenements')
            ];

            if ($this->newsletterService->updatePreferences($newsletter, $preferences)) {
                return redirect()->back()->with('success', 'Vos informations et préférences ont été mises à jour avec succès.');
            }

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour.');
        }
    }

    /**
     * Afficher la page de désabonnement
     */
    public function unsubscribe($token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return view('newsletter.unsubscribe-error', [
                'error' => 'Token invalide ou abonnement non trouvé.'
            ]);
        }
        
        if (!$newsletter->actif) {
            return view('newsletter.already-unsubscribed', [
                'newsletter' => $newsletter
            ]);
        }
        
        return view('newsletter.unsubscribe', [
            'newsletter' => $newsletter
        ]);
    }

    /**
     * Traiter le désabonnement
     */
    public function confirmUnsubscribe(Request $request, $token)
    {
        $request->validate([
            'reasons' => 'nullable|array',
            'reasons.*' => 'in:too_many_emails,not_relevant,not_interested,technical_issues,other',
            'comment' => 'nullable|string|max:500'
        ]);

        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return redirect()->route('newsletter.unsubscribe', $token)
                ->with('error', 'Token invalide.');
        }

        // Préparer les données de désabonnement
        $reasons = $request->input('reasons', []);
        $comment = $request->input('comment');
        
        // Conserver l'ancien commentaire s'il existe et ajouter le nouveau
        $finalReason = $comment;
        if ($newsletter->unsubscribe_reason && $comment) {
            $finalReason = $newsletter->unsubscribe_reason . ' | ' . $comment;
        } elseif ($newsletter->unsubscribe_reason && !$comment) {
            $finalReason = $newsletter->unsubscribe_reason;
        }
        
        if ($this->newsletterService->unsubscribe($newsletter, $finalReason, $reasons)) {
            return view('newsletter.unsubscribe', ['newsletter' => $newsletter])
                ->with('success', 'Votre désabonnement a été confirmé. Merci pour vos retours, ils nous aideront à améliorer notre service.');
        }

        return redirect()->back()->with('error', 'Une erreur est survenue lors du désabonnement.');
    }

    /**
     * Réabonner un utilisateur
     */
    public function resubscribe($token)
    {
        $newsletter = Newsletter::where('token', $token)->first();
        
        if (!$newsletter) {
            return view('newsletter.preferences-error', [
                'error' => 'Token invalide ou abonnement non trouvé.'
            ]);
        }
        
        if ($newsletter->actif) {
            return redirect()->route('newsletter.preferences', $token)
                ->with('info', 'Vous êtes déjà abonné à notre newsletter.');
        }
        
        $newsletter->update([
            'actif' => true,
            'unsubscribe_reason' => null,
            'unsubscribe_reasons' => null,
            'unsubscribed_at' => null
        ]);
        
        return redirect()->route('newsletter.preferences', $token)
            ->with('success', 'Vous êtes de nouveau abonné à notre newsletter !');
    }
}
