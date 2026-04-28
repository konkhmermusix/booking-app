<div x-show="viewMode === 'list'" class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm overflow-hidden  dark:border-gray-800">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50/50 dark:bg-gray-800/50 dark:border-gray-800">
            <tr>
                <th class="px-6 py-4 text-sm font-bold text-gray-400 uppercase tracking-wider">រូបភាព</th>
                <th class="px-6 py-4 text-sm font-bold text-gray-400 uppercase tracking-wider">ឈ្មោះ</th>
                <th class="px-6 py-4 text-sm font-bold text-gray-400 uppercase tracking-wider">ចម្ងាយ</th>
                <th class="px-6 py-4 text-sm font-bold text-gray-400 uppercase tracking-wider">ស្ថានភាព</th>
                <th class="px-6 py-4 text-sm font-bold text-gray-400 uppercase tracking-wider text-right">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
            @foreach($tours as $tour)
            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                <td class="px-6 py-4">
                    <img src="{{ $tour->image ? asset('storage/'. (is_array($tour->image) ? $tour->image[0] : $tour->image)) : 'https://via.placeholder.com/100' }}"
                        class="w-12 h-12 rounded-xl object-cover border dark:border-gray-700">
                </td>
                <td class="px-6 py-4 font-bold text-gray-700 dark:text-gray-200">{{ $tour->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $tour->distance ?? 'N/A' }} គីឡូម៉ែត្រ</td>
                <td class="px-6 py-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $tour->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $tour->status ? 'បង្ហាញ' : 'មិនបង្ហាញ' }}
                    </span>
                </td>

                <td class="px-6 py-4 text-right flex gap-3 justify-end">
                    <div class="flex justify-end gap-2 space-2">
                        <button @click="currentTour = {{ $tour->toJson() }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                        <button @click="currentTour = {{ $tour->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                        <button type="button"
                            onclick="confirmDelete('{{ $tour->id }}')"
                            class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"
                            title="លុប">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($tours as $tour)
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm dark:border-gray-800">
        <img src="{{ $tour->image ? asset('storage/'. (is_array($tour->image) ? $tour->image[0] : $tour->image)) : 'https://via.placeholder.com/300' }}"
            class="w-full h-40 object-cover rounded-2xl mb-4">
        <h3 class="font-bold dark:text-white">{{ $tour->name }}</h3>
        <p class="text-xs text-gray-500 mb-4">{{ $tour->distance }}</p>
        <div class="flex gap-2">
            <button @click="currentTour = {{ $tour->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
            <button type="button"
                onclick="confirmDelete('{{ $tour->id }}')"
                class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"
                title="លុប">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6">
    {{ $tours->links() }}
</div>