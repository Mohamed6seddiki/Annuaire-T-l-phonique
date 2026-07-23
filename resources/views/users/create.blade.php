<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('Radio-dz.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Créer un utilisateur</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script>
        (function() {
            try {
                var isDark = localStorage.getItem('dark-mode') === 'true' || (!('dark-mode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', isDark);
            } catch(e) {}
        })();
    </script>
    <style>
        .dark { --bg: #0f172a; --surface: #1e293b; --surface-lowest: #0f172a; --surface-low: #1e293b; --surface-high: #334155; --on-surface: #f1f5f9; --secondary: #94a3b8; --outline-variant: #334155; --outline: #64748b; --primary: #60a5fa; --primary-container: #1d4ed8; --on-primary-container: #ffffff; --surface-container: #1e293b; }
        .dark body { background-color: #0f172a; color: #f1f5f9; }
        .dark .bg-background { background-color: var(--bg); }
        .dark .text-on-surface { color: var(--on-surface); }
        .dark .bg-surface { background-color: var(--surface); }
        .dark .bg-surface-container-lowest { background-color: var(--surface-lowest); }
        .dark .bg-surface-container-low { background-color: var(--surface-low); }
        .dark .bg-surface-container-high { background-color: var(--surface-high); }
        .dark .bg-surface-container { background-color: var(--surface-container); }
        .dark .text-secondary { color: var(--secondary); }
        .dark .text-outline { color: var(--outline); }
        .dark .text-primary { color: var(--primary); }
        .dark .border-outline-variant { border-color: var(--outline-variant); }
        .dark .bg-primary-container { background-color: var(--primary-container); }
        .dark .text-on-primary-container { color: var(--on-primary-container); }
        .dark .hover\:bg-surface-container-high:hover { background-color: var(--surface-high); }
        .dark .bg-white { background-color: var(--surface); }
        .dark .text-gray-900 { color: var(--on-surface); }
        .dark .text-gray-700, .dark .text-gray-500 { color: var(--secondary); }
        .dark .border-gray-200, .dark .border-gray-300 { border-color: var(--outline-variant); }
        .dark .bg-gray-100 { background-color: var(--surface-low); }
        .dark .hover\:bg-gray-100:hover { background-color: var(--surface-high); }
        .dark .bg-green-50 { background-color: #064e3b; }
        .dark .text-green-800 { color: #6ee7b7; }
        .dark .border-green-200 { border-color: #065f46; }
        .dark select, .dark input, .dark textarea { background-color: var(--surface); color: var(--on-surface); border-color: var(--outline-variant); }
        .dark .shadow-sm { box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.5), 0 2px 4px -2px rgb(0 0 0 / 0.5); }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen">
    <div class="max-w-3xl mx-auto px-6 py-10">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-secondary text-sm">Gestion des comptes</p>
                <h1 class="text-3xl font-semibold">Créer un utilisateur</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-primary hover:underline">Retour au tableau de bord</a>
        </div>

        @if (session('status'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="rounded-2xl border border-outline-variant bg-surface-container-lowest shadow-sm p-6">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
                @csrf

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="username" class="block text-sm font-medium text-secondary mb-2">Nom d'utilisateur</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}" required
                            class="w-full rounded-lg border border-outline-variant bg-surface px-4 py-3 outline-none focus:border-primary"
                            placeholder="Nom d'utilisateur">
                        @error('username') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-secondary mb-2">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                            class="w-full rounded-lg border border-outline-variant bg-surface px-4 py-3 outline-none focus:border-primary"
                            placeholder="utilisateur@example.com">
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="password" class="block text-sm font-medium text-secondary mb-2">Mot de passe</label>
                        <input id="password" name="password" type="password" required
                            class="w-full rounded-lg border border-outline-variant bg-surface px-4 py-3 outline-none focus:border-primary"
                            placeholder="••••••••">
                        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-secondary mb-2">Confirmer le mot de passe</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full rounded-lg border border-outline-variant bg-surface px-4 py-3 outline-none focus:border-primary"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-semibold hover:opacity-90 transition-colors">
                        Créer l'utilisateur
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>