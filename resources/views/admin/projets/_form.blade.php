<!-- Container principal avec design moderne -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- En-tête moderne -->
        <div class="mb-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-white/20">
                <div class="bg-gradient-to-r from-iri-primary via-blue-600 to-iri-secondary px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">
                                    {{ isset($projet) ? 'Modifier le projet' : 'Nouveau projet' }}
                                </h1>
                                <p class="text-blue-100 text-sm mt-1">
                                    {{ isset($projet) ? 'Modifiez les informations de ce projet' : 'Créez un nouveau projet pour votre organisation' }}
                                </p>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-white/80 text-sm text-right">
                                <div>{{ date('d/m/Y') }}</div>
                                <div class="text-xs">{{ now()->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Messages de succès/erreur avec design moderne -->
        @if ($errors->any())
            <div class="mb-6">
                <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-red-800 font-semibold text-lg">Erreurs détectées</h3>
                            <p class="text-red-700 text-sm mt-1 mb-3">Veuillez corriger les erreurs suivantes :</p>
                            <ul class="space-y-1 bg-white/60 rounded-lg p-3">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start text-sm text-red-700">
                                        <svg class="h-3 w-3 text-red-500 mt-1 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3"/>
                                        </svg>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenu principal -->
        <div class="w-full">
            
            <!-- Formulaire principal -->
            <div class="w-full">
                <form id="projet-form" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                    @csrf
                    @if(isset($projet))
                        @method('PUT')
                    @endif

                    <!-- Section 1: Informations générales -->
                    <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-iri-primary rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Informations générales</h3>
                                    <p class="text-sm text-gray-600">Renseignez les informations de base du projet</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <!-- Nom du projet -->
                            <div>
                                <label for="nom" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a4 4 0 01-4-4V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Nom du projet
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <input type="text" 
                                       name="nom" 
                                       id="nom"
                                       value="{{ old('nom', $projet->nom ?? '') }}"
                                       class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('nom') border-red-500 bg-red-50 @enderror"
                                       placeholder="Saisissez un nom descriptif pour votre projet"
                                       required
                                       maxlength="100">
                                @error('nom')
                                    <div class="mt-2 flex items-center text-sm text-red-600">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Résumé du projet -->
                            <div>
                                <label for="resume" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                    <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Résumé du projet
                                </label>
                                <textarea name="resume" 
                                          id="resume"
                                          rows="3"
                                          value="{{ old('resume', $projet->resume ?? '') }}"
                                          class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('resume') border-red-500 bg-red-50 @enderror"
                                          placeholder="Résumé court du projet (1-2 phrases maximum)"
                                          maxlength="255">{{ old('resume', $projet->resume ?? '') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Maximum 255 caractères</p>
                                @error('resume')
                                    <div class="mt-2 flex items-center text-sm text-red-600">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Ligne avec deux colonnes -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Date début -->
                                <div>
                                    <label for="date_debut" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Date de début
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="date" 
                                           name="date_debut" 
                                           id="date_debut"
                                           value="{{ old('date_debut', isset($projet) ? $projet->date_debut?->format('Y-m-d') : '') }}"
                                           class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('date_debut') border-red-500 bg-red-50 @enderror"
                                           required>
                                    @error('date_debut')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Date fin -->
                                <div>
                                    <label for="date_fin" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Date de fin estimée
                                    </label>
                                    <input type="date" 
                                           name="date_fin" 
                                           id="date_fin"
                                           value="{{ old('date_fin', isset($projet) ? $projet->date_fin?->format('Y-m-d') : '') }}"
                                           class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('date_fin') border-red-500 bg-red-50 @enderror">
                                    @error('date_fin')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ligne avec service et état -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Service -->
                                <div>
                                    <label for="service_id" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                        Service
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <select name="service_id" 
                                            id="service_id"
                                            class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('service_id') border-red-500 bg-red-50 @enderror"
                                            required>
                                        <option value="">Sélectionner un service</option>
                                        @if(isset($services))
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" 
                                                        {{ old('service_id', $projet->service_id ?? '') == $service->id ? 'selected' : '' }}>
                                                    {{ $service->nom }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('service_id')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- État -->
                                <div>
                                    <label for="etat" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        État du projet
                                        <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <select name="etat" 
                                            id="etat"
                                            class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('etat') border-red-500 bg-red-50 @enderror"
                                            required>
                                        <option value="">Sélectionner un état</option>
                                        <option value="en cours" {{ old('etat', $projet->etat ?? '') == 'en cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="terminé" {{ old('etat', $projet->etat ?? '') == 'terminé' ? 'selected' : '' }}>Terminé</option>
                                        <option value="suspendu" {{ old('etat', $projet->etat ?? '') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                                    </select>
                                    @error('etat')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ligne avec budget et bénéficiaires -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Budget -->
                                <div>
                                    <label for="budget" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                        Budget (USD)
                                    </label>
                                    <input type="number" 
                                           name="budget" 
                                           id="budget"
                                           value="{{ old('budget', $projet->budget ?? '') }}"
                                           class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('budget') border-red-500 bg-red-50 @enderror"
                                           placeholder="0"
                                           min="0"
                                           step="1000">
                                    @error('budget')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Bénéficiaires totaux -->
                                <div>
                                    <label for="beneficiaires_total" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                        <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Bénéficiaires totaux
                                    </label>
                                    <input type="number" 
                                           name="beneficiaires_total" 
                                           id="beneficiaires_total"
                                           value="{{ old('beneficiaires_total', $projet->beneficiaires_total ?? 0) }}"
                                           class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-gray-50 @error('beneficiaires_total') border-red-500 bg-red-50 @enderror"
                                           placeholder="0"
                                           min="0"
                                           readonly>
                                    <p class="text-xs text-gray-500 mt-1">Calculé automatiquement : Hommes + Femmes + Enfants</p>
                                    @error('beneficiaires_total')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Bénéficiaires détaillés -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-slate-50 rounded-xl p-4">
                                <!-- Hommes -->
                                <div>
                                    <label for="beneficiaires_hommes" class="block text-sm font-medium text-gray-700 mb-2">Hommes</label>
                                    <input type="number" 
                                           name="beneficiaires_hommes" 
                                           id="beneficiaires_hommes"
                                           value="{{ old('beneficiaires_hommes', $projet->beneficiaires_hommes ?? 0) }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary transition-all bg-white"
                                           placeholder="0"
                                           min="0">
                                </div>

                                <!-- Femmes -->
                                <div>
                                    <label for="beneficiaires_femmes" class="block text-sm font-medium text-gray-700 mb-2">Femmes</label>
                                    <input type="number" 
                                           name="beneficiaires_femmes" 
                                           id="beneficiaires_femmes"
                                           value="{{ old('beneficiaires_femmes', $projet->beneficiaires_femmes ?? 0) }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary transition-all bg-white"
                                           placeholder="0"
                                           min="0">
                                </div>

                                <!-- Enfants -->
                                <div>
                                    <label for="beneficiaires_enfants" class="block text-sm font-medium text-gray-700 mb-2">Enfants</label>
                                    <input type="number" 
                                           name="beneficiaires_enfants" 
                                           id="beneficiaires_enfants"
                                           value="{{ old('beneficiaires_enfants', $projet->beneficiaires_enfants ?? 0) }}"
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-iri-primary/20 focus:border-iri-primary transition-all bg-white"
                                           placeholder="0"
                                           min="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Description -->
                    <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-iri-primary rounded-lg flex items-center justify-center">
                                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">Description détaillée</h3>
                                        <p class="text-sm text-gray-600">Présentez tous les aspects de votre projet</p>
                                    </div>
                                </div>
                                
                                <button type="button" 
                                        id="testMediaButton" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 transition-all duration-200 shadow-md hover:shadow-lg">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Médiathèque
                                </button>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-2">
                                <label for="description" class="flex items-center text-sm font-semibold text-gray-700">
                                    <svg class="h-4 w-4 text-iri-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Description du projet
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                
                                <textarea name="description" 
                                          id="description" 
                                          class="wysiwyg w-full px-4 py-4 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('description') border-red-500 bg-red-50 @enderror"
                                          rows="12"
                                          placeholder="Décrivez en détail votre projet :
• Les objectifs principaux et secondaires
• La méthodologie et les activités prévues  
• Le public cible et les bénéficiaires
• Les résultats attendus et indicateurs de succès
• Le contexte et la justification du projet
• Les partenariats et collaborations..."
                                          required>{{ old('description', $projet->description ?? '') }}</textarea>
                                
                                @error('description')
                                    <div class="flex items-center text-sm text-red-600 bg-red-50 p-3 rounded-lg mt-2">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Image du projet -->
                            <div>
                                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-image text-olive mr-2"></i>Image du projet
                                    <span class="text-xs font-normal text-gray-500">(optionnelle)</span>
                                </label>
                                
                                <div class="flex items-start space-x-6">
                                    <div class="flex-1">
                                        <div class="relative">
                                            <input type="file" 
                                                   id="image"
                                                   name="image" 
                                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-olive focus:border-transparent transition-all duration-200 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-olive/10 file:text-olive hover:file:bg-olive/20"
                                                   accept="image/*"
                                                   onchange="previewImageProjet(this)">
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500">
                                            Formats acceptés: JPG, PNG, GIF (max 5MB)
                                        </p>
                                    </div>
                                    
                                    <!-- Aperçu de l'image -->
                                    <div class="flex-shrink-0">
                                        @if(isset($projet) && $projet->image && Storage::disk('public')->exists($projet->image))
                                            <div class="relative group">
                                                <img id="image-preview-projet" 
                                                     src="{{ asset('storage/' . $projet->image) }}" 
                                                     class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm" 
                                                     alt="Aperçu"
                                                     loading="lazy">
                                                <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-eye text-white text-lg"></i>
                                                </div>
                                            </div>
                                        @else
                                            <div id="image-placeholder-projet" class="w-24 h-24 bg-gray-100 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center">
                                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                                            </div>
                                            <img id="image-preview-projet" 
                                                 class="w-24 h-24 object-cover rounded-lg border-2 border-gray-200 shadow-sm hidden" 
                                                 alt="Aperçu">
                                        @endif
                                    </div>
                                </div>
                                @error('image')
                                    <div class="mt-2 flex items-center text-sm text-red-600">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Actions -->
                    <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                                <a href="{{ route('admin.projets.index') }}" 
                                   class="inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 focus:ring-4 focus:ring-gray-500/20 transition-all duration-200">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Annuler
                                </a>
                                
                                <button type="submit" 
                                        class="inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-iri-primary to-iri-secondary text-white font-semibold rounded-xl hover:shadow-lg focus:ring-4 focus:ring-iri-primary/20 transition-all duration-200 transform hover:scale-105">
                                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ isset($projet) ? 'Mettre à jour' : 'Créer le projet' }}
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<style>
/* Styles pour le modal */
#mediaModal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    margin: 0;
    padding: 1rem;
}

#mediaModal.hidden {
    display: none !important;
}

#mediaModal .bg-white {
    border-radius: 0.75rem;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    max-width: 32rem;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    margin: 0;
}

/* Empêcher le scroll du body quand le modal est ouvert */
body.modal-open {
    overflow: hidden !important;
    position: fixed !important;
    width: 100% !important;
}

/* Styles pour les boutons personnalisés CKEditor */
.ck-dropdown__panel {
    position: absolute;
    top: 100%;
    left: 0;
    background: white;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1000;
    padding: 8px;
    min-width: 160px;
}

.color-palette {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 4px;
}

.color-btn {
    width: 24px;
    height: 24px;
    border: 1px solid #ccc;
    border-radius: 2px;
    cursor: pointer;
    transition: transform 0.1s;
}

.color-btn:hover {
    transform: scale(1.1);
    border-color: #000;
}

.ck-dropdown {
    position: relative;
    display: inline-block;
}

/* Styles pour les textes colorés et surlignés */
.ck-content span[style*="color"] {
    /* Préserver les couleurs dans l'éditeur */
}

.ck-content span[style*="background-color"] {
    /* Préserver les surlignages dans l'éditeur */
    padding: 2px 4px;
    border-radius: 2px;
}
</style>

<!-- Modale Médiathèque -->
<div id="mediaModal" class="hidden">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b flex items-center justify-between bg-gradient-to-r from-iri-primary to-iri-secondary">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <i class="fas fa-photo-video mr-3"></i>
                Médiathèque
            </h3>
            <button type="button" onclick="closeMediaModal()" class="text-white hover:text-gray-200 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <!-- Upload d'image -->
            <form id="mediaUploadForm" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6 flex items-center space-x-4">
                @csrf
                <input type="file" name="image" id="mediaUploadInput" accept="image/*" class="border rounded p-2">
                <button type="submit" class="px-4 py-2 bg-iri-primary text-white rounded hover:bg-iri-secondary transition">Uploader</button>
            </form>
            <!-- Liste des images existantes -->
            <div id="mediaList" class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="col-span-full text-center text-gray-500 py-8">
                    <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                    <p>Chargement des médias...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CKEditor 5 avec build étendu -->
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/translations/fr.js"></script>

<script>
let editorInstance = null;

function initializeCKEditor() {
    const textElement = document.getElementById('description');
    if (!textElement) return;

    ClassicEditor
        .create(textElement, {
            language: 'fr',
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'link', 'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', '|',
                'imageUpload', '|',
                'undo', 'redo'
            ],
            image: {
                toolbar: [
                    'imageTextAlternative', '|',
                    'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight'
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        })
        .then(editor => {
            editorInstance = editor;
            
            // Ajouter des boutons personnalisés à la toolbar
            addCustomToolbarButtons(editor);
            
            // Ajouter le bouton Médiathèque manuellement
            addMediaLibraryButton(editor);
            
            // Synchronisation avec le textarea
            editor.model.document.on('change:data', () => {
                textElement.value = editor.getData();
            });

            // Charger le contenu existant en mode édition
            const existingContent = textElement.value;
            if (existingContent) {
                editor.setData(existingContent);
            }
        })
        .catch(error => {
            console.error('Erreur CKEditor:', error);
        });
}

function addCustomToolbarButtons(editor) {
    const toolbar = editor.ui.view.toolbar;
    
    // Bouton pour changer la couleur du texte
    const colorButton = document.createElement('div');
    colorButton.className = 'ck ck-dropdown';
    colorButton.innerHTML = `
        <button class="ck ck-button ck-dropdown__button" type="button">
            <svg class="ck ck-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-5L9 9z"/>
            </svg>
            <span class="ck ck-button__label">Couleur</span>
            <svg class="ck ck-dropdown__arrow" width="10" height="10" viewBox="0 0 10 10">
                <path d="M.941 4.523a.75.75 0 1 1 1.06-1.06l3.006 3.005 3.005-3.005a.75.75 0 1 1 1.06 1.06l-3.549 3.55a.75.75 0 0 1-1.06 0L.941 4.523z"/>
            </svg>
        </button>
        <div class="ck ck-dropdown__panel" style="display: none;">
            <div class="color-palette">
                <button class="color-btn" style="background: #000000" data-color="#000000" title="Noir"></button>
                <button class="color-btn" style="background: #ff0000" data-color="#ff0000" title="Rouge"></button>
                <button class="color-btn" style="background: #00ff00" data-color="#00ff00" title="Vert"></button>
                <button class="color-btn" style="background: #0000ff" data-color="#0000ff" title="Bleu"></button>
                <button class="color-btn" style="background: #ffff00" data-color="#ffff00" title="Jaune"></button>
                <button class="color-btn" style="background: #ff00ff" data-color="#ff00ff" title="Magenta"></button>
                <button class="color-btn" style="background: #00ffff" data-color="#00ffff" title="Cyan"></button>
                <button class="color-btn" style="background: #ffa500" data-color="#ffa500" title="Orange"></button>
                <button class="color-btn" style="background: #800080" data-color="#800080" title="Violet"></button>
                <button class="color-btn" style="background: #808080" data-color="#808080" title="Gris"></button>
            </div>
        </div>
    `;
    
    // Gérer l'ouverture/fermeture du dropdown
    const dropdownButton = colorButton.querySelector('.ck-dropdown__button');
    const dropdownPanel = colorButton.querySelector('.ck-dropdown__panel');
    
    dropdownButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dropdownPanel.style.display = dropdownPanel.style.display === 'none' ? 'block' : 'none';
    });
    
    // Gérer la sélection des couleurs
    const colorButtons = colorButton.querySelectorAll('.color-btn');
    colorButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const color = btn.dataset.color;
            
            // Appliquer la couleur au texte sélectionné
            editor.model.change(writer => {
                const selection = editor.model.document.selection;
                if (!selection.isCollapsed) {
                    writer.setSelectionAttribute('fontColor', color);
                }
            });
            
            dropdownPanel.style.display = 'none';
        });
    });
    
    // Bouton pour surligner
    const highlightButton = document.createElement('button');
    highlightButton.type = 'button';
    highlightButton.className = 'ck ck-button ck-off';
    highlightButton.innerHTML = `
        <svg class="ck ck-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
        </svg>
        <span class="ck ck-button__label">Surligner</span>
    `;
    highlightButton.title = 'Surligner le texte';
    
    highlightButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        // Appliquer le surlignage jaune
        editor.model.change(writer => {
            const selection = editor.model.document.selection;
            if (!selection.isCollapsed) {
                writer.setSelectionAttribute('fontBackgroundColor', '#ffff00');
            }
        });
    });
    
    // Insérer les boutons dans la toolbar
    setTimeout(() => {
        const toolbarElement = toolbar.element.querySelector('.ck-toolbar__items');
        if (toolbarElement) {
            // Ajouter un séparateur
            const separator = document.createElement('span');
            separator.className = 'ck ck-toolbar__separator';
            toolbarElement.appendChild(separator);
            
            // Ajouter les nouveaux boutons
            toolbarElement.appendChild(colorButton);
            toolbarElement.appendChild(highlightButton);
        }
    }, 100);
}

