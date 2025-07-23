<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des préférences - Newsletter IRI-UCBC</title>
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
            <div class="w-16 h-16 bg-gradient-to-br from-red-400 to-green-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 17h-7a2 2 0 01-2-2V5a2 2 0 012-2h14a2 2 0 012 2v7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Préférences Newsletter</h1>
            <p class="text-gray-600">IRI-UCBC - Université Catholique de Bukavu</p>
        </div>

        <!-- Messages de feedback -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r-lg mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Informations abonné -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Informations de l'abonné</h2>
            <div class="space-y-2">
                <p><span class="font-medium">Email :</span> {{ $newsletter->email }}</p>
                @if($newsletter->nom)
                    <p><span class="font-medium">Nom :</span> {{ $newsletter->nom }}</p>
                @endif
                <p><span class="font-medium">Inscrit le :</span> {{ $newsletter->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Formulaire de préférences -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Vos préférences de notification</h2>
            <p class="text-gray-600 mb-6 text-sm">Choisissez les types de contenus pour lesquels vous souhaitez recevoir des notifications par email.</p>
            
            <form method="POST" action="{{ route('newsletter.preferences.update', $newsletter->token) }}">
                @csrf
                
                <div class="space-y-4">
                    @foreach(App\Models\NewsletterPreference::TYPES as $type => $label)
                        <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                            <input type="checkbox" 
                                   name="preferences[]" 
                                   value="{{ $type }}"
                                   {{ in_array($type, $preferences) ? 'checked' : '' }}
                                   class="w-4 h-4 text-red-500 bg-gray-100 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                            <div class="ml-3 flex-1">
                                <div class="font-medium text-gray-800">{{ $label }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($type === 'publications')
                                        Recevez les notifications pour les nouvelles publications académiques
                                    @elseif($type === 'actualites')
                                        Recevez les notifications pour les actualités importantes
                                    @elseif($type === 'projets')
                                        Recevez les notifications pour les nouveaux projets de recherche
                                    @endif
                                </div>
                            </div>
                            <div class="text-2xl">
                                @if($type === 'publications')
                                    📚
                                @elseif($type === 'actualites')
                                    📰
                                @elseif($type === 'projets')
                                    🚀
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="mt-6 space-y-3">
                    <button type="submit" 
                            class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                        Enregistrer mes préférences
                    </button>
                    
                    <a href="{{ route('site.home') }}" 
                       class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors">
                        Retour au site
                    </a>
                </div>
            </form>
        </div>

        <!-- Zone de désabonnement -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-800 mb-2">Se désabonner complètement</h3>
            <p class="text-red-700 text-sm mb-4">
                Si vous ne souhaitez plus recevoir aucune notification de notre part, vous pouvez vous désabonner complètement.
            </p>
            <a href="{{ route('newsletter.unsubscribe', $newsletter->token) }}" 
               onclick="return confirm('Êtes-vous sûr de vouloir vous désabonner de toutes nos notifications ?')"
               class="inline-block bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm">
                Me désabonner
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-500 text-sm mt-8">
            <p>© {{ date('Y') }} Institut de Recherche Interdisciplinaire - UCBC</p>
            <p>Université Catholique de Bukavu</p>
        </div>
    </div>
</body>
</html>
