<!DOCTYPE html>
<html lang="zh-Hant" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>廠商請款追蹤系統</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="h-full">
    <div class="min-h-full">
        <!-- Sidebar Navigation -->
        <div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
            <div class="flex flex-col flex-grow bg-brand-800 pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center flex-shrink-0 px-4">
                    <svg class="h-8 w-auto text-brand-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    <span class="ml-3 text-white text-lg font-bold">廠商請款追蹤系統</span>
                </div>
                <nav class="mt-5 flex-1 flex flex-col divide-y divide-brand-700 overflow-y-auto" aria-label="Sidebar">
                    <div class="px-2 space-y-1">
                        <a href="#" data-view="dashboard" class="nav-link active group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                            <svg class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            智慧儀表板
                        </a>
                        <a href="#" data-view="operations" class="nav-link group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                            <svg class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"></path><path d="M14 2v4a2 2 0 0 0 2 2h4"></path><path d="M10 9H8"></path><path d="M16 13H8"></path><path d="M16 17H8"></path></svg>
                            每月請款作業
                        </a>
                        <a href="#" data-view="calendar" class="nav-link group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                            <svg class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            作業日曆
                        </a>
                        <a href="#" data-view="search" class="nav-link group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                            <svg class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" x2="21" y1="6" y2="6"></line><line x1="8" x2="21" y1="12" y2="12"></line><line x1="8" x2="21" y1="18" y2="18"></line><line x1="3" x2="3.01" y1="6" y2="6"></line><line x1="3" x2="3.01" y1="12" y2="12"></line><line x1="3" x2="3.01" y1="18" y2="18"></line></svg>
                            費用清單
                        </a>
                        <a href="#" data-view="vendors" class="nav-link group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                            <svg class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                            廠商管理
                        </a>
                        <a href="#" data-view="reports" class="nav-link group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                           <svg class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"></path><path d="m19 9-5 5-4-4-3 3"></path></svg>
                            報表與分析
                        </a>
                    </div>
                     <div class="pt-6">
                        <div class="px-2 space-y-1">
                           <a href="#" data-view="settings" class="nav-link group flex items-center px-2 py-2 text-sm leading-6 font-medium rounded-md">
                                <svg class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 0 2.4l-.15.08a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1 0-2.4l.15-.08a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                系統設定
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:pl-64 flex flex-col flex-1">
            <main class="flex-1 pb-8">
                <!-- Dashboard View -->
                <div id="dashboard-view" class="view-content px-4 py-8 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold text-gray-900">智慧儀表板</h1>
                    <span class="text-gray-500" id="dashboard-date"></span>

                    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                             <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">本月應付總額</dt>
                                <dd class="text-3xl font-bold text-gray-900" id="dashboard-total-payable">$ 0</dd>
                            </dl>
                        </div>
                        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">發票狀態 (本月)</dt>
                                <dd class="text-lg font-semibold text-gray-900 mt-1">
                                    <span class="text-green-600"><span id="dashboard-invoice-received">0</span> 張已收到</span> /
                                    <span class="text-red-600"><span id="dashboard-invoice-pending">0</span> 張待收</span>
                                </dd>
                            </dl>
                        </div>
                        <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                           <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">流程進度 (本月)</dt>
                                <dd class="text-lg font-semibold text-gray-900 mt-1">
                                    <span class="text-blue-600">N/A</span> / <span class="text-yellow-600">N/A</span>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                        <div class="bg-white p-6 shadow rounded-lg">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">本週任務與提醒</h3>
                            <ul role="list" class="divide-y divide-gray-200 mt-4" id="dashboard-tasks">
                               <!-- Tasks will be dynamically generated -->
                            </ul>
                        </div>
                        <div class="bg-white p-6 shadow rounded-lg">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">各抬頭本月付款分析</h3>
                            <ul role="list" class="divide-y divide-gray-200 mt-4" id="dashboard-billing-analysis">
                               <!-- Analysis will be dynamically generated -->
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Monthly Operations View -->
                <div id="operations-view" class="view-content hidden px-4 py-8 sm:px-6 lg:px-8">
                     <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-900">每月請款作業</h1>
                        <div class="flex items-center space-x-2">
                            <select id="operations-year-select" class="rounded-md border-gray-300"></select>
                            <select id="operations-month-select" class="rounded-md border-gray-300"></select>
                        </div>
                    </div>
                    <div class="mt-6 flow-root"><div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"><div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"><div class="space-y-6" id="operations-list"></div></div></div></div>
                </div>

                <!-- Calendar View -->
                <div id="calendar-view" class="view-content hidden px-4 py-8 sm:px-6 lg:px-8">
                     <div class="flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-900">作業日曆</h1>
                        <div class="flex items-center space-x-2">
                             <select id="calendar-vendor-filter" class="rounded-md border-gray-300"><option value="all">所有廠商</option></select>
                             <select id="calendar-entity-filter" class="rounded-md border-gray-300"><option value="all">所有抬頭</option></select>
                             <input type="month" id="calendar-month-picker" class="rounded-md border-gray-300">
                        </div>
                    </div>
                    <div class="mt-6 bg-white p-6 shadow rounded-lg">
                        <div id="calendar-header" class="grid grid-cols-7 text-center font-semibold text-gray-600 border-b pb-2 mb-2">
                            <div>日</div><div>一</div><div>二</div><div>三</div><div>四</div><div>五</div><div>六</div>
                        </div>
                        <div id="calendar-grid" class="grid grid-cols-7 gap-1">
                            <!-- Calendar days will be generated here -->
                        </div>
                    </div>
                </div>

                <!-- Search View -->
                <div id="search-view" class="view-content hidden px-4 py-8 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold text-gray-900">費用清單 (資料查找)</h1>
                     <div class="mt-6 p-6 bg-white rounded-lg shadow">
                         <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                             <div>
                                <label class="text-sm font-medium">年份</label>
                                <select id="search-year-select" class="mt-1 w-full rounded-md border-gray-300"></select>
                            </div>
                             <div><label class="text-sm font-medium">起始日期</label><input type="date" id="search-start-date" class="mt-1 w-full rounded-md border-gray-300"></div>
                             <div><label class="text-sm font-medium">結束日期</label><input type="date" id="search-end-date" class="mt-1 w-full rounded-md border-gray-300"></div>
                             <div><label class="text-sm font-medium">廠商</label><select id="search-vendor-filter" class="mt-1 w-full rounded-md border-gray-300"><option value="">所有廠商</option></select></div>
                             <div><label class="text-sm font-medium">抬頭</label><select id="search-entity-filter" class="mt-1 w-full rounded-md border-gray-300"><option value="">所有抬頭</option></select></div>
                             <button id="search-button" class="w-full bg-brand-600 text-white px-4 py-2 rounded-md hover:bg-brand-700">查詢</button>
                         </div>
                    </div>
                    <div id="search-batch-actions-container" class="mt-4">
                        <!-- Batch actions will be inserted here -->
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            共 <span id="search-total-records" class="font-bold">0</span> 筆資料，
                            總金額: <span id="search-total-amount" class="font-bold">0</span> 元
                        </div>
                    </div>
                    <div class="mt-2 flex flex-col">
                        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="relative px-6 sm:w-12 sm:px-8">
                                                    <input type="checkbox" id="search-select-all" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-brand-600 focus:ring-brand-500">
                                                </th>
                                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">請款項目</th>
                                                <th class="px-2 py-3.5 text-left text-sm font-semibold text-gray-900">廠商</th>
                                                <th class="px-2 py-3.5 text-left text-sm font-semibold text-gray-900">抬頭</th>
                                                <th class="px-2 py-3.5 text-left text-sm font-semibold text-gray-900">金額</th>
                                                <th class="px-2 py-3.5 text-left text-sm font-semibold text-gray-900">發票收到日</th>
                                                <th class="px-2 py-3.5 text-left text-sm font-semibold text-gray-900">送財務日</th>
                                            </tr>
                                        </thead>
                                        <tbody id="search-table-body" class="divide-y divide-gray-200 bg-white">
                                            <!-- Search results will be generated here -->
                                        </tbody>
                                    </table>
                                </div>
                                 <nav id="search-pagination" class="mt-4 flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                                     <!-- Pagination controls will be generated here -->
                                 </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vendor Management View -->
                <div id="vendors-view" class="view-content hidden px-4 py-8 sm:px-6 lg:px-8">
                     <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto"><h1 class="text-2xl font-bold text-gray-900">廠商管理</h1><p class="mt-2 text-sm text-gray-700">管理所有合作廠商的基礎資訊與付款週期。</p></div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none"><button type="button" id="add-vendor-button" class="inline-flex items-center justify-center rounded-md border bg-brand-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-brand-700">新增廠商</button></div>
                    </div>
                     <div class="mt-8 flex flex-col"><div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8"><div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8"><div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"><table class="min-w-full divide-y divide-gray-300"><thead class="bg-gray-50"><tr><th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">廠商名稱</th><th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">付款週期</th><th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">下次請款日</th><th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">狀態</th><th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">編輯</span></th></tr></thead><tbody class="divide-y divide-gray-200 bg-white" id="vendor-table-body"></tbody></table></div></div></div></div>
                </div>

                <!-- Reports View -->
                <div id="reports-view" class="view-content hidden px-4 py-8 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold text-gray-900">報表與分析</h1>
                    <div class="mt-6 p-6 bg-white rounded-lg shadow">
                         <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                             <div>
                                <label for="report-year-select" class="text-sm font-medium">年份快速篩選</label>
                                <select id="report-year-select" class="mt-1 w-full rounded-md border-gray-300"></select>
                            </div>
                             <div><label class="text-sm font-medium">起始日期</label><input type="date" id="report-start-date" class="mt-1 w-full rounded-md border-gray-300"></div>
                             <div><label class="text-sm font-medium">結束日期</label><input type="date" id="report-end-date" class="mt-1 w-full rounded-md border-gray-300"></div>
                             <div><label class="text-sm font-medium">廠商</label><select id="report-vendor-filter" class="mt-1 w-full rounded-md border-gray-300"><option value="">所有廠商</option></select></div>
                             <button id="report-query-button" class="w-full bg-brand-600 text-white px-4 py-2 rounded-md hover:bg-brand-700">查詢</button>
                         </div>
                    </div>
                    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-white p-6 shadow rounded-lg">
                            <h3 class="text-lg font-medium">總費用趨勢分析 (所有款項)</h3>
                            <div class="mt-4" style="height: 300px;"><canvas id="trendsChart"></canvas></div>
                        </div>
                        <div class="bg-white p-6 shadow rounded-lg">
                            <h3 class="text-lg font-medium">廠商費用佔比 (依期間查詢)</h3>
                            <div class="mt-4" style="height: 300px;"><canvas id="distributionChart"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- Settings View -->
                <div id="settings-view" class="view-content hidden px-4 py-8 sm:px-6 lg:px-8">
                     <h1 class="text-2xl font-bold text-gray-900">系統設定</h1>
                     <div class="mt-8 space-y-8">
                         <div class="bg-white p-6 shadow rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900">管理請款抬頭</h3>
                            <p class="text-sm text-gray-500 mt-1">此處設定的抬頭將會顯示在「每月請款作業」的下拉選單中。</p>
                            <div class="mt-4 space-y-3" id="billing-entities-list"></div>
                            <div class="mt-4 flex">
                                <input type="text" id="new-billing-entity-name" placeholder="新增抬頭名稱" class="w-full rounded-md border-gray-300">
                                <button id="add-billing-entity-btn" class="ml-2 bg-brand-600 text-white px-4 py-2 rounded-md">新增</button>
                            </div>
                         </div>
                         <div class="bg-white p-6 shadow rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900">作業連結設定</h3>
                            <p class="text-sm text-gray-500 mt-1">設定後，在「每月請款作業」頁面可直接點擊連結前往。</p>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="setting-notion-url" class="block text-sm font-medium text-gray-700">Notion 工作區/頁面連結</label>
                                    <input type="url" id="setting-notion-url" placeholder="https://www.notion.so/..." class="mt-1 w-full rounded-md border-gray-300">
                                </div>
                                <div>
                                    <label for="setting-cloud-url" class="block text-sm font-medium text-gray-700">雲端空間資料夾連結</label>
                                    <input type="url" id="setting-cloud-url" placeholder="https://www.dropbox.com/..." class="mt-1 w-full rounded-md border-gray-300">
                                </div>
                            </div>
                             <div class="mt-6 text-right">
                                <button id="save-links-btn" class="bg-brand-600 text-white px-5 py-2 rounded-md">儲存連結</button>
                             </div>
                         </div>
                     </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Vendor Modal -->
    <div id="vendor-modal" class="modal fixed inset-0 z-10 bg-gray-500 bg-opacity-75 items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6">
                <div class="flex justify-between items-center"><h2 class="text-xl font-bold text-gray-900" id="vendor-modal-title">新增廠商</h2><button id="close-vendor-modal" class="text-gray-400">&times;</button></div>
                <form id="vendor-form" class="mt-6 space-y-4">
                    <input type="hidden" id="vendor-id">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div><label class="block text-sm font-medium">廠商名稱 *</label><input type="text" id="vendor-name" class="mt-1 w-full rounded-md border-gray-300" required></div>
                        <div><label class="block text-sm font-medium">付款週期 *</label><select id="vendor-cycle" class="mt-1 w-full rounded-md border-gray-300" required><option>每月</option><option>每年</option><option>N年合約</option><option>單次</option></select></div>
                        <div><label class="block text-sm font-medium">預計發票寄達日 (每月)</label><input type="number" id="vendor-invoice-day" class="mt-1 w-full rounded-md border-gray-300" min="1" max="31"></div>
                        <div><label class="block text-sm font-medium">下次請款日 *</label><input type="date" id="vendor-next-payment-date" class="mt-1 w-full rounded-md border-gray-300" required></div>
                        <div><label class="block text-sm font-medium">合約起始日</label><input type="date" id="vendor-contract-start-date" class="mt-1 w-full rounded-md border-gray-300"></div>
                        <div><label class="block text-sm font-medium">合約結束日</label><input type="date" id="vendor-contract-end-date" class="mt-1 w-full rounded-md border-gray-300"></div>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-gray-200 mt-6">
                <h3 class="font-medium text-gray-900">請款範本管理</h3>
                <div id="template-list" class="mt-4 space-y-2"></div>
                <div class="mt-4 flex items-end gap-2">
                    <div class="flex-grow"><label class="text-xs">範本名稱</label><input type="text" id="new-template-name" class="w-full text-sm p-1 border-gray-300 rounded"></div>
                    <div class="flex-grow"><label class="text-xs">請款項目</label><input type="text" id="new-template-item" class="w-full text-sm p-1 border-gray-300 rounded"></div>
                    <div class="w-28"><label class="text-xs">預設金額</label><input type="number" id="new-template-amount" class="w-full text-sm p-1 border-gray-300 rounded"></div>
                    <button type="button" id="add-template-btn" class="bg-gray-200 px-3 py-1 rounded">新增範本</button>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3"><button type="button" id="cancel-vendor-form" class="bg-white border rounded-md px-4 py-2">取消</button><button type="submit" form="vendor-form" class="bg-brand-600 text-white rounded-md px-4 py-2">儲存廠商</button></div>
        </div>
    </div>

    <!-- Apply Template Modal -->
    <div id="template-modal" class="modal fixed inset-0 z-20 bg-gray-500 bg-opacity-75 items-start justify-center pt-20">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
            <div class="p-4 border-b">
                <h3 class="font-medium">套用請款範本</h3>
            </div>
            <div id="template-options-list" class="p-4 max-h-60 overflow-y-auto"></div>
            <div class="bg-gray-50 p-3 text-right">
                <button id="close-template-modal" class="bg-white border rounded px-3 py-1">關閉</button>
            </div>
        </div>
    </div>
    <script src="js/app.js"></script>
</body>
</html>