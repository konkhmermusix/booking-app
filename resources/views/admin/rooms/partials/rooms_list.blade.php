<!-- Table View Mode -->
<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">លេខបន្ទប់</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ប្រភេទបន្ទប់</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">សណ្ឋាគារ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-center">ជាន់</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">តម្លៃ/យប់</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-center">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($rooms as $room)
                @php
                    $roomTypeImg = $room->roomType && $room->roomType->images->count() > 0 
                        ? asset('storage/' . $room->roomType->images->first()->image_path) 
                        : null;
                    $price = $room->roomType->base_price ?? 0;
                    $statusKhmer = match($room->status) {
                        'available'   => 'បន្ទប់ទំនេរ',
                        'booked'      => 'មានភ្ញៀវ',
                        'maintenance' => 'ជួសជុល',
                        default       => $room->status
                    };
                    $statusBadgeClass = match($room->status) {
                        'available'   => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200',
                        'booked'      => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-200',
                        'maintenance' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-200',
                        default       => 'bg-gray-100 text-gray-800'
                    };
                @endphp
                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-all">
                    <td class="px-6 py-4 font-bold dark:text-white uppercase text-xs flex items-center gap-4">
                        <div class="w-14 h-11 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0 flex items-center justify-center">
                            @if($roomTypeImg)
                                <img src="{{ $roomTypeImg }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 font-black text-xs">
                                    <i class="fas fa-door-closed"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-sm">No. {{ $room->room_number }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-xs font-bold text-gray-800 dark:text-gray-200">
                        {{ $room->roomType->name ?? 'មិនទាន់កំណត់' }}
                    </td>

                    <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400 font-medium">
                        {{ $room->hotel->name ?? 'មិនមាន' }}
                    </td>

                    <td class="px-6 py-4 text-xs text-center font-bold text-gray-700 dark:text-gray-300">
                        ជាន់ទី {{ $room->floor }}
                    </td>

                    <td class="px-6 py-4 text-right font-black text-emerald-600 dark:text-emerald-400 text-sm whitespace-nowrap">
                        <div>${{ number_format($price, 2) }}</div>
                        <div class="text-[11px] text-gray-400 font-normal font-mono">({{ number_format($price * $khrRate) }} ៛)</div>
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusBadgeClass }}">
                            {{ $statusKhmer }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex justify-end items-center gap-1">
                            <button type="button"
                                @click="currentRoom = { 
                                    room_number: '{{ $room->room_number }}', 
                                    floor: '{{ $room->floor }}', 
                                    status: '{{ $room->status }}',
                                    hotel_name: '{{ $room->hotel->name ?? 'មិនទាន់មាន' }}', 
                                    room_type_name: '{{ $room->roomType->name ?? 'មិនទាន់មាន' }}',
                                    price: '{{ number_format($price, 2) }}'
                                }; showDetailModal = true"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors"
                                title="មើលលម្អិត">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <button type="button"
                                @click="currentRoom = {{ json_encode($room) }}; showEditModal = true"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors"
                                title="កែប្រែ">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <button type="button"
                                onclick="confirmDelete('{{ $room->id }}')"
                                class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                title="លុប">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12">
                        @include('admin.rooms.partials.empty_state')
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- List View Mode -->
<div x-show="viewMode === 'list'" class="space-y-3" x-transition>
    @forelse($rooms as $room)
    @php
        $roomTypeImg = $room->roomType && $room->roomType->images->count() > 0 
            ? asset('storage/' . $room->roomType->images->first()->image_path) 
            : null;
        $price = $room->roomType->base_price ?? 0;
        $statusKhmer = match($room->status) {
            'available'   => 'បន្ទប់ទំនេរ',
            'booked'      => 'មានភ្ញៀវ',
            'maintenance' => 'ជួសជុល',
            default       => $room->status
        };
        $statusBadgeClass = match($room->status) {
            'available'   => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200',
            'booked'      => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-200',
            'maintenance' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-200',
            default       => 'bg-gray-100 text-gray-800'
        };
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:shadow-md transition-all border-none">
        <div class="flex items-center gap-4">
            <div class="w-16 h-14 rounded-xl overflow-hidden bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex flex-col items-center justify-center flex-shrink-0 font-black">
                @if($roomTypeImg)
                    <img src="{{ $roomTypeImg }}" class="w-full h-full object-cover">
                @else
                    <span class="text-xs font-mono font-black">No. {{ $room->room_number }}</span>
                @endif
            </div>

            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-sm">No. {{ $room->room_number }}</span>
                    <span class="text-xs font-bold text-gray-800 dark:text-gray-100">• {{ $room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ' }}</span>
                </div>
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-2">
                    <span><i class="fas fa-layer-group text-[10px]"></i> ជាន់ទី {{ $room->floor }}</span>
                    <span>|</span>
                    <span><i class="fas fa-hotel text-[10px]"></i> {{ $room->hotel->name ?? 'មិនមាន' }}</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <span class="font-black text-emerald-600 dark:text-emerald-400 text-sm">${{ number_format($price, 2) }}</span>
                <span class="text-[11px] text-gray-400 font-normal font-mono block">({{ number_format($price * $khrRate) }} ៛)</span>
            </div>

            <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $statusBadgeClass }}">
                {{ $statusKhmer }}
            </span>

            <div class="flex items-center gap-1 text-gray-400">
                <button @click="currentRoom = { 
                    room_number: '{{ $room->room_number }}', 
                    floor: '{{ $room->floor }}', 
                    status: '{{ $room->status }}',
                    hotel_name: '{{ $room->hotel->name ?? 'មិនទាន់មាន' }}', 
                    room_type_name: '{{ $room->roomType->name ?? 'មិនទាន់មាន' }}',
                    price: '{{ number_format($price, 2) }}'
                }; showDetailModal = true"
                    title="មើលលម្អិត"
                    class="p-2 hover:text-blue-500 transition-colors">
                    <i class="fas fa-eye text-sm"></i>
                </button>
                <button @click="currentRoom = {{ json_encode($room) }}; showEditModal = true" title="កែប្រែ"
                    class="p-2 hover:text-amber-500 transition-colors">
                    <i class="fas fa-edit text-sm"></i>
                </button>
                <button onclick="confirmDelete('{{ $room->id }}')" title="លុប"
                    class="p-2 hover:text-red-500 transition-colors">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    @include('admin.rooms.partials.empty_state')
    @endforelse
