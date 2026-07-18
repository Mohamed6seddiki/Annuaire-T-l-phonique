<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des employés</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24; vertical-align: middle; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-on-surface">Liste des employés</h1>
            <a href="{{ route('standards.create') }}"
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
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">N°</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Nom</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Direction</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Sous-direction</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Département</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Service</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Site</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($standards as $standard)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $standard->numero }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $standard->nom }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $standard->direction?->libelle }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $standard->sdirection?->libelle }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $standard->departement?->libelle }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $standard->service }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $standard->site?->libelle }}</td>
                            <td class="px-4 py-3 text-sm text-center whitespace-nowrap">
                                <a href="{{ route('standards.show', $standard) }}"
                                   class="inline-block p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Voir">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                                <a href="{{ route('standards.edit', $standard) }}"
                                   class="inline-block p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Modifier">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </a>
                                <form action="{{ route('standards.destroy', $standard) }}" method="POST"
                                      onsubmit="return confirm('Supprimer cet employé ?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Supprimer">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-gray-500">
                                <p>Aucun employé trouvé.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $standards->links() }}
            </div>
        </div>
    </div>
</body>
</html>
