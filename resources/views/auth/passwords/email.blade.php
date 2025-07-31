@extends('layouts.app')

@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-iri-primary">
                <i class="fas fa-key text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Mot de passe oublié ?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.
            </p>
        </div>

        @if (session('status'))
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('status') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Adresse email
                </label>
                <div class="mt-1 relative">
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           value="{{ old('email') }}"
                           class="appearance-none relative block w-full px-3 py-2 border @error('email') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-iri-primary focus:border-iri-primary focus:z-10 sm:text-sm"
                           placeholder="votre.email@exemple.com">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-iri-primary hover:bg-iri-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-colors duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-paper-plane text-iri-accent group-hover:text-white"></i>
                    </span>
                    Envoyer le lien de réinitialisation
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="font-medium text-iri-primary hover:text-iri-secondary transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Retour à la connexion
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
