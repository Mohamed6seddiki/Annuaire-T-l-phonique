// AUTO LOGOUT

const AUTO_LOGOUT_MINUTES = 30; 
const WARNING_SECONDS     = 10; 

let logoutTimer;
let warningTimer;
let countdownInterval;
let warningShown = false;

function getLogoutUrl() {
    const logoutForm = document.querySelector('form[action*="logout"]');
    return logoutForm ? logoutForm.action : '/logout';
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('input[name="_token"]')?.value
        || '';
}

function doLogout() {
    fetch('/logout', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Content-Type': 'application/json',
        },
    }).finally(() => {
        window.location.href = '/login';
    });
}

function showWarning() {
    if (warningShown) return;
    warningShown = true;

    let remaining = WARNING_SECONDS;

 Swal.fire({
    title: 'Avertissement : session expirée',
    html: `Vous serez automatiquement déconnecté en raison de votre inactivité.<br><br>
           <b id="swal-countdown">${remaining}</b> secondes restantes`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#2563eb',
    cancelButtonColor: '#dc3545',
    confirmButtonText: 'Rester sur la page',
    cancelButtonText: 'Se déconnecter maintenant',
    allowOutsideClick: false,
    allowEscapeKey: false,
    didOpen: () => {
        countdownInterval = setInterval(() => {
            remaining--;
            const el = document.getElementById('swal-countdown');
            if (el) el.textContent = remaining;

            if (remaining <= 0) {
                clearInterval(countdownInterval);
                Swal.close();
                doLogout();
            }
        }, 1000);
    },
    willClose: () => {
        clearInterval(countdownInterval);
    }
}).then((result) => {
    if (result.isConfirmed) {
        warningShown = false;
        resetAutoLogout();
    } else if (result.isDismissed && result.dismiss === Swal.DismissReason.cancel) {
        doLogout();
    }
});
}

function resetAutoLogout() {
    clearTimeout(logoutTimer);
    clearTimeout(warningTimer);

    const totalMs = AUTO_LOGOUT_MINUTES * 60 * 1000;
    const warnMs  = totalMs - WARNING_SECONDS * 1000;

    warningTimer = setTimeout(showWarning, warnMs > 0 ? warnMs : totalMs);
    logoutTimer  = setTimeout(() => { if (!warningShown) doLogout(); }, totalMs);
}

['mousemove', 'mousedown', 'keypress', 'touchstart', 'scroll', 'click'].forEach(event => {
    document.addEventListener(event, () => { if (!warningShown) resetAutoLogout(); });
});

resetAutoLogout();

const searchInput = document.getElementById('search');
const typeSelect = document.getElementById('type');
const searchForm = document.getElementById('search-form');
const tableBody = document.getElementById('table-body');
const tableContainer = document.getElementById('table-container');
const modal = document.getElementById('employee-modal');
const modalTitle = document.getElementById('modal-title');
const modalOverlay = document.getElementById('modal-overlay');
const modalCloseBtn = document.getElementById('modal-close-btn');
const modalCancelBtn = document.getElementById('modal-cancel-btn');
const addEmployeeBtn = document.getElementById('add-employee-btn');
const employeeForm = document.getElementById('employee-form');
const formId = document.getElementById('form-id');
const printEmployeesBtn = document.getElementById('print-employees-btn');
const exportEmployeesBtn = document.getElementById('export-employees-btn');

const dashboardRoute = document.body.dataset.dashboardRoute;
const employeesStoreRoute = document.body.dataset.employeesStoreRoute;
const employeesBaseUrl = document.body.dataset.employeesBaseUrl;

window._allEmployees = window._allEmployees || [];
window._currentEmployees = window._currentEmployees || [];

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

if (addEmployeeBtn) {
    addEmployeeBtn.addEventListener('click', () => openModal('Ajouter un employé'));
}
modalOverlay.addEventListener('click', closeModal);
modalCloseBtn.addEventListener('click', closeModal);
modalCancelBtn.addEventListener('click', closeModal);

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
});

