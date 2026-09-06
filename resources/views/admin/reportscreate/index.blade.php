@extends('layouts.admin')
@section('title', 'បង្កើតរបាយការណ៍តាមតារាង')

@section('content')
<div class="p-4 sm:p-6 space-y-6" x-data="reportCreateManager()">

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">ប្រព័ន្ធបង្កើតរបាយការណ៍ស្វ័យប្រវត្ត</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">ជ្រើសរើសតារាងទិន្នន័យ កាលបរិច្ឆេទ (ថ្ងៃ, សប្ដាហ៍, ខែ, ឆ្នាំ) និងទាញយកជា Excel/PDF ឬ Print</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
             <div class="flex flex-wrap items-center gap-2">
                <a :href="exportExcelUrl" target="_blank"
                   class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2 active:scale-95">
                    <i class="fas fa-file-excel text-base"></i> ទាញយក Excel
                </a>
                <a :href="exportPdfUrl" target="_blank"
                   class="px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-xl shadow-lg shadow-rose-500/30 transition-all flex items-center gap-2 active:scale-95">
                    <i class="fas fa-file-pdf text-base"></i> ទាញយក PDF
                </a>
                <button onclick="window.print()" 
                        class="px-4 py-2.5 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded-xl backdrop-blur-md transition-all flex items-center gap-2">
                    <i class="fas fa-print"></i> បោះពុម្ព
                </button>
            </div>

        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800">
        <form @submit.prevent="fetchReport()" class="space-y-4" id="reportFilterForm">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                {{-- 1. Table Data Selector --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-database text-blue-500 mr-1"></i> ជ្រើសរើសតារាងទិន្នន័យ
                    </label>
                    <div class="relative group">
                        <select x-model="tableType" @change="fetchReport()"
                                class="w-full h-11 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0 cursor-pointer">
                            <option value="room_bookings">ការកក់បន្ទប់សណ្ឋាគារ</option>
                            <option value="meeting_bookings">ការកក់សាលប្រជុំ</option>
                            <option value="payments">ប្រតិបត្តិការបង់ប្រាក់</option>
                            <option value="customers">អ្នកប្រើប្រាស់ និងអតិថិជន</option>
                            <option value="rooms">ស្ថានភាពបន្ទប់</option>
                            <option value="promotions">កម្មវិធីបញ្ចុះតម្លៃ</option>
                            <option value="reviews">ការវាយតម្លៃភ្ញៀវ</option>
                            <option value="contacts">សារទំនាក់ទំនង</option>
                            <option value="tours">កញ្ចប់ទស្សនកិច្ច</option>
                            <option value="posts">ព័ត៌មាននិងអត្ថបទ</option>
                            <option value="facilities">បរិក្ខារ</option>
                            <option value="room_types">ប្រភេទបន្ទប់</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                    </div>
                </div>

                {{-- 2. Period / Time Range Selector --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-calendar-alt text-indigo-500 mr-1"></i> កាលបរិច្ឆេទ / រយៈពេល
                    </label>
                    <div class="relative group">
                        <select x-model="period" @change="fetchReport()"
                                class="w-full h-11 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0 cursor-pointer">
                            <option value="today">ថ្ងៃនេះ</option>
                            <option value="yesterday">ម្សិលមិញ</option>
                            <option value="this_week">សប្ដាហ៍នេះ</option>
                            <option value="last_7_days">៧ ថ្ងៃចុងក្រោយ</option>
                            <option value="this_month">ខែនេះ</option>
                            <option value="last_month">ខែមុន</option>
                            <option value="this_year">ឆ្នាំនេះ</option>
                            <option value="custom">កំណត់ថ្ងៃដោយខ្លួនឯង</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                    </div>
                </div>

                {{-- 3. Status Filter --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-filter text-purple-500 mr-1"></i> ស្ថានភាព
                    </label>
                    <div class="relative group">
                        <select x-model="status" @change="fetchReport()"
                                class="w-full h-11 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0 cursor-pointer">
                            <option value="all">ទាំងអស់</option>
                            <template x-if="tableType === 'customers'">
                                <g>
                                    <option value="customer">អតិថិជន</option>
                                    <option value="admin">អ្នកគ្រប់គ្រង</option>
                                    <option value="staff">បុគ្គលិក</option>
                                </g>
                            </template>
                            <template x-if="tableType === 'rooms'">
                                <g>
                                    <option value="available">ទំនេរ</option>
                                    <option value="booked">បានកក់</option>
                                    <option value="maintenance">ជួសជុល</option>
                                </g>
                            </template>
                            <template x-if="tableType === 'payments'">
                                <g>
                                    <option value="paid">បានបង់</option>
                                    <option value="pending">រង់ចាំ</option>
                                    <option value="failed">បរាជ័យ</option>
                                </g>
                            </template>
                            <template x-if="['room_bookings', 'meeting_bookings'].includes(tableType)">
                                <g>
                                    <option value="pending">រង់ចាំពិនិត្យ</option>
                                    <option value="confirmed">បានបញ្ជាក់</option>
                                    <option value="completed">បានបញ្ចប់</option>
                                    <option value="cancelled">បានបោះបង់</option>
                                </g>
                            </template>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                    </div>
                </div>

                {{-- 4. Search Filter --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 uppercase tracking-wider">
                        <i class="fas fa-search text-emerald-500 mr-1"></i> ស្វែងរកពាក្យគន្លឹះ
                    </label>
                    <div class="relative">
                        <input type="text" x-model="search" @input.debounce.400ms="fetchReport()" placeholder="កូដកក់, ឈ្មោះ, លេខទូរស័ព្ទ..."
                               class="w-full h-11 pl-9 pr-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all font-medium">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>
                </div>

            </div>

            {{-- Custom Date Inputs (shown only if period == custom) --}}
            <div x-show="period === 'custom'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-gray-100 dark:border-gray-800">
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">ចាប់ពីថ្ងៃទី</label>
                    <input type="date" x-model="startDate" @change="fetchReport()"
                           class="w-full h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 dark:text-gray-400 mb-1">ដល់ថ្ងៃទី</label>
                    <div class="flex gap-2">
                        <input type="date" x-model="endDate" @change="fetchReport()"
                               class="w-full h-10 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white text-sm font-medium">
                        <button type="button" @click="fetchReport()" class="px-5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95">
                            អនុវត្ត
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Loading Bar --}}
    <div x-show="loading" x-cloak class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-xs font-bold animate-pulse text-center flex items-center justify-center gap-2">
        <i class="fas fa-spinner fa-spin text-sm"></i> កំពុងស្វែងរកទិន្នន័យ (AJAX)...
    </div>

    {{-- Dynamic Report AJAX Content Container --}}
    <div id="report-content-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.reportscreate.partials.report_content')
    </div>

</div>

<script>
    function reportCreateManager() {
        return {
            tableType: '{{ $tableType }}',
            period: '{{ $period }}',
            status: '{{ $status }}',
            search: '{{ $search }}',
            startDate: '{{ $startDate }}',
            endDate: '{{ $endDate }}',
            loading: false,

            init() {
                // Intercept pagination clicks inside container for AJAX pagination
                document.getElementById('report-content-container').addEventListener('click', (e) => {
                    const link = e.target.closest('.pagination a, a.page-link');
                    if (link && link.href) {
                        e.preventDefault();
                        this.fetchReport(link.href);
                    }
                });
            },

            async fetchReport(url = null) {
                this.loading = true;
                let fetchUrl = url || '{{ route('reportscreate.index') }}';
                if (window.location.protocol === 'https:' && fetchUrl.startsWith('http://')) {
                    fetchUrl = fetchUrl.replace('http://', 'https://');
                }
                try {
                    const response = await axios.get(fetchUrl, {
                        params: {
                            table: this.tableType,
                            period: this.period,
                            status: this.status,
                            search: this.search,
                            start_date: this.startDate,
                            end_date: this.endDate
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    document.getElementById('report-content-container').innerHTML = response.data;
                } catch (error) {
                    console.error('Error fetching report data:', error);
                } finally {
                    this.loading = false;
                }
            },

            get exportExcelUrl() {
                const params = new URLSearchParams({
                    table: this.tableType,
                    period: this.period,
                    status: this.status,
                    search: this.search,
                    start_date: this.startDate,
                    end_date: this.endDate
                });
                return '{{ route('reportscreate.export-excel') }}?' + params.toString();
            },

            get exportPdfUrl() {
                const params = new URLSearchParams({
                    table: this.tableType,
                    period: this.period,
                    status: this.status,
                    search: this.search,
                    start_date: this.startDate,
                    end_date: this.endDate
                });
                return '{{ route('reportscreate.export-pdf') }}?' + params.toString();
            }
        };
    }
</script>
@endsection
