@extends('layouts.admin')
@section('title', 'ប្រតិទិនបន្ទប់សណ្ឋាគារ')
@section('content')

<div class="p-2 sm:p-2" x-data="calendarSystem()">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">ប្រតិទិនបន្ទប់សណ្ឋាគារ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Manage room booking schedule professionally</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchBookings()" placeholder="ស្វែងរកលេខកូដកក់..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <div class="flex flex-wrap gap-3">

                <button
                    class="h-10 px-4 rounded-xl  border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
                    <i class="fas fa-filter mr-2"></i>
                    តម្រង
                </button>

                <button
                    class="h-10 px-4 rounded-xl  border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
                    <i class="fas fa-download mr-2"></i>
                    នាំចេញ
                </button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមកក់ថ្មី
            </button>

        </div>
    </div>


    {{-- STATS SECTION --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">

        <!-- Total Rooms -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all">
            <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">
                បន្ទប់សរុប
            </div>
            <div class="text-3xl font-black mt-2 text-gray-900 dark:text-white">
                {{ $rooms->count() }}
            </div>
        </div>

        <!-- Available Rooms -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all">
            <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">
                បន្ទប់ទំនេរ
            </div>
            <div class="text-3xl font-black mt-2 text-emerald-500">
                {{ $rooms->where('status', 'available')->count() }}
            </div>
        </div>

        <!-- Booked Rooms -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all">
            <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">
                បន្ទប់បានកក់
            </div>
            <div class="text-3xl font-black mt-2 text-amber-500">
                {{ $rooms->where('status', 'booked')->count() }}
            </div>
        </div>

        <!-- Maintenance/Cleaning -->
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 transition-all">
            <div class="text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">
                បន្ទប់ជួសជុល/សម្អាត
            </div>
            <div class="text-3xl font-black mt-2 text-red-500">
                {{ $rooms->whereIn('status', ['maintenance', 'cleaning'])->count() }}
            </div>
        </div>

    </div>

    {{-- CALENDAR CARD --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800">
        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-black text-xl text-gray-800 dark:text-white leading-none">
                    {{ now()->format('F Y') }}
                </h2>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-wider font-bold">
                    Monthly room booking overview
                </p>
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap items-center gap-4 bg-gray-50 dark:bg-gray-800/50 p-2 px-4 rounded-xl">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-[11px] font-black text-gray-500 uppercase">ទំនេរ</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                    <span class="text-[11px] font-black text-gray-500 uppercase">បានកក់</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="text-[11px] font-black text-gray-500 uppercase">ជួសជុល/សម្អាត</span>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse table-fixed">
                <thead class="bg-gray-50 dark:bg-gray-800 sticky top-0 z-20">
                    <tr>
                        {{-- Sticky Room Header --}}
                        <th class="sticky left-0 z-30 bg-gray-50 dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 p-5 min-w-[240px] w-[240px]">
                            <div class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                                ព័ត៌មានបន្ទប់
                            </div>
                        </th>

                        {{-- Days Headers --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                            $date=\Carbon\Carbon::create(now()->year, now()->month, $d);
                            $isToday = now()->day == $d;
                            @endphp
                            <th class="border-r border-gray-100 dark:border-gray-700 min-w-[100px] h-[70px] {{ $isToday ? 'bg-blue-600 text-white' : 'dark:text-gray-300' }}">
                                <div class="flex flex-col items-center justify-center">
                                    <span class="text-lg font-black leading-none">{{ $d }}</span>
                                    <span class="text-[9px] uppercase opacity-70 font-bold mt-1">{{ $date->format('D') }}</span>
                                </div>
                            </th>
                            @endfor
                    </tr>
                </thead>

                <tbody>
                    @foreach($rooms as $room)
                    <tr class="group hover:bg-blue-50/20 transition">
                        {{-- Room Info Sticky Column --}}
                        <td class="sticky left-0 z-10 bg-white dark:bg-gray-900 border-r border-b border-gray-100 dark:border-gray-800 p-4 group-hover:bg-gray-50 dark:group-hover:bg-gray-800 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-20 h-10 shrink-0 rounded-lg bg-gray-100 dark:bg-gray-800 flex items-center justify-center font-black text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                    {{ $room->room_number }}
                                </div>
                                <div class="truncate">
                                    <div class="text-sm font-black text-gray-800 dark:text-gray-100 truncate">
                                        បន្ទប់ {{ $room->room_number }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 uppercase font-bold">
                                        {{ $room->roomType->name ?? 'បន្ទប់ធម្មតា' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Dynamic Day Cells --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                            $currentDate=\Carbon\Carbon::create(now()->year, now()->month, $d)->format('Y-m-d');
                            // Optimization: It's better to eager load bookings and pre-key them by date in the Controller
                            $booking = $room->bookings->first(fn($b) => $currentDate >= $b->check_in && $currentDate < $b->check_out);
                                @endphp

                                <td class="border-r border-b border-gray-100 dark:border-gray-800 p-0 h-72px min-w-[100px] relative group/cell">
                                    @if($booking)
                                    @php
                                    $isStart = $currentDate == \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d');
                                    $isEnd = $currentDate == \Carbon\Carbon::parse($booking->check_out)->subDay()->format('Y-m-d');
                                    @endphp

                                    {{-- Booking Bar --}}
                                    <div class="absolute inset-y-3 left-0 right-0 z-0
                                    bg-gradient-to-r from-amber-400 to-orange-400 
                                    group-hover/cell:from-amber-500 group-hover/cell:to-orange-500
                                    shadow-sm transition-all cursor-pointer
                                    {{ $isStart ? 'rounded-l-xl ml-1' : '' }}
                                    {{ $isEnd ? 'rounded-l-none' : '' }}">

                                        @if($isStart)
                                        <div class="h-full flex items-center px-3">
                                            <span class="text-[11px] font-black text-white truncate drop-shadow-sm">
                                                {{ $booking->user ? $booking->user->name . ' (អនឡាញ)' : 'ភ្ញៀវមកផ្ទាល់' }}
                                            </span>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Improved Tooltip --}}
                                    <div class="invisible group-hover/cell:visible opacity-0 group-hover/cell:opacity-100 absolute z-50 bottom-full left-1/2 -translate-x-1/2 mb-3 transition-all duration-200">
                                        <div class="bg-gray-900 dark:bg-black text-white rounded-xl p-3 shadow-2xl w-48 border border-white/10">
                                            <div class="flex items-center gap-2 mb-2 border-b border-white/10 pb-2">
                                                <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                                                <span class="font-black text-xs uppercase tracking-tighter">ព័ត៌មានលម្អិតអំពីការកក់</span>
                                            </div>
                                            <div class="space-y-1.5 text-[11px] font-medium text-gray-300">
                                                {{-- ឈ្មោះភ្ញៀវ --}}
                                                <div class="flex justify-between gap-4">
                                                    <span>ភ្ញៀវ:</span>
                                                    <span class="text-white text-right">
                                                        {{ $booking->user ? $booking->user->name . ' (អនឡាញ)' : 'ភ្ញៀវមកផ្ទាល់' }}
                                                    </span>
                                                </div>

                                                {{-- ថ្ងៃចូល --}}
                                                <div class="flex justify-between">
                                                    <span>ថ្ងៃចូល:</span>
                                                    <span class="text-white">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</span>
                                                </div>

                                                {{-- ថ្ងៃចេញ --}}
                                                <div class="flex justify-between">
                                                    <span>ថ្ងៃចេញ:</span>
                                                    <span class="text-white">{{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</span>
                                                </div>

                                                {{-- ចំនួនយប់ (បន្ថែមថ្មី) --}}
                                                <div class="flex justify-between border-t border-white/10 pt-1.5 mt-1.5">
                                                    <span>សរុប:</span>
                                                    <span class="text-amber-400 font-bold">
                                                        {{ \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) }} យប់
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mt-2 pt-2 border-t border-white/10 text-center font-black text-amber-400 text-xs">
                                                ${{ number_format($booking->total_price, 2) }}
                                            </div>
                                        </div>
                                        {{-- Tooltip Arrow --}}
                                        <div class="w-3 h-3 bg-gray-900 rotate-45 absolute -bottom-1.5 left-1/2 -translate-x-1/2"></div>
                                    </div>
                                    @else
                                    {{-- Empty Cell / Quick Add --}}
                                    <button class="w-full h-full opacity-0 hover:opacity-100 hover:bg-emerald-50/50 dark:hover:bg-emerald-500/10 transition-all flex items-center justify-center">
                                        <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg transform scale-75 group-hover/cell:scale-100 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
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

{{-- STYLES --}}
<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 999px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>

{{-- ALPINE --}}
<script>
    function calendarSystem() {
        return {
            showModal: false,
        }
    }
</script>

@endsection