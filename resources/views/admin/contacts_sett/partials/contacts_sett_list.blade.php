<div class="bg-white dark:bg-gray-900 rounded-[1.5rem] overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/50 dark:bg-gray-800/50">
                <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">ព័ត៌មាន</th>
                <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">តម្លៃ (Value)</th>
                <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider">ស្ថានភាព</th>
                <th class="px-6 py-4 text-[11px] font-bold text-gray-400 uppercase tracking-wider text-right">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($settings as $item)
            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-{{ $item->color }}-100 dark:bg-{{ $item->color }}-900/20 flex items-center justify-center text-{{ $item->color }}-600">
                            <i class="fas {{ $item->icon }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold dark:text-white">{{ $item->label }}</p>
                            <p class="text-[10px] text-gray-400 font-mono">{{ $item->key }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 truncate max-w-xs">{{ $item->value }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-lg text-[10px] font-bold {{ $item->status ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $item->status ? 'ACTIVE' : 'INACTIVE' }}
                    </span>
                </td>
               
                <td class="px-6 py-4 text-right flex justify-end gap-1">
                    <button @click="openEdit({{ $item }})" class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button @click="currentContact = {{ $item }}; showDeleteModal = true" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>