<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $standard->nom }} - Employé</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24; vertical-align: middle; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen">
    <div class="max-w-3xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('standards.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-2">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Retour à la liste
                </a>
                <h1 class="text-2xl font-bold text-on-surface">{{ $standard->nom }}</h1>
                <p class="text-sm text-gray-500">N° {{ $standard->numero }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('standards.edit', $standard) }}"
                   class="bg-[#2563eb] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">edit</span>
                    Modifier
                </a>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm divide-y divide-gray-100">
            <div class="grid grid-cols-2 gap-6 p-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Direction</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->direction?->libelle }}</p>
                    @if ($standard->direction?->libelle_arb)
                        <p class="text-xs text-gray-400" dir="rtl">{{ $standard->direction->libelle_arb }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Sous-direction</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->sdirection?->libelle }}</p>
                    @if ($standard->sdirection?->libelle_arb)
                        <p class="text-xs text-gray-400" dir="rtl">{{ $standard->sdirection->libelle_arb }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Département</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->departement?->libelle }}</p>
                    @if ($standard->departement?->libelle_arb)
                        <p class="text-xs text-gray-400" dir="rtl">{{ $standard->departement->libelle_arb }}</p>
                    @endif
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Site</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->site?->libelle }}</p>
                    @if ($standard->site?->libelle_arb)
                        <p class="text-xs text-gray-400" dir="rtl">{{ $standard->site->libelle_arb }}</p>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6 p-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Service</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->service ?? '—' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Niveau</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->niveau ?? '—' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Type</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->type ?? '—' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Créé le</label>
                    <p class="text-sm font-medium text-gray-900">{{ $standard->created_at?->format('d/m/Y H:i') ?? '—' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <form action="{{ route('standards.destroy', $standard) }}" method="POST"
                  onsubmit="return confirm('Supprimer définitivement {{ $standard->nom }} ?')">
                @csrf @method('DELETE')
                <button class="px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">delete</span>
                    Supprimer
                </button>
            </form>
        </div>
    </div>
    
</body>
</html>
