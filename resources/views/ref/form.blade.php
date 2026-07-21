<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24; vertical-align: middle; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen">
    <div class="max-w-lg mx-auto px-4 py-8">
        <a href="{{ route("$route.index") }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-4">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Retour
        </a>
        <h1 class="text-2xl font-bold text-on-surface mb-6">{{ $title }}</h1>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <form action="{{ isset($item) ? route("$route.update", $item) : route("$route.store") }}" method="POST">
                @csrf
                @isset($item) @method('PUT') @endisset

                @foreach ($fields as $f)
                <div class="space-y-1 mb-4">
                    <label class="text-sm font-medium text-gray-700" for="{{ $f['name'] }}">{{ $f['label'] }}</label>
                    @if ($f['type'] === 'text')
                    <input id="{{ $f['name'] }}" name="{{ $f['name'] }}"
                           value="{{ old($f['name'], $item->{$f['name']} ?? '') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm">
                    @endif
                </div>
                @endforeach

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route("$route.index") }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 text-sm font-medium bg-[#2563eb] text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        {{ isset($item) ? 'Enregistrer' : 'Ajouter' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
