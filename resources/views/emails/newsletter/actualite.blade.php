<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Actualité - IRI-UCBC</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #ee6751, #505c10); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; margin: -20px -20px 20px -20px; }
        .content { padding: 20px 0; }
        .button { display: inline-block; background: #ee6751; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
        .footer { background: #f8f9fa; padding: 15px; margin: 20px -20px -20px -20px; border-radius: 0 0 10px 10px; font-size: 12px; color: #666; }
        .unsubscribe { font-size: 11px; color: #999; margin-top: 10px; }
        .image { max-width: 100%; height: auto; border-radius: 8px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📰 Nouvelle Actualité</h1>
            <p>Institut de Recherche Intégré à l'Université Chrétienne Bilingue du Congo</p>
        </div>

        <div class="content">
            <p>Bonjour {{ $subscriber->nom ?? 'Cher abonné' }},</p>
            
            <p>Nous souhaitons vous informer de cette actualité importante :</p>
            
            <h2 style="color: #ee6751;">{{ $actualite->titre }}</h2>
            
            @if($actualite->image)
                <img src="{{ asset('storage/' . $actualite->image) }}" alt="{{ $actualite->titre }}" class="image">
            @endif

            @if($actualite->resume)
                <p><strong>Résumé :</strong></p>
                <p style="background: #f8f9fa; padding: 15px; border-left: 4px solid #ee6751; margin: 15px 0;">
                    {{ $actualite->resume }}
                </p>
            @endif

            @if($actualite->texte)
                <div style="margin: 20px 0;">
                    {!! Str::limit($actualite->texte, 300) !!}
                    @if(strlen($actualite->texte) > 300)
                        <p><em>... <a href="{{ route('site.home') }}">Lire la suite sur notre site</a></em></p>
                    @endif
                </div>
            @endif

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ route('site.home') }}" class="button">Consulter sur notre site</a>
            </p>
        </div>

        <div class="footer">
            <p><strong>Institut de Recherche Intégré à l'Université Chrétienne Bilingue du Congo</strong></p>
            <p>Congo Initiative-Université Chrétienne Bilingue du Congo</p>
            
            <div class="unsubscribe">
                <p>
                    <a href="{{ $preferencesUrl }}">Gérer mes préférences</a> | 
                    <a href="{{ route('newsletter.unsubscribe', $subscriber->token) }}">Se désabonner</a>
                </p>
                <p>Vous recevez cet email car vous êtes abonné à nos notifications d'actualités.</p>
            </div>
        </div>
    </div>
</body>
</html>
