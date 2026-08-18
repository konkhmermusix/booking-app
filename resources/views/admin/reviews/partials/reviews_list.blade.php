<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400">ព័ត៌មានភ្ញៀវ</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400">ប្រភេទបន្ទប់</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400">មតិយោបល់</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400 text-center">កាលបរិច្ឆេទ</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($reviews as $rev)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold dark:text-white text-sm">{{ $rev->name }}</div>
                        <div class="text-[11px] text-gray-400">{{ $rev->user ? $rev->user->email : 'ភ្ញៀវទូទៅ' }}</div>
                        <div class="flex items-center gap-0.5 text-yellow-400 mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rev->rating)
                                    <i class="fas fa-star text-[10px]"></i>
                                @else
                                    <i class="far fa-star text-[10px] text-gray-300 dark:text-gray-600"></i>
                                @endif
                            @endfor
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        {{ $rev->roomType ? $rev->roomType->name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 italic text-sm max-w-xs truncate">
                        "{{ Str::limit(strip_tags($rev->comment), 50) }}"
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $statusClasses = [
                            1 => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400',
                            0 => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400'
                        ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusClasses[$rev->status] ?? 'bg-gray-100' }}">
                            {{ $rev->status == 1 ? 'បង្ហាញ' : 'លាក់' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $rev->created_at->format('d M, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex justify-end items-center gap-1">
                            <button type="button" @click="openDetail({{ json_encode($rev->load('roomType', 'user')) }})"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <button type="button" @click="editReview({{ json_encode($rev->load('roomType', 'user')) }})"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <form action="{{ route('reviews.destroy', $rev->id) }}" method="POST" class="inline m-0">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.form)"
                                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    title="លុប">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-4xl text-gray-200 mb-3"></i>
                            <p class="text-gray-400 text-sm">មិនមានការវាយតម្លៃក្នុងប្រព័ន្ធឡើយ</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
    <div class="dark:text-white">
        {{ $reviews->links() }}
    </div>
</div>
