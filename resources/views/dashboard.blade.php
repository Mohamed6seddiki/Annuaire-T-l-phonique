<!DOCTYPE html>
<html class="light" lang="fr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Annuaire des employés </title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css'])

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
    </style>
</head>

<body class="bg-background text-on-surface antialiased min-h-screen">
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
                    <span class="font-body-md">Employees</span>
                </a>
                <a class="flex items-center gap-3 px-3 py-2 text-secondary hover:bg-surface-container-high rounded-lg transition-all"
                    href="{{ route('profile.edit') }}">
                    <span class="material-symbols-outlined" data-icon="person">person</span>
                    <span class="font-body-md">Profile</span>
                </a>
            </nav>
            <div class="pt-md border-t border-outline-variant space-y-1">
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 px-3 py-2 text-secondary hover:bg-surface-container-high rounded-lg transition-all w-full text-left">
                        <span class="material-symbols-outlined" data-icon="logout">logout</span>
                        <span class="font-body-md">Logout</span>
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
                        <h1 class="hidden md:block font-h2 text-h2 font-semibold text-on-surface">Annuaire des employés</h1>
                        <h1 class="md:hidden font-h1-mobile text-h1-mobile font-semibold text-on-surface">Annuaire</h1>
                    </div>
                    <div class="flex items-center gap-md">
                        <a href="#"
                            class="bg-primary-container text-on-primary-container px-4 py-2 rounded-lg font-medium shadow-sm hover:opacity-90 active:scale-95 transition-all text-sm">
                            Add Employee
                        </a>
                    </div>
                </div>
            </header>

            <section class="max-w-container-max mx-auto w-full px-lg py-xl">
                {{-- Search Bar --}}
                <div class="flex flex-col md:flex-row gap-4 items-end bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
                    <div class="flex-1 w-full space-y-2">
                        <label class="text-label-md text-secondary ml-1" for="search">Recherche globale</label>
                        <div class="relative group">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors" data-icon="search">search</span>
                            <input
                                class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-outline/70"
                                id="search" placeholder="Rechercher un employé..." type="text">
                        </div>
                    </div>

                    <div class="w-full md:w-48 space-y-2">
                        <label class="text-label-md text-secondary ml-1" for="type">Type</label>
                        <select id="type" class="w-full px-4 py-2.5 bg-surface border border-outline-variant rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all text-on-surface">
                            <option value="">Tous les types</option>
                            <option value="nom">Nom</option>
                            <option value="prenom">Prénom</option>
                            <option value="telephone">Téléphone</option>
                            <option value="service">Service</option>
                            <option value="departement">Département</option>
                        </select>
                    </div>

                    <div class="w-full md:w-auto">
                        <button id="search-btn" class="w-full md:w-auto bg-[#2563eb] text-white px-8 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                            <span>Rechercher</span>
                        </button>
                    </div>
                </div>

                {{-- Table --}}
                <div id="table-container" class="mt-lg bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low border-b border-outline-variant">
                                    <th class="px-6 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">Nom</th>
                                    <th class="px-6 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">Prénom</th>
                                    <th class="px-6 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">Téléphone</th>
                                    <th class="px-6 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider">Département</th>
                                    <th class="px-6 py-4 font-semibold text-secondary text-label-md uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="table-body" class="divide-y divide-outline-variant">
                                @forelse ($employees as $employee)
                                <tr class="hover:bg-surface-container-low/50 transition-colors group">
                                    <td class="px-6 py-4 font-medium text-on-surface">{{ $employee['nom'] }}</td>
                                    <td class="px-6 py-4 text-secondary">{{ $employee['prenom'] }}</td>
                                    <td class="px-6 py-4 text-secondary whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-outline text-lg" data-icon="phone">phone</span>
                                            <span>{{ $employee['telephone'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                                            style="color: {{ $employee['service_color'] ?? '#1d4ed8' }}; background-color: {{ $employee['service_bg'] ?? '#eff6ff' }}; border-color: {{ $employee['service_border'] ?? '#bfdbfe' }};">
                                            {{ $employee['service'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-surface-container-high text-on-surface-variant">{{ $employee['departement'] }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button class="p-1 hover:text-primary transition-colors">
                                            <span class="material-symbols-outlined" data-icon="more_vert">more_vert</span>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-secondary">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-4xl text-outline" data-icon="group_off">group_off</span>
                                            <p class="text-body-md">Aucun employé trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div id="pagination-container" class="px-6 py-5 bg-surface-container-lowest border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-body-sm text-secondary">
                            Affichage de <span class="font-semibold text-on-surface">{{ $employees->count() }}</span>
                            sur <span class="font-semibold text-on-surface">{{ $employees->total() }}</span> employés
                        </div>
                        <nav class="flex items-center gap-1">
                            @if ($employees->onFirstPage())
                            <button class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high disabled:opacity-40 transition-all" disabled>
                                <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                            </button>
                            @else
                            <button onclick="fetchEmployees({{ $employees->currentPage() - 1 }})" class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high transition-all">
                                <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                            </button>
                            @endif

                            <div class="flex items-center px-2">
                                @for ($i = 1; $i <= $employees->lastPage(); $i++)
                                    @if ($i == $employees->currentPage())
                                    <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white font-medium shadow-sm">{{ $i }}</button>
                                    @elseif ($i == 1 || $i == $employees->lastPage() || abs($i - $employees->currentPage()) <= 2)
                                    <button onclick="fetchEmployees({{ $i }})" class="w-9 h-9 flex items-center justify-center rounded-lg text-secondary hover:bg-surface-container-high transition-all">{{ $i }}</button>
                                    @elseif ($i == 2 || $i == $employees->lastPage() - 1)
                                    <span class="px-2 text-outline">...</span>
                                    @endif
                                @endfor
                            </div>

                            @if ($employees->hasMorePages())
                            <button onclick="fetchEmployees({{ $employees->currentPage() + 1 }})" class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high transition-all">
                                <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                            </button>
                            @else
                            <button class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high disabled:opacity-40 transition-all" disabled>
                                <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                            </button>
                            @endif
                        </nav>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-outline-variant flex justify-around items-center py-3 px-2 z-50 shadow-2xl">
        <a class="flex flex-col items-center text-primary" href="{{ route('dashboard') }}">
            <span class="material-symbols-outlined" data-icon="group" style="font-variation-settings: 'FILL' 1;">group</span>
            <span class="text-[10px] mt-1 font-medium">Employés</span>
        </a>
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

    <script>
        const searchInput = document.getElementById('search');
        const typeSelect  = document.getElementById('type');
        const searchBtn   = document.getElementById('search-btn');
        const tableBody   = document.getElementById('table-body');
        const tableContainer = document.getElementById('table-container');
        const paginationContainer = document.getElementById('pagination-container');

        let currentPage = 1;
        let debounceTimer;

        
        async function fetchEmployees(page = 1) {
            currentPage = page;
            const search = searchInput.value.trim();
            const type   = typeSelect.value;

            const params = new URLSearchParams({ page });
            if (search) params.append('search', search);
            if (type)   params.append('type', type);

            tableContainer.classList.add('loading');

            try {
                const response = await fetch(`{{ route('dashboard') }}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                const data = await response.json();
                renderTable(data.employees);
                renderPagination(data.pagination);
            } catch (error) {
                console.error('Erreur:', error);
            } finally {
                tableContainer.classList.remove('loading');
            }
        }

        // ========== Render Table ==========
        function renderTable(employees) {
            if (employees.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-secondary">
                            <div class="flex flex-col items-center gap-3">
                                <span class="material-symbols-outlined text-4xl text-outline">group_off</span>
                                <p class="text-body-md">Aucun employé trouvé</p>
                            </div>
                        </td>
                    </tr>`;
                return;
            }

            tableBody.innerHTML = employees.map(emp => `
                <tr class="hover:bg-surface-container-low/50 transition-colors group">
                    <td class="px-6 py-4 font-medium text-on-surface">${emp.nom}</td>
                    <td class="px-6 py-4 text-secondary">${emp.prenom}</td>
                    <td class="px-6 py-4 text-secondary whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-outline text-lg">phone</span>
                            <span>${emp.telephone}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                            style="color:${emp.service_color ?? '#1d4ed8'}; background-color:${emp.service_bg ?? '#eff6ff'}; border-color:${emp.service_border ?? '#bfdbfe'};">
                            ${emp.service}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-surface-container-high text-on-surface-variant">
                            ${emp.departement}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="p-1 hover:text-primary transition-colors">
                            <span class="material-symbols-outlined">more_vert</span>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        
        function renderPagination(p) {
            let pagesHtml = '';

            for (let i = 1; i <= p.last_page; i++) {
                if (i === p.current_page) {
                    pagesHtml += `<button class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white font-medium shadow-sm">${i}</button>`;
                } else if (i === 1 || i === p.last_page || Math.abs(i - p.current_page) <= 2) {
                    pagesHtml += `<button onclick="fetchEmployees(${i})" class="w-9 h-9 flex items-center justify-center rounded-lg text-secondary hover:bg-surface-container-high transition-all">${i}</button>`;
                } else if (i === 2 || i === p.last_page - 1) {
                    pagesHtml += `<span class="px-2 text-outline">...</span>`;
                }
            }

            paginationContainer.innerHTML = `
                <div class="text-body-sm text-secondary">
                    Affichage de <span class="font-semibold text-on-surface">${p.count}</span>
                    sur <span class="font-semibold text-on-surface">${p.total}</span> employés
                </div>
                <nav class="flex items-center gap-1">
                    <button ${p.current_page === 1 ? 'disabled' : `onclick="fetchEmployees(${p.current_page - 1})"`}
                        class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high disabled:opacity-40 transition-all">
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <div class="flex items-center px-2">${pagesHtml}</div>
                    <button ${p.current_page === p.last_page ? 'disabled' : `onclick="fetchEmployees(${p.current_page + 1})"`}
                        class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high disabled:opacity-40 transition-all">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </nav>`;
        }

       
        searchBtn.addEventListener('click', () => fetchEmployees(1));

        searchInput.addEventListener('keyup', (e) => {
            if (e.key === 'Enter') {
                fetchEmployees(1);
            } else {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchEmployees(1), 500);
            }
        });

        typeSelect.addEventListener('change', () => fetchEmployees(1));

        // Focus effect
        searchInput.addEventListener('focus', () => searchInput.parentElement.classList.add('scale-[1.01]'));
        searchInput.addEventListener('blur',  () => searchInput.parentElement.classList.remove('scale-[1.01]'));
    </script>
</body>
</html>