<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copie de votre message</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .email-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .content {
            padding: 30px;
        }
        
        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 6px;
            padding: 20px;
            margin-bottom: 25px;
            color: #155724;
            text-align: center;
        }
        
        .success-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }
        
        .info-label {
            font-weight: 600;
            color: #28a745;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 16px;
            color: #333;
        }
        
        .message-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 4px solid #007bff;
        }
        
        .message-label {
            font-weight: 600;
            color: #007bff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .message-content {
            background: white;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            white-space: pre-line;
            line-height: 1.8;
        }
        
        .next-steps {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .next-steps h4 {
            margin: 0 0 15px 0;
            color: #856404;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer p {
            margin: 5px 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .contact-info {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
            margin: 25px 0;
        }
        
        .contact-info h4 {
            margin: 0 0 15px 0;
            color: #1976d2;
        }
        
        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            body {
                padding: 10px;
            }
            
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>✅ Message Bien Reçu</h1>
            <p>Copie de confirmation de votre message</p>
        </div>
        
        <div class="content">
            <div class="success-message">
                <div class="success-icon">📬</div>
                <h3 style="margin: 0 0 10px 0;">Merci pour votre message !</h3>
                <p style="margin: 0;">
                    Nous avons bien reçu votre message et vous répondrons dans les plus brefs délais.
                    Voici une copie de ce que vous nous avez envoyé.
                </p>
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Votre nom</div>
                    <div class="info-value">{{ $contact->nom }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Votre email</div>
                    <div class="info-value">{{ $contact->email }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Sujet</div>
                    <div class="info-value">{{ $contact->sujet }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Date d'envoi</div>
                    <div class="info-value">{{ $contact->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
            
            <div class="message-section">
                <div class="message-label">Votre message</div>
                <div class="message-content">{{ $contact->message }}</div>
            </div>
            
            <div class="next-steps">
                <h4>⏰ Prochaines étapes</h4>
                <ul style="margin: 0; padding-left: 20px; color: #856404;">
                    <li><strong>Délai de réponse:</strong> Nous vous répondrons sous 24-48 heures</li>
                    <li><strong>Vérification email:</strong> Surveillez votre boîte de réception (et spam)</li>
                    <li><strong>Newsletter:</strong> Vous avez été automatiquement ajouté à notre liste de diffusion</li>
                    <li><strong>Urgence:</strong> Pour les demandes urgentes, contactez-nous par téléphone</li>
                </ul>
            </div>
            
            <div class="contact-info">
                <h4>📞 Autres moyens de nous contacter</h4>
                <div style="color: #424242;">
                    <p><strong>Téléphone:</strong> +243 XX XXX XXXX</p>
                    <p><strong>Email:</strong> grn@ucbc.org</p>
                    <p><strong>Adresse:</strong> Université Catholique du Congo, Kinshasa</p>
                    <p><strong>Site web:</strong> <a href="{{ url('/') }}" style="color: #1976d2;">{{ url('/') }}</a></p>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Centre de Gouvernance des Ressources Naturelles - Université Chrétienne Bilingue du Congo</strong></p>
            <p>Merci de nous avoir contactés. Nous apprécions votre intérêt pour nos activités.</p>
            <p style="color: #adb5bd; font-size: 12px;">
                Ceci est un email automatique de confirmation. Ne pas répondre à cette adresse.
            </p>
        </div>
    </div>
</body>
</html>
