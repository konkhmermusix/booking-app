<!-- 3 KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">អតិថិជនសរុប (Unique)</span>
            <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-0.5">{{ number_format($totalUniqueCustomers) }} នាក់</h3>
            <p class="text-[10px] text-gray-400 mt-0.5">អតិថិជនធ្លាប់កក់សរុប</p>
        </div>
        <div class="w-10 h-10 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-users"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">អតិថិជនថ្មីខែនេះ</span>
            <h3 class="text-2xl font-black text-emerald-500 mt-0.5">{{ number_format($newCustomersThisMonth) }} នាក់</h3>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-0.5">កក់ដំបូងក្នុងខែនេះ</p>
        </div>
        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-user-plus"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">អតិថិជនត្រឡប់មកវិញ (Returning)</span>
            <h3 class="text-2xl font-black text-indigo-500 mt-0.5">{{ number_format($returningCustomersCount) }} នាក់</h3>
            <p class="text-[10px] text-indigo-400 mt-0.5">ធ្លាប់កក់លើសពី 1 ដង</p>
        </div>
        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-user-check"></i>
        </div>
    </div>
</div>

<!-- Customer Rankings Table Section -->
<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i> ចំណាត់ថ្នាក់ និងប្រវត្តិអតិថិជន
            </h3>
            <p class="text-[10px] text-gray-400">សណ្ឋាគារ ភីអេនធី ផាលេស (PNT Palace Hotel)</p>
        </div>
        <span class="text-xs text-gray-400 font-semibold">បង្ហាញ {{ $topCustomers->count() }} ពី {{ $topCustomers->total() }} អតិថិជន</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 uppercase tracking-wider">
                    <th class="p-3.5 text-center w-20">ចំណាត់ថ្នាក់</th>
                    <th class="p-3.5">ឈ្មោះអតិថិជន</th>
                    <th class="p-3.5">ព័ត៌មានទំនាក់ទំនង</th>
                    <th class="p-3.5 text-center">ចំនួនដងដែលបានកក់</th>
                    <th class="p-3.5 text-right">ចំណាយសរុប (Spend)</th>
                    <th class="p-3.5 text-center">Loyalty Segment</th>
                    <th class="p-3.5 text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($topCustomers as $index => $customer)
                @php
                    $segment = $customer->total_bookings >= 5 ? 'VIP' : ($customer->total_bookings >= 2 ? 'Regular' : 'New');
                @endphp
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/30 transition-colors cursor-pointer" @click="selectedCustomer = {{ json_encode($customer) }}; timelineModalOpen = true">
                    <td class="p-3.5 text-center font-bold">
                        @if($topCustomers->currentPage() == 1 && $index == 0)
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-400 rounded text-[10px] font-bold">🥇 លេខ ១</span>
                        @elseif($topCustomers->currentPage() == 1 && $index == 1)
                            <span class="px-2 py-0.5 bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-400 rounded text-[10px] font-bold">🥈 លេខ ២</span>
                        @elseif($topCustomers->currentPage() == 1 && $index == 2)
                            <span class="px-2 py-0.5 bg-amber-600/10 text-amber-800 dark:bg-amber-900/30 dark:text-amber-500 rounded text-[10px] font-bold">🥉 លេខ ៣</span>
                        @else
                            <span class="text-gray-400 font-mono text-[11px]">#{{ ($topCustomers->currentPage() - 1) * $topCustomers->perPage() + $index + 1 }}</span>
                        @endif
                    </td>
                    <td class="p-3.5 font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-[10px] uppercase">
                            {{ mb_substr($customer->name, 0, 1) }}
                        </div>
                        {{ $customer->name }}
                    </td>
                    <td class="p-3.5">
                        <span class="block font-mono text-[11px] text-gray-700 dark:text-gray-300">{{ $customer->email }}</span>
                        <span class="block font-mono text-[10px] text-gray-400">{{ $customer->phone }}</span>
                    </td>
                    <td class="p-3.5 text-center font-black text-blue-600 dark:text-blue-400 text-sm">{{ number_format($customer->total_bookings) }} ដង</td>
                    <td class="p-3.5 text-right font-black text-emerald-500 text-sm">${{ number_format($customer->lifetime_spend, 2) }}</td>
                    <td class="p-3.5 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                            @if($segment == 'VIP') bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-300 dark:border-amber-700
                            @elseif($segment == 'Regular') bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border border-blue-300 dark:border-blue-700
                            @else bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700 @endif">
                            {{ $segment }}
                        </span>
                    </td>
                    <td class="p-3.5 text-center">
                        <button @click.stop="selectedCustomer = {{ json_encode($customer) }}; timelineModalOpen = true" class="px-2.5 py-1 bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/50 dark:hover:bg-blue-900 text-blue-600 dark:text-blue-400 rounded-lg text-[10px] font-bold transition">
                            <i class="fas fa-history mr-1"></i> ប្រវត្តិ
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center p-12 text-gray-400 text-xs">មិនទាន់មានទិន្នន័យទំនាក់ទំនងរបស់អតិថិជននៅឡើយទេ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-4 border-t border-gray-100 dark:border-gray-800 pagination">
        {{ $topCustomers->links() }}
    </div>
</div>
