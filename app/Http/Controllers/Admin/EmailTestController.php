<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class EmailTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:viewAny,App\Models\User')->only(['index']); // Seuls les admins peuvent tester les emails
    }

    /**
     * Afficher la page de test d'email
     */
    public function index()
    {
        $config = [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'username' => config('mail.mailers.smtp.username'),
            'from_address' => config('mail.from.address'),
            'from_name' => config('mail.from.name'),
        ];

        return view('admin.email-test.index', compact('config'));
    }

    /**
     * Envoyer un email de test
     */
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            Mail::raw($request->message, function (Message $message) use ($request) {
                $message->to($request->email)
                        ->subject($request->subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            return response()->json([
                'success' => true,
                'message' => 'Email envoyé avec succès à ' . $request->email
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tester la connexion SMTP
     */
    public function testConnection()
    {
        try {
            // Test simple de la configuration
            $transport = Mail::getSwiftMailer()->getTransport();
            
            return response()->json([
                'success' => true,
                'message' => 'Configuration SMTP valide',
                'config' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'username' => config('mail.mailers.smtp.username'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de configuration : ' . $e->getMessage()
            ], 500);
        }
    }
}
