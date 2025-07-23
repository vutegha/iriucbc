<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Publication - IRI-UCBC</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ee6751, #505c10); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin: -20px -20px 20px -20px; }
        .content { padding: 20px 0; }
        .button { display: inline-block; background: #ee6751; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .footer { background: #f8f9fa; padding: 15px; margin: 20px -20px -20px -20px; border-radius: 0 0 10px 10px; font-size: 12px; color: #666; }
        .unsubscribe { font-size: 11px; color: #999; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Nouvelle Publication</h1>
            <p>Institut de Recherche Interdisciplinaire - UCBC</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $subscriber->nom ?? 'Cher abonné' }},</p>
            
            <p>Nous avons le plaisir de vous annoncer la publication de :</p>
            
            <h2 style="color: #ee6751;">{{ $publication->titre }}</h2>
            
            @if($publication->resume)
                <p><strong>Résumé :</strong></p>
                <p style="background: #f8f9fa; padding: 15px; border-left: 4px solid #ee6751; margin: 15px 0;">
                    {{ $publication->resume }}
                </p>
            @endif

            @if($publication->auteurs && $publication->auteurs->count() > 0)
                <p><strong>Auteur(s) :</strong> 
                    {{ $publication->auteurs->pluck('nom')->join(', ') }}
                </p>
            @endif

            @if($publication->categorie)
                <p><strong>Catégorie :</strong> {{ $publication->categorie->nom }}</p>
            @endif

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ route('site.home') }}" class="button">Consulter sur notre site</a>
            </p>
        </div>

        <div class="footer">
            <p><strong>Institut de Recherche Interdisciplinaire - UCBC</strong></p>
            <p>Université Catholique de Bukavu</p>
            
            <div class="unsubscribe">
                <p>
                    <a href="{{ $preferencesUrl }}">Gérer mes préférences</a> | 
                    <a href="{{ route('newsletter.unsubscribe', $subscriber->token) }}">Se désabonner</a>
                </p>
                <p>Vous recevez cet email car vous êtes abonné à nos notifications de publications.</p>
            </div>
        </div>
    </div>
</body>
</html>
