const searchInput = document.getElementById('search');
const typeSelect = document.getElementById('type');
const searchBtn = document.getElementById('search-btn');
const tableBody = document.getElementById('table-body');
const tableContainer = document.getElementById('table-container');
const paginationContainer = document.getElementById('pagination-container');

const modal = document.getElementById('employee-modal');
const modalTitle = document.getElementById('modal-title');
const modalOverlay = document.getElementById('modal-overlay');
const modalCloseBtn = document.getElementById('modal-close-btn');
const modalCancelBtn = document.getElementById('modal-cancel-btn');
const addEmployeeBtn = document.getElementById('add-employee-btn');
const employeeForm = document.getElementById('employee-form');
const formId = document.getElementById('form-id');

const dashboardRoute = document.body.dataset.dashboardRoute;
const employeesStoreRoute = document.body.dataset.employeesStoreRoute;
const employeesBaseUrl = document.body.dataset.employeesBaseUrl;

let currentPage = 1;
let debounceTimer;

function openModal(title) {
    modalTitle.textContent = title || 'Ajouter un employé';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.classList.add('hidden');
    document.body.style.overflow = '';
    employeeForm.reset();
    formId.value = '';
}

addEmployeeBtn.addEventListener('click', () => openModal('Ajouter un employé'));
modalOverlay.addEventListener('click', closeModal);
modalCloseBtn.addEventListener('click', closeModal);
modalCancelBtn.addEventListener('click', closeModal);

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
});

window.editEmployee = function (id) {
    const emp = window._allEmployees?.find(e => e.id == id);
    if (!emp) return;

    document.getElementById('form-nom').value = emp.nom;
    document.getElementById('form-numero').value = emp.numero;
    document.getElementById('form-id-direction').value = emp.direction_id;
    document.getElementById('form-id-sdirection').value = emp.sous_direction_id;
    document.getElementById('form-id-departement').value = emp.departement_id;
    document.getElementById('form-id-site').value = emp.site_id;
    document.getElementById('form-service').value = emp.service;
    document.getElementById('form-niveau').value = emp.niveau;
    document.getElementById('form-type').value = emp.type;
    formId.value = id;

    openModal("Modifier l'employé");
};

window.deleteEmployee = async function (id) {
    if (!confirm('Supprimer cet employé ?')) return;

    try {
        const response = await fetch(`${employeesBaseUrl}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value,
            },
        });

        if (!response.ok) throw new Error('Erreur');

        fetchEmployees(currentPage);
    } catch (error) {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    }
};

employeeForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(employeeForm);
    const isEdit = !!formId.value;

    const submitBtn = employeeForm.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="material-symbols-outlined text-lg animate-spin">refresh</span>';

    try {
        let url, method;
        if (isEdit) {
            url = `${employeesBaseUrl}/${formId.value}`;
            method = 'POST';
            formData.append('_method', 'PUT');
        } else {
            url = employeesStoreRoute;
            method = 'POST';
        }

        const response = await fetch(url, {
            method,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        });

        if (!response.ok) {
            const err = await response.json();
            alert(Object.values(err.errors || { message: 'Erreur' }).flat().join('\n'));
            return;
        }

        closeModal();
        fetchEmployees(1);
    } catch (error) {
        console.error('Erreur:', error);
        alert('Une erreur est survenue');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="material-symbols-outlined text-lg">add</span><span>Ajouter</span>';
    }
});

async function fetchEmployees(page = 1) {
    currentPage = page;
    const search = searchInput.value.trim();
    const type = typeSelect.value;

    const params = new URLSearchParams({ page });
    if (search) params.append('search', search);
    if (type) params.append('type', type);

    tableContainer.classList.add('loading');

    try {
        const response = await fetch(`${dashboardRoute}?${params.toString()}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();
        window._allEmployees = data.employees;
        renderTable(data.employees);
        renderPagination(data.pagination);
    } catch (error) {
        console.error('Erreur:', error);
    } finally {
        tableContainer.classList.remove('loading');
    }
}

function renderTable(employees) {
    window._allEmployees = employees;

    if (employees.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-secondary">
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
            <td class="px-4 py-4 font-medium text-on-surface">${emp.nom}</td>
            <td class="px-4 py-4 text-secondary">${emp.numero}</td>
            <td class="px-4 py-4 text-secondary">${emp.direction}</td>
            <td class="px-4 py-4 text-secondary">${emp.sous_direction}</td>
            <td class="px-4 py-4 text-secondary">${emp.departement}</td>
            <td class="px-4 py-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                    style="color:${emp.service_color ?? '#1d4ed8'}; background-color:${emp.service_bg ?? '#eff6ff'}; border-color:${emp.service_border ?? '#bfdbfe'};">
                    ${emp.service}
                </span>
            </td>
            <td class="px-4 py-4 text-secondary">${emp.site}</td>
            <td class="px-4 py-4 text-center whitespace-nowrap">
                <button onclick="editEmployee('${emp.id}')"
                    class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Modifier">
                    <span class="material-symbols-outlined text-lg">edit</span>
                </button>
                <button onclick="deleteEmployee('${emp.id}')"
                    class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Supprimer">
                    <span class="material-symbols-outlined text-lg">delete</span>
                </button>
            </td>
        </tr>
    `).join('');
}

function renderPagination(p) {
    let pagesHtml = '';

    for (let i = 1; i <= p.last_page; i++) {
        if (i === p.current_page) {
            pagesHtml +=
                `<button class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white font-medium shadow-sm">${i}</button>`;
        } else if (i === 1 || i === p.last_page || Math.abs(i - p.current_page) <= 2) {
            pagesHtml +=
                `<button onclick="fetchEmployees(${i})" class="w-9 h-9 flex items-center justify-center rounded-lg text-secondary hover:bg-surface-container-high transition-all">${i}</button>`;
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

searchInput.addEventListener('focus', () => searchInput.parentElement.classList.add('scale-[1.01]'));
searchInput.addEventListener('blur', () => searchInput.parentElement.classList.remove('scale-[1.01]'));
