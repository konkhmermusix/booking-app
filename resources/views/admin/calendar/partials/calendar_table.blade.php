{{-- 1. Stay Rooms Tab --}}
<div x-show="currentTab === 'stay'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800">
        {{-- Matrix Scroll Container --}}
        <div class="custom-calendar-scrollbar relative rounded-2xl" style="max-height: 72vh; min-height: 420px; overflow-x: auto; overflow-y: auto; -webkit-overflow-scrolling: touch;">
            <table class="min-w-[2200px] w-full text-left border-separate border-spacing-0">
                <thead class="sticky top-0 z-30">
                    <tr>
                        {{-- Sticky Top-Left Room Column Header --}}
                        <th class="sticky left-0 top-0 z-40 bg-gray-100 dark:bg-gray-800 border-b border-r border-gray-200 dark:border-gray-700 p-4 min-w-[240px] w-[240px] shadow-[2px_2px_8px_rgba(0,0,0,0.06)]">
                            <div class="text-[11px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 flex items-center justify-between">
                                <span class="flex items-center gap-1.5"><i class="fas fa-bed text-blue-500"></i> បន្ទប់សណ្ឋាគារ</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300 font-bold">{{ count($stayRooms) }} បន្ទប់</span>
                            </div>
                        </th>

                        {{-- Date Headers --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $dateObj = \Carbon\Carbon::create($year, $month, $d);
                                $isWeekend = in_array($dateObj->format('D'), ['Sat', 'Sun']);
                                $isToday = $dateObj->isToday();
                            @endphp
                            <th class="sticky top-0 z-30 border-b border-r border-gray-200 dark:border-gray-700 w-[130px] min-w-[130px] text-center p-2.5 transition-colors
                                {{ $isToday ? 'bg-blue-600 text-white dark:bg-blue-600 shadow-md' : ($isWeekend ? 'bg-amber-50/80 dark:bg-amber-950/40 text-amber-900 dark:text-amber-200' : 'bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200') }}">
                                <span class="text-base font-black block leading-none">{{ sprintf('%02d', $d) }}</span>
                                <span class="text-[10px] uppercase font-bold tracking-wider mt-1 block {{ $isToday ? 'text-blue-100' : ($isWeekend ? 'text-amber-600 dark:text-amber-400' : 'text-gray-400 dark:text-gray-400') }}">
                                    {{ $dateObj->format('D') }}
                                </span>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($stayRooms as $room)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors">
                        {{-- Sticky Room Column --}}
                        <td class="sticky left-0 z-20 bg-white dark:bg-gray-900 border-b border-r border-gray-200 dark:border-gray-700 p-3 shadow-[2px_0_8px_rgba(0,0,0,0.06)]">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-10 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-800 flex flex-col items-center justify-center font-black text-blue-600 dark:text-blue-400 text-xs shadow-xs shrink-0">
                                    <span>{{ $room->room_number }}</span>
                                </div>
                                <div class="truncate">
                                    <div class="text-xs font-black text-gray-900 dark:text-gray-100 truncate">បន្ទប់ {{ $room->room_number }}</div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-400 font-bold uppercase tracking-wider truncate">
                                        {{ $room->roomType->name ?? 'Standard' }} (${{ number_format($room->roomType->base_price ?? 0, 0) }})
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Days Matrix Cells --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $currentDate = \Carbon\Carbon::create($year, $month, $d)->format('Y-m-d');
                                $booking = $room->hotelBookings->first(function($b) use ($currentDate) {
                                    $checkIn = \Carbon\Carbon::parse($b->check_in)->format('Y-m-d');
                                    $checkOut = \Carbon\Carbon::parse($b->check_out)->format('Y-m-d');
                                    return $currentDate >= $checkIn && $currentDate < $checkOut;
                                });
                                $isWeekend = in_array(\Carbon\Carbon::create($year, $month, $d)->format('D'), ['Sat', 'Sun']);
                            @endphp
                            <td class="border-b border-r border-gray-200 dark:border-gray-800 p-0 h-[80px] relative align-middle {{ $isWeekend ? 'bg-amber-50/20 dark:bg-amber-950/10' : '' }}">
                                @if($booking)
                                    @php
                                        $isStart = $currentDate == \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d');
                                        $isEnd = $currentDate == \Carbon\Carbon::parse($booking->check_out)->subDay()->format('Y-m-d');
                                        $guestDisplay = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវ Walk-in');

                                        $gradientClass = match($booking->status) {
                                            'pending' => 'bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 border-amber-400/40 text-white',
                                            'confirmed', 'checked_in' => 'bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 border-blue-400/40 text-white',
                                            'completed', 'checked_out' => 'bg-gradient-to-r from-emerald-600 to-teal-600 border-emerald-400/40 text-white',
                                            'cancelled' => 'bg-gradient-to-r from-red-500 to-rose-600 border-red-400/40 text-white opacity-60 line-through',
                                            default => 'bg-gradient-to-r from-gray-600 to-gray-700 border-gray-400/40 text-white',
                                        };
                                    @endphp
                                    <div @click="selectedBooking = Object.assign({}, {{ $booking->toJson() }}, {type:'stay', room: {{ $room->toJson() }} }); showDetailModal = true;"
                                        class="absolute inset-y-2 left-0 right-0 z-10 {{ $gradientClass }} shadow-md hover:brightness-110 hover:scale-[1.02] transition-all cursor-pointer flex items-center px-2.5 border
                                        {{ $isStart ? 'rounded-l-2xl ml-1' : '' }} {{ $isEnd ? 'rounded-r-2xl mr-1' : '' }}"
                                        title="ភ្ញៀវ: {{ $guestDisplay }} | កូដ: {{ $booking->booking_code }}">
                                        @if($isStart || $d == 1)
                                        <div class="flex flex-col truncate leading-tight w-full">
                                            <span class="text-xs font-black tracking-wide truncate">{{ $guestDisplay }}</span>
                                            <div class="flex items-center justify-between text-[9px] font-bold opacity-90 gap-1 mt-0.5">
                                                <span class="truncate"><i class="fas fa-tag mr-0.5"></i>{{ $booking->booking_code }}</span>
                                                <span class="shrink-0 px-1.5 py-0.2 rounded-full bg-white/20 text-[8px]">{{ App\Models\HotelBooking::statusLabel($booking->status) }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                @else
                                    <button @click="openAddModalForRoom('{{ $room->id }}', '{{ $currentDate }}')"
                                        class="w-full h-full opacity-0 hover:opacity-100 bg-emerald-500/10 dark:hover:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-bold text-xs transition-all flex items-center justify-center gap-1 group"
                                        title="ចុចដើម្បីកក់បន្ទប់នេះ">
                                        <i class="fas fa-plus-circle text-base group-hover:scale-110 transition-transform"></i>
                                        <span>កក់</span>
                                    </button>
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- 2. Meeting Rooms Tab --}}
<div x-show="currentTab === 'meeting'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800">
        {{-- Matrix Scroll Container --}}
        <div class="custom-calendar-scrollbar relative rounded-2xl" style="max-height: 72vh; min-height: 420px; overflow-x: auto; overflow-y: auto; -webkit-overflow-scrolling: touch;">
            <table class="min-w-[2200px] w-full text-left border-separate border-spacing-0">
                <thead class="sticky top-0 z-30">
                    <tr>
                        {{-- Sticky Top-Left Header --}}
                        <th class="sticky left-0 top-0 z-40 bg-gray-100 dark:bg-gray-800 border-b border-r border-gray-200 dark:border-gray-700 p-4 min-w-[240px] w-[240px] shadow-[2px_2px_8px_rgba(0,0,0,0.06)]">
                            <div class="text-[11px] font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 flex items-center justify-between">
                                <span class="flex items-center gap-1.5"><i class="fas fa-users text-purple-500"></i> សាលប្រជុំ</span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] bg-purple-100 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300 font-bold">{{ count($meetingRooms) }} សាល</span>
                            </div>
                        </th>

                        {{-- Date Headers --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $dateObj = \Carbon\Carbon::create($year, $month, $d);
                                $isWeekend = in_array($dateObj->format('D'), ['Sat', 'Sun']);
                                $isToday = $dateObj->isToday();
                            @endphp
                            <th class="sticky top-0 z-30 border-b border-r border-gray-200 dark:border-gray-700 w-[130px] min-w-[130px] text-center p-2.5 transition-colors
                                {{ $isToday ? 'bg-purple-600 text-white dark:bg-purple-600 shadow-md' : ($isWeekend ? 'bg-purple-50/80 dark:bg-purple-950/40 text-purple-900 dark:text-purple-200' : 'bg-gray-50 dark:bg-gray-800 text-gray-800 dark:text-gray-200') }}">
                                <span class="text-base font-black block leading-none">{{ sprintf('%02d', $d) }}</span>
                                <span class="text-[10px] uppercase font-bold tracking-wider mt-1 block {{ $isToday ? 'text-purple-100' : ($isWeekend ? 'text-purple-600 dark:text-purple-400' : 'text-gray-400 dark:text-gray-400') }}">
                                    {{ $dateObj->format('D') }}
                                </span>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($meetingRooms as $room)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/40 transition-colors">
                        {{-- Sticky Room Column --}}
                        <td class="sticky left-0 z-20 bg-white dark:bg-gray-900 border-b border-r border-gray-200 dark:border-gray-700 p-3 shadow-[2px_0_8px_rgba(0,0,0,0.06)]">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-10 rounded-2xl bg-purple-50 dark:bg-purple-950/60 border border-purple-100 dark:border-purple-800 flex flex-col items-center justify-center font-black text-purple-600 dark:text-purple-400 text-xs shadow-xs shrink-0">
                                    <span>{{ $room->room_number }}</span>
                                </div>
                                <div class="truncate">
                                    <div class="text-xs font-black text-gray-900 dark:text-gray-100 truncate">សាល {{ $room->room_number }}</div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-400 font-bold uppercase tracking-wider truncate">
                                        {{ $room->roomType->name ?? 'Meeting Hall' }} (${{ number_format($room->roomType->base_price ?? 0, 0) }}/h)
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Days Matrix Cells --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $currentDate = \Carbon\Carbon::create($year, $month, $d)->format('Y-m-d');
                                $booking = $room->meetingBookings->first(function($mb) use ($currentDate) {
                                    $start = \Carbon\Carbon::parse($mb->start_date)->format('Y-m-d');
                                    $end = \Carbon\Carbon::parse($mb->end_date)->format('Y-m-d');
                                    return $currentDate >= $start && $currentDate <= $end;
                                });
                                $isWeekend = in_array(\Carbon\Carbon::create($year, $month, $d)->format('D'), ['Sat', 'Sun']);
                            @endphp
                            <td class="border-b border-r border-gray-200 dark:border-gray-800 p-0 h-[80px] relative align-middle {{ $isWeekend ? 'bg-purple-50/10 dark:bg-purple-950/10' : '' }}">
                                @if($booking)
                                    @php
                                        $guestDisplay = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវ Walk-in');
                                    @endphp
                                    <div @click="selectedBooking = Object.assign({}, {{ $booking->toJson() }}, {type:'meeting', room: {{ $room->toJson() }} }); showDetailModal = true;"
                                        class="absolute inset-y-2 left-1 right-1 z-10 bg-gradient-to-r from-purple-600 via-indigo-600 to-purple-700 text-white shadow-md hover:brightness-110 hover:scale-[1.02] transition-all cursor-pointer flex items-center px-2.5 rounded-2xl border border-purple-400/40"
                                        title="សាលប្រជុំ: {{ $guestDisplay }}">
                                        <div class="flex flex-col truncate leading-tight w-full">
                                            <span class="text-xs font-black tracking-wide truncate">{{ $guestDisplay }}</span>
                                            <span class="text-[9px] font-bold opacity-90 flex items-center gap-1 mt-0.5">
                                                <i class="far fa-clock"></i> {{ $booking->start_time }} - {{ $booking->end_time }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <button @click="openAddModalForMeeting('{{ $room->id }}', '{{ $currentDate }}')"
                                        class="w-full h-full opacity-0 hover:opacity-100 bg-purple-500/10 dark:hover:bg-purple-500/20 text-purple-600 dark:text-purple-400 font-bold text-xs transition-all flex items-center justify-center gap-1 group"
                                        title="ចុចដើម្បីកក់សាលនេះ">
                                        <i class="fas fa-plus-circle text-base group-hover:scale-110 transition-transform"></i>
                                        <span>កក់</span>
                                    </button>
                                @endif
                            </td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>