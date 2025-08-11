@extends('layouts.iri')

@section('title', e($actualite->titre) . ' - Actualités')

@section('breadcrumb')
    <x-breadcrumb-overlay :items="[
        ['title' => 'Actualités', 'url' => route('site.actualites')],
        ['title' => Str::limit(e($actualite->titre), 50), 'url' => null]
    ]" />
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/actualite-show.css') }}">
@endpush

@section('content')
<!-- Main Content -->
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-iri-primary via-iri-secondary to-iri-accent py-20">
        <div class="absolute inset-0 bg-black/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <!-- Content -->
                <div class="lg:col-span-2">
                    <!-- Category Badge -->
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm border border-white/30 text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                        <i class="fas fa-newspaper mr-2" aria-hidden="true"></i>
                        {{ e($actualite->categorie->nom ?? 'Actualité') }}
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 drop-shadow-2xl">
                        {{ e($actualite->titre) }}
                    </h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-white/90 mb-8">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2" aria-hidden="true"></i>
                            <time datetime="{{ $actualite->created_at->format('Y-m-d') }}">
                                {{ $actualite->created_at->format('d M Y') }}
                            </time>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2" aria-hidden="true"></i>
                            <span>Lecture : {{ ceil(str_word_count(strip_tags($actualite->contenu ?? $actualite->texte ?? '')) / 200) }} min</span>
                        </div>
                        
                        <!-- Share Buttons -->
                        <x-social-share 
                            :url="route('site.actualite.show', ['slug' => $actualite->slug])"
                            :title="e($actualite->titre)"
                            size="md"
                            style="hero"
                            class="ml-auto" />
                    </div>

                </div>

                <!-- Article Image -->
                @if($actualite->image && Storage::disk('public')->exists($actualite->image))
                    <div class="lg:col-span-1">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 p-6 shadow-2xl">
                            <img src="{{ asset('storage/' . $actualite->image) }}" 
                                 alt="{{ e($actualite->titre) }}" 
                                 class="w-full rounded-lg shadow-lg cursor-pointer hover:scale-105 transition-transform duration-200"
                                 loading="lazy"
                                 data-modal-trigger
                                 data-modal-src="{{ asset('storage/' . $actualite->image) }}"
                                 data-modal-alt="{{ e($actualite->titre) }}">
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <article class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                        <!-- Article Header -->
                        <div class="bg-gradient-to-r from-gray-50 to-white p-8 border-b border-gray-200">
                            @if($actualite->resume)
                                <blockquote class="border-l-4 border-iri-primary bg-iri-primary/5 p-6 rounded-lg mb-6">
                                    <p class="text-lg text-gray-700 italic leading-relaxed font-medium">
                                        "{{ e($actualite->resume) }}"
                                    </p>
                                </blockquote>
                            @endif
                        </div>

                        <!-- Article Body -->
                        <div class="p-8">
                            @if($actualite->texte || $actualite->contenu)
                                <x-rich-text-display 
                                    :content="$actualite->texte ?? $actualite->contenu" 
                                    class="prose-headings:text-iri-primary prose-links:text-iri-secondary prose-strong:text-iri-dark" />
                            @else
                                <div class="text-center py-12">
                                    <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                                    </div>
                                    <p class="text-gray-500">Contenu de l'article en cours de rédaction...</p>
                                </div>
                            @endif
                        </div>

                        <!-- Article Footer -->
                        <div class="bg-gray-50 p-6 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('site.actualites') }}" 
                                   class="inline-flex items-center text-iri-primary hover:text-iri-secondary font-semibold transition-colors duration-200">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Retour aux actualités
                                </a>

                                <!-- Tags -->
                                @if($actualite->categorie)
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500">Catégorie :</span>
                                        <span class="bg-iri-primary/10 text-iri-primary px-3 py-1 rounded-full text-sm font-medium">
                                            {{ e($actualite->categorie->nom) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="space-y-8">
                        <!-- Recent Articles -->
                        @if($recentActualites && optional($recentActualites)->count() > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
                                    <i class="fas fa-newspaper text-iri-primary mr-2"></i>
                                    Actualités récentes
                                </h3>
                                
                                <div class="space-y-4">
                                    @foreach($recentActualites->take(5) as $recente)
                                        @if($recente->slug && $recente->id !== $actualite->id)
                                            <a href="{{ route('site.actualite.show', ['slug' => $recente->slug]) }}" 
                                               class="block p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 group">
                                                <h4 class="font-medium text-gray-900 text-sm mb-2 line-clamp-2 group-hover:text-iri-primary transition-colors duration-200">
                                                    {{ e($recente->titre) }}
                                                </h4>
                                                <p class="text-xs text-gray-500 flex items-center">
                                                    <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>
                                                    <time datetime="{{ $recente->created_at->format('Y-m-d') }}">
                                                        {{ $recente->created_at->format('d M Y') }}
                                                    </time>
                                                </p>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="mt-6">
                                    <a href="{{ route('site.actualites') }}" 
                                       class="inline-flex items-center w-full justify-center bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold py-2 px-4 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-eye mr-2"></i>
                                        Voir toutes les actualités
                                    </a>
                                </div>
                            </div>
                        @endif

                        <!-- Newsletter Subscription -->
                        <div class="bg-gradient-to-br from-iri-primary to-iri-secondary rounded-2xl p-6 text-white">
                            <h3 class="text-lg font-bold mb-4">Restez informé</h3>
                            <p class="text-white/90 text-sm mb-4">
                                Recevez les dernières actualités du GRN-UCBC directement dans votre boîte mail.
                            </p>
                            @if(Route::has('newsletter.subscribe'))
                                <a href="{{ route('newsletter.subscribe') }}" 
                                   class="inline-flex items-center justify-center w-full bg-white/20 backdrop-blur-sm border border-white/30 text-white font-semibold py-2 px-4 rounded-lg hover:bg-white/30 transition-all duration-200">
                                    <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                    S'abonner à la newsletter
                                </a>
                            @else
                                <div class="bg-white/20 backdrop-blur-sm border border-white/30 text-white/70 font-medium py-2 px-4 rounded-lg text-center text-sm">
                                    <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                    Newsletter bientôt disponible
                                </div>
                            @endif
                        </div>

                        <!-- Contact for Information -->
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Plus d'informations</h3>
                            <p class="text-gray-600 text-sm mb-4">
                                Vous souhaitez en savoir plus sur cette actualité ?
                            </p>
                            <a href="{{ route('site.contact') }}" 
                               class="inline-flex items-center w-full justify-center bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold py-2 px-4 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                <i class="fas fa-envelope mr-2" aria-hidden="true"></i>
                                Nous contacter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="{{ asset('js/actualite-modal.js') }}" defer></script>
@endpush
@endsection
