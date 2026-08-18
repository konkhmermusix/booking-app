@extends('layouts.admin')
@section('title', 'របាយការណ៍អតិថិជន')
@section('content')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableReportArea, #printableReportArea * {
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

<div id="reportcustomers-page" class="p-2 sm:p-2 space-y-6" x-data="{ 
        timelineModalOpen: false, 
        selectedCustomer: null,
        search: '{{ request('search') }}', 
        segment: '{{ request('segment', 'all') }}', 
        per_page: '{{ request('per_page', '10') }}',
        loading: false,

        async fetchReports(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('reportcustomers.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { 
                        search: this.search, 
                        segment: this.segment, 
                        per_page: this.per_page 
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('report-container').innerHTML = response.data;
            } catch (error) { console.error('Error fetching customer reports:', error); }
            this.loading = false;
        },

        resetFilters() {
            this.search = '';
            this.segment = 'all';
            this.per_page = '10';
            this.fetchReports('{{ route('reportcustomers.index') }}');
        },

        formatDisplayDate(d) {
            if (!d) return 'N/A';
            const clean = d.split('T')[0];
            const parts = clean.split('-');
            if (parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`;
            return clean;
        }
    }">

    <!-- Combined Filter & Search Controls Top Bar -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800 print-hide">
        <div class="shrink-0">
            <h2 class="text-base font-bold text-gray-800 dark:text-white tracking-tight">របាយការណ៍ស្ថិតិ និងចំណាត់ថ្នាក់អតិថិជន</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Live Customer Loyalty & Analytics Report</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
            {{-- Search Input --}}
            <div class="relative w-55 shrink-0">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchReports()" placeholder="ស្វែងរក ឈ្មោះ, អ៊ីមែល, ទូរស័ព្ទ..."
                    class="w-full pl-8 pr-3 h-10 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all text-xs font-semibold">
            </div>

            {{-- Segment Filter --}}
            <div class="w-48 shrink-0">
                <div class="relative group">
                    <select x-model="segment" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="all">-- គ្រប់ Segment --</option>
                        <option value="vip">VIP Customer (>= 5 ដង)</option>
                        <option value="regular">Regular (2 - 4 ដង)</option>
                        <option value="new">New Customer (1 ដង)</option>
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

            {{-- Print & Export Action Buttons --}}
            <button onclick="window.print()" class="h-10 px-3.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition shadow-sm active:scale-95 cursor-pointer shrink-0">
                <i class="fas fa-print text-blue-500"></i> បោះពុម្ព
            </button>

            <a :href="'{{ route('reportcustomers.export-excel') }}?search=' + search + '&segment=' + segment + '&per_page=' + per_page" class="h-10 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-excel"></i> Excel
            </a>

            <a :href="'{{ route('reportcustomers.export-pdf') }}?search=' + search + '&segment=' + segment + '&per_page=' + per_page" target="_blank" class="h-10 px-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Report Dynamic Partial Container -->
    <div id="report-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.reportcustomers.partials.report_content')
    </div>

    <!-- Customer History Timeline Modal -->
    <div x-show="timelineModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="timelineModalOpen = false" class="bg-white dark:bg-gray-900 w-full max-w-xl rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800 p-5 space-y-4">
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-3">
                <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-user-clock text-blue-500"></i> ប្រវត្តិស្នាក់នៅរបស់អតិថិជន (Stay Timeline)
                </h3>
                <button @click="timelineModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <template x-if="selectedCustomer">
                <div class="space-y-3">
                    <div class="bg-blue-50 dark:bg-blue-950/30 p-4 rounded-2xl border border-blue-100 dark:border-blue-900/30 flex justify-between items-center">
                        <div>
                            <h4 class="font-bold text-sm text-gray-800 dark:text-white" x-text="selectedCustomer.name"></h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="selectedCustomer.email + ' • ' + selectedCustomer.phone"></p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-gray-400 block uppercase font-bold">ចំណាយសរុប</span>
                            <span class="font-black text-emerald-500 text-base" x-text="'$' + parseFloat(selectedCustomer.lifetime_spend).toFixed(2)"></span>
                        </div>
                    </div>

                    <div class="space-y-3 max-h-[350px] overflow-y-auto pr-1">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ប្រវត្តិនៃការកក់កន្លងមក (សរុប៖ <span x-text="selectedCustomer.total_bookings"></span> ដង)</p>
                        <div class="border-l-2 border-blue-500 pl-4 space-y-3 text-xs">
                            <div class="bg-gray-50 dark:bg-gray-800/40 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                                <div class="flex justify-between font-bold">
                                    <span class="text-blue-600">ការកក់ចុងក្រោយបង្អស់</span>
                                    <span class="text-emerald-500 font-black" x-text="'$' + parseFloat(selectedCustomer.lifetime_spend / (selectedCustomer.total_bookings || 1)).toFixed(2)"></span>
                                </div>
                                <p class="text-gray-400 mt-1" x-text="'កាលបរិច្ឆេទចុងក្រោយ៖ ' + formatDisplayDate(selectedCustomer.last_visit)"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Handle Pagination Clicks via AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#report-container a, .pagination a, nav a');
        if (link && link.href && (link.href.includes('page=') || link.href.includes('reportcustomers'))) {
            e.preventDefault();
            const pageContainer = document.getElementById('reportcustomers-page') || document.querySelector('[x-data*="fetchReports"]');
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