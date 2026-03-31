<div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-2xl shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 dark:bg-gray-800 text-gray-500 text-[11px] uppercase tracking-wider">
            <tr>
                <th class="p-4 font-bold">ឆ្នាំ</th>
                <th class="p-4 font-bold">ចំណងជើង (KH)</th>
                <th class="p-4 font-bold">ពិរពណ៌នា (KH)</th>
                <th class="p-4 font-bold">ស្ថានភាព</th>
                <th class="p-4 font-bold text-right">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($histories as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                <td class="p-4 text-sm font-bold text-blue-600">{{ $item->year }}</td>
                <td class="p-4 text-sm dark:text-gray-300">{{ $item->title_kh }}</td>
                <td class="p-4 text-sm">{{ Str::limit($item->description_kh, 50) }}</td>
                <td class="p-4">
                    @if($item->status == 1)
                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                        សកម្ម
                    </span>
                    @else
                    <span class="px-3 py-1 text-[10px] font-bold uppercase rounded-lg bg-red-100 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                        អសកម្ម
                    </span>
                    @endif
                </td>
            
                <td class="px-4 py-4 text-right flex gap-3 justify-end">
                    <div class="flex justify-end gap-2 space-x-3">
                        <button @click="currentHistory = {{ json_encode($item) }}; showDetailModal = true"
                            class="text-gray-400 hover:text-indigo-500 transition-all active:scale-90"
                            title="មើលលម្អិត">
                            <i class="fas fa-eye"></i>
                        </button>

                        <button @click="currentHistory = {{ json_encode($item) }}; showEditModal = true"
                            class="text-emerald-500 hover:text-emerald-600 transition-all active:scale-90"
                            title="កែប្រែ">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form action="{{ route('abouts.destroy', $item->id) }}" method="POST" class="delete-form inline">
                            @csrf @method('DELETE')
                            <button type="button"
                                class="text-red-500 hover:text-red-700 transition-all active:scale-90 btn-delete"
                                title="លុប">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-10 text-center text-gray-400 text-sm">
                    មិនមានទិន្នន័យឡើយ
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4 pagination-container">
    {{ $histories->links() }}
</div>