<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Désabonnement confirmé - Newsletter GRN-UCBC</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-md">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <div class="w-16 h-16 bg-green-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Désabonnement confirmé</h1>
            
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-700">Vous avez été désabonné(e) avec succès de la newsletter GRN-UCBC.</p>
            </div>
            
            <p class="text-gray-600 mb-6">
                Nous sommes désolés de vous voir partir. Si vous changez d'avis, vous pouvez vous réabonner à tout moment en utilisant le formulaire d'inscription sur notre site web.
            </p>
            
            <div class="space-y-3">
                <a href="{{ route('newsletter.preferences', $newsletter->token) }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                    Se réabonner
                </a>
                
                <a href="{{ route('site.home') }}" 
                   class="block w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                    Retour au site
                </a>
            </div>
        </div>
    </div>
</body>
</html>
