<!-- KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ប្រាក់បានប្រមូល</span>
            <h3 class="text-2xl font-black text-emerald-500 mt-0.5" x-text="currency === 'USD' ? '$' + parseFloat({{ $paidAmountCollected }}).toLocaleString('en-US', {minimumFractionDigits: 2}) : (parseFloat({{ $paidAmountCollected }}) * exchangeRate).toLocaleString('km-KH') + ' ៛'"></h3>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-0.5">ប្រាក់បានចូលគណនី</p>
        </div>
        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-check-double"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ប្រាក់រង់ចាំទូទាត់</span>
            <h3 class="text-2xl font-black text-amber-500 mt-0.5" x-text="currency === 'USD' ? '$' + parseFloat({{ $pendingAmount }}).toLocaleString('en-US', {minimumFractionDigits: 2}) : (parseFloat({{ $pendingAmount }}) * exchangeRate).toLocaleString('km-KH') + ' ៛'"></h3>
            <p class="text-[10px] text-amber-500 mt-0.5">មិនទាន់ទូទាត់រួច</p>
        </div>
        <div class="w-10 h-10 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-hourglass-half"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ប្រាក់សងវិញ / Deposit</span>
            <h3 class="text-2xl font-black text-blue-500 mt-0.5" x-text="currency === 'USD' ? '$' + parseFloat({{ $totalDeposits }}).toLocaleString('en-US', {minimumFractionDigits: 2}) : (parseFloat({{ $totalDeposits }}) * exchangeRate).toLocaleString('km-KH') + ' ៛'"></h3>
            <p class="text-[10px] text-blue-400 mt-0.5">Refunded / Deposits</p>
        </div>
        <div class="w-10 h-10 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-undo"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ប្រតិបត្តិការសរុប</span>
            <h3 class="text-2xl font-black text-indigo-500 mt-0.5">{{ number_format($totalCount) }}</h3>
            <p class="text-[10px] text-indigo-400 mt-0.5">គ្រប់ទម្រង់ទូទាត់</p>
        </div>
        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-receipt"></i>
        </div>
    </div>
</div>

<!-- Data Table Section -->
<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                បញ្ជីប្រវត្តិវិក្កយបត្របង់ប្រាក់ទាំងអស់
            </h3>
            <p class="text-[10px] text-gray-400">សណ្ឋាគារ ភីអេនធី ផាលេស</p>
        </div>
        <span class="text-xs text-gray-400 font-semibold">សរុប {{ $payments->total() }} កំណត់ត្រា</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 uppercase tracking-wider">
                    <th class="p-3.5">ID</th>
                    <th class="p-3.5">លេខកូដកក់</th>
                    <th class="p-3.5">ឈ្មោះអតិថិជន</th>
                    <th class="p-3.5">វិធីសាស្ត្រទូទាត់</th>
                    <th class="p-3.5">លេខប្រតិបត្តិការ (TXN ID)</th>
                    <th class="p-3.5 text-right">ចំនួនទឹកប្រាក់</th>
                    <th class="p-3.5 text-center">ស្លីបបង់ប្រាក់</th>
                    <th class="p-3.5 text-center">ស្ថានភាព</th>
                    <th class="p-3.5 text-center">កាលបរិច្ឆេទបង់</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($payments as $payment)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="p-3.5 font-mono text-[11px] font-bold text-gray-400">#{{ $payment->id }}</td>
                    <td class="p-3.5 font-mono font-bold text-blue-600 dark:text-blue-400 text-[11px]">{{ $payment->booking_code ?? 'N/A' }}</td>
                    <td class="p-3.5 font-semibold text-gray-800 dark:text-white">{{ $payment->customer_name ?? 'ភ្ញៀវទូទៅ' }}</td>
                    <td class="p-3.5">
                        @if(strtolower($payment->method) == 'qr')
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300 border border-blue-200">
                            <i class="fas fa-qrcode"></i> ស្កែន KHQR
                        </span>
                        @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200">
                            <i class="fas fa-money-bill-wave"></i> សាច់ប្រាក់ (Cash)
                        </span>
                        @endif
                    </td>
                    <td class="p-3.5 font-mono text-gray-500 dark:text-gray-400 text-[11px]">{{ $payment->transaction_id ?? 'N/A' }}</td>
                    <td class="p-3.5 text-right font-black text-emerald-500 text-sm">
                        <span x-show="currency === 'USD'">${{ number_format($payment->amount, 2) }}</span>
                        <span x-show="currency === 'KHR'" class="font-mono text-xs">{{ number_format($payment->amount * 4100) }} ៛</span>
                    </td>
                    <td class="p-3.5 text-center">
                        @if($payment->payment_slip)
                        <button @click="selectedSlip = '{{ asset('storage/' . $payment->payment_slip) }}'; slipModalOpen = true" class="text-blue-500 hover:underline flex items-center justify-center gap-1 text-[11px] mx-auto font-bold">
                            <i class="fas fa-image"></i> មើលរូបភាព
                        </button>
                        @else
                        <span class="text-[10px] text-gray-400">គ្មានឯកសារ</span>
                        @endif
                    </td>
                    <td class="p-3.5 text-center">
                        @php
                        $payStatusKhmer = match($payment->status) {
                        'paid' => 'បានបង់',
                        'pending' => 'រង់ចាំ',
                        'refunded' => 'បានសងប្រាក់វិញ',
                        default => 'បរាជ័យ'
                        };
                        $payBadgeClass = match($payment->status) {
                        'paid' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-300',
                        'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-300',
                        'refunded' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-300',
                        default => 'bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 border-rose-300'
                        };
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $payBadgeClass }}">
                            {{ $payStatusKhmer }}
                        </span>
                    </td>
                    <td class="p-3.5 text-center text-[11px] text-gray-500 dark:text-gray-400 font-mono">
                        {{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d/m/Y H:i') : ($payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d/m/Y H:i') : 'N/A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center p-12 text-gray-400 text-xs">មិនទាន់មានទិន្នន័យប្រតិបត្តិការបង់ប្រាក់ទេ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 pagination">
        {{ $payments->links() }}
    </div>
</div>