<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer un utilisateur</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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