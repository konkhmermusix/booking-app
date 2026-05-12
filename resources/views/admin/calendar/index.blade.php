{{-- ========================= --}}
{{-- HOTEL ROOM CALENDAR UI --}}
{{-- ========================= --}}

@extends('layouts.admin')

@section('title', 'Room Calendar')

@section('content')

<div
    x-data="calendarSystem()"
    class="p-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

    {{-- HEADER --}}
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>
            <h1 class="text-3xl font-black text-gray-800 tracking-tight flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-alt"></i>
                </div>

                Hotel Room Calendar
            </h1>

            <p class="text-gray-500 mt-2 text-sm">
                Manage room booking schedule professionally
            </p>
        </div>

        {{-- ACTIONS --}}
        <div class="flex flex-wrap gap-3">

            <button
                class="px-5 py-3 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-lg transition font-bold text-sm">
                <i class="fas fa-filter mr-2"></i>
                Filter
            </button>

            <button
                class="px-5 py-3 rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-lg transition font-bold text-sm">
                <i class="fas fa-download mr-2"></i>
                Export
            </button>

            <button
                @click="showModal = true"
                class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white shadow-lg font-bold text-sm transition">

                <i class="fas fa-plus mr-2"></i>
                New Booking
            </button>

        </div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">

        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
            <div class="text-gray-400 text-xs font-bold uppercase">
                Total Rooms
            </div>

            <div class="text-3xl font-black mt-2 text-gray-800">
                {{ $rooms->count() }}
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
            <div class="text-gray-400 text-xs font-bold uppercase">
                Available
            </div>

            <div class="text-3xl font-black mt-2 text-emerald-500">
                24
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
            <div class="text-gray-400 text-xs font-bold uppercase">
                Booked
            </div>

            <div class="text-3xl font-black mt-2 text-amber-500">
                12
            </div>
        </div>

        <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100">
            <div class="text-gray-400 text-xs font-bold uppercase">
                Maintenance
            </div>

            <div class="text-3xl font-black mt-2 text-red-500">
                2
            </div>
        </div>

    </div>

    {{-- CALENDAR CARD --}}
    <div class="bg-white rounded-[30px] shadow-2xl overflow-hidden border border-gray-100">

        {{-- TOP BAR --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

            <div>
                <h2 class="font-black text-xl text-gray-800">
                    {{ now()->format('F Y') }}
                </h2>

                <p class="text-xs text-gray-400 mt-1">
                    Monthly room booking overview
                </p>
            </div>

            {{-- LEGEND --}}
            <div class="hidden md:flex items-center gap-4">

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-gray-500">
                        Available
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                    <span class="text-xs font-bold text-gray-500">
                        Booked
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                    <span class="text-xs font-bold text-gray-500">
                        Maintenance
                    </span>
                </div>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="overflow-x-auto custom-scrollbar">

            <table class="w-full border-collapse">

                {{-- HEADER --}}
                <thead class="bg-gray-50 sticky top-0 z-20">

                    <tr>

                        {{-- ROOM --}}
                        <th class="sticky left-0 z-30 bg-gray-50 border-r border-gray-100 p-5 min-w-[240px] text-left">

                            <div class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                Rooms
                            </div>

                        </th>

                        {{-- DAYS --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)

                            <th class="border-r border-gray-100 min-w-[70px] h-[70px]
                                {{ now()->day == $d ? 'bg-blue-600 text-white' : '' }}">

                            <div class="flex flex-col items-center justify-center">

                                <span class="text-lg font-black">
                                    {{ $d }}
                                </span>

                                <span class="text-[9px] uppercase opacity-70 font-bold">
                                    {{ \Carbon\Carbon::create(now()->year, now()->month, $d)->format('D') }}
                                </span>

                            </div>

                            </th>

                            @endfor

                    </tr>

                </thead>

                {{-- BODY --}}
                <tbody>

                    @foreach($rooms as $room)

                    <tr class="group hover:bg-blue-50/20 transition">

                        {{-- ROOM INFO --}}
                        <td class="sticky left-0 z-10 bg-white border-r border-b border-gray-100 p-4 group-hover:bg-gray-50 transition">

                            <div class="flex items-center gap-4">

                                <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center font-black text-gray-700">
                                    {{ $room->room_number }}
                                </div>

                                <div>

                                    <div class="font-black text-gray-800">
                                        Room {{ $room->room_number }}
                                    </div>

                                    <div class="text-xs text-gray-400 uppercase font-bold mt-1">
                                        {{ $room->type ?? 'Standard Room' }}
                                    </div>

                                </div>

                            </div>

                        </td>

                        {{-- DAYS --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)

                            @php
                            $currentDate=\Carbon\Carbon::create(
                            now()->year,
                            now()->month,
                            $d
                            )->format('Y-m-d');

                            $booking = $room->bookings->filter(function($b) use ($currentDate) {

                            return $currentDate >= $b->check_in &&
                            $currentDate < $b->check_out;

                                })->first();
                                @endphp

                                <td class="border-r border-b border-gray-100 p-0 h-[72px] min-w-[70px] relative group/cell">

                                    @if($booking)

                                    @php
                                    $isStart = $currentDate == \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d');

                                    $isEnd = $currentDate ==
                                    \Carbon\Carbon::parse($booking->check_out)
                                    ->subDay()
                                    ->format('Y-m-d');
                                    @endphp

                                    <div
                                        class="absolute inset-y-2 left-0 right-0
                                        bg-gradient-to-r from-amber-400 to-orange-400
                                        hover:from-orange-500 hover:to-amber-500
                                        shadow-lg transition-all duration-200 cursor-pointer

                                        {{ $isStart ? 'rounded-l-2xl ml-1' : '' }}
                                        {{ $isEnd ? 'rounded-r-2xl mr-1' : '' }}">

                                        @if($isStart)

                                        <div class="h-full flex items-center px-3">

                                            <span class="text-[11px] font-black text-white truncate">
                                                {{ $booking->guest_name ?? 'Guest' }}
                                            </span>

                                        </div>

                                        @endif

                                    </div>

                                    {{-- TOOLTIP --}}
                                    <div class="hidden group-hover/cell:block absolute z-50 bottom-full left-1/2 -translate-x-1/2 mb-2">

                                        <div class="bg-gray-900 text-white rounded-2xl px-4 py-3 shadow-2xl w-56">

                                            <div class="font-black text-amber-300 mb-2">
                                                Room {{ $room->room_number }}
                                            </div>

                                            <div class="space-y-1 text-xs">

                                                <div>
                                                    👤 {{ $booking->guest_name ?? 'Guest Name' }}
                                                </div>

                                                <div>
                                                    📅 {{ $booking->check_in }}
                                                </div>

                                                <div>
                                                    🛏️ {{ $booking->check_out }}
                                                </div>

                                                <div>
                                                    💰 ${{ number_format($booking->total_amount ?? 0, 2) }}
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    @else

                                    {{-- AVAILABLE --}}
                                    <button
                                        class="w-full h-full hover:bg-emerald-50 transition">

                                        <div class="opacity-0 group-hover/cell:opacity-100 h-full flex items-center justify-center transition">

                                            <div class="w-7 h-7 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow-lg">
                                                +
                                            </div>

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
        height: 8px;
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