</div>

<!-- Grid View Mode (Matching room_types UI Style) -->
<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition>
    @forelse($rooms as $room)
    @php
        $roomTypeImg = $room->roomType && $room->roomType->images->count() > 0 
            ? asset('storage/' . $room->roomType->images->first()->image_path) 
            : null;
        $price = $room->roomType->base_price ?? 0;
        $maxGuests = $room->roomType->max_guests ?? 2;
        $statusKhmer = match($room->status) {
            'available'   => 'បន្ទប់ទំនេរ',
            'booked'      => 'មានភ្ញៀវ',
            'maintenance' => 'ជួសជុល',
            default       => $room->status
        };
        $statusBadgeClass = match($room->status) {
            'available'   => 'bg-emerald-500 text-white',
            'booked'      => 'bg-blue-500 text-white',
            'maintenance' => 'bg-amber-500 text-white',
            default       => 'bg-gray-500 text-white'
        };
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border-none">
        <!-- Top Image / Thumbnail Container -->
        <div class="h-44 -mx-5 -mt-5 mb-4 overflow-hidden rounded-t-2xl relative group/img bg-gradient-to-br from-blue-900 via-slate-800 to-indigo-950">
            @if($roomTypeImg)
                <img src="{{ $roomTypeImg }}"
                    class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-white/40">
                    <i class="fa-solid fa-door-open text-4xl mb-2"></i>
                    <span class="text-[10px] uppercase font-black tracking-widest">No. {{ $room->room_number }}</span>
                </div>
            @endif

            <!-- Floating Room Status Badge (Top Right) -->
            <div class="absolute top-3 right-3 shadow-md">
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $statusBadgeClass }}">
                    {{ $statusKhmer }}
                </span>
            </div>

            <!-- Floating Price Pill (Bottom Left) -->
            <div class="absolute bottom-3 left-3 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-xl shadow-md border border-gray-100 dark:border-gray-800">
                <span class="text-xs font-black text-blue-600 dark:text-blue-400">${{ number_format($price, 0) }}</span>
                <span class="text-[10px] font-bold text-gray-400 dark:text-gray-400 ml-1 font-mono">({{ number_format($price * $khrRate) }} ៛)</span>
            </div>

            <!-- Hover Action Overlay -->
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300">
                <button type="button"
                    @click="currentRoom = { 
                        room_number: '{{ $room->room_number }}', 
                        floor: '{{ $room->floor }}', 
                        status: '{{ $room->status }}',
                        hotel_name: '{{ $room->hotel->name ?? 'មិនទាន់មាន' }}', 
                        room_type_name: '{{ $room->roomType->name ?? 'មិនទាន់មាន' }}',
                        price: '{{ number_format($price, 2) }}'
                    }; showDetailModal = true"
                    class="p-2.5 bg-white/90 dark:bg-gray-800/90 hover:bg-blue-600 hover:text-white text-gray-700 rounded-xl transition shadow-md"
                    title="មើលលម្អិត">
                    <i class="fas fa-eye text-sm"></i>
                </button>

                <button type="button"
                    @click="currentRoom = {{ json_encode($room) }}; showEditModal = true"
                    class="p-2.5 bg-white/90 dark:bg-gray-800/90 hover:bg-amber-600 hover:text-white text-gray-700 rounded-xl transition shadow-md"
                    title="កែប្រែ">
                    <i class="fas fa-edit text-sm"></i>
                </button>

                <button type="button"
                    onclick="confirmDelete('{{ $room->id }}')"
                    class="p-2.5 bg-white/90 dark:bg-gray-800/90 hover:bg-rose-600 hover:text-white text-gray-700 rounded-xl transition shadow-md"
                    title="លុប">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Room Header Info -->
        <div class="flex justify-between items-start mb-2">
            <div>
                <h3 class="font-black text-gray-800 dark:text-gray-100 text-base uppercase tracking-tight">No. {{ $room->room_number }}</h3>
                <p class="text-xs font-bold text-blue-600 dark:text-blue-400 mt-0.5">{{ $room->roomType->name ?? 'មិនទាន់កំណត់' }}</p>
            </div>
        </div>

        <!-- Details Badges Row -->
        <div class="flex items-center gap-2 my-3">
            <div class="flex items-center gap-1 text-[10px] font-bold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/60 px-2.5 py-1 rounded-lg">
                <i class="fa-solid fa-layer-group text-blue-500"></i> ជាន់ទី {{ $room->floor }}
            </div>
            <div class="flex items-center gap-1 text-[10px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-1 rounded-lg">
                <i class="fa-solid fa-users"></i> {{ $maxGuests }} នាក់
            </div>
        </div>

        <!-- Hotel Name Footer Tag -->
        <p class="text-[11px] text-gray-400 dark:text-gray-500 font-medium truncate pt-2 border-t border-gray-100 dark:border-gray-700/50">
            <i class="fas fa-hotel text-[10px] mr-1"></i> {{ $room->hotel->name ?? 'មិនទាន់មានសណ្ឋាគារ' }}
        </p>
    </div>
    @empty
    <div class="col-span-full">
        @include('admin.rooms.partials.empty_state')
    </div>
    @endforelse
</div>

<!-- Pagination Container -->
<div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border-none transition-colors pagination">
    <div class="dark:text-white">
        {{ $rooms->links() }}
    </div>
</div>