<div class="bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800 text-sm">
    <table class="w-full">
        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b dark:border-gray-800">
            <tr>
                <th class="px-6 py-4 text-left font-bold text-gray-600 dark:text-gray-400">អតិថិជន</th>
                <th class="px-6 py-4 text-left font-bold text-gray-600 dark:text-gray-400">ខ្លឹមសារសង្ខេប</th>
                <th class="px-6 py-4 text-center font-bold text-gray-600 dark:text-gray-400">ស្ថានភាព</th>
                <th class="px-6 py-4 text-center font-bold text-gray-600 dark:text-gray-400">កាលបរិច្ឆេទ</th>
                <th class="px-6 py-4 text-right font-bold text-gray-600 dark:text-gray-400">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody class="divide-y dark:divide-gray-800">
            @forelse($messages as $msg)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                <td class="px-6 py-4">
                    <div class="font-bold dark:text-white">{{ $msg->name }}</div>
                    <div class="text-[11px] text-gray-400">{{ $msg->email }}</div>
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400 italic">
                    "{{ Str::limit($msg->description, 40) }}"
                </td>
                <td class="px-6 py-4 text-center">
                    @php
                    $statusClasses = [
                    'unread' => 'bg-red-100 text-red-600 dark:bg-red-900/20 dark:text-red-400',
                    'pending' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400',
                    'completed' => 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400',
                    ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusClasses[$msg->status] }}">
                        {{ $msg->status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center text-gray-400 text-xs">
                    {{ $msg->created_at->format('d M, Y H:i') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <button @click="openDetail({{ $msg }})" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all">
                            <i class="fas fa-eye"></i>
                        </button>
                        <form action="{{ route('contact.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបសារនេះមែនទេ?')">
                            @csrf @method('DELETE')
                            <button class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="p-10 text-center text-gray-400 font-medium">មិនមានសារថ្មីទេ</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4 border-t dark:border-gray-800">
        {{ $messages->links('vendor.pagination.tailwind') }}
    </div>
</div>