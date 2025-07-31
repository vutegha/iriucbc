@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="IRI-UCBC Logo">
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Connectez-vous à votre compte
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Ou
                <a href="{{ route('register') }}" class="font-medium text-iri-primary hover:text-iri-secondary">
                    créez un nouveau compte
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Adresse email</label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-iri-primary focus:border-iri-primary focus:z-10 sm:text-sm @error('email') border-red-300 @enderror" 
                           placeholder="Adresse email"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required 
                           class="relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-iri-primary focus:border-iri-primary focus:z-10 sm:text-sm @error('password') border-red-300 @enderror" 
                           placeholder="Mot de passe">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" 
                           name="remember" 
                           type="checkbox" 
                           class="h-4 w-4 text-iri-primary focus:ring-iri-primary border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Se souvenir de moi
                    </label>
                </div>

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="font-medium text-iri-primary hover:text-iri-secondary">
                        Mot de passe oublié ?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-iri-primary hover:bg-iri-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-colors duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt text-iri-accent group-hover:text-white"></i>
                    </span>
                    Se connecter
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