function addMediaLibraryButton(editor) {
    // Ajouter un bouton personnalisé dans la toolbar
    const toolbar = editor.ui.view.toolbar;
    const mediaButton = document.createElement('button');
    mediaButton.type = 'button'; // Important: empêcher la soumission de formulaire
    mediaButton.className = 'ck ck-button ck-off';
    mediaButton.innerHTML = `
        <svg class="ck ck-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="ck ck-button__label">Médiathèque</span>
    `;
    mediaButton.title = 'Médiathèque';
    
    mediaButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopImmediatePropagation();
        
        openMediaModal(function(url) {
            try {
                editor.model.change(writer => {
                    const imageElement = writer.createElement('imageBlock', { src: url });
                    editor.model.insertContent(imageElement, editor.model.document.selection);
                });
            } catch (error) {
                console.error('Erreur lors de l\'insertion:', error);
            }
        });
        
        return false;
    });
    
    // Insérer le bouton dans la toolbar
    setTimeout(() => {
        const toolbarElement = toolbar.element.querySelector('.ck-toolbar__items');
        if (toolbarElement) {
            toolbarElement.appendChild(mediaButton);
        }
    }, 100);
}

// Ouvre la modale médiathèque
function openMediaModal(callback) {
    console.log('=== DÉBUT openMediaModal ===');
    const modal = document.getElementById('mediaModal');
    if (!modal) {
        console.error('Modal mediaModal non trouvé !');
        return;
    }
    
    console.log('Modal trouvé, ouverture...');
    
    // Sauvegarder la position de scroll actuelle
    const scrollY = window.scrollY;
    
    // S'assurer que le modal est centré par rapport au viewport
    modal.style.position = 'fixed';
    modal.style.top = '0';
    modal.style.left = '0';
    modal.style.width = '100vw';
    modal.style.height = '100vh';
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
    modal.style.zIndex = '9999';
    modal.style.margin = '0';
    modal.style.padding = '1rem';
    
    // Empêcher le scroll du body
    document.body.classList.add('modal-open');
    document.body.style.top = `-${scrollY}px`;
    
    modal.classList.remove('hidden');
    window._mediaInsertCallback = callback;
    
    // Empêcher la fermeture automatique du modal
    if (window.event) {
        window.event.stopPropagation();
        window.event.preventDefault();
    }
    
    console.log('Chargement de la liste des médias...');
    loadMediaList();
    console.log('=== FIN openMediaModal ===');
}

