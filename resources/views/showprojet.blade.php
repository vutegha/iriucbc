@extends('layouts.iri')

@section('title', $projet->exists ? 'Projet - ' . $projet->nom : 'Projet introuvable')

@section('content')
@if($projet->exists)
    <!-- Breadcrumb -->
    @php
        $breadcrumbs = [
            ['title' => 'Services', 'url' => route('site.services')]
        ];
        
        if($projet->service) {
            $breadcrumbs[] = ['title' => $projet->service->nom, 'url' => route('site.service.show', $projet->service->slug)];
        }
        
        $breadcrumbs[] = ['title' => $projet->nom, 'url' => null];
    @endphp
    
    @include('partials.breadcrumb', ['breadcrumbs' => $breadcrumbs])

    <!-- Main Content -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
        <!-- Hero Section -->
        <section class="relative">
            <div class="relative h-96 bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent overflow-hidden">
                @if($projet->image)
                    <img src="{{ asset('storage/'.$projet->image) }}" 
                         alt="{{ $projet->nom }}" 
                         class="absolute inset-0 w-full h-full object-cover mix-blend-overlay">
                @endif
                <div class="absolute inset-0 bg-black/20"></div>
                
                <div class="relative z-10 flex items-center justify-center h-full">
                    <div class="text-center text-white px-6 max-w-4xl mx-auto">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 drop-shadow-2xl">
                            {{ $projet->nom }}
                        </h1>
                        @if($projet->service)
                            <p class="text-xl md:text-2xl text-white/90 leading-relaxed drop-shadow-lg">
                                Programme : {{ $projet->service->nom }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Project Details -->
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                            <h2 class="text-3xl font-bold text-gray-900 mb-6">À propos de ce projet</h2>
                            
                            @if($projet->description)
                                <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                                    {!! nl2br(e($projet->description)) !!}
                                </div>
                            @else
                                <p class="text-gray-500 italic">Aucune description disponible pour ce projet.</p>
                            @endif
                        </div>

                        <!-- Project Gallery -->
                        @if($projet->medias && optional($projet->medias)->count() ?? 0 > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 mt-8">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6">Galerie du projet</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @foreach($projet->medias as $media)
                                        <div class="relative group overflow-hidden rounded-xl bg-gray-100 aspect-video">
                                            @if($media->type === 'image')
                                                <img src="{{ asset('storage/'.$media->chemin) }}" 
                                                     alt="{{ $media->nom }}" 
                                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                                            @elseif($media->type === 'video')
                                                <video class="w-full h-full object-cover" controls>
                                                    <source src="{{ asset('storage/'.$media->chemin) }}" type="video/mp4">
                                                    Votre navigateur ne supporte pas les vidéos.
                                                </video>
                                            @endif
                                            
                                            @if($media->nom)
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                                    <p class="text-white text-sm font-medium">{{ $media->nom }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Project Info -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">Informations du projet</h3>
                            
                            <div class="space-y-4">
                                <!-- Status -->
                                @if($projet->etat)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">État :</span>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($projet->etat === 'en cours') bg-green-100 text-green-800
                                            @elseif($projet->etat === 'terminé') bg-blue-100 text-blue-800
                                            @elseif($projet->etat === 'suspendu') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($projet->etat) }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Budget -->
                                @if($projet->budget)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Budget :</span>
                                        <span class="font-bold text-iri-gold text-lg">{{ number_format($projet->budget, 0, ',', ' ') }} $</span>
                                    </div>
                                @endif

                                <!-- Dates -->
                                @if($projet->date_debut)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Début :</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($projet->date_debut)->format('d/m/Y') }}</span>
                                    </div>
                                @endif

                                @if($projet->date_fin)
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600">Fin :</span>
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($projet->date_fin)->format('d/m/Y') }}</span>
                                    </div>
                                @endif

                                <!-- Beneficiaries Section -->
                                @php
                                    $beneficiaires_attendus = ($projet->beneficiaires_hommes ?? 0) + ($projet->beneficiaires_femmes ?? 0);
                                @endphp
                                @if($projet->beneficiaires_total > 0 || $beneficiaires_attendus > 0)
                                    <div class="border-t pt-4 mt-4">
                                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Bénéficiaires</h4>
                                        
                                        <div class="space-y-2">
                                            @if($beneficiaires_attendus > 0)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-600">Attendus :</span>
                                                    <span class="font-bold text-iri-accent">{{ number_format($beneficiaires_attendus) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($projet->beneficiaires_total > 0)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-600">Atteints :</span>
                                                    <span class="font-bold text-iri-primary">{{ number_format($projet->beneficiaires_total) }}</span>
                                                </div>
                                            @endif
                                            
                                            @if($projet->beneficiaires_hommes > 0 || $projet->beneficiaires_femmes > 0)
                                                <div class="mt-2 pt-2 border-t border-gray-100">
                                                    @if($projet->beneficiaires_hommes > 0)
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-gray-500">• Hommes :</span>
                                                            <span class="font-medium">{{ number_format($projet->beneficiaires_hommes) }}</span>
                                                        </div>
                                                    @endif
                                                    
                                                    @if($projet->beneficiaires_femmes > 0)
                                                        <div class="flex items-center justify-between text-sm">
                                                            <span class="text-gray-500">• Femmes :</span>
                                                            <span class="font-medium">{{ number_format($projet->beneficiaires_femmes) }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Service Link -->
                                @if($projet->service)
                                    <div class="border-t pt-4 mt-4">
                                        <a href="{{ route('site.service.show', $projet->service->slug) }}" 
                                           class="inline-flex items-center gap-2 text-iri-primary hover:text-iri-secondary transition-colors duration-200">
                                            <i class="fas fa-arrow-left"></i>
                                            <span>Retour au programme</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@else
    <!-- Project Not Found -->
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white flex items-center justify-center">
        <div class="text-center">
            <div class="text-6xl text-gray-300 mb-8">
                <i class="fas fa-project-diagram"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Projet introuvable</h1>
            <p class="text-xl text-gray-600 mb-8">Le projet que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="{{ route('site.services') }}" 
               class="btn-iri-primary">
                <i class="fas fa-arrow-left mr-2"></i>
                Retour aux services
            </a>
        </div>
    </div>
@endif
@endsection
