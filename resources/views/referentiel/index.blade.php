<!DOCTYPE html>
<html class="light" lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Référentiel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24; vertical-align: middle; }
        @keyframes modal-in { from { opacity:0; transform:scale(0.95) translateY(10px); } to { opacity:1; transform:scale(1) translateY(0); } }
        .animate-modal-in { animation: modal-in 0.2s ease-out; }
    </style>
</head>
<body class="bg-background text-on-surface antialiased min-h-screen">

@php
$sections = [
    'standards' => ['icon' => 'list_alt', 'label' => 'Liste des employés', 'model' => 'standard', 'route_type' => null],
    'direction' => ['icon' => 'account_tree', 'label' => 'Directions', 'model' => 'direction', 'route_type' => 'direction'],
    'sdirection' => ['icon' => 'account_tree', 'label' => 'Sous-directions', 'model' => 'sdirection', 'route_type' => 'sdirection'],
    'departement' => ['icon' => 'layers', 'label' => 'Départements', 'model' => 'departement', 'route_type' => 'departement'],
    'site' => ['icon' => 'location_on', 'label' => 'Sites', 'model' => 'site', 'route_type' => 'site'],
];
$items_map = [
    'standards' => $standards,
    'direction' => $directions,
    'sdirection' => $sdirections,
    'departement' => $departements,
    'site' => $sites,
];
@endphp

<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-on-surface">
            <span class="material-symbols-outlined text-3xl text-primary align-middle mr-2">database</span>
            Référentiel
        </h1>
        <a href="{{ route('dashboard') }}"
           class="text-sm text-blue-600 hover:underline flex items-center gap-1">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Tableau de bord
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 px-4 py-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    @foreach (['direction', 'sdirection', 'departement', 'site'] as $key)
        @php $sec = $sections[$key]; @endphp
        <div class="mb-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden" id="section-{{ $key }}">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">{{ $sec['icon'] }}</span>
                    {{ $sec['label'] }}
                    <span class="text-sm font-normal text-gray-500 ml-1">({{ $items_map[$key]->count() }})</span>
                </h2>
                <button onclick="openAddModal('{{ $key }}')"
                        class="text-sm font-medium text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">add</span>
                    Ajouter
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Libellé</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Libellé (Arabe)</th>
                            <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center w-24">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($items_map[$key] as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $item->libelle }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600" dir="rtl">{{ $item->libelle_arb }}</td>
                            <td class="px-4 py-3 text-sm text-center whitespace-nowrap">
                                <button onclick="openEditModal('{{ $key }}', '{{ $item->id }}', '{{ addslashes($item->libelle) }}', '{{ addslashes($item->libelle_arb ?? '') }}')"
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Modifier">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                </button>
                                <form action="{{ route('referentiel.destroy', [$key, $item->id]) }}" method="POST"
                                      onsubmit="return confirm('Supprimer {{ $sec['label'] }} « {{ $item->libelle }} » ?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Supprimer">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="px-4 py-8 text-center text-gray-500">Aucun élément.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    {{-- Standards table --}}
    <div class="mb-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h2 class="text-lg font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">list_alt</span>
                Liste des employés
                <span class="text-sm font-normal text-gray-500 ml-1">({{ $standards->total() }})</span>
            </h2>
            <button onclick="openAddEmployeeModal()"
                    class="text-sm font-medium text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-base">add</span>
                Ajouter
            </button>
        </div>
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
                        <th class="px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider text-center w-28">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($standards as $s)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $s->numero }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $s->nom }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $s->direction?->libelle }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $s->sdirection?->libelle }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $s->departement?->libelle }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $s->service }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $s->site?->libelle }}</td>
                        <td class="px-4 py-3 text-sm text-center whitespace-nowrap">
                            <a href="{{ route('standards.edit', $s) }}"
                               class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all inline-block" title="Modifier">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <form action="{{ route('standards.destroy', $s) }}" method="POST"
                                  onsubmit="return confirm('Supprimer {{ $s->nom }} ?')" class="inline">
                                @csrf @method('DELETE')
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Supprimer">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-8 text-center text-gray-500">Aucun employé.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($standards->hasPages())
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $standards->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Shared modal --}}
<div id="ref-modal" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" id="ref-modal-overlay"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-md max-h-[90vh] overflow-y-auto animate-modal-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 id="ref-modal-title" class="text-lg font-semibold text-gray-900">Ajouter</h2>
                <button id="ref-modal-close" class="p-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="ref-modal-form" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" id="ref-form-type" name="type" value="">
                <input type="hidden" id="ref-form-method" value="POST">
                @method('POST')
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700" for="ref-form-libelle">Libellé</label>
                    <input id="ref-form-libelle" name="libelle" required
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-medium text-gray-700" for="ref-form-libelle-arb">Libellé (Arabe)</label>
                    <input id="ref-form-libelle-arb" name="libelle_arb" dir="rtl"
                           class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                </div>
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" id="ref-modal-cancel"
                            class="px-5 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-2 rounded-lg text-sm font-semibold bg-[#2563eb] text-white hover:bg-blue-700 transition-colors shadow-sm">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Shared reference modal
