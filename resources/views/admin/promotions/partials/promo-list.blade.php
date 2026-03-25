<div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">ព័ត៌មានប្រូម៉ូសិន</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">ប្រភេទបន្ទប់</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-center">តម្លៃ (ដើម vs បញ្ចុះ)</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">ថ្ងៃផុតកំណត់</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-800">
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
                                    {{ $promo->tag ?? 'General' }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-bed text-gray-400 text-xs"></i>
                            {{ $promo->roomType->name }}
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
                            {{ \Carbon\Carbon::parse($promo->expiry_date)->format('d M, Y') }}
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        @if($promo->status)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                            Active
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                            Inactive
                        </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button @click="showEditModal = true; currentPromo = {{ $promo }}"
                                class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="កែប្រែ">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <button @click="deletePromo({{ $promo->id }})" class="text-red-600 ...">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center">
                            <div class="bg-gray-100 dark:bg-gray-800 p-6 rounded-full mb-4">
                                <i class="fas fa-percent text-3xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-400 text-sm">មិនទាន់មានទិន្នន័យប្រូម៉ូសិននៅឡើយទេ។</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 border-t border-gray-50 dark:border-gray-800 pagination">
        {{ $promotions->links() }}
    </div>
</div>