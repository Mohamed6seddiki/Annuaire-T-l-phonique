<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($standard) ? 'Modifier' : 'Ajouter' }} un employé</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24; vertical-align: middle; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen">
    <div class="max-w-2xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <a href="{{ route('standards.index') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1 mb-2">
                    <span class="material-symbols-outlined text-base">arrow_back</span>
                    Retour à la liste
                </a>
                <h1 class="text-2xl font-bold text-on-surface">
                    {{ isset($standard) ? 'Modifier' : 'Ajouter' }} un employé
                </h1>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <form action="{{ isset($standard) ? route('standards.update', $standard) : route('standards.store') }}"
                  method="POST">
                @csrf
                @isset($standard) @method('PUT') @endisset

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="numero">Numéro</label>
                        <input id="numero" name="numero" value="{{ old('numero', $standard->numero ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm" required>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="nom">Nom</label>
                        <input id="nom" name="nom" value="{{ old('nom', $standard->nom ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="id_direction">Direction</label>
                        <select id="id_direction" name="id_direction"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm bg-white" required>
                            <option value="">—</option>
                            @foreach ($directions as $d)
                                <option value="{{ $d->id }}" {{ old('id_direction', $standard->id_direction ?? '') == $d->id ? 'selected' : '' }}>
                                    {{ $d->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="id_sdirection">Sous-direction</label>
                        <select id="id_sdirection" name="id_sdirection"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm bg-white" required>
                            <option value="">—</option>
                            @foreach ($sdirections as $sd)
                                <option value="{{ $sd->id }}" {{ old('id_sdirection', $standard->id_sdirection ?? '') == $sd->id ? 'selected' : '' }}>
                                    {{ $sd->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="id_departement">Département</label>
                        <select id="id_departement" name="id_departement"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm bg-white" required>
                            <option value="">—</option>
                            @foreach ($departements as $dep)
                                <option value="{{ $dep->id }}" {{ old('id_departement', $standard->id_departement ?? '') == $dep->id ? 'selected' : '' }}>
                                    {{ $dep->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="id_site">Site</label>
                        <select id="id_site" name="id_site"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm bg-white" required>
                            <option value="">—</option>
                            @foreach ($sites as $s)
                                <option value="{{ $s->id }}" {{ old('id_site', $standard->id_site ?? '') == $s->id ? 'selected' : '' }}>
                                    {{ $s->libelle }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="service">Service</label>
                        <input id="service" name="service" value="{{ old('service', $standard->service ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="niveau">Niveau</label>
                        <input id="niveau" name="niveau" value="{{ old('niveau', $standard->niveau ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm">
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-medium text-gray-700" for="type">Type</label>
                        <input id="type" name="type" value="{{ old('type', $standard->type ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none text-sm">
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('standards.index') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-2 text-sm font-medium bg-[#2563eb] text-white rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        {{ isset($standard) ? 'Enregistrer' : 'Ajouter' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
