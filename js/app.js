document.addEventListener('DOMContentLoaded', function () {
    const API_URL = 'php/api.php';

    // --- Global State ---
    let state = {
        vendors: [],
        billingEntities: [],
        settings: {},
        currentEditingVendor: null,
        operations: {
            year: new Date().getFullYear(),
            month: new Date().getMonth() + 1,
            data: []
        },
        search: {
            results: [],
            totals: { total_records: 0, total_amount: 0 }
        }
    };

    // --- DOM Elements ---
    const elements = {
        navLinks: document.querySelectorAll('.nav-link'),
        views: document.querySelectorAll('.view-content'),

        // Dashboard
        dashboardDate: document.getElementById('dashboard-date'),
        dashboardTotalPayable: document.getElementById('dashboard-total-payable'),
        dashboardInvoiceReceived: document.getElementById('dashboard-invoice-received'),
        dashboardInvoicePending: document.getElementById('dashboard-invoice-pending'),
        dashboardTasks: document.getElementById('dashboard-tasks'),
        dashboardBillingAnalysis: document.getElementById('dashboard-billing-analysis'),

        // Operations
        operationsYearSelect: document.getElementById('operations-year-select'),
        operationsMonthSelect: document.getElementById('operations-month-select'),
        operationsList: document.getElementById('operations-list'),

        // Vendors
        vendorTableBody: document.getElementById('vendor-table-body'),
        addVendorBtn: document.getElementById('add-vendor-button'),

        // Vendor Modal
        vendorModal: document.getElementById('vendor-modal'),
        vendorModalTitle: document.getElementById('vendor-modal-title'),
        closeVendorModalBtn: document.getElementById('close-vendor-modal'),
        cancelVendorFormBtn: document.getElementById('cancel-vendor-form'),
        vendorForm: document.getElementById('vendor-form'),
        vendorIdInput: document.getElementById('vendor-id'),
        vendorNameInput: document.getElementById('vendor-name'),
        vendorCycleSelect: document.getElementById('vendor-cycle'),
        vendorInvoiceDayInput: document.getElementById('vendor-invoice-day'),
        vendorNextPaymentDateInput: document.getElementById('vendor-next-payment-date'),
        vendorContractStartDateInput: document.getElementById('vendor-contract-start-date'),
        vendorContractEndDateInput: document.getElementById('vendor-contract-end-date'),
        templateList: document.getElementById('template-list'),
        addTemplateBtn: document.getElementById('add-template-btn'),
        newTemplateNameInput: document.getElementById('new-template-name'),
        newTemplateItemInput: document.getElementById('new-template-item'),
        newTemplateAmountInput: document.getElementById('new-template-amount'),

        // Settings
        billingEntitiesList: document.getElementById('billing-entities-list'),
        addBillingEntityBtn: document.getElementById('add-billing-entity-btn'),
        newBillingEntityNameInput: document.getElementById('new-billing-entity-name'),
        settingNotionUrlInput: document.getElementById('setting-notion-url'),
        settingCloudUrlInput: document.getElementById('setting-cloud-url'),
        saveLinksBtn: document.getElementById('save-links-btn'),

        // Search
        searchView: document.getElementById('search-view'),
        searchTableBody: document.getElementById('search-table-body'),
        searchPagination: document.getElementById('search-pagination'),
        searchTotalRecords: document.getElementById('search-total-records'),
        searchTotalAmount: document.getElementById('search-total-amount'),
        searchBatchActionsContainer: document.getElementById('search-batch-actions-container'),
        searchButton: document.getElementById('search-button'),
        searchVendorFilter: document.getElementById('search-vendor-filter'),
        searchEntityFilter: document.getElementById('search-entity-filter'),

        // Other elements can be added here
    };

    // --- API Service ---
    const api = {
        get: (action, params = {}) => {
            const url = new URL(API_URL, window.location.href);
            url.searchParams.set('action', action);
            for (const key in params) {
                url.searchParams.set(key, params[key]);
            }
            return fetch(url).then(res => res.json());
        },
        post: (action, body) => {
            const url = new URL(API_URL, window.location.href);
            url.searchParams.set('action', action);
            return fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(body)
            }).then(res => res.json());
        }
    };

    // --- Helper Functions ---
    const formatDate = (dateString) => {
        if (!dateString || dateString === '0000-00-00') return '';
        const date = new Date(dateString);
        return date.toISOString().split('T')[0];
    };

    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(amount);
    };

    // --- Render Functions ---

    function renderVendorTable() {
        elements.vendorTableBody.innerHTML = '';
        if (!state.vendors || state.vendors.length === 0) return;

        state.vendors.forEach(vendor => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="py-4 pl-6 text-sm font-medium text-gray-900">${vendor.name}</td>
                <td class="px-3 py-4 text-sm text-gray-500">${vendor.cycle}</td>
                <td class="px-3 py-4 text-sm text-gray-500">${formatDate(vendor.next_payment_date)}</td>
                <td class="px-3 py-4 text-sm text-gray-500">${vendor.status}</td>
                <td class="py-4 pr-6 text-right text-sm font-medium">
                    <a href="#" data-vendor-id="${vendor.id}" class="edit-vendor-btn text-brand-600 hover:text-brand-900">編輯</a>
                </td>
            `;
            elements.vendorTableBody.appendChild(row);
        });
    }

    function renderBillingEntities() {
        elements.billingEntitiesList.innerHTML = '';
        state.billingEntities.forEach(be => {
            const div = document.createElement('div');
            div.className = 'flex justify-between items-center p-2 bg-gray-50 rounded';
            div.innerHTML = `
                <span>${be.name}</span>
                <button data-id="${be.id}" class="delete-entity-btn text-red-500 hover:text-red-700">刪除</button>
            `;
            elements.billingEntitiesList.appendChild(div);
        });
    }

    function renderOperations() {
        elements.operationsList.innerHTML = '';
        const { data, year, month } = state.operations;

        if (!data || data.length === 0) {
            elements.operationsList.innerHTML = '<p class="text-center text-gray-500 py-8">本月尚無請款資料。</p>';
            return;
        }

        data.forEach(vendorGroup => {
            const billingEntityOptions = state.billingEntities.map(be => `<option value="${be.id}">${be.name}</option>`).join('');
            const card = document.createElement('div');
            card.className = 'bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg';
            card.innerHTML = `
                <div class="p-4 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <h3 class="text-lg font-semibold text-gray-900">${vendorGroup.vendor_name}</h3>
                    </div>
                    <div class="flex gap-2">
                        <button data-vendor-id="${vendorGroup.payments[0].vendor_id}" class="apply-template-btn bg-gray-200 px-3 py-1 rounded text-sm">套用範本</button>
                        <button data-vendor-id="${vendorGroup.payments[0].vendor_id}" class="add-payment-btn bg-brand-100 text-brand-700 px-3 py-1 rounded text-sm">新增發票</button>
                    </div>
                </div>
                <div class="border-t">
                    <dl class="divide-y divide-gray-200">
                       ${vendorGroup.payments.map(p => {
                            const isReceived = p.status === 'received';
                            const statusHtml = isReceived
                                ? `<div class="p-2 rounded-md bg-green-100 text-green-800 text-center text-sm">已收到<br><span class="text-xs">${formatDate(p.date_invoice_received)}</span></div>`
                                : `<button data-payment-id="${p.id}" class="w-full p-2 rounded-md bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-sm register-invoice-btn">登記收到</button>`;

                            return `<div class="payment-row flex items-center px-4 py-3" data-payment-id="${p.id}">
                                <div class="flex-grow grid grid-cols-12 gap-4 items-center">
                                    <dt class="col-span-2">${statusHtml}</dt>
                                    <dd class="col-span-10 grid grid-cols-12 gap-x-4 items-end">
                                        <div class="col-span-3"><label class="text-xs">請款項目</label><input type="text" data-field="item_description" value="${p.item_description}" class="payment-field w-full p-1 text-sm bg-gray-50 rounded border-gray-300"></div>
                                        <div class="col-span-2"><label class="text-xs">抬頭</label><select data-field="billing_entity_id" class="payment-field w-full p-1 text-sm bg-gray-50 rounded border-gray-300">${billingEntityOptions}</select></div>
                                        <div class="col-span-2"><label class="text-xs">發票號碼</label><input type="text" data-field="invoice_number" value="${p.invoice_number || ''}" class="payment-field w-full p-1 text-sm bg-gray-50 rounded border-gray-300"></div>
                                        <div class="col-span-1"><label class="text-xs">請款金額</label><input type="number" data-field="amount" value="${p.amount}" class="payment-field w-full p-1 text-sm bg-gray-50 rounded border-gray-300"></div>
                                        <div class="col-span-4 flex justify-between text-sm">
                                            <label class="flex items-center"><input type="checkbox" data-field="date_notion_registered" ${p.date_notion_registered ? 'checked' : ''} class="payment-checkbox"><a href="${state.settings.notion_url || '#'}" target="_blank" class="ml-1">Notion</a></label>
                                            <label class="flex items-center"><input type="checkbox" data-field="date_form_created" ${p.date_form_created ? 'checked' : ''} class="payment-checkbox"><span class="ml-1">請款單</span></label>
                                            <label class="flex items-center"><input type="checkbox" data-field="date_cloud_saved" ${p.date_cloud_saved ? 'checked' : ''} class="payment-checkbox"><a href="${state.settings.cloud_url || '#'}" target="_blank" class="ml-1">雲端</a></label>
                                            <label class="flex items-center"><input type="checkbox" data-field="date_submitted_to_finance" ${p.date_submitted_to_finance ? 'checked' : ''} class="payment-checkbox"><span class="ml-1">送財務</span></label>
                                        </div>
                                    </dd>
                                </div>
                            </div>`;
                       }).join('')}
                    </dl>
                </div>
            `;
            // Set select values after innerHTML is set
            card.querySelectorAll('select.payment-field[data-field="billing_entity_id"]').forEach((select, index) => {
                select.value = vendorGroup.payments[index].billing_entity_id;
            });
            elements.operationsList.appendChild(card);
        });
    }

    function renderSearchList() {
        elements.searchTableBody.innerHTML = '';
        const { results, totals } = state.search;

        elements.searchTotalRecords.textContent = totals.total_records || 0;
        elements.searchTotalAmount.textContent = formatCurrency(totals.total_amount || 0);

        if (!results || results.length === 0) {
            elements.searchTableBody.innerHTML = '<tr><td colspan="7" class="text-center py-8 text-gray-500">沒有找到符合條件的資料。</td></tr>';
            return;
        }

        results.forEach(p => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="relative px-7 sm:w-12 sm:px-6">
                    <input type="checkbox" class="search-checkbox absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300" data-payment-id="${p.id}">
                </td>
                <td class="py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">${p.item_description}</td>
                <td class="px-2 py-4 text-sm text-gray-500">${p.vendor_name}</td>
                <td class="px-2 py-4 text-sm text-gray-500">${p.billing_entity_name}</td>
                <td class="px-2 py-4 text-sm text-gray-500">${formatCurrency(p.amount)}</td>
                <td class="px-2 py-4 text-sm text-gray-500">${formatDate(p.date_invoice_received) || '-'}</td>
                <td class="px-2 py-4 text-sm text-gray-500">${formatDate(p.date_submitted_to_finance) || '-'}</td>
            `;
            elements.searchTableBody.appendChild(row);
        });
    }


    // --- Data Loading and State Management ---

    async function loadInitialData() {
        const [vendorsRes, entitiesRes, settingsRes, dashboardRes] = await Promise.all([
            api.get('get_vendors'),
            api.get('get_billing_entities'),
            api.get('get_settings'),
            api.get('get_dashboard_stats')
        ]);

        if (vendorsRes.status === 'success') state.vendors = vendorsRes.data;
        if (entitiesRes.status === 'success') state.billingEntities = entitiesRes.data;
        if (settingsRes.status === 'success') state.settings = settingsRes.data;

        if (dashboardRes.status === 'success') {
            const stats = dashboardRes.data;
            elements.dashboardTotalPayable.textContent = formatCurrency(stats.total_payable);
            elements.dashboardInvoiceReceived.textContent = stats.invoice_received;
            elements.dashboardInvoicePending.textContent = stats.invoice_pending;
        }

        populateDropdowns();
        renderVendorTable();
        renderBillingEntities();
        updateSettingsForm();
        loadOperationsData();
    }

    async function loadOperationsData() {
        const { year, month } = state.operations;
        const res = await api.get('get_monthly_operations', { year, month });
        if (res.status === 'success') {
            state.operations.data = res.data;
            renderOperations();
        }
    }

    function populateDropdowns() {
        const vendorOptions = state.vendors.map(v => `<option value="${v.id}">${v.name}</option>`).join('');
        elements.searchVendorFilter.innerHTML = '<option value="">所有廠商</option>' + vendorOptions;

        const entityOptions = state.billingEntities.map(e => `<option value="${e.id}">${e.name}</option>`).join('');
        elements.searchEntityFilter.innerHTML = '<option value="">所有抬頭</option>' + entityOptions;
    }

    function updateSettingsForm() {
        elements.settingNotionUrlInput.value = state.settings.notion_url || '';
        elements.settingCloudUrlInput.value = state.settings.cloud_url || '';
    }

    // --- Vendor Modal Logic ---
    function openVendorModal(vendor = null) {
        state.currentEditingVendor = vendor;
        elements.vendorForm.reset();
        elements.vendorModalTitle.textContent = vendor ? '編輯廠商' : '新增廠商';

        if (vendor) {
            elements.vendorIdInput.value = vendor.id;
            elements.vendorNameInput.value = vendor.name;
            elements.vendorCycleSelect.value = vendor.cycle;
            elements.vendorInvoiceDayInput.value = vendor.invoice_day;
            elements.vendorNextPaymentDateInput.value = formatDate(vendor.next_payment_date);
            elements.vendorContractStartDateInput.value = formatDate(vendor.contract_start_date);
            elements.vendorContractEndDateInput.value = formatDate(vendor.contract_end_date);
            renderTemplateList(vendor.templates || []);
        } else {
            renderTemplateList([]);
        }

        elements.vendorModal.classList.add('active');
    }

    function closeVendorModal() {
        elements.vendorModal.classList.remove('active');
        state.currentEditingVendor = null;
    }

    function renderTemplateList(templates) {
        elements.templateList.innerHTML = '';
        if (!templates) return;
        templates.forEach((template, index) => {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-2 text-sm';
            div.innerHTML = `
                <input type="text" value="${template.name}" class="template-field w-full p-1 border-gray-300 rounded" data-index="${index}" data-field="name" placeholder="範本名稱">
                <input type="text" value="${template.item_description}" class="template-field w-full p-1 border-gray-300 rounded" data-index="${index}" data-field="item_description" placeholder="請款項目">
                <input type="number" value="${template.default_amount}" class="template-field w-28 p-1 border-gray-300 rounded" data-index="${index}" data-field="default_amount" placeholder="金額">
                <button type="button" class="remove-template-btn text-red-500" data-index="${index}">&times;</button>
            `;
            elements.templateList.appendChild(div);
        });
    }

    async function handleVendorFormSubmit(e) {
        e.preventDefault();
        const formData = new FormData(elements.vendorForm);
        const vendorData = {
            id: formData.get('vendor-id') ? parseInt(formData.get('vendor-id')) : null,
            name: elements.vendorNameInput.value,
            cycle: elements.vendorCycleSelect.value,
            invoice_day: elements.vendorInvoiceDayInput.value ? parseInt(elements.vendorInvoiceDayInput.value) : null,
            next_payment_date: elements.vendorNextPaymentDateInput.value,
            contract_start_date: elements.vendorContractStartDateInput.value || null,
            contract_end_date: elements.vendorContractEndDateInput.value || null,
            templates: state.currentEditingVendor ? state.currentEditingVendor.templates : []
        };

        const res = await api.post('save_vendor', vendorData);
        if (res.status === 'success') {
            alert('廠商資料已儲存');
            closeVendorModal();
            loadInitialData(); // Reload all data
        } else {
            alert('儲存失敗: ' + res.message);
        }
    }

    // --- Event Listeners ---
    elements.navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const viewId = link.dataset.view;
            elements.navLinks.forEach(nav => nav.classList.remove('active', 'bg-brand-900', 'text-white'));
            link.classList.add('active', 'bg-brand-900', 'text-white');
            elements.views.forEach(view => view.classList.add('hidden'));
            document.getElementById(`${viewId}-view`).classList.remove('hidden');
        });
    });

    // Vendor listeners
    elements.addVendorBtn.addEventListener('click', () => openVendorModal());
    elements.closeVendorModalBtn.addEventListener('click', closeVendorModal);
    elements.cancelVendorFormBtn.addEventListener('click', closeVendorModal);
    elements.vendorForm.addEventListener('submit', handleVendorFormSubmit);
    elements.vendorTableBody.addEventListener('click', async (e) => {
        if (e.target.classList.contains('edit-vendor-btn')) {
            e.preventDefault();
            const id = e.target.dataset.vendorId;
            const res = await api.get('get_vendor_details', { id });
            if (res.status === 'success') {
                openVendorModal(res.data);
            }
        }
    });

    // Template listeners in modal
    elements.addTemplateBtn.addEventListener('click', () => {
        if (!state.currentEditingVendor) {
            state.currentEditingVendor = { templates: [] };
        }
        if (!state.currentEditingVendor.templates) {
            state.currentEditingVendor.templates = [];
        }
        const newTemplate = {
            name: elements.newTemplateNameInput.value,
            item_description: elements.newTemplateItemInput.value,
            default_amount: elements.newTemplateAmountInput.value
        };
        state.currentEditingVendor.templates.push(newTemplate);
        renderTemplateList(state.currentEditingVendor.templates);
        elements.newTemplateNameInput.value = '';
        elements.newTemplateItemInput.value = '';
        elements.newTemplateAmountInput.value = '';
    });

    elements.templateList.addEventListener('change', (e) => {
        if (e.target.classList.contains('template-field')) {
            const index = parseInt(e.target.dataset.index);
            const field = e.target.dataset.field;
            state.currentEditingVendor.templates[index][field] = e.target.value;
        }
    });

    elements.templateList.addEventListener('click', (e) => {
        if (e.target.classList.contains('remove-template-btn')) {
            const index = parseInt(e.target.dataset.index);
            state.currentEditingVendor.templates.splice(index, 1);
            renderTemplateList(state.currentEditingVendor.templates);
        }
    });

    // Settings listeners
    elements.addBillingEntityBtn.addEventListener('click', async () => {
        const name = elements.newBillingEntityNameInput.value.trim();
        if (name) {
            const res = await api.post('add_billing_entity', { name });
            if (res.status === 'success') {
                state.billingEntities.push(res.data);
                renderBillingEntities();
                elements.newBillingEntityNameInput.value = '';
            } else {
                alert('新增失敗: ' + res.message);
            }
        }
    });

    elements.billingEntitiesList.addEventListener('click', async (e) => {
        if (e.target.classList.contains('delete-entity-btn')) {
            const id = e.target.dataset.id;
            if (confirm('確定要刪除這個抬頭嗎？')) {
                const res = await api.post('delete_billing_entity', { id });
                if (res.status === 'success') {
                    state.billingEntities = state.billingEntities.filter(be => be.id != id);
                    renderBillingEntities();
                } else {
                    alert('刪除失敗: ' + res.message);
                }
            }
        }
    });

    elements.saveLinksBtn.addEventListener('click', async () => {
        const settings = {
            notion_url: elements.settingNotionUrlInput.value,
            cloud_url: elements.settingCloudUrlInput.value
        };
        const res = await api.post('save_settings', settings);
        if (res.status === 'success') {
            alert('連結已儲存');
            state.settings = settings;
        } else {
            alert('儲存失敗: ' + res.message);
        }
    });

    // Operations listeners
    const setupMonthYearPickers = () => {
        const currentYear = new Date().getFullYear();
        for (let i = currentYear + 1; i >= currentYear - 5; i--) {
            elements.operationsYearSelect.innerHTML += `<option value="${i}">${i}</option>`;
        }
        for (let i = 1; i <= 12; i++) {
            elements.operationsMonthSelect.innerHTML += `<option value="${i}">${i}月</option>`;
        }
        elements.operationsYearSelect.value = state.operations.year;
        elements.operationsMonthSelect.value = state.operations.month;
    };

    elements.operationsYearSelect.addEventListener('change', (e) => {
        state.operations.year = parseInt(e.target.value);
        loadOperationsData();
    });
    elements.operationsMonthSelect.addEventListener('change', (e) => {
        state.operations.month = parseInt(e.target.value);
        loadOperationsData();
    });

    elements.operationsList.addEventListener('click', async (e) => {
        const target = e.target;
        if (target.classList.contains('register-invoice-btn')) {
            const paymentId = target.dataset.paymentId;
            const res = await api.post('update_payment_status', { id: paymentId, field: 'date_invoice_received', value: new Date().toISOString().split('T')[0] });
            if (res.status === 'success') {
                loadOperationsData();
            } else {
                alert('登記失敗: ' + res.message);
            }
        }
        if (target.classList.contains('add-payment-btn')) {
            const vendorId = target.dataset.vendorId;
            const newPayment = {
                vendor_id: vendorId,
                item_description: '新增項目',
                amount: 0,
                billing_entity_id: state.billingEntities[0]?.id,
                payment_month: `${state.operations.year}-${String(state.operations.month).padStart(2,'0')}`
            };
            const res = await api.post('save_payment', newPayment);
            if(res.status === 'success') {
                loadOperationsData();
            } else {
                alert('新增失敗: ' + res.message);
            }
        }
    });

    elements.operationsList.addEventListener('change', async (e) => {
        const target = e.target;
        const paymentRow = target.closest('.payment-row');
        if (!paymentRow) return;

        const paymentId = paymentRow.dataset.paymentId;

        if (target.classList.contains('payment-field')) {
            const field = target.dataset.field;
            const value = target.value;
            const res = await api.post('save_payment', { id: paymentId, [field]: value });
            if (res.status !== 'success') {
                alert('更新失敗: ' + res.message);
                loadOperationsData(); // Revert
            }
        } else if (target.classList.contains('payment-checkbox')) {
            const field = target.dataset.field;
            const value = target.checked ? new Date().toISOString().split('T')[0] : null;
            const res = await api.post('update_payment_status', { id: paymentId, field, value });
             if (res.status !== 'success') {
                alert('更新失敗: ' + res.message);
                loadOperationsData(); // Revert
            }
        }
    });

    // Search listeners
    elements.searchButton.addEventListener('click', async () => {
        const params = {
            start_date: document.getElementById('search-start-date').value,
            end_date: document.getElementById('search-end-date').value,
            vendor_id: elements.searchVendorFilter.value,
            billing_entity_id: elements.searchEntityFilter.value,
        };
        const res = await api.get('search_payments', params);
        if (res.status === 'success') {
            state.search.results = res.data;
            state.search.totals = res.totals;
            renderSearchList();
        }
    });

    const setupSearchYearSelector = () => {
        const selectId = 'search-year-select';
        const yearSelect = document.getElementById(selectId);
        if (!yearSelect) return;
        const currentYear = new Date().getFullYear();
        let options = '';
        for (let i = currentYear + 1; i >= currentYear - 5; i--) {
            options += `<option value="${i}">${i}</option>`;
        }
        yearSelect.innerHTML = options;
        yearSelect.value = currentYear;
    };


    // --- Init ---
    function init() {
        elements.dashboardDate.textContent = new Date().toLocaleDateString('zh-TW', { year: 'numeric', month: 'long', day: 'numeric', weekday: 'long' });
        setupMonthYearPickers();
        setupSearchYearSelector();
        loadInitialData();
        document.querySelector('.nav-link[data-view="dashboard"]').click();
    }

    init();
});