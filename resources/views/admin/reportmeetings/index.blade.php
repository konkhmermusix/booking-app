@extends('layouts.admin')
@section('title', 'របាយការណ៍កក់សាលប្រជុំ')
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

<div id="reportmeetings-page" class="p-2 sm:p-2 space-y-6" x-data="{ 
        selectedMeeting: null, 
        detailModalOpen: false,
        search: '{{ request('search') }}', 
        status: '{{ request('status', 'all') }}', 
        date_range: '{{ request('date_range') }}',
        per_page: '{{ request('per_page', '10') }}',
        loading: false,

        async fetchReports(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('reportmeetings.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { 
                        search: this.search, 
                        status: this.status, 
                        date_range: this.date_range,
                        per_page: this.per_page 
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('report-container').innerHTML = response.data;
            } catch (error) { console.error('Error fetching meeting reports:', error); }
            this.loading = false;
        },

        resetFilters() {
            this.search = '';
            this.status = 'all';
            this.date_range = '';
            this.per_page = '10';
            this.fetchReports('{{ route('reportmeetings.index') }}');
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
            <h2 class="text-base font-bold text-gray-800 dark:text-white tracking-tight">របាយការណ៍កក់សាលប្រជុំ & ព្រឹត្តិការណ៍</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Live Meeting Hall Report</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
            {{-- Search Input --}}
            <div class="relative w-55 shrink-0">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchReports()" placeholder="ស្វែងរកកូដ ឬ ឈ្មោះ..."
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

            {{-- Status Filter --}}
            <div class="w-36 shrink-0">
                <div class="relative group">
                    <select x-model="status" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="all">-- គ្រប់ស្ថានភាព --</option>
                        <option value="pending">រង់ចាំពិនិត្យ</option>
                        <option value="confirmed">បានបញ្ជាក់</option>
                        <option value="completed">បានបញ្ចប់</option>
                        <option value="cancelled">បានបោះបង់</option>
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

            <a :href="'{{ route('reportmeetings.export-excel') }}?search=' + search + '&status=' + status + '&date_range=' + date_range + '&per_page=' + per_page" class="h-10 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-excel"></i> Excel
            </a>

            <a :href="'{{ route('reportmeetings.export-pdf') }}?search=' + search + '&status=' + status + '&date_range=' + date_range + '&per_page=' + per_page" target="_blank" class="h-10 px-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Report Dynamic Partial Container -->
    <div id="report-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.reportmeetings.partials.report_content')
    </div>

    <!-- Meeting Details Modal -->
    <div x-show="detailModalOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="detailModalOpen = false" class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800 p-5 space-y-4">
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-3">
                <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-building text-indigo-500"></i> លម្អិតការកក់សាលប្រជុំ
                </h3>
                <button @click="detailModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="fas fa-times text-base"></i>
                </button>
            </div>

            <template x-if="selectedMeeting">
                <div class="space-y-3 text-xs">
                    <div class="grid grid-cols-2 gap-3 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-2xl">
                        <div>
                            <span class="text-gray-400">លេខកូដកក់:</span>
                            <p class="font-mono font-bold text-indigo-600" x-text="selectedMeeting.booking_code"></p>
                        </div>
                        <div>
                            <span class="text-gray-400">ស្ថានភាព:</span>
                            <p class="font-bold text-emerald-500 capitalize" x-text="selectedMeeting.status === 'pending' ? 'រង់ចាំពិនិត្យ' : (selectedMeeting.status === 'confirmed' ? 'បានបញ្ជាក់' : (selectedMeeting.status === 'completed' ? 'បានបញ្ចប់' : 'បានបោះបង់'))"></p>
                        </div>
                        <div>
                            <span class="text-gray-400">អ្នកកក់ / ស្ថាប័ន:</span>
                            <p class="font-bold text-gray-800 dark:text-white" x-text="selectedMeeting.customer_name || (selectedMeeting.user ? selectedMeeting.user.name : 'N/A')"></p>
                        </div>
                        <div>
                            <span class="text-gray-400">ចំនួនអ្នកចូលរួម:</span>
                            <p class="font-bold text-indigo-500" x-text="(selectedMeeting.attendees_count || 0) + ' នាក់'"></p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between border-b dark:border-gray-800 pb-1">
                            <span class="text-gray-400">ម៉ោង & កាលបរិច្ឆេទ:</span>
                            <span class="font-mono font-bold text-blue-600 dark:text-blue-400" x-text="formatDisplayDate(selectedMeeting.start_date) + ' (' + (selectedMeeting.start_time || 'N/A') + ' - ' + (selectedMeeting.end_time || 'N/A') + ')'"></span>
                        </div>
                        <div class="flex justify-between border-b dark:border-gray-800 pb-1">
                            <span class="text-gray-400">សេវាកម្មបន្ថែម:</span>
                            <span class="font-bold text-gray-700 dark:text-gray-300" x-text="selectedMeeting.setup_style || 'Standard'"></span>
                        </div>
                        <div class="flex justify-between border-b dark:border-gray-800 pb-1">
                            <span class="text-gray-400">តម្លៃសរុប:</span>
                            <span class="font-black text-emerald-500 text-sm" x-text="'$' + parseFloat(selectedMeeting.total_price).toFixed(2)"></span>
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
        if (link && link.href && (link.href.includes('page=') || link.href.includes('reportmeetings'))) {
            e.preventDefault();
            const pageContainer = document.getElementById('reportmeetings-page') || document.querySelector('[x-data*="fetchReports"]');
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