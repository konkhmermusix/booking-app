<!-- 4 KPI Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">អត្រាបន្ទប់ជាប់ភ្ញៀវ</span>
            <h3 class="text-2xl font-black text-blue-500 mt-0.5">{{ $occupancyRate }}%</h3>
            <p class="text-[10px] text-blue-400 mt-0.5">{{ $occupiedRoomsCount }} ពី {{ $totalRooms }} បន្ទប់</p>
        </div>
        <div class="w-10 h-10 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">បន្ទប់ទំនេរ (Available)</span>
            <h3 class="text-2xl font-black text-emerald-500 mt-0.5">{{ number_format($availableRoomsCount) }}</h3>
            <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-0.5">រួចរាល់សម្រាប់លក់</p>
        </div>
        <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-check-circle"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">បន្ទប់មានភ្ញៀវ (Occupied)</span>
            <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-0.5">{{ number_format($occupiedRoomsCount) }}</h3>
            <p class="text-[10px] text-blue-400 mt-0.5">កំពុងស្នាក់នៅ</p>
        </div>
        <div class="w-10 h-10 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-bed"></i>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <div>
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">ជួសជុល (Maintenance)</span>
            <h3 class="text-2xl font-black text-amber-500 mt-0.5">{{ number_format($maintenanceRoomsCount) }}</h3>
            <p class="text-[10px] text-amber-400 mt-0.5">ផ្អាកលក់បណ្តោះអាសន្ន</p>
        </div>
        <div class="w-10 h-10 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center text-base">
            <i class="fas fa-tools"></i>
        </div>
    </div>
</div>

