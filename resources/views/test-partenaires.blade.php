<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Partenaires Dynamiques</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .partners-section {
            background: #f8f9fa;
            padding: 40px 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .partners-container {
            overflow: hidden;
            position: relative;
            width: 100%;
        }
        
        .partners-scroll {
            display: flex;
            animation: scroll-infinite 30s linear infinite;
            gap: 30px;
            width: fit-content;
        }
        
        .partners-scroll:hover {
            animation-play-state: paused;
        }
        
        .partner-logo {
            height: 64px;
            object-fit: contain;
            flex-shrink: 0;
            filter: grayscale(50%);
            transition: filter 0.3s ease;
        }
        
        .partner-logo:hover {
            filter: grayscale(0%);
        }
        
        @keyframes scroll-infinite {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .debug-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-family: monospace;
            font-size: 14px;
        }
        
        h1, h2 {
            color: #333;
            text-align: center;
        }
        
        .count {
            color: #007bff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Test des Partenaires Dynamiques - IRI-UCBC</h1>
        
        <div class="debug-info">
            <strong>Informations de debug :</strong><br>
            Nombre de partenaires récupérés : <span class="count">{{ $partenaires->count() }}</span><br>
            @if($partenaires->count() > 0)
                Partenaires affichés :
                <ul>
                    @foreach($partenaires as $partenaire)
                        <li>{{ $partenaire->nom }} - Logo: {{ $partenaire->logo }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        
        <div class="partners-section">
            <h2>Nos Partenaires</h2>
            @if($partenaires->count() > 0)
                <div class="partners-container">
                    <div class="partners-scroll">
                        <!-- Première série de logos -->
                        @foreach($partenaires as $partenaire)
                            <img src="{{ $partenaire->logo_url }}" 
                                 alt="{{ $partenaire->nom }}" 
                                 title="{{ $partenaire->nom }}"
                                 class="partner-logo"
                                 onerror="console.log('Erreur chargement logo: {{ $partenaire->logo_url }}')"/>
                        @endforeach
                        <!-- Duplication pour boucle fluide -->
                        @foreach($partenaires as $partenaire)
                            <img src="{{ $partenaire->logo_url }}" 
                                 alt="{{ $partenaire->nom }}" 
                                 title="{{ $partenaire->nom }}"
                                 class="partner-logo"
                                 onerror="console.log('Erreur chargement logo: {{ $partenaire->logo_url }}')"/>
                        @endforeach
                    </div>
                </div>
            @else
                <div style="text-align: center; color: #666; padding: 40px;">
                    <p>Aucun partenaire avec logo disponible pour le moment.</p>
                </div>
            @endif
        </div>
        
        <div style="text-align: center; margin-top: 30px; color: #666; font-size: 14px;">
            <p>Test effectué le {{ date('d/m/Y à H:i:s') }}</p>
        </div>
    </div>
</body>
</html>
