<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Menu Principal avec Design Moderne -->
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 shadow-sm"
        x-data="{ 
            mobileOpen: false, 
            programsOpen: false,
            scrolled: false,
            searchOpen: false,
            currentPath: window.location.pathname,
            currentHash: window.location.hash
        }"
        x-init="
            window.addEventListener('scroll', () => {
                scrolled = window.scrollY > 20
            });
            window.addEventListener('hashchange', () => {
                currentHash = window.location.hash
            })
        "
        :class="{ 'shadow-lg': scrolled }"
        @resize.window="if(window.innerWidth >= 1024) mobileOpen = false">

    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">
            
            <!-- Logo et Identité -->
            <div class="flex items-center space-x-4 flex-shrink-0">
                <div class="relative">
                    <img src="{{ asset('assets/img/logos/ucbc-2.png') }}" 
                         alt="Logo IRI-UCBC" 
                         class="h-12 w-12 lg:h-14 lg:w-14 rounded-xl object-contain ring-2 ring-olive/20 transition-all duration-300 hover:ring-olive/40 shadow-sm">
                </div>
                <div class="hidden sm:block">
                    <h1 class="text-lg lg:text-xl font-bold text-olive leading-tight">
                        Institut de Recherche<br>
                        <span class="text-sm lg:text-base font-medium text-gray-600">Intégré - UCBC</span>
                    </h1>
                </div>
            </div>

            <!-- Navigation Desktop -->
            <div class="hidden lg:flex items-center space-x-2">
                <!-- Accueil -->
                <a href="{{ url('/') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group relative"
                   :class="(currentPath === '/' && currentHash !== '#aboutus') ? 
                       'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-2 border-iri-primary border-l-4 border-l-iri-gold' : 
                       'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md border-2 border-transparent hover:border-iri-primary/20 hover:border-l-4 hover:border-l-iri-accent'">
                    <svg class="w-4 h-4 mr-2 transition-colors" 
                         :class="(currentPath === '/' && currentHash !== '#aboutus') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Accueil
                </a>

                <!-- À propos -->
                <a href="{{ route('site.home') }}#aboutus" 
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group relative"
                   :class="(currentPath === '/' && currentHash === '#aboutus') || currentPath.includes('aboutus') || currentPath.includes('a-propos') ? 
                       'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-2 border-iri-primary border-l-4 border-l-iri-gold' : 
                       'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md border-2 border-transparent hover:border-iri-primary/20 hover:border-l-4 hover:border-l-iri-accent'">
                    <svg class="w-4 h-4 mr-2 transition-colors" 
                         :class="(currentPath === '/' && currentHash === '#aboutus') || currentPath.includes('aboutus') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    À propos
                </a>

                <!-- Programmes avec Dropdown -->
                <div class="relative" 
                     x-data="{ open: false }"
                     @mouseenter="open = true"
                     @mouseleave="open = false">
                    <button class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group relative"
                            :class="currentPath.includes('/service') || currentPath.includes('/programme') ? 
                                'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-2 border-iri-primary border-l-4 border-l-iri-gold' : 
                                'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md border-2 border-transparent hover:border-iri-primary/20 hover:border-l-4 hover:border-l-iri-accent'"
                            @click="open = !open">
                        <svg class="w-4 h-4 mr-2 transition-colors" 
                             :class="currentPath.includes('/service') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Programmes
                        <svg class="w-4 h-4 ml-2 transition-transform duration-200" 
                             :class="{ 'rotate-180': open, 'text-white': currentPath.includes('/service'), 'text-iri-gray group-hover:text-white': !currentPath.includes('/service') }" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute top-full left-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-200 py-2 z-50"
                         style="display: none;"
                         @click.away="open = false">
                        
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nos Programmes</p>
                        </div>
                        
                        @if(isset($menuServices) && optional($menuServices)->count() > 0)
                            @foreach($menuServices as $service)
                                @php
                                    // Utilise nom_menu si défini, sinon utilise le nom principal
                                    $nomAffiche = !empty($service->nom_menu) && trim($service->nom_menu) !== '' 
                                        ? $service->nom_menu 
                                        : $service->nom;
                                @endphp
                                
                                @if($nomAffiche && trim($nomAffiche) !== '')
                                <a href="{{ route('site.service.show', $service->slug) }}"
                                   class="flex items-center w-full px-4 py-3 text-sm transition-all duration-200 group"
                                   :class="currentPath.includes('/service/{{ $service->slug }}') ? 
                                       'bg-gradient-to-r from-iri-accent/20 to-iri-gold/10 text-iri-accent border-r-4 border-iri-accent font-medium' : 
                                       'text-iri-dark hover:bg-gradient-to-r hover:from-iri-light hover:to-iri-light/50 hover:text-iri-primary'"
                                   @click="open = false">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center mr-3 transition-colors"
                                             :class="currentPath.includes('/service/{{ $service->slug }}') ? 
                                                 'bg-iri-accent/20 ring-2 ring-iri-accent/30' : 
                                                 'bg-iri-light group-hover:bg-iri-accent/15'">
                                            <svg class="w-4 h-4 transition-colors" 
                                                 :class="currentPath.includes('/service/{{ $service->slug }}') ? 'text-iri-accent' : 'text-iri-gray group-hover:text-iri-primary'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium transition-colors"
                                                 :class="currentPath.includes('/service/{{ $service->slug }}') ? 
                                                     'text-iri-accent' : 
                                                     'text-iri-dark group-hover:text-iri-primary'">
                                                {{ $nomAffiche }}
                                            </div>
                                            @if($service->description_courte)
                                                <div class="text-xs mt-0.5 transition-colors"
                                                     :class="currentPath.includes('/service/{{ $service->slug }}') ? 
                                                         'text-iri-accent/70' : 
                                                         'text-iri-gray group-hover:text-iri-primary/80'">
                                                    {{ Str::limit($service->description_courte, 60) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                @endif
                            @endforeach
                        @else
                            <div class="px-4 py-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">Aucun programme disponible</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Travailler avec nous -->
                <a href="{{ route('site.work-with-us') }}" 
                   class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group relative"
                   :class="currentPath.includes('work-with-us') || currentPath.includes('partenariats') || currentPath.includes('travailler') ? 
                       'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-2 border-iri-primary border-l-4 border-l-iri-gold' : 
                       'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md border-2 border-transparent hover:border-iri-primary/20 hover:border-l-4 hover:border-l-iri-accent'">
                    <svg class="w-4 h-4 mr-2 transition-colors" 
                         :class="currentPath.includes('work-with-us') || currentPath.includes('travailler') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    Travailler avec nous
                </a>
            </div>

            <!-- CTA et Recherche et Burger -->
            <div class="flex items-center space-x-4">
                <!-- Bouton de recherche -->
                <button @click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.searchInput?.focus())" 
                        class="hidden sm:flex p-2 rounded-xl text-iri-gray hover:text-iri-primary hover:bg-iri-light transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-iri-primary focus:ring-offset-2"
                        :class="{ 'bg-iri-light text-iri-primary': searchOpen }"
                        aria-label="Recherche">
                    <svg class="h-5 w-5 transition-transform duration-300" 
                         :class="{ 'rotate-90': searchOpen }" 
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>

                <!-- CTA Principal -->
                <a href="https://congoinitiative.org/give/"
                   target="_blank"
                   class="hidden sm:inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 group focus:outline-none focus:ring-2 focus:ring-iri-accent focus:ring-offset-2">
                    <svg class="w-4 h-4 mr-2 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Faire un Don
                </a>

                <!-- Menu Burger -->
                <button @click="mobileOpen = !mobileOpen" 
                        class="lg:hidden p-2 rounded-xl text-iri-gray hover:text-iri-primary hover:bg-iri-light transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-iri-primary focus:ring-offset-2"
                        :class="{ 'bg-iri-light text-iri-primary': mobileOpen }"
                        aria-label="Menu mobile">
                    <svg class="h-6 w-6 transition-transform duration-300" 
                         :class="{ 'rotate-90': mobileOpen }" 
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" x-show="!mobileOpen" d="M4 6h16M4 12h16M4 18h16"/>
                        <path stroke-linecap="round" stroke-linejoin="round" x-show="mobileOpen" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div x-show="searchOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="border-t border-gray-100 bg-white/95 backdrop-blur-md"
             style="display: none;"
             @click.away="searchOpen = false">
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <form action="{{ route('site.search') }}" method="GET" class="flex items-center space-x-3" onsubmit="return validateSearch(this)">
                    <div class="flex-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-iri-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" 
                               name="q" 
                               id="searchInput"
                               placeholder="Rechercher des publications, actualités, projets..." 
                               class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-iri-dark placeholder-iri-gray focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
                               value="{{ request('q') }}"
                               autocomplete="off"
                               x-ref="searchInput"
                               @keydown.escape="searchOpen = false"
                               @focus="searchOpen = true"
                               required
                               minlength="2">
                    </div>
                    
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-medium rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-iri-primary focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Rechercher
                    </button>
                    
                    <button type="button" 
                            @click="searchOpen = false"
                            class="inline-flex items-center p-3 text-iri-gray hover:text-iri-primary hover:bg-iri-light rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-iri-primary focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </form>
                
                <!-- Suggestions de recherche rapide -->
                <div class="mt-3 flex flex-wrap gap-2">
                    <p class="text-xs text-iri-gray">Recherches populaires:</p>
                    <a href="{{ route('site.search') }}?q=publications" 
                       class="inline-flex items-center px-3 py-1 text-xs bg-iri-light text-iri-primary rounded-full hover:bg-iri-accent/10 transition-colors duration-200"
                       @click="searchOpen = false">
                        Publications
                    </a>
                    <a href="{{ route('site.search') }}?q=projets" 
                       class="inline-flex items-center px-3 py-1 text-xs bg-iri-light text-iri-primary rounded-full hover:bg-iri-accent/10 transition-colors duration-200"
                       @click="searchOpen = false">
                        Projets
                    </a>
                    <a href="{{ route('site.search') }}?q=actualités" 
                       class="inline-flex items-center px-3 py-1 text-xs bg-iri-light text-iri-primary rounded-full hover:bg-iri-accent/10 transition-colors duration-200"
                       @click="searchOpen = false">
                        Actualités
                    </a>
                    <a href="{{ route('site.search') }}?q=rapports" 
                       class="inline-flex items-center px-3 py-1 text-xs bg-iri-light text-iri-primary rounded-full hover:bg-iri-accent/10 transition-colors duration-200"
                       @click="searchOpen = false">
                        Rapports
                    </a>
                </div>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div x-show="mobileOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="lg:hidden border-t border-gray-100 bg-white/95 backdrop-blur-md"
             style="display: none;">
            
            <div class="px-4 py-6 space-y-3">
                <!-- Accueil Mobile -->
                <a href="{{ url('/') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                   :class="(currentPath === '/' && currentHash !== '#aboutus') ? 
                       'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-l-4 border-iri-accent' : 
                       'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md hover:border-l-4 hover:border-iri-accent/50'"
                   @click="mobileOpen = false">
                    <svg class="w-5 h-5 mr-3 transition-colors" 
                         :class="(currentPath === '/' && currentHash !== '#aboutus') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Accueil
                </a>

                <!-- À propos Mobile -->
                <a href="{{ route('site.home') }}#aboutus" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                   :class="(currentPath === '/' && currentHash === '#aboutus') || currentPath.includes('aboutus') || currentPath.includes('a-propos') ? 
                       'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-l-4 border-iri-accent' : 
                       'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md hover:border-l-4 hover:border-iri-accent/50'"
                   @click="mobileOpen = false">
                    <svg class="w-5 h-5 mr-3 transition-colors" 
                         :class="(currentPath === '/' && currentHash === '#aboutus') || currentPath.includes('aboutus') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    À propos
                </a>

                <!-- Programmes Mobile -->
                <div x-data="{ programsOpen: false }">
                    <button @click="programsOpen = !programsOpen" 
                            class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                            :class="currentPath.includes('/service') || currentPath.includes('/programme') ? 
                                'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-l-4 border-iri-accent' : 
                                'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md hover:border-l-4 hover:border-iri-accent/50'">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 transition-colors" 
                                 :class="currentPath.includes('/service') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Programmes
                        </div>
                        <svg class="w-4 h-4 transition-transform duration-200" 
                             :class="{ 'rotate-180': programsOpen, 'text-white': currentPath.includes('/service'), 'text-iri-gray group-hover:text-white': !currentPath.includes('/service') }" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div x-show="programsOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="ml-8 mt-2 space-y-2"
                         style="display: none;">
                        @if(isset($menuServices) && optional($menuServices)->count() > 0)
                            @foreach($menuServices as $service)
                                @php
                                    // Utilise nom_menu si défini, sinon utilise le nom principal
                                    $nomAffiche = !empty($service->nom_menu) && trim($service->nom_menu) !== '' 
                                        ? $service->nom_menu 
                                        : $service->nom;
                                @endphp
                                <a href="{{ route('site.service.show', $service->slug) }}"
                                   class="block py-2 px-4 text-sm rounded-lg transition-all duration-200 group"
                                   :class="currentPath.includes('/service/{{ $service->slug }}') ? 
                                       'bg-iri-accent/20 text-iri-accent font-medium border-l-3 border-iri-accent' : 
                                       'text-iri-dark hover:text-iri-primary hover:bg-iri-light hover:border-l-3 hover:border-iri-accent/50'"
                                   @click="mobileOpen = false; programsOpen = false">
                                    {{ $nomAffiche }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Travailler avec nous Mobile -->
                <a href="{{ route('site.work-with-us') }}" 
                   class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group"
                   :class="currentPath.includes('/work-with-us') || currentPath.includes('/partenariats') || currentPath.includes('travailler') ? 
                       'bg-gradient-to-r from-iri-primary to-iri-secondary text-white shadow-lg border-l-4 border-iri-accent' : 
                       'text-iri-dark hover:text-white hover:bg-gradient-to-r hover:from-iri-primary/80 hover:to-iri-secondary/70 hover:shadow-md hover:border-l-4 hover:border-iri-accent/50'"
                   @click="mobileOpen = false">
                    <svg class="w-5 h-5 mr-3 transition-colors" 
                         :class="currentPath.includes('/work-with-us') || currentPath.includes('travailler') ? 'text-white' : 'text-iri-gray group-hover:text-white'"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    Travailler avec nous
                </a>

                <!-- Recherche Mobile -->
                <div class="pt-2 border-t border-gray-100">
                    <form action="{{ route('site.search') }}" method="GET" class="space-y-3" onsubmit="return validateSearch(this)">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-iri-gray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="q" 
                                   id="searchInputMobile"
                                   placeholder="Rechercher..." 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl leading-5 bg-white text-iri-dark placeholder-iri-gray focus:outline-none focus:ring-2 focus:ring-iri-primary focus:border-iri-primary transition-all duration-200"
                                   value="{{ request('q') }}"
                                   autocomplete="off"
                                   required
                                   minlength="2">
                        </div>
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-medium rounded-xl shadow-md transition-all duration-200"
                                @click="mobileOpen = false">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Rechercher
                        </button>
                    </form>
                </div>

                <!-- CTA Mobile -->
                <div class="pt-4 border-t border-gray-100">
                    <a href="https://congoinitiative.org/give/"
                       target="_blank"
                       class="flex items-center justify-center w-full px-6 py-3 bg-gradient-to-r from-iri-accent to-iri-gold text-white font-semibold rounded-xl shadow-lg"
                       @click="mobileOpen = false">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Faire un Don
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<script>
// Fonction de validation de recherche
function validateSearch(form) {
    const input = form.querySelector('input[name="q"]');
    const searchTerm = input.value.trim();
    
    if (searchTerm.length < 2) {
        alert('Veuillez saisir au moins 2 caractères pour effectuer une recherche.');
        input.focus();
        return false;
    }
    
    // Encoder correctement l'URL
    const url = new URL(form.action);
    url.searchParams.set('q', searchTerm);
    
    // Log pour debug
    console.log('Recherche pour:', searchTerm);
    console.log('URL de redirection:', url.toString());
    
    // Redirection manuelle pour s'assurer que le paramètre est passé
    window.location.href = url.toString();
    return false; // Empêcher la soumission normale du formulaire
}

// Event listeners pour la recherche par clavier
document.addEventListener('DOMContentLoaded', function() {
    // Recherche avec Enter sur le champ desktop
    const desktopSearch = document.getElementById('searchInput');
    if (desktopSearch) {
        desktopSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const form = this.closest('form');
                validateSearch(form);
            }
        });
    }
    
    // Recherche avec Enter sur le champ mobile
    const mobileSearch = document.getElementById('searchInputMobile');
    if (mobileSearch) {
        mobileSearch.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const form = this.closest('form');
                validateSearch(form);
            }
        });
    }
});
</script>
