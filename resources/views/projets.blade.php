@extends('layouts.iri')

@section('title', 'Projets - Institut de Recherche Intégré')

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        
        <!-- Breadcrumb Overlay -->
        <div class="absolute top-4 left-4 z-20">
            <nav class="flex space-x-2 text-sm text-white/90" aria-label="Breadcrumb">
                <a href="{{ route('site.home') }}" class="hover:text-white transition-colors">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
                <span class="text-white/60">›</span>
                @if(isset($service))
                    <a href="{{ route('site.services') }}" class="hover:text-white transition-colors">Domaines d'intervention</a>
                    <span class="text-white/60">›</span>
                    <a href="{{ route('site.service.show', ['slug' => $service->slug]) }}" class="hover:text-white transition-colors">{{ $service->nom }}</a>
                    <span class="text-white/60">›</span>
                    <span class="text-white font-medium">Projets</span>
                @else
                    <span class="text-white font-medium">{{ $currentPage ?? 'Projets' }}</span>
                @endif
            </nav>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                @if(isset($service))
                    Projets - {{ $service->nom }}
                @else
                    Nos Projets
                @endif
            </h1>
            <p class="text-xl text-white/90 max-w-3xl mx-auto">
                @if(isset($service))
                    Découvrez tous les projets du domaine d'intervention {{ $service->nom }}
                @else
                    Découvrez l'ensemble de nos projets de recherche et développement qui contribuent au progrès de notre société
                @endif
            </p>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="py-8 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" class="flex flex-wrap gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-4 items-center">
                    <!-- Service Filter -->
                    @if(!isset($service))
                    <div>
                        <select name="service" class="form-select">
                            <option value="">Tous les domaines</option>
                            @foreach($services as $serviceOption)
                                <option value="{{ $serviceOption->id }}" {{ request('service') == $serviceOption->id ? 'selected' : '' }}>
                                    {{ $serviceOption->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Status Filter -->
                    <div>
                        <select name="etat" class="form-select">
                            <option value="">Tous les statuts</option>
                            <option value="en cours" {{ request('etat') == 'en cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminé" {{ request('etat') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                            <option value="suspendu" {{ request('etat') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-iri-primary">
                        <i class="fas fa-filter mr-2"></i>
                        Filtrer
                    </button>

                    @if(request()->hasAny(['service', 'etat']))
                        <a href="{{ isset($service) ? route('site.service.projets', $service->slug) : route('site.projets') }}" class="btn-iri-outline">
                            <i class="fas fa-times mr-2"></i>
                            Réinitialiser
                        </a>
                    @endif
                </div>

                <div class="text-gray-600">
                    <i class="fas fa-project-diagram mr-2"></i>
                    {{ $projets->total() }} projet(s) trouvé(s)
                </div>
            </form>
        </div>
    </section>

    <!-- Projects Grid -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(optional($projets)->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($projets as $projet)
                        <article class="card-iri group overflow-hidden">
                            <!-- Image -->
                            <div class="aspect-video bg-gradient-to-br from-iri-light to-gray-100 overflow-hidden">
                                @if(optional($projet->medias)->count() ?? 0 > 0)
                                    <img src="{{ Storage::url($projet->medias->first()->chemin) }}" 
                                         alt="{{ $projet->nom }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-project-diagram text-4xl text-iri-primary/30"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <!-- Status Badge -->
                                @php
                                    $statusClass = match($projet->etat) {
                                        'en cours' => 'bg-green-100 text-green-800',
                                        'terminé' => 'bg-blue-100 text-blue-800',
                                        'suspendu' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }} mb-3">
                                    {{ ucfirst($projet->etat) }}
                                </span>

                                <!-- Service -->
                                @if($projet->service)
                                    <p class="text-sm text-iri-primary font-medium mb-2">
                                        {{ $projet->service->nom }}
                                    </p>
                                @endif

                                <!-- Title -->
                                <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2">
                                    {{ $projet->nom }}
                                </h3>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                    {{ $projet->resume ?? $projet->description ?? 'Aucune description disponible.' }}
                                </p>

                                <!-- Stats -->
                                @if($projet->beneficiaires_total > 0)
                                    <div class="flex items-center text-sm text-gray-500 mb-4">
                                        <i class="fas fa-users mr-2"></i>
                                        {{ number_format($projet->beneficiaires_total) }} bénéficiaires
                                    </div>
                                @endif

                                <!-- Action Button -->
                                <a href="{{ route('site.projet.show', $projet->slug) }}" 
                                   class="block w-full text-center btn-iri-primary hover-lift">
                                    Voir le projet
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $projets->links('pagination::tailwind') }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <i class="fas fa-project-diagram text-6xl text-gray-400 mb-6"></i>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Aucun projet trouvé</h3>
                    <p class="text-gray-600 mb-8">
                        @if(request()->hasAny(['service', 'etat']))
                            Aucun projet ne correspond aux critères de recherche sélectionnés.
                        @else
                            Aucun projet n'est actuellement disponible.
                        @endif
                    </p>
                    @if(request()->hasAny(['service', 'etat']))
                        <a href="{{ isset($service) ? route('site.service.projets', $service->slug) : route('site.projets') }}" 
                           class="btn-iri-primary">
                            Voir tous les projets
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