function closeMediaModal() {
    console.log('=== FERMETURE du modal ===');
    const modal = document.getElementById('mediaModal');
    if (modal) {
        // Récupérer la position de scroll sauvegardée
        const scrollY = document.body.style.top;
        
        modal.classList.add('hidden');
        modal.style.display = 'none';
        
        // Restaurer le scroll du body
        document.body.classList.remove('modal-open');
        document.body.style.position = '';
        document.body.style.top = '';
        document.body.style.width = '';
        document.body.style.overflow = '';
        
        // Restaurer la position de scroll
        if (scrollY) {
            window.scrollTo(0, parseInt(scrollY || '0') * -1);
        }
        
        window._mediaInsertCallback = null;
    }
}

// Charger la liste des images depuis le backend
function loadMediaList() {
    console.log('Chargement de la liste des médias...');
    fetch("{{ route('admin.media.list') }}")
        .then(res => {
            console.log('Réponse reçue:', res.status);
            if (!res.ok) {
                throw new Error('Erreur réseau: ' + res.status);
            }
            return res.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            const mediaList = document.getElementById('mediaList');
            mediaList.innerHTML = '';
            
            if (data.length === 0) {
                mediaList.innerHTML = '<p class="text-gray-500 text-center col-span-full">Aucune image disponible</p>';
                return;
            }
            
            data.forEach((media, index) => {
                console.log('Traitement du média:', media);
                const div = document.createElement('div');
                div.className = 'border rounded-lg overflow-hidden cursor-pointer hover:shadow-lg transition duration-200';
                div.innerHTML = `
                    <img src="${media.url}" 
                         alt="${media.name}" 
                         class="w-full h-24 object-cover"
                         onerror="console.error('Erreur de chargement image:', this.src); this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBzdHJva2U9IiNjY2MiIGZpbGw9IiNmZmYiLz4KPHN2Zz4K';">
                    <div class="p-2">
                        <p class="text-xs text-gray-600 truncate">${media.name}</p>
                    </div>
                `;
                div.onclick = function(e) {
                    e.stopPropagation();
                    console.log('Image sélectionnée:', media.url);
                    if(window._mediaInsertCallback) {
                        window._mediaInsertCallback(media.url);
                    }
                    closeMediaModal();
                };
                mediaList.appendChild(div);
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des médias:', error);
            const mediaList = document.getElementById('mediaList');
            mediaList.innerHTML = '<p class="text-red-500 text-center col-span-full">Erreur lors du chargement</p>';
        });
}

// Upload d'image dans la médiathèque
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('mediaUploadForm');
    if(uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Upload en cours...');
            const formData = new FormData(uploadForm);
            const submitButton = uploadForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Désactiver le bouton pendant l'upload
            submitButton.disabled = true;
            submitButton.textContent = 'Upload...';
            
            fetch(uploadForm.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => {
                console.log('Réponse upload:', res.status);
                if (!res.ok) {
                    throw new Error('Erreur upload: ' + res.status);
                }
                return res.json();
            })
            .then(data => {
                console.log('Upload réussi:', data);
                if(data.success) {
                    loadMediaList(); // Recharger la liste
                    uploadForm.reset(); // Vider le formulaire
                    
                    // Afficher un message de succès
                    const successMsg = document.createElement('div');
                    successMsg.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                    successMsg.textContent = data.message || 'Image uploadée avec succès';
                    uploadForm.parentNode.insertBefore(successMsg, uploadForm);
                    
                    // Supprimer le message après 3 secondes
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'upload');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'upload:', error);
                // Afficher un message d'erreur
                const errorMsg = document.createElement('div');
                errorMsg.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
                errorMsg.textContent = 'Erreur: ' + error.message;
                uploadForm.parentNode.insertBefore(errorMsg, uploadForm);
                
                // Supprimer le message après 5 secondes
                setTimeout(() => errorMsg.remove(), 5000);
            })
            .finally(() => {
                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    }

    // Initialiser CKEditor
    initializeCKEditor();
    
    // Calcul automatique du total des bénéficiaires
    function calculateBeneficiariesTotal() {
        const hommes = parseInt(document.getElementById('beneficiaires_hommes')?.value || 0);
        const femmes = parseInt(document.getElementById('beneficiaires_femmes')?.value || 0);
        const enfants = parseInt(document.getElementById('beneficiaires_enfants')?.value || 0);
        
        const total = hommes + femmes + enfants;
        
        const totalField = document.getElementById('beneficiaires_total');
        if (totalField) {
            totalField.value = total;
        }
    }
    
    // Ajouter les écouteurs d'événements pour le calcul automatique
    const beneficiaryFields = ['beneficiaires_hommes', 'beneficiaires_femmes', 'beneficiaires_enfants'];
    beneficiaryFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', calculateBeneficiariesTotal);
            field.addEventListener('change', calculateBeneficiariesTotal);
        }
    });
    
    // Calculer le total initial si les champs ont des valeurs
    calculateBeneficiariesTotal();
    
    // Fonction d'aperçu d'image pour le projet
    window.previewImageProjet = function(input) {
        const preview = document.getElementById('image-preview-projet');
        const placeholder = document.getElementById('image-placeholder-projet');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    };
    
    // Gestionnaire pour empêcher la fermeture du modal par clic extérieur
    const modal = document.getElementById('mediaModal');
    if (modal) {
        // Gestionnaire pour le clic sur l'overlay (fermeture)
        modal.addEventListener('click', function(e) {
            console.log('Clic sur modal, target:', e.target);
            if (e.target === modal) {
                console.log('Clic sur overlay - fermeture du modal');
                closeMediaModal();
            }
        });
        
        // Empêcher la propagation des clics à l'intérieur du modal
        const modalContent = modal.querySelector('.bg-white');
        if (modalContent) {
            modalContent.addEventListener('click', function(e) {
                console.log('Clic à l\'intérieur du modal - propagation stoppée');
                e.stopPropagation();
            });
        }
    }
});
</script>