function buildPinInputs(count) {
    const wrap = document.getElementById('pin-wrap');
    const hidden = document.getElementById('form-numero');
    if (!wrap) return;
    
    wrap.innerHTML = '';
    hidden.value = '';
    
    for (let i = 0; i < count; i++) {
        const inp = document.createElement('input');
        inp.type = 'text';
        inp.inputMode = 'numeric';
        inp.maxLength = 1;
        // Smaller PIN boxes - reduced size from w-12 h-12 to w-8 h-8 or w-9 h-9
        inp.className = 'w-9 h-13  text-center text-base font-medium bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all';
        
        inp.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
            if (e.target.value && i < count - 1) {
                wrap.children[i + 1].focus();
            }
            const allValues = [...wrap.querySelectorAll('input')].map(x => x.value).join('');
            hidden.value = allValues;
        });
        
        inp.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && i > 0) {
                wrap.children[i - 1].focus();
            }
        });
        
        inp.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, count);
            [...pasted].forEach((ch, j) => {
                if (wrap.children[j]) wrap.children[j].value = ch;
            });
            const next = Math.min(pasted.length, count - 1);
            if (wrap.children[next]) {
                wrap.children[next].focus();
            }
            const allValues = [...wrap.querySelectorAll('input')].map(x => x.value).join('');
            hidden.value = allValues;
        });
        
        wrap.appendChild(inp);
    }
}

window.editEmployee = function (id) {
    const emp = window._currentEmployees?.find(e => e.id == id) || window._allEmployees?.find(e => e.id == id);
    if (!emp) return;

    // Reset the form first
    document.getElementById('form-nom').value = '';
    document.getElementById('form-type').value = '';
    document.getElementById('form-numero').value = '';
    document.getElementById('form-id-direction').value = '';
    document.getElementById('form-id-sdirection').value = '';
    document.getElementById('form-id-departement').value = '';
    document.getElementById('form-id-site').value = '';
    document.getElementById('form-service').value = '';
    document.getElementById('form-niveau').value = '';
    
    // Then populate with employee data
    document.getElementById('form-nom').value = emp.nom || '';
    
    const typeSelect = document.getElementById('form-type');
    const pinCount = emp.type === '6 chiffres' ? 6 : 4;
    typeSelect.value = emp.type || '4 chiffres';
    
    // Build the PIN inputs
    buildPinInputs(pinCount);
    
    // Set the PIN values after building
    const digits = emp.numero ? emp.numero.toString().split('') : [];
    const pinInputs = document.getElementById('pin-wrap').querySelectorAll('input');
    pinInputs.forEach((box, i) => {
        box.value = digits[i] || '';
    });
    
    // Update the hidden input with the PIN values
    const allValues = [...pinInputs].map(x => x.value).join('');
    document.getElementById('form-numero').value = allValues || emp.numero || '';
    
    document.getElementById('form-id-direction').value = emp.direction_id || '';
    document.getElementById('form-id-sdirection').value = emp.sous_direction_id || '';
    document.getElementById('form-id-departement').value = emp.departement_id || '';
    document.getElementById('form-id-site').value = emp.site_id || '';
    document.getElementById('form-service').value = emp.service || '';
    document.getElementById('form-niveau').value = emp.niveau || '';
    formId.value = id;

    openModal("Modifier l'employé");
};

