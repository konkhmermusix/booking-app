<div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-800">
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400">ព័ត៌មានទំនាក់ទំនង</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400">ខ្លឹមសារសង្ខេប</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400 text-center">កាលបរិច្ឆេទ</th>
                    <th class="px-6 py-4 text-[11px] uppercase tracking-wider font-bold text-gray-400 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($messages as $msg)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold dark:text-white text-sm">{{ $msg->name }}</div>
                        <div class="text-[11px] text-gray-400">{{ $msg->email }}</div>
                        <div class="text-[11px] text-gray-400">{{ $msg->tell }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 dark:text-gray-400 italic text-sm">
                        "{{ Str::limit(strip_tags($msg->description), 50) }}"
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $statusClasses = [
                        'unread' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
                        'completed' => 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400'
                        ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusClasses[$msg->status] ?? 'bg-gray-100' }}">
                            {{ $msg->status == 'unread' ? 'មិនទាន់អាន' : 'បានអានរួច' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 text-center">{{ $msg->created_at->format('d M, Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end items-center gap-1">
                            <button @click="openDetail({{ json_encode($msg) }})"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="លម្អិត">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button @click="editMessage({{ json_encode($msg) }})"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('contact.destroy', $msg->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.form)"
                                    class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    title="លុប">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-inbox text-4xl text-gray-200 mb-3"></i>
                            <p class="text-gray-400 text-sm">មិនមានសារក្នុងប្រព័ន្ធឡើយ</p>
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
        {{ $messages->links() }}
    </div>
</div>