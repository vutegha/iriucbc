<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'IRI-UCBC')</title>
    <style>
        /* Reset et styles de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        /* Header avec couleurs IRI-UCBC */
        .email-header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .logo-container {
            margin-bottom: 20px;
        }
        
        .logo-text {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }
        
        .tagline {
            font-size: 15px;
            opacity: 0.95;
            line-height: 1.4;
            font-weight: 500;
        }
        
        /* Contenu principal */
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 20px;
            margin-bottom: 25px;
            color: #2563eb;
            font-weight: 600;
        }
        
        .content-text {
            font-size: 16px;
            line-height: 1.8;
            margin-bottom: 22px;
            color: #4b5563;
        }
        
        /* Boutons améliorés */
        .btn {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white !important;
            text-decoration: none;
            border-radius: 30px;
            font-weight: 600;
            text-align: center;
            margin: 12px 8px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            font-size: 15px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.3);
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%);
            border: 2px solid #64748b;
        }
        
        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569 0%, #334155 100%);
            box-shadow: 0 8px 25px rgba(100, 116, 139, 0.3);
        }
        
        /* Section publication */
        .publication-card {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
        }
        
        .publication-title {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        
        .publication-meta {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 15px;
        }
        
        .publication-excerpt {
            font-size: 15px;
            line-height: 1.7;
            color: #4b5563;
        }
        
        /* Footer */
        .email-footer {
            background-color: #f3f4f6;
            padding: 30px 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        
        .footer-links {
            margin-bottom: 20px;
        }
        
        .footer-links a {
            color: #2563eb;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .footer-text {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
        }
        
        .unsubscribe-link {
            color: #9ca3af;
            text-decoration: none;
            font-size: 11px;
        }
        
        /* Responsive */
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .email-content {
                padding: 30px 20px;
            }
            
            .btn {
                display: block;
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo-container">
                <div class="logo-text">IRI-UCBC</div>
                <div class="tagline">Institut de Recherche Intégré à l'Université Chrétienne Bilingue du Congo</div>
            </div>
        </div>
        
        <!-- Contenu principal -->
        <div class="email-content">
            @yield('content')
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-links">
                <a href="{{ config('app.url') }}">Site Web</a>
                <a href="{{ config('app.url') }}/actualites">Actualités</a>
                <a href="{{ config('app.url') }}/publications">Publications</a>
                <a href="{{ config('app.url') }}/contact">Contact</a>
            </div>
            
            <div class="footer-text">
                <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5;">
                  <p style="margin: 0; font-weight: bold;">Institut de Recherche Intégré</p>
                  <p style="margin: 0;">Congo Initiative – Université Chrétienne Bilingue du Congo</p>
                  <p style="margin: 0;">Site web : <a href="https://www.iriucbc.org" style="color: #2563eb;">www.iriucbc.org</a></p>
                  <p style="margin: 0;">Email : <a href="mailto:iri@ucbc.org" style="color: #2563eb;">iri@ucbc.org</a></p>
                  
                  <br />
                  <p style="margin: 0; font-weight: bold;">Nos adresses :</p>
                  
                  <div style="display: flex; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 10px;">
                    <div style="flex: 1; min-width: 180px; margin-bottom: 15px;">
                      <p style="margin: 0; font-weight: bold; text-decoration: underline; color: #2563eb;">Siège social – Nord-Kivu</p>
                      <p style="margin: 0; font-size: 13px; line-height: 1.4;">
                        Ville de Beni, Commune Mulekera,<br>
                        Quartier Masiani, Avenue de l'Université,<br>
                        Cellule Kipriani
                      </p>
                    </div>
                    
                    <div style="flex: 1; min-width: 180px; margin-bottom: 15px;">
                      <p style="margin: 0; font-weight: bold; text-decoration: underline; color: #2563eb;">Bureau de liaison – Tanganyika</p>
                      <p style="margin: 0; font-size: 13px; line-height: 1.4;">
                        Ville de Kalemie, Commune de Lukuga,<br>
                        Quartier Kitali II, Avenue Industrielle
                      </p>
                    </div>
                    
                    <div style="flex: 1; min-width: 180px; margin-bottom: 15px;">
                      <p style="margin: 0; font-weight: bold; text-decoration: underline; color: #2563eb;">Bureau de liaison – Ituri</p>
                      <p style="margin: 0; font-size: 13px; line-height: 1.4;">
                        Ville de Bunia, Commune Mbunia,<br>
                        Quartier Bankoko, Avenue Maniema
                      </p>
                    </div>
                  </div>
                </div>
            </div>
            
            @if(isset($unsubscribeUrl))
            <div style="margin-top: 20px;">
                <a href="{{ $unsubscribeUrl }}" class="unsubscribe-link">
                    Se désabonner de cette newsletter
                </a>
            </div>
            @endif
            
            @if(isset($preferencesUrl))
            <div style="margin-top: 10px;">
                <a href="{{ $preferencesUrl }}" class="unsubscribe-link">
                    Gérer mes préférences d'email
                </a>
            </div>
            @endif
        </div>
    </div>
</body>
</html>
