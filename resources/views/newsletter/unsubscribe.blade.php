<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Désabonnement - Newsletter IRI-UCBC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --coral: #ee6751;
            --olive: #505c10;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-md">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 text-center">
            <div class="w-16 h-16 bg-gray-400 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 21l-5-5m7.071-7.071L21 3l-5 5"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Désabonnement</h1>
            <p class="text-gray-600">IRI-UCBC - Université Catholique de Bukavu</p>
        </div>

        @if(session('success'))
            <!-- Confirmation de désabonnement -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6 text-center">
                <div class="w-12 h-12 bg-green-500 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-green-800 mb-2">Désabonnement confirmé</h2>
                <p class="text-green-700 mb-4">{{ session('success') }}</p>
                <p class="text-sm text-green-600">Vous ne recevrez plus d'emails de notre part.</p>
            </div>
        @else
            <!-- Formulaire de confirmation -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Confirmer le désabonnement</h2>
                <p class="text-gray-600 mb-6">
                    Vous êtes sur le point de vous désabonner de toutes nos notifications email. 
                    Cette action supprimera définitivement votre adresse email de notre liste de diffusion.
                </p>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Attention :</strong> Au lieu de vous désabonner complètement, vous pouvez simplement 
                                <a href="{{ route('newsletter.preferences', $newsletter->token) }}" class="underline text-yellow-800 hover:text-yellow-900">
                                    gérer vos préférences
                                </a> 
                                pour choisir quels types de contenus vous souhaitez recevoir.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h3 class="font-medium text-gray-800 mb-2">Informations de l'abonné :</h3>
                    <p class="text-sm text-gray-600">Email : {{ $newsletter->email }}</p>
                    @if($newsletter->nom)
                        <p class="text-sm text-gray-600">Nom : {{ $newsletter->nom }}</p>
                    @endif
                    <p class="text-sm text-gray-600">Inscrit le : {{ $newsletter->created_at->format('d/m/Y') }}</p>
                </div>

                <form method="POST" action="{{ route('newsletter.unsubscribe.confirm', $newsletter->token) }}">
                    @csrf
                    
                    <div class="space-y-3">
                        <button type="submit" 
                                onclick="return confirm('Êtes-vous vraiment sûr de vouloir vous désabonner ? Cette action est irréversible.')"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            Confirmer le désabonnement
                        </button>
                        
                        <a href="{{ route('newsletter.preferences', $newsletter->token) }}" 
                           class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                            Gérer mes préférences plutôt
                        </a>
                        
                        <a href="{{ route('site.home') }}" 
                           class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors">
                            Annuler et retourner au site
                        </a>
                    </div>
                </form>
            </div>
        @endif

        <!-- Feedback -->
        @if(!session('success'))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-medium text-blue-800 mb-2">Nous aimerions connaître votre avis</h3>
                <p class="text-sm text-blue-700 mb-3">
                    Si vous vous désabonnez, pourriez-vous nous dire pourquoi ? Cela nous aiderait à améliorer notre service.
                </p>
                <div class="space-y-1 text-xs text-blue-600">
                    <p>• Trop d'emails</p>
                    <p>• Contenu non pertinent</p>
                    <p>• Plus intéressé par nos services</p>
                    <p>• Problème technique</p>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Institut de Recherche Interdisciplinaire - UCBC</p>
            <p>Université Catholique de Bukavu</p>
            <p class="mt-2">
                <a href="mailto:iri@ucbc.org" class="text-blue-500 hover:text-blue-600">iri@ucbc.org</a>
            </p>
        </div>
    </div>
</body>
</html>
