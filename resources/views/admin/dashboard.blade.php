@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Vue d\'ensemble de l\'administration')

@section('content')

<!-- Statistiques principales -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Statistique Publications -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-coral bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-coral text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Publications</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['publications'] }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <a href="{{ route('admin.publication.index') }}" class="text-coral hover:underline">
                Gérer les publications
            </a>
        </div>
    </div>

    <!-- Statistique Actualités -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-olive bg-opacity-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-newspaper text-olive text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Actualités</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['actualites'] }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <a href="{{ route('admin.actualite.index') }}" class="text-olive hover:underline">
                Gérer les actualités
            </a>
        </div>
    </div>

    <!-- Statistique Projets -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-project-diagram text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Projets</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['projets'] }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium">
                {{ $statsProjects['projets_en_cours'] }} en cours
            </span>
            <span class="text-gray-500 ml-1">• {{ $statsProjects['projets_termines'] }} terminés</span>
        </div>
    </div>

    <!-- Statistique Événements -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-event text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Événements</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['evenements'] }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium">
                {{ $statsEvenements['evenements_a_venir'] }} à venir
            </span>
            <span class="text-gray-500 ml-1">• {{ $statsEvenements['evenements_passes'] }} passés</span>
        </div>
    </div>
</div>

<!-- Statistiques détaillées des projets -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-users text-iri-accent mr-2"></i>
                Impact des Projets
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <div class="text-2xl font-bold text-iri-primary">{{ number_format($statsProjects['total_beneficiaires']) }}</div>
                    <div class="text-sm text-gray-600">Total Bénéficiaires</div>
                </div>
                <div class="text-center p-4 bg-pink-50 rounded-lg">
                    <div class="text-2xl font-bold text-pink-600">{{ number_format($statsProjects['beneficiaires_femmes']) }}</div>
                    <div class="text-sm text-gray-600">Femmes</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ number_format($statsProjects['beneficiaires_hommes']) }}</div>
                    <div class="text-sm text-gray-600">Hommes</div>
                </div>
            </div>
            
            @if($statsProjects['total_beneficiaires'] > 0)
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Répartition par genre</span>
                        <span>{{ round(($statsProjects['beneficiaires_femmes'] / $statsProjects['total_beneficiaires']) * 100) }}% Femmes</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-pink-400 to-pink-600 h-2 rounded-full" 
                             style="width: {{ ($statsProjects['beneficiaires_femmes'] / $statsProjects['total_beneficiaires']) * 100 }}%"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- Prochains événements -->
        @if(optional($prochainsEvenements)->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calendar-check text-green-600 mr-2"></i>
                    Prochains Événements
                </h3>
                <div class="space-y-3">
                    @foreach($prochainsEvenements as $evenement)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <span class="text-xs font-bold text-green-600">
                                    {{ \Carbon\Carbon::parse($evenement->date_debut)->format('M') }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $evenement->titre }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($evenement->date_debut)->format('d/m/Y à H:i') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.evenements.index') }}" class="text-green-600 hover:underline text-sm">
                        Voir tous les événements →
                    </a>
                </div>
            </div>
        @endif
        
        <!-- Messages non lus -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-envelope text-blue-600 mr-2"></i>
                Messages
            </h3>
            @php $unreadCount = optional(\App\Models\Contact::where('is_read', false))->count() ?? 0; @endphp
            @if($unreadCount > 0)
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <div class="text-2xl font-bold text-red-600">{{ $unreadCount }}</div>
                    <div class="text-sm text-gray-600">Messages non lus</div>
                </div>
            @else
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">✓</div>
                    <div class="text-sm text-gray-600">Tous les messages sont lus</div>
                </div>
            @endif
            <div class="mt-4">
                <a href="{{ route('admin.contacts.index') }}" class="text-blue-600 hover:underline text-sm">
                    Gérer les messages →
                </a>
            </div>
        </div>
    </div>
</div>

</div>

<!-- Nouvelles statistiques avancées -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Statistique Vues de pages -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Vues totales</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\PageView::count() }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-blue-600 font-medium">
                {{ \App\Models\PageView::getTodayViews() }}
            </span>
            <span class="text-gray-500 ml-1">aujourd'hui</span>
        </div>
    </div>

    <!-- Statistique Téléchargements -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-download text-indigo-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Téléchargements</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\PublicationDownload::getTotalDownloads() }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium">
                +{{ \App\Models\PublicationDownload::getMonthDownloads() }}
            </span>
            <span class="text-gray-500 ml-1">ce mois</span>
        </div>
    </div>

    <!-- Statistique Visiteurs uniques -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-globe text-teal-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Visiteurs uniques</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\VisitorLocation::getTotalUniqueVisitors() }}</p>
            </div>
        </div>
        <div class="mt-4 flex items-center text-sm">
            <span class="text-green-600 font-medium">
                +{{ \App\Models\VisitorLocation::getNewVisitorsThisMonth() }}
            </span>
            <span class="text-gray-500 ml-1">ce mois</span>
        </div>
    </div>

