@extends('layouts.admin')
@section('title', 'របាយការណ៍ស្ថានភាពបន្ទប់')
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

<div id="reportroomstatus-page" class="p-2 sm:p-2 space-y-6" x-data="{ 
        layoutMode: 'grid',
        search: '{{ request('search') }}', 
        floor: '{{ request('floor', 'all') }}', 
        status: '{{ request('status', 'all') }}', 
        loading: false,

        async fetchReports(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('reportroomstatus.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { 
                        search: this.search, 
                        floor: this.floor, 
                        status: this.status 
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('report-container').innerHTML = response.data;
            } catch (error) { console.error('Error fetching room status reports:', error); }
            this.loading = false;
        },

        resetFilters() {
            this.search = '';
            this.floor = 'all';
            this.status = 'all';
            this.fetchReports('{{ route('reportroomstatus.index') }}');
        }
    }">

    <!-- Combined Filter & Search Controls Top Bar -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800 print-hide">
        <div class="shrink-0">
            <h2 class="text-base font-bold text-gray-800 dark:text-white tracking-tight">របាយការណ៍ស្ថានភាពបន្ទប់ជាក់ស្តែង</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Real-Time Floor & Room Status Report</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
            {{-- Search Input --}}
            <div class="relative w-55 shrink-0">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchReports()" placeholder="ស្វែងរក លេខបន្ទប់, ប្រភេទ..."
                    class="w-full pl-8 pr-3 h-10 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all text-xs font-semibold">
            </div>

            {{-- Floor Filter --}}
            <div class="w-44 shrink-0">
                <div class="relative group">
                    <select x-model="floor" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="all">-- គ្រប់ជាន់ទាំងអស់ --</option>
                        @foreach($floorsList as $fl)
                            <option value="{{ $fl }}">ជាន់ទី {{ $fl }}</option>
                        @endforeach
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
                        <option value="available">ទំនេរ</option>
                        <option value="booked">មានភ្ញៀវ</option>
                        <option value="maintenance">ជួសជុល</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- Reset Button --}}
            <button @click="resetFilters()" type="button" class="h-10 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-300 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all cursor-pointer shrink-0" title="កំណត់ឡើងវិញ">
                <i class="fa-solid fa-rotate-left"></i>
                <span class="hidden sm:inline">សម្អាត</span>
            </button>

            {{-- Layout Mode Switcher --}}
            <div class="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-xl h-10 items-center shrink-0">
                <button @click="layoutMode = 'grid'" :class="layoutMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600 dark:text-white font-bold' : 'text-gray-400'" class="px-2.5 h-full rounded-lg transition-all flex items-center gap-1 text-xs font-semibold" title="Floor Grid View">
                    <i class="fas fa-th-large"></i> <span class="hidden sm:inline">តាមជាន់</span>
                </button>
                <button @click="layoutMode = 'table'" :class="layoutMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600 dark:text-white font-bold' : 'text-gray-400'" class="px-2.5 h-full rounded-lg transition-all flex items-center gap-1 text-xs font-semibold" title="Data Table View">
                    <i class="fas fa-table"></i> <span class="hidden sm:inline">តារាង</span>
                </button>
            </div>

            {{-- Print & Export Action Buttons --}}
            <button onclick="window.print()" class="h-10 px-3.5 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition shadow-sm active:scale-95 cursor-pointer shrink-0">
                <i class="fas fa-print text-blue-500"></i> បោះពុម្ព
            </button>

            <a :href="'{{ route('reportroomstatus.export-excel') }}?search=' + search + '&floor=' + floor + '&status=' + status" class="h-10 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-excel"></i> Excel
            </a>

            <a :href="'{{ route('reportroomstatus.export-pdf') }}?search=' + search + '&floor=' + floor + '&status=' + status" target="_blank" class="h-10 px-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Report Dynamic Partial Container -->
    <div id="report-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.reportroomstatus.partials.report_content')
    </div>
</div>

@push('scripts')
<script>
    // Handle Pagination / Filter Links Clicks via AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#report-container a, .pagination a, nav a');
        if (link && link.href && (link.href.includes('page=') || link.href.includes('reportroomstatus'))) {
            e.preventDefault();
            const pageContainer = document.getElementById('reportroomstatus-page') || document.querySelector('[x-data*="fetchReports"]');
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