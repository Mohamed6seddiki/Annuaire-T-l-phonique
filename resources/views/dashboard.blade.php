<!DOCTYPE html>
<html class="light" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>annuaire telephonique</title>
    <link rel="icon" type="image/png" href="{{ asset('Radio-dz.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">

    <script>
    window.isAdmin = @json(auth()-> user()-> can('admin'));
    </script>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .material-symbols-outlined {
        font-variation-settings: 'FILL'0, 'wght'400, 'GRAD'0, 'opsz'24;
        vertical-align: middle;
    }

    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }

    #table-container {
        transition: opacity 0.2s ease;
    }

    #table-container.loading {
        opacity: 0.5;
        pointer-events: none;
    }

    @keyframes modal-in {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .animate-modal-in {
        animation: modal-in 0.2s ease-out;
    }
    </style>
</head>

<body class="bg-background text-on-surface antialiased min-h-screen" data-dashboard-route="{{ route('dashboard') }}"
    data-employees-store-route="{{ route('employees.store') }}" data-employees-base-url="{{ url('/employees') }}">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <aside
            class="hidden lg:flex flex-col h-screen w-64 border-r border-outline-variant bg-surface-container-lowest p-md space-y-sm sticky top-0">
            <div class="mb-xl px-sm flex flex-col items-center text-center">
                <img src="{{ asset('Radio-dz.png') }}" alt="Logo" class="h-14 w-auto">
                <div class="font-h3 text-h3 font-bold text-primary mt-2">Radio Algérienne</div>
                <div class="text-secondary text-label-sm"></div>
            </div>
            <nav class="flex-1 space-y-1">
                <a class="flex items-center gap-3 px-3 py-2 bg-primary-container text-on-primary-container font-medium rounded-lg transition-all"
                    href="{{ route('dashboard') }}">
                    <span class="material-symbols-outlined" data-icon="group">group</span>
                    <span class="font-body-md">Employés</span>
                </a>

                @can('admin')
                <a class="flex items-center gap-3 px-3 py-2 text-secondary hover:bg-surface-container-high rounded-lg transition-all"
                    href="{{ route('users.create') }}">
                    <span class="material-symbols-outlined" data-icon="person_add">person_add</span>
                    <span class="font-body-md">Créer utilisateur</span>
                </a>
                @endcan

                <a class="flex items-center gap-3 px-3 py-2 text-secondary hover:bg-surface-container-high rounded-lg transition-all"
                    href="{{ route('profile.edit') }}">
                    <span class="material-symbols-outlined" data-icon="person">person</span>
                    <span class="font-body-md">Profil</span>
                </a>
            </nav>
            <div class="pt-md border-t border-outline-variant space-y-1">
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-3 py-2 text-secondary hover:bg-surface-container-high rounded-lg transition-all w-full text-left">
                        <span class="material-symbols-outlined" data-icon="logout">logout</span>
                        <span class="font-body-md">Déconnexion</span>
                    </button>
                </form>
                @endauth
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            <header class="bg-surface border-b border-outline-variant shadow-sm sticky top-0 z-10">
                <div class="flex justify-between items-center w-full px-lg py-md max-w-container-max mx-auto h-16">
                    <div class="flex items-center gap-4">
                        <button class="lg:hidden p-2 text-secondary">
                            <span class="material-symbols-outlined" data-icon="menu">menu</span>
                        </button>
                        <h1 class="hidden md:block font-h2 text-h2 font-semibold text-on-surface">annuaire téléphonique
                        </h1>
                        <h1 class="md:hidden font-h1-mobile text-h1-mobile font-semibold text-on-surface">annuaire
                            telephonique</h1>
                    </div>
                    @can('admin')
                    <div class="flex items-center gap-md">
                        <button id="add-employee-btn"
                            class="bg-primary-container text-on-primary-container px-4 py-2 rounded-lg font-medium shadow-sm hover:opacity-90 active:scale-95 transition-all text-sm">
                            Ajouter un employé
                        </button>
                    </div>
                    @endcan
                </div>
            </header>

            <section class="max-w-container-max mx-auto w-full px-lg py-xl">
                <form id="search-form" method="GET" action="{{ route('dashboard') }}"
                    class="flex flex-col md:flex-row gap-4 items-end bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-label-md text-secondary ml-1" for="search">Recherche globale</label>
                        <div class="relative group">
                            <span
                                class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors"
                                data-icon="search">search</span>
                            <input
                                class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-outline/70"
                                id="search" name="search" value="{{ request('search') }}"
                                placeholder="Rechercher un employé..." type="text">
                        </div>
                    </div>

                    <div class="w-full flex-1 space-y-2">
                        <label class="text-label-md text-secondary ml-1" for="type">Type</label>
                        <select id="type" name="type"
                            class="w-full px-4 py-2.5 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-on-surface">
                            <option value="" @selected(request('type')==='' )>Tous les types</option>
                            <option value="numero" @selected(request('type')==='numero' )>Numéro</option>
                            <option value="nom" @selected(request('type')==='nom' )>Nom</option>
                            <option value="direction" @selected(request('type')==='direction' )>Direction</option>
                            <option value="sous_direction" @selected(request('type')==='sous_direction' )>Sous-direction
                            </option>
                            <option value="departement" @selected(request('type')==='departement' )>Département</option>
                            <option value="service" @selected(request('type')==='service' )>Service</option>
                            <option value="site" @selected(request('type')==='site' )>Site</option>
                        </select>
                    </div>

                    <div class="w-full md:w-auto">
                        <button id="search-btn" type="submit"
                            class="w-full md:w-auto bg-[#2563eb] text-white px-8 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                            <span>Rechercher</span>
                        </button>
                    </div>
                </form>

                <div id="table-container"
                    class="mt-lg bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant">
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">
                                        Numéro</th>
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">
                                        Nom</th>
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">
                                        Direction</th>
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">
                                        Sous-direction</th>
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">
                                        Département</th>
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">
                                        Service</th>
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">
                                        Site</th>
                                    @can('admin')
                                    <th
                                        class="px-4 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider text-center">
                                        Actions</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody id="table-body" class="divide-y divide-outline-variant">
                                @forelse ($employees as $employee)
                                <tr class="hover:bg-surface-container-low/50 transition-colors group">
                                    <td class="px-4 py-4 font-medium text-on-surface">{{ $employee['numero'] }}</td>
                                    <td class="px-4 py-4  text-secondary">{{ $employee['nom'] }}</td>
                                    <td class="px-4 py-4 text-secondary">{{ $employee['direction'] }}</td>
                                    <td class="px-4 py-4 text-secondary">{{ $employee['sous_direction'] }}</td>
                                    <td class="px-4 py-4 text-secondary">{{ $employee['departement'] }}</td>
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                                            style="color: {{ $employee['service_color'] ?? '#1d4ed8' }}; background-color: {{ $employee['service_bg'] ?? '#eff6ff' }}; border-color: {{ $employee['service_border'] ?? '#bfdbfe' }};">
                                            {{ $employee['service'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-secondary">{{ $employee['site'] }}</td>
                                    @can('admin')
                                    <td class="px-4 py-4 text-center whitespace-nowrap">
                                        <button onclick="editEmployee('{{ $employee['id'] }}')"
                                            class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                            title="Modifier">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </button>
                                        <button onclick="deleteEmployee('{{ $employee['id'] }}')"
                                            class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                            title="Supprimer">
                                            <span class="material-symbols-outlined text-lg">delete</span>
                                        </button>
                                    </td>
                                    @endcan
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->can('admin') ? 8 : 7 }}"
                                        class="px-6 py-12 text-center text-secondary">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-4xl text-outline"
                                                data-icon="group_off">group_off</span>
                                            <p class="text-body-md">Aucun employé trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="px-6 py-5 bg-surface-container-lowest border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button id="print-employees-btn" type="button"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium border border-outline-variant text-secondary hover:bg-surface-container-high transition-colors">
                                <span class="material-symbols-outlined text-lg">print</span>
                                Imprimer la liste
                            </button>
                            <button id="export-employees-btn" type="button" onclick="exportEmployees()"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-[#2563eb] text-white hover:bg-blue-700 transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-lg">download</span>
                                Télécharger
                            </button>
                        </div>

                        <div id="pagination-container" class="flex items-center gap-4">
                            <div class="text-body-sm text-secondary" id="page-info">
                                Affichage de <span
                                    class="font-semibold text-on-surface">{{ $employees->count() }}</span>
                                sur <span class="font-semibold text-on-surface">{{ $employees->total() }}</span>
                                employés
                            </div>

                            <nav class="flex items-center gap-1" id="page-nav">
                                <button @disabled($employees->onFirstPage())
                                    onclick="fetchEmployees({{ max(1, $employees->currentPage() - 1) }})"
                                    class="p-2 rounded-lg border border-outline-variant text-secondary
                                    hover:bg-surface-container-high disabled:opacity-40 transition-all">
                                    <span class="material-symbols-outlined">chevron_left</span>
                                </button>

                                <div class="flex items-center px-2">
                                    @for ($i = 1; $i <= $employees->lastPage(); $i++)
                                        @if ($i == $employees->currentPage())
                                        <button
                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white font-medium shadow-sm">{{ $i }}</button>
                                        @elseif ($i == 1 || $i == $employees->lastPage() || abs($i -
                                        $employees->currentPage()) <= 2) <button onclick="fetchEmployees({{ $i }})"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg text-secondary hover:bg-surface-container-high transition-all">
                                            {{ $i }}</button>
                                            @elseif ($i == 2 || $i == $employees->lastPage() - 1)
                                            <span class="px-2 text-outline">...</span>
                                            @endif
                                            @endfor
                                </div>

                                <button @disabled($employees->hasMorePages())
                                    onclick="fetchEmployees({{ min($employees->lastPage(), $employees->currentPage() + 1) }})"
                                    class="p-2 rounded-lg border border-outline-variant text-secondary
                                    hover:bg-surface-container-high disabled:opacity-40 transition-all">
                                    <span class="material-symbols-outlined">chevron_right</span>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    {{-- Add Employee Modal --}}
    <div id="employee-modal" class="fixed inset-0 z-[60] hidden">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" id="modal-overlay"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 w-full max-w-lg max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <h2 id="modal-title" class="text-xl font-semibold text-gray-900">Ajouter un employé</h2>
                <button id="modal-close-btn" class="p-1 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="employee-form" class="p-6 space-y-5">
                @csrf
                <input type="hidden" id="form-id" name="id" value="">
                
                <!-- Row 1: Type and Numéro (PIN) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-type">Type</label>
                        <select id="form-type" name="type" required
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                            <option value="" disabled selected>—</option>
                            <option value="4 chiffres">4 chiffres</option>
                            <option value="6 chiffres">6 chiffres</option>
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700">Numéro </label>
                        <div id="pin-wrap" class="flex gap-1.5"></div>
                        <input type="hidden" id="form-numero" name="numero" required>
                    </div>
                </div>

                <!-- Row 2: Nom and Département -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-sm font-medium text-gray-700" for="form-nom">Nom complet</label>
                        <input id="form-nom" name="nom" required
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all placeholder:text-gray-400"
                            placeholder="Dupont Jean">
                    </div>
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
                </div>

                <!-- Row 3: Direction and Sous-direction -->
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

                <!-- Row 4: Site, Service, Niveau -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200">
                    <button type="button" id="modal-cancel-btn"
                        class="px-6 py-2.5 rounded-lg font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 rounded-lg font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                        <span class="material-symbols-outlined text-lg">add</span>
                        <span>Ajouter</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
    window._allEmployees = @json($allEmployees);
    </script>

    <nav
        class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-outline-variant flex justify-around items-center py-3 px-2 z-50 shadow-2xl">
        <a class="flex flex-col items-center text-primary" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" data-icon="group"
                style="font-variation-settings: 'FILL' 1;">group</span>
            <span class="text-[10px] mt-1 font-medium">Employés</span>
        </a>
        @can('admin')
        <a class="flex flex-col items-center text-secondary" href="{{ route('users.create') }}">
            <span class="material-symbols-outlined" data-icon="person_add">person_add</span>
            <span class="text-[10px] mt-1 font-medium">Utilisateur</span>
        </a>
        @endcan
        <a class="flex flex-col items-center text-secondary" href="#">
            <span class="material-symbols-outlined" data-icon="search">search</span>
            <span class="text-[10px] mt-1 font-medium">Recherche</span>
        </a>
        <a class="flex flex-col items-center text-secondary" href="#">
            <span class="material-symbols-outlined" data-icon="add_circle">add_circle</span>
            <span class="text-[10px] mt-1 font-medium">Ajouter</span>
        </a>
        <a class="flex flex-col items-center text-secondary" href="{{ route('profile.edit') }}">
            <span class="material-symbols-outlined" data-icon="person">person</span>
            <span class="text-[10px] mt-1 font-medium">Profil</span>
        </a>
    </nav>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- dashboard -->
    <script type="module" src="{{ asset('js/dashboard.js') }}"></script>

</body>

</html>