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
                            <h3 class="text-red-800 font-semibold text-lg">{{ $errors->count() }} erreur(s) détectée(s)</h3>
                            <p class="text-red-700 text-sm mt-1 mb-3">Veuillez corriger les champs suivants :</p>
                            <div class="space-y-2 bg-white/60 rounded-lg p-3">
                                @php
                                    $fieldLabels = [
                                        'nom' => 'Nom du projet',
                                        'description' => 'Description',
                                        'resume' => 'Résumé',
                                        'date_debut' => 'Date de début',
                                        'date_fin' => 'Date de fin',
                                        'service_id' => 'Service responsable',
                                        'etat' => 'État du projet',
                                        'budget' => 'Budget',
                                        'image' => 'Image',
                                        'beneficiaires_hommes' => 'Bénéficiaires hommes',
                                        'beneficiaires_femmes' => 'Bénéficiaires femmes',
                                        'beneficiaires_enfants' => 'Bénéficiaires enfants',
                                        'beneficiaires_total' => 'Total bénéficiaires'
                                    ];
                                @endphp
                                @foreach ($errors->keys() as $field)
                                    @if(isset($fieldLabels[$field]))
                                        <div class="flex items-start p-2 bg-white/80 rounded-md border-l-4 border-red-400">
                                            <svg class="h-4 w-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            <div>
                                                <span class="font-medium text-red-800">{{ $fieldLabels[$field] }} :</span>
                                                <span class="text-red-700 text-sm">{{ $errors->first($field) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-start p-2 bg-white/80 rounded-md border-l-4 border-red-400">
                                            <svg class="h-4 w-4 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                                <circle cx="4" cy="4" r="3"/>
                                            </svg>
                                            <span class="text-red-700 text-sm">{{ $errors->first($field) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
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

        @if(session('error'))
            <div class="mb-6">
                @php
                    $errorMessage = session('error');
                    $errorType = 'system'; // Par défaut
                    $errorTitle = 'Erreur système';
                    $iconColor = 'text-orange-600';
                    $bgColor = 'bg-orange-100';
                    
                    // Analyser le type d'erreur selon le contenu du message
                    if (Str::contains($errorMessage, ['validation', 'champ', 'saisie', 'obligatoire', 'format'])) {
                        $errorType = 'validation';
                        $errorTitle = 'Erreur de saisie';
                        $iconColor = 'text-red-600';
                        $bgColor = 'bg-red-100';
                    } elseif (Str::contains($errorMessage, ['permission', 'accès', 'autorisé', 'unauthorized'])) {
                        $errorType = 'permission';
                        $errorTitle = 'Accès refusé';
                        $iconColor = 'text-yellow-600';
                        $bgColor = 'bg-yellow-100';
                    } elseif (Str::contains($errorMessage, ['stockage', 'espace', 'disk', 'storage', 'fichier'])) {
                        $errorType = 'storage';
                        $errorTitle = 'Problème de stockage';
                        $iconColor = 'text-purple-600';
                        $bgColor = 'bg-purple-100';
                    } elseif (Str::contains($errorMessage, ['serveur', 'timeout', 'memory', 'server', 'difficultés'])) {
                        $errorType = 'server';
                        $errorTitle = 'Erreur serveur';
                        $iconColor = 'text-red-600';
                        $bgColor = 'bg-red-100';
                    } elseif (Str::contains($errorMessage, ['connexion', 'réseau', 'network', 'connection'])) {
                        $errorType = 'network';
                        $errorTitle = 'Problème de connexion';
                        $iconColor = 'text-blue-600';
                        $bgColor = 'bg-blue-100';
                    } elseif (Str::contains($errorMessage, ['session', 'token', 'csrf', 'expiré'])) {
                        $errorType = 'session';
                        $errorTitle = 'Session expirée';
                        $iconColor = 'text-indigo-600';
                        $bgColor = 'bg-indigo-100';
                    } elseif (Str::contains($errorMessage, ['base de données', 'database', 'db', 'sql'])) {
                        $errorType = 'database';
                        $errorTitle = 'Erreur de base de données';
                        $iconColor = 'text-red-600';
                        $bgColor = 'bg-red-100';
                    }
                @endphp
                
                <div class="bg-gradient-to-r from-orange-50 to-red-50 border border-orange-200 rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 {{ $bgColor }} rounded-xl flex items-center justify-center">
                                @if($errorType === 'validation')
                                    <!-- Icône pour erreur de validation -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                @elseif($errorType === 'permission')
                                    <!-- Icône pour erreur de permission -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                @elseif($errorType === 'storage')
                                    <!-- Icône pour erreur de stockage -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                    </svg>
                                @elseif($errorType === 'server')
                                    <!-- Icône pour erreur serveur -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                                    </svg>
                                @elseif($errorType === 'network')
                                    <!-- Icône pour erreur réseau -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path>
                                    </svg>
                                @elseif($errorType === 'session')
                                    <!-- Icône pour session expirée -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($errorType === 'database')
                                    <!-- Icône pour erreur de base de données -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path>
                                    </svg>
                                @else
                                    <!-- Icône par défaut pour erreur système -->
                                    <svg class="h-5 w-5 {{ $iconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-orange-800 font-semibold">{{ $errorTitle }}</h3>
                            <p class="text-orange-700 text-sm mt-1">{{ $errorMessage }}</p>
                            
                            @if($errorType === 'session')
                                <p class="text-orange-600 text-xs mt-2">
                                    💡 <strong>Conseil :</strong> Rechargez la page et réessayez.
                                </p>
                            @elseif($errorType === 'validation')
                                <p class="text-red-600 text-xs mt-2">
                                    💡 <strong>Conseil :</strong> Vérifiez les champs marqués en rouge ci-dessous.
                                </p>
                            @elseif($errorType === 'server')
                                <p class="text-red-600 text-xs mt-2">
                                    💡 <strong>Conseil :</strong> Attendez quelques minutes et réessayez.
                                </p>
                            @elseif($errorType === 'storage')
                                <p class="text-purple-600 text-xs mt-2">
                                    💡 <strong>Conseil :</strong> Contactez l'administrateur système.
                                </p>
                            @elseif($errorType === 'permission')
                                <p class="text-yellow-600 text-xs mt-2">
                                    💡 <strong>Conseil :</strong> Contactez un administrateur pour obtenir les permissions.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Contenu principal -->
        <div class="w-full">
            
            <!-- Formulaire principal -->
            <div class="w-full">
                <form id="projet-form" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="space-y-8" data-form="project">
                    @csrf
                    @if(isset($projet))
                        @method('PUT')
                    @endif

                    <!-- Honeypot pour détecter les bots (invisible et avec attributs renforcés) -->
                    <input type="text" 
                           name="website" 
                           style="display:none !important; position:absolute !important; left:-9999px !important; visibility:hidden !important;" 
                           autocomplete="off" 
                           tabindex="-1" 
                           readonly
                           aria-hidden="true"
                           data-lpignore="true"
                           data-form-type="other">

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
                                          rows="4"
                                          value="{{ old('resume', $projet->resume ?? '') }}"
                                          class="w-full px-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 @error('resume') border-red-500 bg-red-50 @enderror"
                                          placeholder="Résumé du projet - Décrivez en quelques phrases les objectifs principaux et l'impact attendu de votre projet...">{{ old('resume', $projet->resume ?? '') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">
                                    <svg class="w-3 h-3 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Ce résumé sera affiché dans les listes de projets et les aperçus. Pas de limite de caractères.
                                </p>
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
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm font-medium">$</span>
                                        </div>
                                        <input type="number" 
                                               name="budget" 
                                               id="budget"
                                               value="{{ old('budget', $projet->budget ?? '') }}"
                                               class="w-full pl-8 pr-4 py-3 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white @error('budget') border-red-500 bg-red-50 @enderror"
                                               placeholder="0">
                                    </div>
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
                                          class="wysiwyg w-full px-4 py-4 text-base border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 bg-white placeholder-gray-400 min-h-[400px] @error('description') border-red-500 bg-red-50 @enderror"
                                          style="min-height: 400px;"
                                          rows="16"
                                          required>{{ old('description', $projet->description ?? '') }}</textarea>
                                
                                <!-- Texte d'orientation formaté -->
                                <div class="mt-3 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl guide-redaction">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 mt-1">
                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-blue-800 mb-2">💡 Guide de rédaction de la description</h4>
                                            <div class="text-sm text-blue-700 space-y-2">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <div class="font-medium mb-1">📋 Structure recommandée :</div>
                                                        <ul class="text-xs space-y-1 ml-2">
                                                            <li>• <strong>Contexte</strong> et justification</li>
                                                            <li>• <strong>Objectifs</strong> principaux et secondaires</li>
                                                            <li>• <strong>Public cible</strong> et bénéficiaires</li>
                                                            <li>• <strong>Méthodologie</strong> et activités</li>
                                                        </ul>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium mb-1">🎯 Éléments clés :</div>
                                                        <ul class="text-xs space-y-1 ml-2">
                                                            <li>• <strong>Résultats attendus</strong> et indicateurs</li>
                                                            <li>• <strong>Partenariats</strong> et collaborations</li>
                                                            <li>• <strong>Calendrier</strong> et étapes</li>
                                                            <li>• <strong>Impact</strong> et durabilité</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="mt-3 p-2 bg-white/60 rounded-lg">
                                                    <div class="text-xs text-blue-600">
                                                        <strong>✨ Conseils :</strong> Utilisez des <em>listes à puces</em>, des <strong>mots clés en gras</strong>, et structurez avec des <u>sous-titres</u> pour une lecture optimale.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
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
                                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-iri-primary/20 focus:border-iri-primary transition-all duration-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-iri-primary/10 file:text-iri-primary hover:file:bg-iri-primary/20 @error('image') border-red-500 bg-red-50 @enderror"
                                                   accept=".jpg,.jpeg,.png,.gif,.webp,.svg"
                                                   onchange="previewImageProjet(this)"
                                                   data-max-size="10485760">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-2 space-y-1">
                                            <p class="text-xs text-gray-500">
                                                <strong>Formats acceptés :</strong> JPEG, JPG, PNG, GIF, WebP, SVG
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <strong>Taille maximale :</strong> 10 MB
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                <strong>Recommandations :</strong> Images haute résolution (min. 800x600px) pour une meilleure qualité d'affichage
                                            </p>
                                        </div>
                                        <div id="file-error-message" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                            <div class="flex items-center text-sm text-red-700">
                                                <svg class="h-4 w-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span id="file-error-text">Erreur de fichier</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Aperçu de l'image -->
                                    <div class="flex-shrink-0">
                                        @if(isset($projet) && $projet->image && Storage::disk('public')->exists($projet->image))
                                            <div class="space-y-2">
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
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           id="remove_image" 
                                                           name="remove_image" 
                                                           value="1"
                                                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                                    <label for="remove_image" class="ml-2 text-xs text-red-600 font-medium">
                                                        Supprimer l'image
                                                    </label>
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

/* Styles pour CKEditor - Hauteur minimale 400px */
.ck-editor {
    min-height: 450px !important; /* 400px pour le contenu + 50px pour la toolbar */
}

.ck-editor__editable {
    min-height: 400px !important;
    max-height: none !important;
    height: auto !important;
}

.ck.ck-editor__main > .ck-editor__editable {
    min-height: 400px !important;
    padding: 1rem !important;
    font-size: 14px !important;
    line-height: 1.6 !important;
}

/* Style pour le contenu d'aide dans CKEditor */
.ck-content h3 {
    color: #1e40af;
    border-bottom: 2px solid #3b82f6;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.ck-content h4 {
    color: #1f2937;
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.ck-content em {
    color: #6b7280;
    font-style: italic;
}

.ck-content blockquote {
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: 0.5rem;
}

.ck-content blockquote p {
    margin: 0;
    color: #1e40af;
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

/* Animation pour le guide de rédaction */
.guide-redaction {
    animation: slideInFromBottom 0.5s ease-out;
}

@keyframes slideInFromBottom {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
            <form id="mediaUploadForm" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data" class="mb-6">
                @csrf
                <div class="flex items-center space-x-4">
                    <input type="file" name="image" id="mediaUploadInput" accept="image/*" class="border rounded p-2 flex-1">
                    <button type="submit" class="px-4 py-2 bg-iri-primary text-white rounded hover:bg-iri-secondary transition">Uploader</button>
                </div>
                <p class="text-xs text-gray-500 mt-2">
                    <span class="text-green-600">✓ Compression automatique</span> - Les images sont optimisées automatiquement
                </p>
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

<!-- Script de compression d'images -->
<script src="{{ asset('assets/js/image-compressor.js') }}"></script>

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
            },
            // Configuration de la hauteur de l'éditeur
            ui: {
                height: 400
            }
        })
        .then(editor => {
            editorInstance = editor;
            // Rendre l'éditeur accessible globalement pour la validation
            window.descriptionEditor = editor;
            
            // Forcer la hauteur minimale de l'éditeur
            const editorElement = editor.ui.view.editable.element;
            if (editorElement) {
                editorElement.style.minHeight = '400px';
                editorElement.style.height = 'auto';
            }
            
            // Style CSS pour l'éditeur
            const editorContainer = editor.ui.view.element;
            if (editorContainer) {
                editorContainer.style.minHeight = '450px'; // 400px + toolbar
            }
            
            // Ajouter du contenu d'aide si l'éditeur est vide - DÉSACTIVÉ
            // Le contenu par défaut a été supprimé selon la demande utilisateur
            
            // Ajouter des boutons personnalisés à la toolbar
            addCustomToolbarButtons(editor);
            
            // Ajouter le bouton Médiathèque manuellement
            addMediaLibraryButton(editor);
            
            // Synchronisation avec le textarea
            editor.model.document.on('change:data', () => {
                textElement.value = editor.getData();
                // Déclencher un événement pour notifier les validateurs
                textElement.dispatchEvent(new Event('input', { bubbles: true }));
            });

            // Charger le contenu existant en mode édition
            const existingContent = textElement.value;
            if (existingContent && existingContent.trim() !== '') {
                editor.setData(existingContent);
            }
            
            // Debug : Confirmer l'initialisation
            console.log('[DEBUG] CKEditor initialisé et accessible via window.descriptionEditor');
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
    
    // Sauvegarder le callback globalement pour l'upload
    window.currentMediaCallback = callback;
    
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
    
    // Empêcher la fermeture automatique du modal
    if (window.event) {
        window.event.stopPropagation();
        window.event.preventDefault();
    }
    
    console.log('Chargement de la liste des médias...');
    loadMediaList(callback);
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
    }
}

// Charger la liste des images depuis le backend
function loadMediaList(callback) {
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
                    if(callback) {
                        callback(media.url);
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
            
            // Bouton pour réessayer avec le callback
            const retryBtn = document.createElement('button');
            retryBtn.textContent = 'Réessayer';
            retryBtn.className = 'mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600';
            retryBtn.onclick = () => loadMediaList(callback);
            mediaList.appendChild(retryBtn);
        });
}

// Upload d'image dans la médiathèque
document.addEventListener('DOMContentLoaded', function() {
    const uploadForm = document.getElementById('mediaUploadForm');
    if(uploadForm) {
        uploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Upload en cours...');
            const fileInput = uploadForm.querySelector('input[name="image"]');
            const originalFile = fileInput.files[0];
            
            if (!originalFile) {
                alert('Veuillez sélectionner un fichier');
                return;
            }
            
            const submitButton = uploadForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Désactiver le bouton pendant l'upload
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Compression...';
            
            try {
                // Compresser l'image si c'est une image
                let fileToUpload = originalFile;
                if (originalFile.type.startsWith('image/')) {
                    console.log('Compression de l\'image en cours...');
                    fileToUpload = await defaultCompressor.compressImage(originalFile);
                    
                    // Afficher les informations de compression
                    const info = defaultCompressor.getCompressionInfo(originalFile, fileToUpload);
                    console.log('Compression terminée:', {
                        originalSize: formatFileSize(originalFile.size),
                        compressedSize: formatFileSize(fileToUpload.size),
                        compressionRatio: info.compressionRatio.toFixed(1) + '%'
                    });
                }
                
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Upload...';
                
                const formData = new FormData();
                formData.append('image', fileToUpload);
                formData.append('_token', '{{ csrf_token() }}');
                
                const response = await fetch(uploadForm.action, {
                    method: 'POST',
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });
                
                console.log('Réponse upload:', response.status);
                if (!response.ok) {
                    throw new Error('Erreur upload: ' + response.status);
                }
                
                const data = await response.json();
                console.log('Upload réussi:', data);
                
                if(data.success) {
                    loadMediaList(window.currentMediaCallback); // Recharger la liste
                    uploadForm.reset(); // Vider le formulaire
                    
                    // Afficher un message de succès avec informations de compression
                    const successMsg = document.createElement('div');
                    successMsg.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
                    
                    let message = data.message || 'Image uploadée avec succès';
                    if (originalFile.type.startsWith('image/') && fileToUpload !== originalFile) {
                        const info = defaultCompressor.getCompressionInfo(originalFile, fileToUpload);
                        message += ` (Compressée: ${info.compressionRatio.toFixed(1)}% d'économie)`;
                    }
                    
                    successMsg.innerHTML = `
                        <div class="flex items-center">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            ${message}
                        </div>
                    `;
                    
                    uploadForm.parentNode.insertBefore(successMsg, uploadForm);
                    
                    // Supprimer le message après 3 secondes
                    setTimeout(() => successMsg.remove(), 3000);
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'upload');
                }
            } catch (error) {
                console.error('Erreur lors de l\'upload:', error);
                // Afficher un message d'erreur
                const errorMsg = document.createElement('div');
                errorMsg.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
                errorMsg.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Erreur: ${error.message}
                    </div>
                `;
                uploadForm.parentNode.insertBefore(errorMsg, uploadForm);
                
                // Supprimer le message après 5 secondes
                setTimeout(() => errorMsg.remove(), 5000);
            } finally {
                // Réactiver le bouton
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
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
    
    // Fonction d'aperçu d'image pour le projet avec validation
    window.previewImageProjet = function(input) {
        const preview = document.getElementById('image-preview-projet');
        const placeholder = document.getElementById('image-placeholder-projet');
        const errorDiv = document.getElementById('file-error-message');
        const errorText = document.getElementById('file-error-text');
        
        // Réinitialiser les erreurs
        errorDiv.classList.add('hidden');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validation du type de fichier
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
            if (!allowedTypes.includes(file.type)) {
                showFileError('Type de fichier non autorisé. Utilisez : JPEG, JPG, PNG, GIF, WebP ou SVG.');
                input.value = '';
                return;
            }
            
            // Validation de la taille (10MB = 10485760 bytes)
            const maxSize = 10485760;
            if (file.size > maxSize) {
                showFileError(`Fichier trop volumineux (${(file.size / 1024 / 1024).toFixed(2)} MB). Taille maximale : 10 MB.`);
                input.value = '';
                return;
            }
            
            // Validation des dimensions pour les images
            const img = new Image();
            img.onload = function() {
                if (this.width < 400 || this.height < 300) {
                    showFileError(`Image trop petite (${this.width}x${this.height}). Dimensions minimales : 400x300 pixels.`);
                    input.value = '';
                    return;
                }
                
                // Si tout est OK, afficher l'aperçu
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            };
            
            img.onerror = function() {
                showFileError('Impossible de lire ce fichier image. Veuillez choisir un autre fichier.');
                input.value = '';
            };
            
            // Créer une URL temporaire pour vérifier les dimensions
            img.src = URL.createObjectURL(file);
        }
        
        function showFileError(message) {
            errorText.textContent = message;
            errorDiv.classList.remove('hidden');
            
            // Supprimer le message d'erreur après 8 secondes
            setTimeout(() => {
                errorDiv.classList.add('hidden');
            }, 8000);
        }
    }
    
    // Validation légère pour un champ spécifique (sans afficher les erreurs)
    function validateFieldSilently(field) {
        if (!field) return true;
        
        const fieldId = field.id;
        const value = field.value.trim();
        
        switch(fieldId) {
            case 'nom':
                return value.length >= 3;
            case 'description':
                return value.length >= 50;
            case 'service_id':
                return value !== '';
            case 'date_debut':
                return value !== '';
            default:
                return true;
        }
    }
    
    // Validation complète du formulaire (avec affichage des erreurs)
    function validateForm(showErrors = false) {
        let isValid = true;
        const errors = [];
        
        // Validation du nom du projet
        const nom = document.getElementById('nom');
        if (!nom || nom.value.trim().length < 3) {
            errors.push('Le nom du projet doit contenir au moins 3 caractères.');
            if (showErrors) markFieldAsError(nom);
            isValid = false;
        } else {
            if (showErrors) clearFieldError(nom);
        }
        
        // Validation de la description
        const description = document.getElementById('description');
        if (!description || description.value.trim().length < 50) {
            errors.push('La description doit contenir au moins 50 caractères.');
            if (showErrors) markFieldAsError(description);
            isValid = false;
        } else {
            if (showErrors) clearFieldError(description);
        }
        
        // Validation du service
        const service = document.getElementById('service_id');
        if (!service || !service.value) {
            errors.push('Veuillez sélectionner un service responsable.');
            if (showErrors) markFieldAsError(service);
            isValid = false;
        } else {
            if (showErrors) clearFieldError(service);
        }
        
        // Validation de la date de début
        const dateDebut = document.getElementById('date_debut');
        if (!dateDebut || !dateDebut.value) {
            errors.push('La date de début est obligatoire.');
            if (showErrors) markFieldAsError(dateDebut);
            isValid = false;
        } else {
            if (showErrors) clearFieldError(dateDebut);
        }
        
        // Validation de la cohérence des dates
        const dateFin = document.getElementById('date_fin');
        if (dateDebut && dateFin && dateDebut.value && dateFin.value) {
            if (new Date(dateFin.value) < new Date(dateDebut.value)) {
                errors.push('La date de fin ne peut pas être antérieure à la date de début.');
                if (showErrors) markFieldAsError(dateFin);
                isValid = false;
            } else {
                if (showErrors) clearFieldError(dateFin);
            }
        }
        
        // Validation du budget
        const budget = document.getElementById('budget');
        if (budget && budget.value && parseFloat(budget.value) < 0) {
            errors.push('Le budget ne peut pas être négatif.');
            markFieldAsError(budget);
            isValid = false;
        } else if (budget) {
            clearFieldError(budget);
        }
        
        // Validation des bénéficiaires
        const benefFields = ['beneficiaires_hommes', 'beneficiaires_femmes', 'beneficiaires_enfants'];
        benefFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && field.value && parseInt(field.value) < 0) {
                errors.push('Le nombre de bénéficiaires ne peut pas être négatif.');
                markFieldAsError(field);
                isValid = false;
            } else if (field) {
                clearFieldError(field);
            }
        });
        
        // Afficher les erreurs si il y en a
        if (!isValid) {
            showValidationErrors(errors);
        } else {
            hideValidationErrors();
        }
        
        return isValid;
    }
    
    function markFieldAsError(field) {
        if (field) {
            field.classList.add('border-red-500', 'bg-red-50');
            field.classList.remove('border-gray-200', 'border-iri-primary');
        }
    }
    
    function clearFieldError(field) {
        if (field) {
            field.classList.remove('border-red-500', 'bg-red-50');
            field.classList.add('border-gray-200');
        }
    }
    
    function showValidationErrors(errors) {
        // Créer ou mettre à jour la div d'erreurs
        let errorDiv = document.getElementById('validation-errors');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.id = 'validation-errors';
            errorDiv.className = 'mb-6 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-2xl p-6 shadow-lg';
            
            const form = document.getElementById('projet-form');
            form.parentNode.insertBefore(errorDiv, form);
        }
        
        errorDiv.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-red-800 font-semibold text-lg">Erreurs de validation</h3>
                    <p class="text-red-700 text-sm mt-1 mb-3">Veuillez corriger les erreurs suivantes :</p>
                    <ul class="space-y-1 bg-white/60 rounded-lg p-3">
                        ${errors.map(error => `
                            <li class="flex items-start text-sm text-red-700">
                                <svg class="h-3 w-3 text-red-500 mt-1 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3"/>
                                </svg>
                                ${error}
                            </li>
                        `).join('')}
                    </ul>
                </div>
            </div>
        `;
        
        // Scroll vers les erreurs
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    function hideValidationErrors() {
        const errorDiv = document.getElementById('validation-errors');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
    
    // Attacher la validation au formulaire
    const form = document.getElementById('projet-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm(true)) { // Afficher les erreurs lors de la soumission
                e.preventDefault();
                return false;
            }
        });
        
        // Validation en temps réel pour certains champs (seulement après interaction)
        ['nom', 'description', 'service_id', 'date_debut'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                let hasUserInteracted = false;
                
                // Marquer l'interaction utilisateur
                field.addEventListener('input', function() {
                    hasUserInteracted = true;
                });
                
                field.addEventListener('change', function() {
                    hasUserInteracted = true;
                });
                
                // Validation seulement après interaction (sans afficher les erreurs)
                field.addEventListener('blur', function() {
                    if (hasUserInteracted && field.value.trim() !== '') {
                        validateFieldSilently(field);
                    }
                });
            }
        });
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

<!-- Script de validation avancée -->
<script src="{{ asset('assets/js/project-form-validator.js') }}"></script>

<script>
// Configuration spécifique pour ce formulaire
document.addEventListener('DOMContentLoaded', function() {
    // Marquer le formulaire pour la validation
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.setAttribute('data-form', 'project');
        
        console.log('✅ Formulaire de projet configuré pour validation avancée');
        
        // Ajouter un indicateur de progression
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            
            form.addEventListener('submit', function() {
                if (!form.querySelector('.border-red-500')) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enregistrement en cours...';
                    
                    // Réactiver le bouton après 10 secondes en cas de problème
                    setTimeout(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }, 10000);
                }
            });
        }
        
        // Améliorations pour la gestion des erreurs
        const errorSections = document.querySelectorAll('.border-red-500');
        if (errorSections.length > 0) {
            // Scroll vers la première erreur
            errorSections[0].scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            
            // Ajouter des effets visuels aux champs en erreur
            errorSections.forEach(field => {
                field.style.animation = 'shake 0.5s ease-in-out';
                
                // Retirer l'animation après
                setTimeout(() => {
                    field.style.animation = '';
                }, 500);
            });
        }
        
        // Style pour l'animation shake
        if (!document.querySelector('#shake-style')) {
            const shakeStyle = document.createElement('style');
            shakeStyle.id = 'shake-style';
            shakeStyle.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
                    20%, 40%, 60%, 80% { transform: translateX(2px); }
                }
            `;
            document.head.appendChild(shakeStyle);
        }
                        submitButton.innerHTML = originalText;
                    }, 10000);
                }
            });
        }
    }
});
</script>