window.deleteEmployee = async function (id) {
    const result = await Swal.fire({
        title: 'Supprimer cet employé ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    });

    if (!result.isConfirmed) return;

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

        Swal.fire({
            icon: 'success',
            title: 'Supprimé !',
            text: "L'employé a été supprimé avec succès.",
            timer: 1500,
            showConfirmButton: false
        });

    } catch (error) {
        console.error('Erreur:', error);
        Swal.fire({ icon: 'error', title: 'Erreur', text: 'Une erreur est survenue.' });
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
        renderTable(data.employees);
        renderPagination(data.pagination);
    } catch (error) {
        console.error('Erreur:', error);
    } finally {
        tableContainer.classList.remove('loading');
    }
}

window.fetchEmployees = fetchEmployees;

function renderTable(employees) {
    window._currentEmployees = employees;

    if (employees.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="${window.isAdmin ? 8 : 7}" class="px-6 py-12 text-center text-secondary">
                    <div class="flex flex-col items-center gap-3">
                        <span class="material-symbols-outlined text-4xl text-outline">group_off</span>
                        <p class="text-body-md">Aucun employé trouvé</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    tableBody.innerHTML = employees.map(emp => {
        const actions = window.isAdmin ? `
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
        ` : '';

        return `
            <tr class="hover:bg-surface-container-low/50 transition-colors group">
                <td class="px-4 py-4 font-medium text-on-surface">${emp.numero}</td>
                <td class="px-4 py-4 text-secondary">${emp.nom}</td>
                <td class="px-4 py-4 text-secondary">${emp.direction}</td>
                <td class="px-4 py-4 text-secondary">${emp.sous_direction}</td>
                <td class="px-4 py-4 text-secondary">${emp.departement}</td>
                <td class="px-4 py-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border"
                        style="color:${emp.service_color ?? '#1d4ed8'};background-color:${emp.service_bg ?? '#eff6ff'};border-color:${emp.service_border ?? '#bfdbfe'};">
                        ${emp.service}
                    </span>
                </td>
                <td class="px-4 py-4 text-secondary">${emp.site}</td>
                ${actions}
            </tr>
        `;
    }).join('');
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

    const pageInfo = document.getElementById('page-info');
    const pageNav  = document.getElementById('page-nav');

    if (pageInfo) {
        pageInfo.innerHTML = `Affichage de <span class="font-semibold text-on-surface">${p.count}</span> sur <span class="font-semibold text-on-surface">${p.total}</span> employés`;
    }

    if (pageNav) {
        pageNav.innerHTML = `
            <button ${p.current_page === 1 ? 'disabled' : `onclick="fetchEmployees(${p.current_page - 1})"`}
                class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high disabled:opacity-40 transition-all">
                <span class="material-symbols-outlined">chevron_left</span>
            </button>
            <div class="flex items-center px-2">${pagesHtml}</div>
            <button ${p.current_page === p.last_page ? 'disabled' : `onclick="fetchEmployees(${p.current_page + 1})"`}
                class="p-2 rounded-lg border border-outline-variant text-secondary hover:bg-surface-container-high disabled:opacity-40 transition-all">
                <span class="material-symbols-outlined">chevron_right</span>
            </button>`;
    }
}

searchForm.addEventListener('submit', (e) => {
    e.preventDefault();
    fetchEmployees(1);
});

searchInput.addEventListener('keyup', (e) => {
    if (e.key === 'Enter') return;
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => fetchEmployees(1), 500);
});

typeSelect.addEventListener('change', () => fetchEmployees(1));

searchInput.addEventListener('focus', () => searchInput.parentElement.classList.add('scale-[1.01]'));
searchInput.addEventListener('blur',  () => searchInput.parentElement.classList.remove('scale-[1.01]'));

window.printEmployees =   function () {
   

    const employees = window._allEmployees?.length ? window._allEmployees : window._currentEmployees;
    if (!employees || employees.length === 0) return;

    const html = `
        <!DOCTYPE html>
        <html><head><title>Liste des employés</title>
        <style>
            body { font-family: Inter, sans-serif; padding: 24px; color: #0f172a; }
            h1 { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
            p { font-size: 12px; color: #64748b; margin-bottom: 16px; }
            table { width: 100%; border-collapse: collapse; font-size: 12px; }
            th { background: #f1f5f9; padding: 8px 12px; text-align: left; border: 1px solid #e2e8f0; font-weight: 600; color: #475569; }
            td { padding: 8px 12px; border: 1px solid #e2e8f0; color: #334155; }
            tr:nth-child(even) td { background: #f8fafc; }
            @media print { body { padding: 0; } }
        </style></head>
        <body>
        <h1>Liste des employés — Radio Algérienne</h1>
        <p>Total : ${employees.length} employés</p>
        <table>
            <thead><tr>
                <th>Numéro</th><th>Nom</th><th>Direction</th>
                <th>Sous-direction</th><th>Département</th><th>Service</th><th>Site</th>
            </tr></thead>
            <tbody>
                ${employees.map(e => `<tr>
                    <td>${e.numero ?? ''}</td><td>${e.nom ?? ''}</td><td>${e.direction ?? ''}</td>
                    <td>${e.sous_direction ?? ''}</td><td>${e.departement ?? ''}</td>
                    <td>${e.service ?? ''}</td><td>${e.site ?? ''}</td>
                </tr>`).join('')}
            </tbody>
        </table>
        <script>
            window.onload = function () { window.print(); setTimeout(function () { window.close(); }, 500); };
        <\/script>
        </body></html>`;

    const printWindow = window.open('', '_blank', 'width=900,height=600');
    if (!printWindow) {
        alert("Veuillez autoriser les fenêtres pop-up pour ce site, puis réessayez.");
        return;
    }
    printWindow.document.open();
    printWindow.document.write(html);
    printWindow.document.close();
};

window.exportEmployees = function () {
    const employees = window._allEmployees?.length ? window._allEmployees : window._currentEmployees;
    if (!employees || employees.length === 0) return;

    const doc = new window.jspdf.jsPDF({ unit: 'mm', format: 'a4', orientation: 'landscape' });
    const margin = 10;
    const rowH = 7;
    const cols = [
        { title: 'Numéro', width: 20 },
        { title: 'Nom', width: 40 },
        { title: 'Direction', width: 35 },
        { title: 'Sous-direction', width: 35 },
        { title: 'Département', width: 35 },
        { title: 'Service', width: 30 },
        { title: 'Site', width: 30 },
    ];
    let y = margin + 5;
    doc.setFontSize(14);
    doc.setFont('helvetica', 'bold');
    doc.text('Liste des employés — Radio Algérienne', margin, y);
    y += 6;
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text(`Total : ${employees.length} employés`, margin, y);
    y += 8;

    function cellX(ci) {
        return margin + cols.slice(0, ci).reduce((s, c) => s + c.width, 0);
    }

    function drawCell(x, w, yp, text, fill, bold) {
        if (fill) doc.setFillColor(fill[0], fill[1], fill[2]);
        doc.rect(x, yp, w, rowH, fill ? 'FD' : 'D');
        doc.setFont('helvetica', bold ? 'bold' : 'normal');
        doc.setFontSize(8);
        doc.setTextColor(bold ? 71 : 51, bold ? 85 : 65, bold ? 105 : 85);
        doc.text(String(text ?? ''), x + 1, yp + rowH - 2);
    }

    cols.forEach((c, ci) => drawCell(cellX(ci), c.width, y, c.title, [241, 245, 249], true));
    employees.forEach((e, i) => {
        const ry = y + rowH + i * rowH;
        const vals = [e.numero, e.nom, e.direction, e.sous_direction, e.departement, e.service, e.site];
        const fill = i % 2 === 0 ? [248, 250, 252] : null;
        cols.forEach((c, ci) => drawCell(cellX(ci), c.width, ry, vals[ci], fill, false));
    });

    doc.save(`employes_radio_algerienne_${new Date().toISOString().slice(0,10)}.pdf`);
};

if (printEmployeesBtn) printEmployeesBtn.addEventListener('click', window.printEmployees);
if (exportEmployeesBtn) exportEmployeesBtn.addEventListener('click', window.exportEmployees);

document.getElementById('form-type').addEventListener('change', function () {
    const count = this.value === '6 chiffres' ? 6 : 4;
    buildPinInputs(count);
    document.getElementById('form-numero').value = '';
});

buildPinInputs(4);