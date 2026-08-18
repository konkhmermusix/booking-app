@extends('layouts.admin')
@section('title', 'របាយការណ៍ការបង់ប្រាក់')
@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #printableReportArea,
        #printableReportArea * {
            visibility: visible;
        }

        #printableReportArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .print-hide {
            display: none !important;
        }
    }
</style>

<div id="reportpayments-page" class="p-2 sm:p-2 space-y-6" x-data="{ 
        currency: 'USD', 
        exchangeRate: {{ $khrRate }}, 
        slipModalOpen: false, 
        selectedSlip: null,
        search: '{{ request('search') }}', 
        status: '{{ request('status', 'all') }}', 
        method: '{{ request('method', 'all') }}', 
        date_range: '{{ request('date_range') }}',
        per_page: '{{ request('per_page', '10') }}',
        loading: false,

        async fetchReports(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('reportpayments.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { 
                        search: this.search, 
                        status: this.status, 
                        method: this.method,
                        date_range: this.date_range,
                        per_page: this.per_page 
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('report-container').innerHTML = response.data;
            } catch (error) { console.error('Error fetching payment reports:', error); }
            this.loading = false;
        },

        resetFilters() {
            this.search = '';
            this.status = 'all';
            this.method = 'all';
            this.date_range = '';
            this.per_page = '10';
            this.fetchReports('{{ route('reportpayments.index') }}');
        }
    }">

    <!-- Combined Filter & Search Controls Top Bar -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800 print-hide">
        <div class="shrink-0">
            <h2 class="text-base font-bold text-gray-800 dark:text-white tracking-tight">របាយការណ៍ប្រតិបត្តិការបង់ប្រាក់</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Live Payment & Financial Transaction Report</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
            {{-- Search Input --}}
            <div class="relative w-55 shrink-0">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchReports()" placeholder="ស្វែងរក TXN ID ឬ កូដ..."
                    class="w-full pl-8 pr-3 h-10 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all text-xs font-semibold">
            </div>

            {{-- Date Range Filter --}}
            <div class="w-40 shrink-0">
                <div class="relative group">
                    <select x-model="date_range" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="">-- គ្រប់កាលបរិច្ឆេទ --</option>
                        <option value="today">ថ្ងៃនេះ</option>
                        <option value="yesterday">ម្សិលមិញ</option>
                        <option value="last_7_days">៧ថ្ងៃចុងក្រោយ</option>
                        <option value="this_month">ខែនេះ</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- Method Filter --}}
            <div class="w-36 shrink-0">
                <div class="relative group">
                    <select x-model="method" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="all">-- គ្រប់វិធីសាស្ត្រ --</option>
                        <option value="qr">ស្កែនឃ្យូអរកូដ</option>
                        <option value="cash">ទូទាត់សាច់ប្រាក់</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="w-36 shrink-0">
                <div class="relative group">
                    <select x-model="status" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="all">-- គ្រប់ស្ថានភាព --</option>
                        <option value="paid">បានបង់</option>
                        <option value="pending">រង់ចាំ</option>
                        <option value="refunded">បានសងប្រាក់វិញ</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- Per Page Filter --}}
            <div class="w-36 shrink-0">
                <div class="relative group">
                    <select x-model="per_page" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="10">10 ជួរ/ទំព័រ</option>
                        <option value="25">25 ជួរ/ទំព័រ</option>
                        <option value="50">50 ជួរ/ទំព័រ</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- Reset Button --}}
            <button @click="resetFilters()" type="button" class="h-10 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-300 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer shrink-0" title="កំណត់ឡើងវិញ">
                <i class="fa-solid fa-rotate-left"></i>
                <span class="hidden sm:inline">សម្អាត</span>
            </button>

            {{-- Currency Switcher Toggle --}}
            <div class="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-xl h-10 items-center shrink-0">
                <button @click="currency = 'USD'" :class="currency === 'USD' ? 'bg-white dark:bg-gray-700 shadow-sm text-emerald-600 dark:text-white font-bold' : 'text-gray-400'" class="px-2.5 h-full rounded-lg transition-all flex items-center gap-1 text-xs font-semibold" title="USD Dollar">
                    <i class="fas fa-dollar-sign"></i> USD
                </button>
                <button @click="currency = 'KHR'" :class="currency === 'KHR' ? 'bg-white dark:bg-gray-700 shadow-sm text-emerald-600 dark:text-white font-bold' : 'text-gray-400'" class="px-2.5 h-full rounded-lg transition-all flex items-center gap-1 text-xs font-semibold" title="Khmer Riel">
                    ៛ KHR
                </button>
            </div>

            {{-- Print & Export Action Buttons --}}
            <button onclick="window.print()" class="h-10 px-3.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition shadow-sm active:scale-95 cursor-pointer shrink-0">
                <i class="fas fa-print text-blue-500"></i> បោះពុម្ព
            </button>

            <a :href="'{{ route('reportpayments.export-excel') }}?search=' + search + '&status=' + status + '&method=' + method + '&date_range=' + date_range + '&per_page=' + per_page" class="h-10 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-excel"></i> Excel
            </a>

            <a :href="'{{ route('reportpayments.export-pdf') }}?search=' + search + '&status=' + status + '&method=' + method + '&date_range=' + date_range + '&per_page=' + per_page" target="_blank" class="h-10 px-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Report Dynamic Partial Container -->
    <div id="report-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.reportpayments.partials.report_content')
    </div>

    <!-- Payment Slip Modal Lightbox -->
    <div x-show="slipModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/75 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="slipModalOpen = false" class="bg-white dark:bg-gray-900 max-w-lg w-full rounded-3xl p-5 border border-gray-100 dark:border-gray-800 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-3">
                <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-file-image text-emerald-500"></i> សន្លឹកចុងបង់ប្រាក់
                </h3>
                <button @click="slipModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="flex justify-center bg-gray-100 dark:bg-gray-800/50 p-2 rounded-2xl">
                <img :src="selectedSlip" class="max-h-[450px] object-contain rounded-xl shadow-sm">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle Pagination Clicks via AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#report-container a, .pagination a, nav a');
        if (link && link.href && (link.href.includes('page=') || link.href.includes('reportpayments'))) {
            e.preventDefault();
            const pageContainer = document.getElementById('reportpayments-page') || document.querySelector('[x-data*="fetchReports"]');
            if (pageContainer && typeof Alpine !== 'undefined' && Alpine.$data) {
                const component = Alpine.$data(pageContainer);
                if (component && typeof component.fetchReports === 'function') {
                    component.fetchReports(link.href);
                }
            }
        }
    });
</script>
@endpush
@endsection