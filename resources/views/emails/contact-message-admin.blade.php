<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
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
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
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
            border-left: 4px solid #2a5298;
        }
        
        .info-label {
            font-weight: 600;
            color: #2a5298;
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
            border-left: 4px solid #28a745;
        }
        
        .message-label {
            font-weight: 600;
            color: #28a745;
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
        
        .alert {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        
        .alert-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background: #ffc107;
            border-radius: 50%;
            text-align: center;
            line-height: 20px;
            color: white;
            font-weight: bold;
            margin-right: 10px;
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
            <h1>📧 Nouveau Message de Contact</h1>
            <p>Un nouveau message a été reçu via le formulaire de contact</p>
        </div>
        
        <div class="content">
            <div class="alert">
                <span class="alert-icon">!</span>
                <strong>Action requise:</strong> Un nouveau message de contact nécessite votre attention. 
                Vous pouvez répondre directement en cliquant sur "Répondre" à cet email.
            </div>
            
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Expéditeur</div>
                    <div class="info-value">{{ $contact->nom }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $contact->email }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Sujet</div>
                    <div class="info-value">{{ $contact->sujet }}</div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Date de réception</div>
                    <div class="info-value">{{ $contact->created_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
            
            <div class="message-section">
                <div class="message-label">Message reçu</div>
                <div class="message-content">{{ $contact->message }}</div>
            </div>
            
            <div style="background: #e3f2fd; padding: 20px; border-radius: 8px; border-left: 4px solid #2196f3;">
                <h4 style="margin: 0 0 10px 0; color: #1976d2;">📋 Actions recommandées</h4>
                <ul style="margin: 0; padding-left: 20px; color: #424242;">
                    <li>Répondre dans les 24 heures pour maintenir un bon service client</li>
                    <li>Vérifier si ce contact existe déjà dans votre base de données</li>
                    <li>Marquer ce message comme traité une fois la réponse envoyée</li>
                    <li>Considérer l'ajout automatique à la newsletter (déjà fait)</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>Institut de Recherche Intégré à l'Université Chrétienne Bilingue du Congo</strong></p>
            <p>Email automatique généré par le système de contact du site web</p>
            <p style="color: #adb5bd; font-size: 12px;">
                ID du message: #{{ $contact->id }} | 
                IP: {{ request()->ip() ?? 'N/A' }} | 
                User Agent: {{ Str::limit(request()->userAgent() ?? 'N/A', 50) }}
            </p>
        </div>
    </div>
</body>
</html>
