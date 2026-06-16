<div x-show="currentTab === 'stay'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-800">
        <div class="overflow-x-auto custom-calendar-scrollbar">
            <table class="min-w-[2000px] w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-20">
                    <tr class="divide-x divide-gray-200 dark:divide-gray-700">
                        <th class="sticky left-0 z-30 bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-5 min-w-[260px] w-[260px] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            <div class="text-[11px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">បន្ទប់សណ្ឋាគារ</div>
                        </th>

                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                            $isWeekend=in_array(\Carbon\Carbon::create($year, $month, $d)->format('D'), ['Sat', 'Sun']);
                            @endphp
                            <th class="border-b border-gray-200 dark:border-gray-700 w-[130px] min-w-[130px] text-center p-4 {{ $isWeekend ? 'bg-orange-50/40 dark:bg-orange-950/20' : 'bg-gray-50 dark:bg-gray-800' }}">
                                <span class="text-lg font-black block text-gray-800 dark:text-gray-200">{{ sprintf('%02d', $d) }}</span>
                                <span class="text-[11px] uppercase font-bold {{ $isWeekend ? 'text-orange-500' : 'text-gray-400 dark:text-gray-500' }}">
                                    {{ \Carbon\Carbon::create($year, $month, $d)->format('D') }}
                                </span>
                            </th>
                            @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($stayRooms as $room)
                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="sticky left-0 z-10 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 p-4 group-hover:bg-gray-50 dark:group-hover:bg-gray-800 transition shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            <div class="flex items-center gap-3">
                                <div class="w-25 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 flex items-center justify-center font-black text-blue-600 dark:text-blue-400 text-sm shadow-sm">
                                    {{ $room->room_number }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-gray-800 dark:text-gray-100">បន្ទប់ {{ $room->room_number }}</div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider">{{ $room->roomType->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>

                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                            $currentDate=\Carbon\Carbon::create($year, $month, $d)->format('Y-m-d');
                            $booking = $room->hotelBookings->first(function($b) use ($currentDate) {
                            $checkIn = \Carbon\Carbon::parse($b->check_in)->format('Y-m-d');
                            $checkOut = \Carbon\Carbon::parse($b->check_out)->format('Y-m-d');
                            return $currentDate >= $checkIn && $currentDate < $checkOut;
                                });
                                $isWeekend=in_array(\Carbon\Carbon::create($year, $month, $d)->format('D'), ['Sat', 'Sun']);
                                @endphp
                                <td class="border-r border-gray-100 dark:border-gray-700 p-0 h-[95px] relative group/cell {{ $isWeekend ? 'bg-orange-50/10 dark:bg-orange-950/5' : '' }}">
                                    @if($booking)
                                    @php
                                    $isStart = $currentDate == \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d');
                                    $isEnd = $currentDate == \Carbon\Carbon::parse($booking->check_out)->subDay()->format('Y-m-d');
                                    @endphp
                                    <div class="absolute inset-y-4 left-0 right-0 z-10 bg-gradient-to-r from-amber-500 via-orange-500 to-red-500 shadow-md hover:brightness-105 transition-all cursor-pointer flex items-center px-3 border border-orange-400/20
                                        {{ $isStart ? 'rounded-l-2xl ml-2' : '' }} {{ $isEnd ? 'rounded-r-2xl mr-2' : '' }}"
                                        title="ភ្ញៀវ: {{ $booking->guest_name }}">
                                        @if($isStart || $d == 1)
                                        <div class="flex flex-col truncate text-white leading-tight">
                                            <span class="text-xs font-black tracking-wide">{{ $booking->guest_name }}</span>
                                            <span class="text-[9px] font-medium opacity-90">កក់ជោគជ័យ</span>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <button @click="showAddModal = true; selectedRoom='{{ $room->id }}'; selectedDate='{{ $currentDate }}'"
                                        class="w-full h-full opacity-0 hover:opacity-100 bg-emerald-50/50 dark:hover:bg-emerald-950/20 text-emerald-600 font-bold text-lg transition-all flex items-center justify-center">
                                        <i class="fas fa-plus-circle"></i>
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


<div x-show="currentTab === 'meeting'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-800">
        <div class="overflow-x-auto custom-calendar-scrollbar">

            <table class="min-w-[2000px] w-full text-left border-collapse">
                <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-20">
                    <tr class="divide-x divide-gray-200 dark:divide-gray-700">
                        <th class="sticky left-0 z-30 bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-5 min-w-[260px] w-[260px] shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            <div class="text-[11px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">បន្ទប់ប្រជុំ</div>
                        </th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                            $isWeekend=in_array(\Carbon\Carbon::create($year, $month, $d)->format('D'), ['Sat', 'Sun']);
                            @endphp
                            <th class="border-b border-gray-200 dark:border-gray-700 w-[130px] min-w-[130px] text-center p-4 {{ $isWeekend ? 'bg-purple-50/40 dark:bg-purple-950/20' : 'bg-gray-50 dark:bg-gray-800' }}">
                                <span class="text-lg font-black block text-gray-800 dark:text-gray-200">{{ sprintf('%02d', $d) }}</span>
                                <span class="text-[11px] uppercase font-bold {{ $isWeekend ? 'text-purple-500' : 'text-gray-400 dark:text-gray-500' }}">
                                    {{ \Carbon\Carbon::create($year, $month, $d)->format('D') }}
                                </span>
                            </th>
                            @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($meetingRooms as $room)
                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                        <td class="sticky left-0 z-10 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-700 p-4 group-hover:bg-gray-50 dark:group-hover:bg-gray-800 transition shadow-[2px_0_5px_-2px_rgba(0,0,0,0.1)]">
                            <div class="flex items-center gap-3">
                                <div class="w-25 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/50 flex items-center justify-center font-black text-purple-700 dark:text-purple-400 text-sm shadow-sm">
                                    {{ $room->room_number }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-gray-800 dark:text-gray-100">សាល {{ $room->room_number }}</div>
                                    <div class="text-[10px] text-purple-500 font-bold uppercase tracking-wider">{{ $room->roomType->name ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                            $currentDate=\Carbon\Carbon::create($year, $month, $d)->format('Y-m-d');
                            $dayBookings = $room->meetingBookings->filter(function($b) use ($currentDate) {
                            $startDate = \Carbon\Carbon::parse($b->start_date)->format('Y-m-d');
                            $endDate = \Carbon\Carbon::parse($b->end_date)->format('Y-m-d');
                            return $currentDate >= $startDate && $currentDate <= $endDate;
                                });
                                $isWeekend=in_array(\Carbon\Carbon::create($year, $month, $d)->format('D'), ['Sat', 'Sun']);
                                @endphp

                                <td class="border-r border-gray-100 dark:border-gray-700 p-2 h-[95px] relative align-top {{ $isWeekend ? 'bg-purple-50/10 dark:bg-purple-950/5' : '' }}">
                                    <div class="flex flex-col gap-1.5 h-full overflow-y-auto custom-calendar-scrollbar-thin">
                                        @forelse($dayBookings as $mBooking)
                                        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 text-white rounded-xl p-2 text-[10px] leading-tight shadow-sm cursor-pointer transition-all border border-purple-500/20"
                                            title="លេខកូដកក់: {{ $mBooking->booking_code }}">
                                            <span class="font-black block text-purple-200">
                                                <i class="far fa-clock"></i> {{ \Carbon\Carbon::parse($mBooking->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($mBooking->end_time)->format('H:i') }}
                                            </span>
                                            <span class="truncate block font-bold mt-0.5">កូដ: {{ $mBooking->booking_code }}</span>
                                        </div>
                                        @empty
                                        <button @click="showAddModal = true; selectedRoom='{{ $room->id }}'; selectedDate='{{ $currentDate }}'"
                                            class="w-full h-full opacity-0 hover:opacity-100 text-purple-500 font-bold transition-all flex items-center justify-center">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        @endforelse
                                    </div>
                                </td>
                                @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    /* រចនាបថរបារ Scroll ធំខាងក្រោមតារាង */
    .custom-calendar-scrollbar::-webkit-scrollbar {
        height: 10px;
    }

    .custom-calendar-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .custom-calendar-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
        border: 2px solid #f1f5f9;
    }

    .custom-calendar-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>