<x-guest-layout>

    <!-- Logo + Titre -->
    <div class="mb-6 flex flex-col items-center text-center">
        <a href="/">
            <img src="{{ asset('Radio-dz.png') }}" alt="Logo Radio Algérienne" class="w-20 h-auto mb-3">
        </a>
        <h2 class="text-lg font-bold text-[#2563eb]">Radio Algérienne</h2>
        <p class="text-sm text-gray-500 mt-1">Annuaire des employés</p>
    </div>

    <!-- Séparateur -->
    <div class="border-t border-gray-200 mb-6"></div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Adresse e-mail -->
        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <div class="relative mt-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">

                </span>
                <x-text-input id="email" class="block w-full pl-9" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <div class="relative mt-1">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">

                </span>
                <x-text-input id="password" class="block w-full pl-9" type="password" name="password" required
                    autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Mot de passe oublié -->
        <div class="flex items-center justify-between mt-4">


            @if (Route::has('password.request'))
            <a class="text-sm text-[#2563eb] hover:underline" href="{{ route('password.request') }}">
                {{ __('Mot de passe oublié ?') }}
            </a>
            @endif
        </div>

        <!-- Bouton Se connecter -->
        <div class="mt-6">
            <button type="submit"
                class="w-full justify-center bg-primary-container text-on-primary-container px-4 py-2.5 rounded-lg font-medium shadow-sm hover:opacity-90 active:scale-95 transition-all text-sm">
                {{ __('Se connecter') }}
            </button>
        </div>

    </form>

    <!-- Footer -->
    <p class="text-center text-xs text-gray-400 mt-6">
        © {{ date('Y') }} Radio Algérienne
    </p>

</x-guest-layout>