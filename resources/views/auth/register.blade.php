@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="IRI-UCBC Logo">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Créer un nouveau compte
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="{{ route('login') }}" class="font-medium text-iri-primary hover:text-iri-secondary">
                    connectez-vous à votre compte existant
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom complet</label>
                    <input id="name" 
                           name="name" 
                           type="text" 
                           autocomplete="name" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-iri-primary focus:border-iri-primary @error('name') border-red-300 @enderror" 
                           placeholder="Votre nom complet"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse email</label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-iri-primary focus:border-iri-primary @error('email') border-red-300 @enderror" 
                           placeholder="votre.email@exemple.com"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="new-password" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-iri-primary focus:border-iri-primary @error('password') border-red-300 @enderror" 
                           placeholder="Minimum 8 caractères">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           autocomplete="new-password" 
                           required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-iri-primary focus:border-iri-primary" 
                           placeholder="Répétez votre mot de passe">
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Type de compte</label>
                    <select id="role" 
                            name="role" 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-iri-primary focus:border-iri-primary">
                        <option value="user">Utilisateur</option>
                        <option value="editor">Éditeur</option>
                        <option value="moderator">Modérateur</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        Le rôle administrateur doit être assigné par un administrateur existant
                    </p>
                </div>
            </div>

            <div class="flex items-center">
                <input id="agree-terms" 
                       name="agree_terms" 
                       type="checkbox" 
                       required
                       class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                <label for="agree-terms" class="ml-2 block text-sm text-gray-900">
                    J'accepte les 
                    <a href="#" class="text-iri-primary hover:text-iri-secondary">conditions d'utilisation</a>
                    et la 
                    <a href="#" class="text-iri-primary hover:text-iri-secondary">politique de confidentialité</a>
                </label>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-iri-primary hover:bg-iri-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-colors duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-iri-accent group-hover:text-white"></i>
                    </span>
                    Créer mon compte
                </button>
            </div>

            @if (session('error'))
                <div class="mt-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mt-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
        </form>
    </div>
</div>
@endsection
