<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24; vertical-align: middle; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen">
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-on-surface">{{ $title }}</h1>
            <a href="{{ route("$route.create") }}"
               class="bg-[#2563eb] text-white px-5 py-2.5 rounded-lg font-medium hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add</span>
                Ajouter
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        @foreach ($fields as $f)
                        <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">{{ Str::headline($f) }}</th>
                        @endforeach
                        <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($items as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        @foreach ($fields as $f)
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $item->$f }}</td>
                        @endforeach
                        <td class="px-4 py-3 text-sm text-center whitespace-nowrap">
                            <a href="{{ route("$route.edit", $item) }}"
                               class="inline-block p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Modifier">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <form action="{{ route("$route.destroy", $item) }}" method="POST"
                                  onsubmit="return confirm('Supprimer ?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Supprimer">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ count($fields) + 1 }}" class="px-4 py-12 text-center text-gray-500">
                            Aucun élément.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('dashboard') }}" class="text-sm text-blue-600 hover:underline">&larr; Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>
