@extends('layouts.app')

@section('title', 'Nouveau mot de passe')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-iri-primary">
                <i class="fas fa-lock text-white text-xl"></i>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Nouveau mot de passe
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Saisissez votre nouveau mot de passe ci-dessous.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
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
                           value="{{ $email ?? old('email') }}"
                           readonly
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md bg-gray-50 focus:outline-none focus:ring-iri-primary focus:border-iri-primary focus:z-10 sm:text-sm">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Nouveau mot de passe
                </label>
                <div class="mt-1 relative">
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="new-password" 
                           required 
                           class="appearance-none relative block w-full px-3 py-2 border @error('password') border-red-300 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-iri-primary focus:border-iri-primary focus:z-10 sm:text-sm"
                           placeholder="Nouveau mot de passe">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" 
                                onclick="togglePassword('password')"
                                class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password-eye"></i>
                        </button>
                    </div>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                    Confirmer le mot de passe
                </label>
                <div class="mt-1 relative">
                    <input id="password_confirmation" 
                           name="password_confirmation" 
                           type="password" 
                           autocomplete="new-password" 
                           required 
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-iri-primary focus:border-iri-primary focus:z-10 sm:text-sm"
                           placeholder="Confirmer le mot de passe">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <button type="button" 
                                onclick="togglePassword('password_confirmation')"
                                class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password_confirmation-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-iri-primary hover:bg-iri-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-iri-primary transition-colors duration-200">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-save text-iri-accent group-hover:text-white"></i>
                    </span>
                    Réinitialiser le mot de passe
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

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const eye = document.getElementById(inputId + '-eye');
    
    if (input.type === 'password') {
        input.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}
</script>
@endsection
