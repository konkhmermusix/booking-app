<div x-show="viewMode === 'list'" class="bg-white dark:bg-gray-900 rounded-[1.5rem] border dark:border-gray-800 overflow-hidden shadow-sm">
    <table class="w-full text-left">
        <thead class="bg-gray-50/80 dark:bg-gray-800/50 text-gray-400 text-[13px] uppercase font-black tracking-widest border-b dark:border-gray-800">
            <tr>
                <th class="px-6 py-4">បន្ទប់ / ជាន់</th>
                <th class="px-6 py-4">សណ្ឋាគារ</th>
                <th class="px-6 py-4">ប្រភេទ</th>
                <th class="px-6 py-4 text-center">ស្ថានភាព</th>
                <th class="px-6 py-4 text-right">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody class="divide-y dark:divide-gray-800">
            @forelse($rooms as $room)
            <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-all">
                <td class="px-6 py-4 min-w-[100px] flex">
                    <div @click="currentRoom = {{ $room->toJson() }}; showDetailModal = true"
                        class="group relative w-10 h-10 bg-gray-50 dark:bg-gray-800 rounded-2xl mb-4 flex items-center justify-center overflow-hidden border dark:border-gray-700 cursor-pointer shadow-sm hover:shadow-md transition-all">

                        @if($room->roomType->images->first())
                        <img src="{{ asset('storage/'.$room->roomType->images->first()->image_path) }}"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition-colors"></div>
                        @else
                        <div class="flex flex-col items-center opacity-20">
                            <i class="fas fa-hotel text-5xl mb-2"></i>
                            <span class="text-[10px] font-bold">គ្មានរូបភាព</span>
                        </div>
                        @endif
                    </div>

                    <div class="px-1">
                        <div class="text-md font-black dark:text-white leading-tight">#{{ $room->room_number }}</div>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                ជាន់ {{ $room->floor ?? '0' }}
                            </span>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-3 text-sm font-medium dark:text-gray-300">{{ $room->hotel->name }}</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-0.5 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-md text-sm font-black border border-blue-100 dark:border-blue-500/20">
                        {{ $room->roomType->name }}
                    </span>
                </td>
                <td class="px-6 py-3 text-center">
                    @include('admin.rooms.partials.status-badge', ['status' => $room->status])
                </td>
                <td class="px-6 py-3 text-right">
                    <div class="flex justify-end gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                        <button @click="currentRoom = {{ $room->toJson() }}; showDetailModal = true" class="w-7 h-7 flex items-center justify-center rounded-lg text-blue-500 hover:bg-amber-50"><i class="fas fa-eye text-[13px]"></i></button>
                        <button @click="currentRoom = {{ $room->toJson() }}; showEditModal = true" class="w-7 h-7 flex items-center justify-center rounded-lg text-amber-500 hover:bg-amber-50"><i class="fas fa-edit text-[13px]"></i></button>
                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete(this.closest('form'))" class="w-7 h-7 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50"><i class="fas fa-trash text-[13px]"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="py-20 text-center text-sm text-gray-400 font-medium tracking-wide">រកមិនឃើញទិន្នន័យបន្ទប់ដែលអ្នកចង់រកឡើយ...
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div x-show="viewMode === 'grid'"
    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6">

    @forelse($rooms as $room)
    <div class="bg-white dark:bg-gray-900 p-2 rounded-2xl border dark:border-gray-800 hover:shadow-2xl transition-all duration-300 group flex flex-col justify-between">

        <div @click="currentRoom = {{ $room->toJson() }}; showDetailModal = true" class="relative w-full h-34 bg-gray-50 dark:bg-gray-800 rounded-2xl mb-5 flex items-center justify-center overflow-hidden border dark:border-gray-700">
            @if($room->roomType->images->first())
            <img src="{{ asset('storage/'.$room->roomType->images->first()->image_path) }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            @else
            <div class="flex flex-col items-center opacity-20">
                <i class="fas fa-hotel text-5xl mb-2"></i>
                <span class="text-[10px] font-bold">គ្មានរូបភាព</span>
            </div>
            @endif

            <div class="absolute top-4 right-4 scale-125">
                @include('admin.rooms.partials.status-badge', ['status' => $room->status])
            </div>
        </div>

        <div class="px-1 space-y-2">
            <div class="flex items-center justify-between">
                <h5 class="text-md font-black dark:text-white tracking-tighter">#{{ $room->room_number }}</h5>
                <span class="text-[10px] font-black text-blue-600 bg-blue-100 dark:bg-blue-500/20 px-3 py-1.5 rounded-xl uppercase">
                    {{ $room->roomType->name }}
                </span>
            </div>
            <p class="text-xs text-gray-400 font-bold uppercase  flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-[10px]"></i>
                {{ $room->hotel->name }}
            </p>
        </div>

        <div class="mt-3 pt-3 border-t dark:border-gray-800 flex justify-end">
            <button @click="currentRoom = {{ $room->toJson() }}; showEditModal = true"
                class="w-7 h-7 flex items-center justify-center rounded-lg text-amber-500 hover:bg-amber-50"><i class="fas fa-edit text-[13px]"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="col-span-full py-32 text-center bg-gray-50 dark:bg-gray-900/50 rounded-[3rem] border-2 border-dashed dark:border-gray-800">
        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
        <p class="text-base text-gray-400 font-medium">រកមិនឃើញទិន្នន័យបន្ទប់ដែលអ្នកចង់រកឡើយ...</p>
    </div>
    @endforelse
</div>

<div class="mt-6 text-sm">
    {{ $rooms->links() }}
</div>