const refModal = document.getElementById('ref-modal');
const refOverlay = document.getElementById('ref-modal-overlay');
const refClose = document.getElementById('ref-modal-close');
const refCancel = document.getElementById('ref-modal-cancel');
const refForm = document.getElementById('ref-modal-form');
const refTitle = document.getElementById('ref-modal-title');
const refType = document.getElementById('ref-form-type');
const refMethodInput = document.getElementById('ref-form-method');
const refLibelle = document.getElementById('ref-form-libelle');
const refLibelleArb = document.getElementById('ref-form-libelle-arb');

function openAddModal(type) {
    refTitle.textContent = 'Ajouter une ' + (type === 'direction' ? 'direction' : type === 'sdirection' ? 'sous-direction' : type === 'departement' ? 'département' : 'site');
    refType.value = type;
    refForm.action = '{{ url("referentiel") }}/' + type;
    refMethodInput.value = 'POST';
    refLibelle.value = '';
    refLibelleArb.value = '';
    refModal.classList.remove('hidden');
}

function openEditModal(type, id, libelle, libelleArb) {
    refTitle.textContent = 'Modifier';
    refType.value = type;
    refForm.action = '{{ url("referentiel") }}/' + type + '/' + id;
    refMethodInput.value = 'PUT';
    refLibelle.value = libelle;
    refLibelleArb.value = libelleArb;
    refModal.classList.remove('hidden');
}

function closeRefModal() {
    refModal.classList.add('hidden');
}

refOverlay.addEventListener('click', closeRefModal);
refClose.addEventListener('click', closeRefModal);
refCancel.addEventListener('click', closeRefModal);

refForm.addEventListener('submit', function(e) {
    if (refMethodInput.value === 'PUT') {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'PUT';
        this.appendChild(input);
    }
});
</script>

{{-- Employee modal --}}
<div id="employee-modal" class="fixed inset-0 z-[60] hidden">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" id="employee-modal-overlay"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg max-h-[90vh] overflow-y-auto animate-modal-in">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 id="employee-modal-title" class="text-lg font-semibold text-gray-900">Ajouter un employé</h2>
                <button id="employee-modal-close"
                        class="p-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="employee-form" class="p-6 space-y-5" action="{{ route('standards.store') }}" method="POST">
                @csrf
                <input type="hidden" id="employee-form-id" name="id" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-nom">Nom</label>
                        <input id="form-nom" name="nom" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400"
                               placeholder="Dupont">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-numero">Numéro</label>
                        <input id="form-numero" name="numero" required
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400"
                               placeholder="0001">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-id-direction">Direction</label>
                        <select id="form-id-direction" name="id_direction" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                            <option value="">—</option>
                            @foreach ($directions as $d)
                                <option value="{{ $d->id }}">{{ $d->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-id-sdirection">Sous-direction</label>
                        <select id="form-id-sdirection" name="id_sdirection" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                            <option value="">—</option>
                            @foreach ($sdirections as $sd)
                                <option value="{{ $sd->id }}">{{ $sd->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-id-departement">Département</label>
                        <select id="form-id-departement" name="id_departement" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                            <option value="">—</option>
                            @foreach ($departements as $dep)
                                <option value="{{ $dep->id }}">{{ $dep->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-id-site">Site</label>
                        <select id="form-id-site" name="id_site" required
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                            <option value="">—</option>
                            @foreach ($sites as $s)
                                <option value="{{ $s->id }}">{{ $s->libelle }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-service">Service</label>
                        <input id="form-service" name="service"
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400"
                               placeholder="Support">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-niveau">Niveau</label>
                        <input id="form-niveau" name="niveau"
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400"
                               placeholder="Cadre">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-type">Type</label>
                        <input id="form-type" name="type"
                               class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400"
                               placeholder="CDI">
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" id="employee-modal-cancel"
                            class="px-6 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-lg text-sm font-semibold bg-[#2563eb] text-white hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">add</span>
                        <span>Ajouter</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Employee modal
const empModal = document.getElementById('employee-modal');
const empOverlay = document.getElementById('employee-modal-overlay');
const empClose = document.getElementById('employee-modal-close');
const empCancel = document.getElementById('employee-modal-cancel');
const empForm = document.getElementById('employee-form');
const empTitle = document.getElementById('employee-modal-title');
const empStoreRoute = '{{ route('standards.store') }}';

function openAddEmployeeModal() {
    empTitle.textContent = 'Ajouter un employé';
    empForm.reset();
    document.getElementById('employee-form-id').value = '';
    empModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEmployeeModal() {
    empModal.classList.add('hidden');
    document.body.style.overflow = '';
    empForm.reset();
    document.getElementById('employee-form-id').value = '';
}

empOverlay.addEventListener('click', closeEmployeeModal);
empClose.addEventListener('click', closeEmployeeModal);
empCancel.addEventListener('click', closeEmployeeModal);

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if (!empModal.classList.contains('hidden')) closeEmployeeModal();
        else if (!refModal.classList.contains('hidden')) closeRefModal();
    }
});

empForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(empForm);
    const submitBtn = empForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">refresh</span>';

    try {
        const response = await fetch(empStoreRoute, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        });

        if (!response.ok) {
            const err = await response.json();
            alert(Object.values(err.errors || { message: 'Erreur' }).flat().join('\n'));
            return;
        }

        closeEmployeeModal();
        window.location.reload();
    } catch (error) {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-lg">add</span><span>Ajouter</span>';
    }
});
</script>

</body>
</html>
