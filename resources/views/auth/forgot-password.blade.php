<x-guest-layout>

    <!-- Logo + Titre -->
    <div class="mb-6 flex flex-col items-center text-center">
        <a href="/">
            <img src="{{ asset('Radio-dz.png') }}" alt="Logo Radio Algérienne" class="w-20 h-auto mb-3 dark:hidden">
            <img src="{{ asset('Radio-dz-blanc.png') }}" alt="Logo Radio Algérienne" class="w-20 h-auto mb-3 hidden dark:block">
        </a>
        <h2 class="text-lg font-bold text-[#2563eb]">Radio Algérienne</h2>
        <p class="text-sm text-gray-500 mt-1">Réinitialisation du mot de passe</p>
    </div>

    <!-- Séparateur -->
    <div class="border-t border-gray-200 mb-6"></div>

    <!-- Description -->
    <div class="mb-5 text-sm text-gray-600 text-center">
        {{ __('Mot de passe oublié ? Aucun problème. Indiquez votre adresse e-mail et nous vous enverrons un lien de réinitialisation.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Adresse e-mail -->
        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <div class="relative mt-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                   
                </span>
                <x-text-input 
                    id="email" 
                    class="block w-full pl-9" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required autofocus
                    
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Boutons -->
        <div class="mt-6 flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm text-[#2563eb] hover:underline">
                ← Retour à la connexion
            </a>

            <button type="submit" class="bg-primary-container text-on-primary-container px-4 py-2.5 rounded-lg font-medium shadow-sm hover:opacity-90 active:scale-95 transition-all text-sm">
                {{ __('Envoyer le lien') }}
            </button>
        </div>

    </form>

    <!-- Footer -->
    <p class="text-center text-xs text-gray-400 mt-6">
        © {{ date('Y') }} Radio Algérienne
    </p>

</x-guest-layout>