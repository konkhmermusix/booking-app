<!-- 4 KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ចំណូលសរុប (Revenue)</span>
            <h3 class="text-2xl font-black text-emerald-500 mt-0.5" x-text="currency === 'USD' ? '$' + parseFloat({{ $totalPaidRevenue }}).toLocaleString('en-US', {minimumFractionDigits: 2}) : (parseFloat({{ $totalPaidRevenue }}) * exchangeRate).toLocaleString('km-KH') + ' ៛'"></h3>
            <p class="text-[10px] text-gray-400 mt-0.5">ចំណូលបានទូទាត់រួចក្នុងប្រព័ន្ធ</p>
        </div>
        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-hand-holding-usd"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ចំណាយប្រតិបត្តិការ (Est. Expenses)</span>
            <h3 class="text-2xl font-black text-rose-500 mt-0.5" x-text="currency === 'USD' ? '$' + parseFloat({{ $estimatedExpenses }}).toLocaleString('en-US', {minimumFractionDigits: 2}) : (parseFloat({{ $estimatedExpenses }}) * exchangeRate).toLocaleString('km-KH') + ' ៛'"></h3>
            <p class="text-[10px] text-gray-400 mt-0.5">ចំណាយប្រតិបត្តិការប៉ាន់ស្មាន (25%)</p>
        </div>
        <div class="w-10 h-10 bg-rose-500/10 text-rose-600 dark:text-rose-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ចំណេញដុល (Net Profit)</span>
            <h3 class="text-2xl font-black text-blue-500 mt-0.5" x-text="currency === 'USD' ? '$' + parseFloat({{ $netProfit }}).toLocaleString('en-US', {minimumFractionDigits: 2}) : (parseFloat({{ $netProfit }}) * exchangeRate).toLocaleString('km-KH') + ' ៛'"></h3>
            <p class="text-[10px] text-gray-400 mt-0.5">ប្រាក់ចំណេញបន្ទាប់ពីដកចំណាយ</p>
        </div>
        <div class="w-10 h-10 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-piggy-bank"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">កំណើន (MoM Growth)</span>
            <h3 class="text-2xl font-black text-indigo-500 mt-0.5">{{ $momGrowth >= 0 ? '+' : '' }}{{ $momGrowth }}%</h3>
            <p class="text-[10px] text-indigo-400 mt-0.5">ប្រៀបធៀបនឹងខែមុន</p>
        </div>
        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
</div>

<!-- Financial Charts Grid Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-chart-area text-emerald-500"></i> ក្រាហ្វិកចំណូលប្រចាំខែក្នុងឆ្នាំ {{ $year }}
        </h4>
        <div class="h-[300px]">
            <canvas id="revenueLineChart" data-chart='{!! json_encode(array_values($chartData)) !!}'></canvas>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
        <h4 class="text-sm font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
            <i class="fas fa-pie-chart text-blue-500"></i> ចំណូលតាមទម្រង់ទូទាត់
        </h4>
        <div class="h-[300px] flex justify-center items-center">
            <canvas id="paymentMethodChart" data-labels='{!! json_encode($paymentMethods->pluck("method")->map(fn($m) => strtoupper($m ?: "CASH"))) !!}' data-values='{!! json_encode($paymentMethods->pluck("total")) !!}'></canvas>
        </div>
    </div>
</div>

<!-- Revenue Breakdown Table Section -->
<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-list-alt text-emerald-500"></i> ការបែងចែកចំណូលតាមផ្នែក (Department Revenue Breakdown)
            </h3>
            <p class="text-[10px] text-gray-400">សណ្ឋាគារ ភីអេនធី ផាលេស (PNT Palace Hotel)</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 uppercase tracking-wider">
                    <th class="p-3.5">ផ្នែក / ប្រភពចំណូល</th>
                    <th class="p-3.5 text-right">ចំណូលសរុប (Subtotal)</th>
                    <th class="p-3.5 text-center">ភាគរយនៃចំណូល (% of Total)</th>
                    <th class="p-3.5 text-center">កំណើនធៀបនឹងគ្រាមុន</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($departmentBreakdown as $dept)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="p-3.5 font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-{{ $dept['status_color'] }}-500"></span>
                        {{ $dept['name'] }}
                    </td>
                    <td class="p-3.5 text-right font-black text-emerald-500 text-sm">
                        <div x-show="currency === 'USD'">${{ number_format($dept['subtotal'], 2) }}</div>
                        <div x-show="currency === 'KHR'" class="font-mono text-xs">{{ number_format($dept['subtotal'] * $khrRate) }} ៛</div>
                    </td>
                    <td class="p-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <span class="font-bold text-xs">{{ $dept['percentage'] }}%</span>
                            <div class="w-20 bg-gray-100 dark:bg-gray-800 h-2 rounded-full overflow-hidden">
                                <div class="bg-{{ $dept['status_color'] }}-500 h-full rounded-full" style="width: {{ $dept['percentage'] }}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="p-3.5 text-center font-bold text-xs text-emerald-600 dark:text-emerald-400">
                        {{ $dept['comparison'] }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