</div>

<!-- Alertes en temps réel -->
@if(optional(\App\Models\Contact::where('is_read', false))->count() > 0)
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm text-yellow-700">
                <strong>{{ optional(\App\Models\Contact::where('is_read', false))->count() ?? 0 }}</strong> nouveau(x) message(s) non lu(s) dans votre messagerie.
            </p>
        </div>
        <div class="ml-3">
            <a href="{{ route('admin.contacts.index') }}" 
               class="inline-flex items-center px-3 py-1 border border-yellow-300 text-xs font-medium rounded text-yellow-700 bg-yellow-100 hover:bg-yellow-200 transition-colors duration-200">
                <i class="fas fa-eye mr-1"></i>
                Voir les messages
            </a>
        </div>
    </div>
</div>
@endif

<!-- Contenu principal du dashboard -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Publications & Actualités à la une -->
    <div class="space-y-6">
        
        <!-- Publications à la une -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        Publications à la une
                    </h3>
                    <a href="{{ route('admin.publication.index') }}" 
                       class="text-sm text-coral hover:text-coral-dark font-medium">
                        Voir tout →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                @forelse(\App\Models\Publication::where('a_la_une', true)->latest()->limit(5)->get() as $publication)
                <div class="p-4 hover:bg-gray-50" x-data="{ loading: false }">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                {{ $publication->title }}
                            </h4>
                            <p class="text-xs text-gray-500">
                                {{ $publication->created_at->format('d/m/Y') }}
                                @if($publication->categorie)
                                    • {{ $publication->categorie->nom }}
                                @endif
                            </p>
                        </div>
                        <div class="ml-4 flex items-center space-x-2">
                            <!-- Toggle à la une -->
                            <button @click="loading = true; fetch('{{ route('admin.publication.toggle-une', $publication) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => location.reload())"
                                    :disabled="loading"
                                    class="p-1 rounded-full hover:bg-yellow-100 text-yellow-600 transition-colors duration-200"
                                    title="Retirer de la une">
                                <i class="fas fa-star text-sm" :class="loading ? 'animate-spin' : ''"></i>
                            </button>
                            <!-- Lien vers édition -->
                            <a href="{{ route('admin.publication.edit', $publication) }}" 
                               class="p-1 rounded-full hover:bg-gray-100 text-gray-600 transition-colors duration-200"
                               title="Modifier">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-star text-gray-300 text-2xl mb-2"></i>
                    <p class="text-sm">Aucune publication à la une</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Actualités à la une -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-newspaper text-olive mr-2"></i>
                        Actualités à la une
                    </h3>
                    <a href="{{ route('admin.actualite.index') }}" 
                       class="text-sm text-coral hover:text-coral-dark font-medium">
                        Voir tout →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                @forelse(\App\Models\Actualite::where('a_la_une', true)->latest()->limit(5)->get() as $actualite)
                <div class="p-4 hover:bg-gray-50" x-data="{ loading: false }">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate">
                                {{ $actualite->title }}
                            </h4>
                            <p class="text-xs text-gray-500">
                                {{ $actualite->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="ml-4 flex items-center space-x-2">
                            <!-- Toggle à la une -->
                            <button @click="loading = true; fetch('{{ route('admin.actualite.toggle-une', $actualite) }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(() => location.reload())"
                                    :disabled="loading"
                                    class="p-1 rounded-full hover:bg-yellow-100 text-yellow-600 transition-colors duration-200"
                                    title="Retirer de la une">
                                <i class="fas fa-star text-sm" :class="loading ? 'animate-spin' : ''"></i>
                            </button>
                            <!-- Lien vers édition -->
                            <a href="{{ route('admin.actualite.edit', $actualite) }}" 
                               class="p-1 rounded-full hover:bg-gray-100 text-gray-600 transition-colors duration-200"
                               title="Modifier">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-newspaper text-gray-300 text-2xl mb-2"></i>
                    <p class="text-sm">Aucune actualité à la une</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Actions rapides & Messages récents -->
    <div class="space-y-6">
        
        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Actions rapides</h3>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4">
                <a href="{{ route('admin.publication.create') }}" 
                   class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-coral border-opacity-50 rounded-lg hover:border-coral hover:bg-coral hover:bg-opacity-5 transition-all duration-200">
                    <i class="fas fa-plus-circle text-coral text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Publication</span>
                </a>
                <a href="{{ route('admin.actualite.create') }}" 
                   class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-olive border-opacity-50 rounded-lg hover:border-olive hover:bg-olive hover:bg-opacity-5 transition-all duration-200">
                    <i class="fas fa-plus-circle text-olive text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Actualité</span>
                </a>
                <a href="{{ route('admin.service.create') }}" 
                   class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-400 border-opacity-50 rounded-lg hover:border-gray-500 hover:bg-gray-50 transition-all duration-200">
                    <i class="fas fa-plus-circle text-gray-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Service</span>
                </a>
                <a href="{{ route('admin.newsletter.index') }}" 
                   class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-green-400 border-opacity-50 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all duration-200">
                    <i class="fas fa-mail-bulk text-green-600 text-2xl mb-2"></i>
                    <span class="text-sm font-medium text-gray-700">Newsletter</span>
                </a>
            </div>
        </div>

        <!-- Messages récents -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-inbox text-blue-600 mr-2"></i>
                        Messages récents
                    </h3>
                    <a href="{{ route('admin.contacts.index') }}" 
                       class="text-sm text-coral hover:text-coral-dark font-medium">
                        Voir tout →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                @php $recentMessages = \App\Models\Contact::latest()->limit(5)->get(); @endphp
                
                @forelse($recentMessages as $message)
                <div class="p-4 hover:bg-gray-50 {{ !$message->is_read ? 'bg-blue-50' : '' }}">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-blue-600">
                                    {{ strtoupper(substr($message->name, 0, 1)) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $message->name }}</p>
                                @if(!$message->is_read)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Non lu
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 truncate">{{ $message->subject }}</p>
                            <p class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.contacts.show', $message) }}" 
                               class="p-1 rounded-full hover:bg-gray-100 text-gray-600 transition-colors duration-200"
                               title="Lire">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500">
                    <i class="fas fa-inbox text-gray-300 text-2xl mb-2"></i>
                    <p class="text-sm">Aucun message</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<!-- Statistiques avancées -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
    
    <!-- Pages les plus consultées -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-chart-line text-purple-600 mr-2"></i>
                Pages les plus consultées
            </h3>
        </div>
        <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
            @forelse(\App\Models\PageView::getMostViewedPages() as $page)
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">
                            {{ $page->page_title ?: 'Page sans titre' }}
                        </h4>
                        <p class="text-xs text-gray-500 truncate">{{ $page->url }}</p>
                    </div>
                    <div class="ml-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            {{ $page->views }} vues
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-chart-line text-gray-300 text-2xl mb-2"></i>
                <p class="text-sm">Aucune donnée de visite</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Publications les plus téléchargées -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-download text-indigo-600 mr-2"></i>
                Publications les plus téléchargées
            </h3>
        </div>
        <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
            @forelse(\App\Models\PublicationDownload::getMostDownloadedPublications() as $publication)
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">
                            {{ $publication->titre }}
                        </h4>
                        <p class="text-xs text-gray-500">ID: {{ $publication->id }}</p>
                    </div>
                    <div class="ml-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $publication->downloads }} DL
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-download text-gray-300 text-2xl mb-2"></i>
                <p class="text-sm">Aucun téléchargement</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Localisation géographique des visiteurs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-globe-americas text-teal-600 mr-2"></i>
                Visiteurs par pays
            </h3>
        </div>
        <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
            @forelse(\App\Models\VisitorLocation::getTopCountries() as $country)
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center">
                            @if($country->country_code)
                                <img src="https://flagcdn.com/16x12/{{ strtolower($country->country_code) }}.png" 
                                     alt="{{ $country->country_name }}" 
                                     class="w-4 h-3 mr-2 rounded">
                            @endif
                            <h4 class="text-sm font-medium text-gray-900">
                                {{ $country->country_name ?: 'Inconnu' }}
                            </h4>
                        </div>
                        <p class="text-xs text-gray-500">{{ $country->country_code }}</p>
                    </div>
                    <div class="ml-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                            {{ $country->total_visits }} visites
                        </span>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                <i class="fas fa-globe-americas text-gray-300 text-2xl mb-2"></i>
                <p class="text-sm">Aucune donnée géographique</p>
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
