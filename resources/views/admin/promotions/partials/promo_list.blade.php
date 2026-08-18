<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ព័ត៌មានប្រូម៉ូសិន</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ប្រភេទបន្ទប់</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-center">តម្លៃ (ដើម vs បញ្ចុះ)</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ថ្ងៃផុតកំណត់</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($promotions as $promo)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="relative w-14 h-10 rounded-lg overflow-hidden flex-shrink-0 shadow-sm">
                                <img src="{{ $promo->image_path ? asset('storage/' . $promo->image_path) : 'https://placehold.co/100x70?text=No+Image' }}"
                                    class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-transparent transition-all"></div>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-0.5 line-clamp-1">{{ $promo->title }}</h4>
                                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[9px] font-bold px-2 py-0.5 rounded-md uppercase tracking-tight">
                                    {{ $promo->tag ?? 'ទូទៅ' }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-bed text-gray-400 text-xs"></i>
                            {{ $promo->roomType->name ?? 'គ្រប់ប្រភេទ' }}
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex flex-col items-center">
                            <span class="text-[11px] text-red-400 line-through opacity-70">${{ number_format($promo->original_price, 2) }}</span>
                            <span class="text-sm font-bold text-green-600">${{ number_format($promo->discounted_price, 2) }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="far fa-clock {{ $promo->expiry_date < now() ? 'text-red-500' : 'text-gray-400' }}"></i>
                            {{ \Carbon\Carbon::parse($promo->expiry_date)->format('d-m-Y H:i:s') }}
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @if($promo->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            បង្ហាញ
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                            មិនបង្ហាញ
                        </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex justify-end items-center gap-1">
                            <button type="button" @click="currentPromo = {{ json_encode($promo) }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                            <button type="button" @click="currentPromo = {{ json_encode($promo) }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                            <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" class="inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.form)" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-400">មិនទាន់មានទិន្នន័យប្រូម៉ូសិននៅឡើយ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-show="viewMode === 'list'" class="space-y-3" x-cloak x-transition>
    @forelse($promotions as $promo)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-none group">
        <div class="flex items-center gap-4">
            <div class="w-16 h-12 rounded-xl overflow-hidden shadow-sm flex-shrink-0 relative">
                <img src="{{ $promo->image_path ? asset('storage/' . $promo->image_path) : 'https://placehold.co/100x70?text=No+Image' }}" class="w-full h-full object-cover">
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1">{{ $promo->title }}</span>
                    <span class="bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 text-[9px] font-black px-1.5 py-0.5 rounded uppercase">
                        {{ $promo->tag ?? 'ទូទៅ' }}
                    </span>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-400 mt-0.5">
                    <span class="flex items-center gap-1"><i class="fas fa-bed"></i> {{ $promo->roomType->name ?? 'គ្រប់ប្រភេទ' }}</span>
                    <span>•</span>
                    <span class="text-green-600 font-bold">${{ number_format($promo->discounted_price, 2) }}</span>
                    <span class="text-red-400 line-through opacity-60 text-[10px]">${{ number_format($promo->original_price, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div>
                @if($promo->status)
                <span class="w-2 h-2 block rounded-full bg-green-500 animate-pulse" title="កំពុងបង្ហាញ"></span>
                @else
                <span class="w-2 h-2 block rounded-full bg-gray-400" title="មិនបង្ហាញ"></span>
                @endif
            </div>
            <div class="flex justify-end items-center gap-1">
                <button type="button" @click="currentPromo = {{ json_encode($promo) }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                <button type="button" @click="currentPromo = {{ json_encode($promo) }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" class="inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete(this.form)" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 text-center text-gray-400 shadow-sm">មិនទាន់មានទិន្នន័យប្រូម៉ូសិននៅឡើយ</div>
    @endforelse
</div>

<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" x-cloak x-transition>
    @forelse($promotions as $promo)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm hover:shadow-md transition-all group border-none flex flex-col justify-between relative overflow-hidden">

        <div class="absolute top-3 right-3 z-10">
            @if($promo->status)
            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400">សកម្ម</span>
            @else
            <span class="px-2 py-0.5 rounded-md text-[9px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">បិទ</span>
            @endif
        </div>

        <div>
            <div class="w-full h-32 rounded-xl overflow-hidden shadow-sm mb-3 relative bg-gray-100 dark:bg-gray-900">
                <img src="{{ $promo->image_path ? asset('storage/' . $promo->image_path) : 'https://placehold.co/300x200?text=No+Image' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            </div>

            <div class="space-y-1 mb-4">
                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[9px] font-bold px-2 py-0.5 rounded-md uppercase">
                    {{ $promo->tag ?? 'ទូទៅ' }}
                </span>
                <h4 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1 mt-1">{{ $promo->title }}</h4>
                <p class="text-xs text-gray-400 flex items-center gap-1"><i class="fas fa-bed text-[10px]"></i> {{ $promo->roomType->name ?? 'គ្រប់ប្រភេទ' }}</p>

                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-base font-black text-green-600">${{ number_format($promo->discounted_price, 2) }}</span>
                    <span class="text-xs text-red-400 line-through opacity-70">${{ number_format($promo->original_price, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center pt-2 border-t border-gray-50 dark:border-gray-700/50">
            <span class="text-[10px] text-gray-400 font-medium truncate" title="ផុតកំណត់">
                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($promo->expiry_date)->format('d-m-Y') }}
            </span>
            <div class="flex justify-end items-center gap-1">
                <button type="button" @click="currentPromo = {{ json_encode($promo) }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                <button type="button" @click="currentPromo = {{ json_encode($promo) }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" class="inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete(this.form)" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl p-8 text-center text-gray-400 shadow-sm">មិនទាន់មានទិន្នន័យប្រូម៉ូសិននៅឡើយ</div>
    @endforelse
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
    <div class="dark:text-white">
        {{ $promotions->links() }}
    </div>
</div>