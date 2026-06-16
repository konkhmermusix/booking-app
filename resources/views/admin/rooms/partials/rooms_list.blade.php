<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">លេខបន្ទប់</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ឈ្មោះសណ្ឋាគារ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ប្រភេទ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ជាន់</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($rooms as $room)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400">{{ $room->room_number }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $room->hotel->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $room->roomType->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $room->floor }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase 
                        @if($room->status === 'available') text-green-500 
                        @elseif($room->status === 'booked') text-blue-500 
                        @else text-orange-500 @endif">

                            <span class="w-1.5 h-1.5 rounded-full 
                            @if($room->status === 'available') bg-green-500 
                            @elseif($room->status === 'booked') bg-blue-500 
                            @else bg-orange-500 @endif">
                            </span>

                            @if($room->status === 'available')
                            បន្ទប់ទំនេរ
                            @elseif($room->status === 'booked')
                            មានភ្ញៀវ
                            @elseif($room->status === 'maintenance')
                            ជួសជុល
                            @else
                            {{ $room->status }}
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-4">
                        <button @click="currentRoom = { 
                                room_number: '{{ $room->room_number }}', 
                                floor: '{{ $room->floor }}', 
                                status: '{{ $room->status }}',
                                hotel_name: '{{ $room->hotel->name ?? 'មិនទាន់មាន' }}', 
                                room_type_name: '{{ $room->roomType->name ?? 'មិនទាន់មាន' }}',
                                price: '{{ number_format($room->roomType->base_price ?? 0, 2) }}'
                            }; showDetailModal = true"
                            title="មើលលម្អិត"
                            class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors p-2">
                            <i class="fas fa-eye text-sm"></i>
                        </button>
                        <button @click="currentRoom = {{ $room }}; showEditModal = true" title="កែប្រែ" class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-400"><i class="fas fa-edit text-sm"></i></button>
                        <button onclick="confirmDelete('{{ $room->id }}')" title="លុប" class="text-red-500 hover:text-red-700 dark:hover:text-red-400"><i class="fas fa-trash text-sm"></i></button>
                    </td>
                </tr>
                @empty
                <div class="col-span-full">@include('admin.rooms.partials.empty_state')</div>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-show="viewMode === 'list'" class="space-y-3" x-transition>
    @forelse($rooms as $room)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-none">
        <div class="flex items-center gap-4">
            <div class="w-20 h-10 rounded-xl bg-gray-50 dark:bg-gray-700 flex items-center justify-center font-bold text-blue-600 dark:text-blue-400">{{ $room->room_number }}</div>
            <div>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100">{{ $room->roomType->name }}</h4>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 italic">ជាន់ទី {{ $room->floor }} | {{ $room->hotel->name }}</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase transition-colors
            @if($room->status === 'available') 
                bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400
            @elseif($room->status === 'booked') 
                bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400
            @else 
                bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-400
            @endif">

                @if($room->status === 'available')
                បន្ទប់ទំនេរ
                @elseif($room->status === 'booked')
                មានភ្ញៀវ
                @elseif($room->status === 'maintenance')
                ជួសជុល
                @else
                {{ $room->status }}
                @endif
            </span>

            <div class="flex gap-2 text-gray-500 dark:text-gray-400">
                <button @click="currentRoom = { 
                    room_number: '{{ $room->room_number }}', 
                    floor: '{{ $room->floor }}', 
                    status: '{{ $room->status }}',
                    hotel_name: '{{ $room->hotel->name ?? 'មិនទាន់មាន' }}', 
                    room_type_name: '{{ $room->roomType->name ?? 'មិនទាន់មាន' }}',
                    price: '{{ number_format($room->roomType->base_price ?? 0, 2) }}'
                }; showDetailModal = true"
                    title="មើលលម្អិត"
                    class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors p-2">
                    <i class="fas fa-eye"></i>
                </button>
                <button @click="currentRoom = {{ $room }}; showEditModal = true" title="កែប្រែ"
                    class="hover:text-blue-500 dark:hover:text-blue-400 p-2 transition-colors">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="confirmDelete('{{ $room->id }}')" title="លុប"
                    class="hover:text-red-500 dark:hover:text-red-400 p-2 transition-colors">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">@include('admin.rooms.partials.empty_state')</div>
    @endforelse
</div>

<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition>
    @forelse($rooms as $room)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border-none">
        <div class="flex justify-between items-start mb-4">
            <div class="w-20 h-12 rounded-2xl flex items-center justify-center font-bold text-lg 
                @if($room->status === 'available') 
                    bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400
                @elseif($room->status === 'booked') 
                    bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400
                @else 
                    bg-orange-50 text-orange-600 dark:bg-orange-900/20 dark:text-orange-400
                @endif">
                {{ $room->room_number }}
            </div>

            <div class="flex gap-1 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                <button @click="currentRoom = { 
                    room_number: '{{ $room->room_number }}', 
                    floor: '{{ $room->floor }}', 
                    status: '{{ $room->status }}',
                    hotel_name: '{{ $room->hotel->name ?? 'មិនទាន់មាន' }}', 
                    room_type_name: '{{ $room->roomType->name ?? 'មិនទាន់មាន' }}',
                    price: '{{ number_format($room->roomType->base_price ?? 0, 2) }}'
                }; showDetailModal = true"
                    title="មើលលម្អិត"
                    class="text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors p-2">
                    <i class="fas fa-eye"></i>
                </button>
                <button @click="currentRoom = {{ $room }}; showEditModal = true" title="កែប្រែ" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="confirmDelete('{{ $room->id }}')" title="លុប" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>

        <h3 class="font-bold text-gray-800 dark:text-gray-100">{{ $room->roomType->name }}</h3>

        <div class="flex items-center gap-2 mt-1 mb-2">
            <span class="w-2 h-2 rounded-full 
                @if($room->status === 'available') bg-green-500 
                @elseif($room->status === 'booked') bg-blue-500 
                @else bg-orange-500 @endif">
            </span>
            <span class="text-[11px] font-bold 
                @if($room->status === 'available') text-green-600 dark:text-green-400
                @elseif($room->status === 'booked') text-blue-600 dark:text-blue-400
                @else text-orange-600 dark:text-orange-400 @endif">
                @if($room->status === 'available') បន្ទប់ទំនេរ
                @elseif($room->status === 'booked') មានភ្ញៀវ
                @else ជួសជុល @endif
            </span>
        </div>

        <p class="text-sm text-gray-400 dark:text-gray-500 uppercase font-semibold tracking-wider">
            ជាន់ទី {{ $room->floor }} • {{ $room->hotel->name }}
        </p>
    </div>
    @empty
    <div class="col-span-full">@include('admin.rooms.partials.empty_state')</div>
    @endforelse
</div>




<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
    <div class="dark:text-white">
        {{ $rooms->links() }}
    </div>
</div>