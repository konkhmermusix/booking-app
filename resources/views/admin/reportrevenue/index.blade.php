@extends('layouts.admin')
@section('title', 'របាយការណ៍ហិរញ្ញវត្ថុ')
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

<div id="reportsrevenue-page" class="p-2 sm:p-2 space-y-6" x-data="{ 
        currency: 'USD', 
        exchangeRate: {{ $khrRate }}, 
        year: '{{ $year }}', 
        loading: false,

        async fetchReports(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('reportsrevenue.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { year: this.year },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('report-container').innerHTML = response.data;
                this.initCharts();
            } catch (error) { console.error('Error fetching revenue reports:', error); }
            this.loading = false;
        },

        initCharts() {
            setTimeout(() => {
                // 1. Monthly Revenue Line Chart
                const revEl = document.getElementById('revenueLineChart');
                if (revEl && typeof Chart !== 'undefined') {
                    const rawData = revEl.dataset.chart ? JSON.parse(revEl.dataset.chart) : {!! json_encode(array_values($chartData)) !!};
                    const revCtx = revEl.getContext('2d');
                    if (window.myRevenueChart) { window.myRevenueChart.destroy(); }
                    window.myRevenueChart = new Chart(revCtx, {
                        type: 'line',
                        data: {
                            labels: ['មករា', 'កុម្ភៈ', 'មីនា', 'មេសា', 'ឧសភា', 'មិថុនា', 'កក្កដា', 'សីហា', 'កញ្ញា', 'តុលា', 'វិច្ឆិកា', 'ធ្នូ'],
                            datasets: [{
                                label: 'ចំណូលសរុប ($)',
                                data: rawData,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.08)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.3,
                                pointBackgroundColor: '#10b981'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }

                // 2. Payment Methods Doughnut Chart
                const payEl = document.getElementById('paymentMethodChart');
                if (payEl && typeof Chart !== 'undefined') {
                    const rawLabels = payEl.dataset.labels ? JSON.parse(payEl.dataset.labels) : {!! json_encode($paymentMethods->pluck('method')->map(fn($m) => strtoupper($m ?: 'CASH'))) !!};
                    const rawValues = payEl.dataset.values ? JSON.parse(payEl.dataset.values) : {!! json_encode($paymentMethods->pluck('total')) !!};
                    const payCtx = payEl.getContext('2d');
                    if (window.myPayChart) { window.myPayChart.destroy(); }
                    window.myPayChart = new Chart(payCtx, {
                        type: 'doughnut',
                        data: {
                            labels: rawLabels,
                            datasets: [{
                                data: rawValues,
                                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ec4899'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { position: 'bottom' } }
                        }
                    });
                }
            }, 150);
        }
    }" x-init="initCharts()">

    <!-- Combined Filter & Search Controls Top Bar -->
    <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800 print-hide">
        <div class="shrink-0">
            <h2 class="text-base font-bold text-gray-800 dark:text-white tracking-tight">របាយការណ៍ហិរញ្ញវត្ថុ & ចំណូលរួម</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Live Financial & Revenue Analysis Report</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full xl:w-auto">
            {{-- Year Filter --}}
            <div class="w-40 shrink-0">
                <div class="relative group">
                    <select x-model="year" @change="fetchReports()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        @for($y = date('Y'); $y >= date('Y') - 4; $y--)
                            <option value="{{ $y }}">ឆ្នាំ {{ $y }}</option>
                        @endfor
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

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

            <a :href="'{{ route('reportsrevenue.export-excel') }}?year=' + year" class="h-10 px-3.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-excel"></i> Excel
            </a>

            <a :href="'{{ route('reportsrevenue.export-pdf') }}?year=' + year" target="_blank" class="h-10 px-3.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition active:scale-95 shadow-sm shrink-0">
                <i class="fas fa-file-pdf"></i> PDF
            </a>
        </div>
    </div>

    <!-- Report Dynamic Partial Container -->
    <div id="report-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.reportrevenue.partials.report_content')
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rootElement = document.getElementById('reportsrevenue-page');
        if (rootElement && typeof Alpine !== 'undefined') {
            setTimeout(() => {
                const component = Alpine.$data(rootElement);
                if (component && typeof component.initCharts === 'function') {
                    component.initCharts();
                }
            }, 200);
        }
    });
</script>
@endpush
@endsection