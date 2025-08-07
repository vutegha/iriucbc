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

        <!-- Contenu principal avec sidebar -->
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Formulaire principal -->
            <div class="flex-1">
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

                                <!-- Bénéficiaires totaux (visible seulement en édition) -->
                                @if(isset($projet))
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
                                           class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('beneficiaires_total') border-red-500 bg-red-50 @enderror"
                                           placeholder="0"
                                           min="0"
                                           readonly>
                                    <p class="text-xs text-gray-500 mt-1">Ce nombre est calculé automatiquement</p>
                                    @error('beneficiaires_total')
                                        <div class="mt-2 flex items-center text-sm text-red-600">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                @endif
                            </div>

                            <!-- Bénéficiaires détaillés (visible seulement en édition) -->
                            @if(isset($projet))
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
                            @endif
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

            <!-- Sidebar avec actions de modération (seulement en édition) -->
            @if(isset($projet))
            <div class="lg:w-80">
                <div class="space-y-6">
                    <!-- Actions de Modération -->
                    @if(auth()->user()->hasAnyRole(['admin', 'moderateur']))
                    <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Actions de Modération</h3>
                                    <p class="text-sm text-gray-600">Gestion du statut de publication</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-4">
                            <div class="bg-slate-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-700">Statut actuel :</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $projet->is_published ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $projet->is_published ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </div>
                                
                                @if($projet->published_by)
                                    <p class="text-xs text-gray-600">
                                        Publié par {{ $projet->publishedBy->name ?? 'Utilisateur supprimé' }}
                                        le {{ $projet->published_at?->format('d/m/Y à H:i') }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="space-y-3">
                                @if(!$projet->is_published)
                                    <form action="{{ route('admin.projets.publish', $projet) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 focus:ring-4 focus:ring-green-500/20 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Publier le projet
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.projets.unpublish', $projet) }}" method="POST" class="w-full">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-3 bg-orange-600 text-white font-medium rounded-xl hover:bg-orange-700 focus:ring-4 focus:ring-orange-500/20 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                            </svg>
                                            Retirer de la publication
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Actions Rapides -->
                    <div class="bg-white rounded-2xl shadow-xl border border-white/20 overflow-hidden">
                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">Actions Rapides</h3>
                                    <p class="text-sm text-gray-600">Raccourcis utiles</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-6 space-y-3">
                            <a href="{{ route('admin.projets.show', $projet) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Voir le projet
                            </a>
                            
                            <a href="{{ route('admin.projets.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-50 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                Liste des projets
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>

@include('admin.projets._form-scripts')