<!-- Visual Floor Grid View Section -->
<div x-show="layoutMode === 'grid'" class="space-y-5">
    @forelse($roomsByFloor as $floorTitle => $roomsGroup)
        <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-4 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2.5">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-layer-group text-indigo-500"></i> {{ $floorTitle }}
                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-full text-[10px] font-semibold">({{ $roomsGroup->count() }} បន្ទប់)</span>
                </h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                @foreach($roomsGroup as $room)
                    @php
                        $activeBooking = $room->hotelBookings ? $room->hotelBookings->first() : null;
                        
                        $statusLabel = match($room->status) {
                            'available'   => 'ទំនេរ',
                            'booked'      => 'មានភ្ញៀវ',
                            'maintenance' => 'ជួសជុល',
                            default       => $room->status
                        };

                        $cardBorder = match($room->status) {
                            'available'   => 'border-emerald-300 dark:border-emerald-950/60 bg-emerald-50/20 dark:bg-emerald-950/10',
                            'booked'      => 'border-blue-300 dark:border-blue-950/60 bg-blue-50/20 dark:bg-blue-950/10',
                            default       => 'border-amber-300 dark:border-amber-950/60 bg-amber-50/20 dark:bg-amber-950/10'
                        };

                        $badgeColor = match($room->status) {
                            'available'   => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-400 border-emerald-200',
                            'booked'      => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-400 border-blue-200',
                            default       => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 border-amber-200'
                        };
                    @endphp
                    <div class="bg-white dark:bg-gray-900 border rounded-2xl p-3.5 shadow-sm relative flex flex-col justify-between min-h-[130px] transition-all hover:scale-[1.02] {{ $cardBorder }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-base font-black text-gray-800 dark:text-white">No. {{ $room->room_number }}</h3>
                                <p class="text-[10px] text-gray-400 font-medium line-clamp-1">{{ $room->roomType->name ?? 'មិនកំណត់' }}</p>
                            </div>
                            <span class="text-[9px] px-2 py-0.5 rounded font-bold border {{ $badgeColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="mt-2.5 pt-2 border-t border-gray-100 dark:border-gray-800/60 text-xs">
                            @if($room->status == 'booked' && $activeBooking)
                                <p class="font-bold text-blue-600 dark:text-blue-400 line-clamp-1 text-[11px]">
                                    <i class="fas fa-user text-[9px] mr-1"></i> {{ $activeBooking->customer_name ?: ($activeBooking->user->name ?? 'ភ្ញៀវ') }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-mono mt-0.5">
                                    ចេញ៖ {{ $activeBooking->check_out ? \Carbon\Carbon::parse($activeBooking->check_out)->format('d/m/Y') : 'N/A' }}
                                </p>
                            @elseif($room->status == 'maintenance')
                                <p class="text-[10px] font-semibold text-amber-600 dark:text-amber-400">
                                    <i class="fas fa-tools text-[9px] mr-1"></i> កំពុងជួសជុល
                                </p>
                            @else
                                <p class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                                    <i class="fas fa-check text-[9px] mr-1"></i> ស្អាត រួចរាល់
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-12 text-gray-400 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 text-xs">
            <i class="fas fa-door-closed text-3xl mb-2 block"></i> មិនទាន់មានទិន្នន័យបន្ទប់ក្នុងប្រព័ន្ធនៅឡើយទេ
        </div>
    @endforelse
</div>

<!-- Data Table View Section -->
<div x-show="layoutMode === 'table'" x-cloak class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="p-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center">
        <div>
            <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-table text-indigo-500"></i> បញ្ជីស្ថានភាពបន្ទប់លម្អិត
            </h3>
            <p class="text-[10px] text-gray-400">សណ្ឋាគារ ភីអេនធី ផាលេស (PNT Palace Hotel)</p>
        </div>
        <span class="text-xs text-gray-400 font-semibold">សរុប {{ $allRooms->count() }} បន្ទប់</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-[11px] font-bold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-800 uppercase tracking-wider">
                    <th class="p-3.5">លេខបន្ទប់</th>
                    <th class="p-3.5">ជាន់</th>
                    <th class="p-3.5">ប្រភេទបន្ទប់</th>
                    <th class="p-3.5 text-center">ស្ថានភាពបច្ចុប្បន្ន</th>
                    <th class="p-3.5">ភ្ញៀវកំពុងស្នាក់នៅ</th>
                    <th class="p-3.5">ថ្ងៃចេញ (Check-out)</th>
                    <th class="p-3.5">អ្នកទទួលខុសត្រូវសំអាត</th>
                </tr>
            </thead>
            <tbody class="text-xs text-gray-600 dark:text-gray-300 divide-y divide-gray-100 dark:divide-gray-800">
                @foreach($allRooms as $room)
                @php
                    $activeBooking = $room->hotelBookings ? $room->hotelBookings->first() : null;
                    
                    $statusLabel = match($room->status) {
                        'available'   => 'ទំនេរ',
                        'booked'      => 'មានភ្ញៀវ',
                        'maintenance' => 'ជួសជុល',
                        default       => $room->status
                    };

                    $statusBadgeClass = match($room->status) {
                        'available'   => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border-emerald-200',
                        'booked'      => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 border-blue-200',
                        default       => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border-amber-200'
                    };
                @endphp
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="p-3.5 font-black text-gray-800 dark:text-white text-sm">No. {{ $room->room_number }}</td>
                    <td class="p-3.5 text-xs font-semibold">ជាន់ទី {{ $room->floor }}</td>
                    <td class="p-3.5 text-xs font-medium">{{ $room->roomType->name ?? 'មិនកំណត់' }}</td>
                    <td class="p-3.5 text-center">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $statusBadgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                    <td class="p-3.5 font-semibold text-gray-800 dark:text-white text-xs">
                        @if($room->status == 'booked' && $activeBooking)
                            {{ $activeBooking->customer_name ?: ($activeBooking->user->name ?? 'ភ្ញៀវ Walk-in') }}
                        @else
                            <span class="text-gray-400 font-normal">គ្មានភ្ញៀវ</span>
                        @endif
                    </td>
                    <td class="p-3.5 text-xs font-mono text-gray-500 dark:text-gray-400">
                        {{ ($room->status == 'booked' && $activeBooking && $activeBooking->check_out) ? \Carbon\Carbon::parse($activeBooking->check_out)->format('d/m/Y') : 'N/A' }}
                    </td>
                    <td class="p-3.5 text-xs text-gray-500">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-broom text-blue-500"></i> Housekeeping Staff
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
