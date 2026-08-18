@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')
<div class="p-2 sm:p-2 transition-colors duration-200" x-data="adminDashboardManager">

    {{-- QUICK ACTIONS HEADER BAR --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h1 class="text-xl font-black text-gray-800 dark:text-white flex items-center gap-2">
                ផ្ទាំងគ្រប់គ្រងទូទៅ
            </h1>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">Overview & Quick Reservation Actions</p>
        </div>
        <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
            <a href="{{ route('room-bookings.index') }}" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-md shadow-blue-500/20 active:scale-95 transition-all cursor-pointer">
               <i class="fa-solid fa-plus text-xs"></i> កក់បន្ទប់ស្នាក់នៅ
            </a>
            <a href="{{ route('meeting-bookings.index') }}" class="h-10 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-md shadow-purple-500/20 active:scale-95 transition-all cursor-pointer">
               <i class="fa-solid fa-plus text-xs"></i> កក់សាលប្រជុំ
            </a>
            <a href="{{ route('room_types.index') }}" class="h-10 px-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
                ប្រភេទបន្ទប់ ({{ $roomTypesCount }})
            </a>
            <a href="{{ route('rooms.index') }}" class="h-10 px-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all">
               បន្ទប់/សាល ({{ $roomStatusCounts['available'] + $roomStatusCounts['booked'] + $roomStatusCounts['maintenance'] }})
            </a>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 lg:col-span-9 space-y-6">

            {{-- 5 MAIN STAT CARDS GRID --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                {{-- CARD 1: New Bookings --}}
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">ការកក់សរុប</span>
                        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-base shadow-xs">
                            <i class="fa-solid fa-calendar-check"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($newBookingsCount) }}</h2>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2 py-0.5 rounded-full {{ $bookingsGrowth >= 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950/50 dark:text-red-400' }}">
                            <i class="fa-solid {{ $bookingsGrowth >= 0 ? 'fa-arrow-up-long' : 'fa-arrow-down-long' }}"></i> {{ number_format(abs($bookingsGrowth), 1) }}%
                        </span>
                        <span class="text-[11px] text-gray-400 font-medium">ធៀបខែមុន</span>
                    </div>
                </div>

                {{-- CARD 2: Pending Verification --}}
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">រង់ចាំពិនិត្យ</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base shadow-xs animate-pulse">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black text-amber-600 dark:text-amber-400 tracking-tight">{{ number_format($pendingBookingsCount ?? 0) }}</h2>
                    <div class="flex items-center gap-1.5 mt-2">
                        <a href="{{ route('bookings.index') }}" class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-400 hover:underline">
                            <i class="fa-solid fa-file-invoice"></i> ពិនិត្យបង្កាន់ដៃ
                        </a>
                    </div>
                </div>

                {{-- CARD 3: Active Check-Ins --}}
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">ភ្ញៀវចូលស្នាក់នៅ</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base shadow-xs">
                            <i class="fa-solid fa-door-open"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($checkInCount) }}</h2>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                            <i class="fa-solid fa-check-circle"></i> បានបញ្ជាក់
                        </span>
                    </div>
                </div>

                {{-- CARD 4: Check-Outs / Completed --}}
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">ស្នាក់នៅបានបញ្ចប់</span>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base shadow-xs">
                            <i class="fa-solid fa-flag-checkered"></i>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($checkOutCount) }}</h2>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400">
                            <i class="fa-solid fa-user-check"></i> បញ្ចប់រួចរាល់
                        </span>
                    </div>
                </div>

                {{-- CARD 5: Total Revenue --}}
                <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-xs hover:shadow-md transition-all">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">ចំណូលសរុប</span>
                        <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-base shadow-xs">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">${{ number_format($totalRevenue, 2) }}</h2>
                        <span class="text-[11px] font-bold text-gray-400 font-mono">({{ number_format($totalRevenue * $khrRate) }} ៛)</span>
                    </div>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold px-2 py-0.5 rounded-full {{ $revenueGrowth >= 0 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400' : 'bg-red-50 text-red-600 dark:bg-red-950/50 dark:text-red-400' }}">
                            <i class="fa-solid {{ $revenueGrowth >= 0 ? 'fa-arrow-up-long' : 'fa-arrow-down-long' }}"></i> {{ number_format(abs($revenueGrowth), 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 md:col-span-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                    <div class="flex justify-between items-center mb-6 relative" x-data="{ open: false }">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">ស្ថានភាពនៃបន្ទប់</h3>

                        <button @click="open = !open" @click.outside="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <i class="fa-solid fa-ellipsis"></i>
                        </button>

                        <div x-show="open"
                            x-transition
                            class="absolute right-0 top-8 w-44 bg-white dark:bg-gray-800  border border-gray-100 dark:border-gray-700 shadow-lg rounded-xl py-2 z-10">

                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <i class="fa-solid fa-rotate-right mr-2 text-gray-400 dark:text-gray-500"></i> ធ្វើបច្ចុប្បន្នភាព
                            </a>

                            <a href="{{ route('rooms.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <i class="fa-solid fa-chart-pie mr-2 text-gray-400 dark:text-gray-500"></i> មើលលម្អិត
                            </a>
                        </div>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-8 rounded-xl overflow-hidden flex mb-6">
                        <div class="bg-emerald-400 dark:bg-emerald-500" style="width: {{ $roomPercentages['available'] }}%"></div>
                        <div class="bg-amber-400 dark:bg-amber-500" style="width: {{ $roomPercentages['booked'] }}%"></div>
                        <div class="bg-gray-300 dark:bg-gray-500" style="width: {{ $roomPercentages['maintenance'] }}%"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="border-l-4 border-emerald-400 pl-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">បន្ទប់ទំនេរ</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $roomStatusCounts['available'] }}</h4>
                        </div>
                        <div class="border-l-4 border-amber-400 pl-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">បន្ទប់មានភ្ញៀវ</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $roomStatusCounts['booked'] }}</h4>
                        </div>
                        <div class="border-l-4 border-gray-300 dark:border-gray-500 pl-3">
                            <span class="text-xs text-gray-400 dark:text-gray-500">បន្ទប់ជួសជុល</span>
                            <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $roomStatusCounts['maintenance'] }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-span-12 md:col-span-8 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm relative">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">សមិទ្ធផលប្រាក់ចំណូល</h3>
                        <div class="text-[11px] font-bold bg-yellow-100 dark:bg-yellow-500/20 px-2 py-1 rounded-lg text-gray-800 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-500/20">
                            សរុបរយៈពេល ៦ខែ៖ ${{ number_format($sixMonthsTotalRevenue, 2) }} <span class="text-gray-400 font-mono text-[10px]">({{ number_format($sixMonthsTotalRevenue * $khrRate) }} ៛)</span>
                        </div>
                    </div>
                    <div class="h-44">
                        <canvas id="revenueLineChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200">ការកក់បន្ទប់</h3>
                        <span class="text-xs text-gray-400 dark:text-gray-500">ស្ថិតិនៃការកក់ជោគជ័យ និងការបោះបង់ រយៈពេល ៧ថ្ងៃចុងក្រោយ</span>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium">
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                            <span class="text-gray-600 dark:text-gray-400">បានកក់</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                            <span class="text-gray-600 dark:text-gray-400">បានបោះបង់</span>
                        </div>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="reservationsChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 text-base">បញ្ជីកក់ថ្មីៗ</h3>
                        <p class="text-xs text-gray-400 mt-0.5">ទិន្នន័យរួមបញ្ចូលគ្នារវាងការកក់បន្ទប់ស្នាក់នៅ និងការកក់សាលប្រជុំ</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('room-bookings.index') }}" class="px-3 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl text-xs font-bold hover:underline flex items-center gap-1">
                           បន្ទប់ស្នាក់នៅ
                        </a>
                        <a href="{{ route('meeting-bookings.index') }}" class="px-3 py-1.5 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl text-xs font-bold hover:underline flex items-center gap-1">
                            សាលប្រជុំ
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 text-xs text-gray-400 dark:text-gray-500 font-medium">
                                <th class="pb-3">លេខកូដកក់</th>
                                <th class="pb-3">ប្រភេទការកក់</th>
                                <th class="pb-3">ឈ្មោះអតិថិជន</th>
                                <th class="pb-3">បន្ទប់ / សាលប្រជុំ & ប្រភេទ</th>
                                <th class="pb-3">កាលបរិច្ឆេទស្នាក់នៅ / ប្រជុំ</th>
                                <th class="pb-3 text-center">តម្លៃសរុប</th>
                                <th class="pb-3 text-center">ស្ថានភាព</th>
                                <th class="pb-3 text-right">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-gray-700/50 text-xs">
                            @forelse($recentBookings as $b)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="py-3.5 font-bold {{ $b->category === 'meeting' ? 'text-purple-600 dark:text-purple-400' : 'text-blue-600 dark:text-blue-400' }}">
                                    #{{ $b->booking_code }}
                                </td>

                                {{-- BOOKING CATEGORY --}}
                                <td class="py-3.5 whitespace-nowrap">
                                    @if($b->category === 'meeting')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                                       សាលប្រជុំ
                                    </span>
                                    @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                        បន្ទប់ស្នាក់នៅ
                                    </span>
                                    @endif
                                </td>

                                {{-- GUEST NAME --}}
                                <td class="py-3.5 font-medium text-gray-800 dark:text-gray-200 whitespace-nowrap">{{ $b->guest_name }}</td>

                                {{-- ROOM / MEETING HALL --}}
                                <td class="py-3.5 text-gray-600 dark:text-gray-400">
                                    <div class="font-bold flex items-center gap-1 flex-wrap">
                                        @if($b->category === 'meeting')
                                        <span class="text-purple-600 dark:text-purple-400">សាលប្រជុំ {{ $b->room_number }}</span>
                                        @else
                                        <span class="text-blue-600 dark:text-blue-400">បន្ទប់ {{ $b->room_number }}</span>
                                        @if($b->details_count > 1)
                                        <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-blue-600 text-white shadow-sm">
                                            {{ $b->details_count }} បន្ទប់
                                        </span>
                                        @endif
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-gray-400">({{ $b->room_type }})</div>
                                </td>

                                {{-- DATES --}}
                                <td class="py-3.5 text-gray-500 dark:text-gray-400 font-medium whitespace-nowrap">
                                    {{ $b->date_display }}
                                </td>

                                {{-- TOTAL PRICE --}}
                                <td class="py-3.5 text-center font-black text-gray-800 dark:text-gray-200 whitespace-nowrap">
                                    <div>${{ number_format($b->total_price, 2) }}</div>
                                    <div class="text-[10px] text-gray-400 font-mono font-medium">({{ number_format($b->total_price * $khrRate) }} ៛)</div>
                                </td>

                                {{-- STATUS --}}
                                <td class="py-3.5 text-center whitespace-nowrap">
                                    @if($b->status == 'confirmed')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400">បានបញ្ជាក់</span>
                                    @elseif($b->status == 'completed')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400">បានបញ្ចប់</span>
                                    @elseif($b->status == 'pending')
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400">រង់ចាំ</span>
                                    @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400">បានបោះបង់</span>
                                    @endif
                                </td>

                                {{-- ACTION --}}
                                <td class="py-3.5 text-right whitespace-nowrap">
                                    <a href="{{ $b->url }}" class="p-2 text-gray-400 hover:text-blue-500 cursor-pointer" title="មើលលម្អិត">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="py-6 text-center text-gray-400">មិនទាន់មានទិន្នន័យកក់នៅឡើយទេ</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-span-12 lg:col-span-3 space-y-6">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4">វិធីសាស្ត្របង់ប្រាក់នៃការកក់</h3>
                <div class="h-44 relative flex items-center justify-center">
                    <canvas id="platformChart"></canvas>
                </div>
                <div class="mt-4 space-y-2 text-xs">
                    @foreach($platformData as $name => $percentage)
                    <div class="flex justify-between items-center text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            {{ $name }}
                        </span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $percentage }}%</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4 text-sm">ពិន្ទុវាយតម្លៃអតិថិជន</h3>
                <div class="text-center pb-4 border-b border-gray-50 dark:border-gray-700/50">
                    <h2 class="text-4xl font-extrabold text-gray-800 dark:text-gray-100">{{ $ratingData['avg_rating'] }}</h2>
                    <div class="flex justify-center gap-1 text-amber-400 text-xs my-2">
                        @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star {{ $i <= floor($ratingData['avg_rating']) ? '' : 'text-gray-200 dark:text-gray-700' }}"></i>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400 dark:text-gray-500">ផ្អែកលើការវាយតម្លៃអតិថិជនទាំងអស់</span>
                </div>
                <div class="pt-4 space-y-3 text-xs">
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-500 dark:text-gray-400">សម្ភារៈបរិក្ខារ</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $ratingData['facilities'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-400 h-1.5 rounded-full" style="width: {{ ($ratingData['facilities'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-500 dark:text-gray-400">អនាម័យ</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $ratingData['cleanliness'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-400 h-1.5 rounded-full" style="width: {{ ($ratingData['cleanliness'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-500 dark:text-gray-400">សេវាកម្ម</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200">{{ $ratingData['services'] }}</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-amber-400 h-1.5 rounded-full" style="width: {{ ($ratingData['services'] / 5) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MEETING ROOM TASKS --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 dark:text-gray-200 text-sm">កិច្ចការសាលប្រជុំ</h3>
                    <a href="{{ route('meeting-bookings.index') }}" class="w-7 h-7 bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 rounded-lg text-xs flex items-center justify-center hover:bg-purple-100 dark:hover:bg-purple-900/50 transition-colors cursor-pointer" title="កក់សាលប្រជុំថ្មី">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>

                <div class="space-y-3 text-xs">
                    @forelse($tasks as $task)
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-700 rounded-xl border flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-purple-600 dark:text-purple-400">#{{ $task->booking_code }}</span>
                            @if($task->status == 'confirmed' || $task->status == 'បានបញ្ជាក់')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400">បានបញ្ជាក់</span>
                            @elseif($task->status == 'completed' || $task->status == 'បានបញ្ចប់')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400">បានបញ្ចប់</span>
                            @elseif($task->status == 'pending' || $task->status == 'រង់ចាំ')
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400">រង់ចាំ</span>
                            @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400">បានបោះបង់</span>
                            @endif
                        </div>
                        <div class="text-gray-500 dark:text-gray-400 text-[11px]">
                            <i class="far fa-calendar-alt text-purple-500 mr-1"></i>{{ \Carbon\Carbon::parse($task->execution_date)->format('d/m/Y') }}
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-center text-xs py-4">មិនទាន់មានកិច្ចការសាលប្រជុំនៅឡើយទេ</p>
                    @endforelse
                </div>
            </div>

            {{-- RECENT PAYMENTS --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-50 dark:border-gray-700/50 shadow-sm">
                <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4 text-sm">ការបង់ប្រាក់ថ្មីៗ</h3>
                <div class="space-y-3 text-xs">
                    @forelse($activities as $act)
                    <div class="p-3 bg-gray-50 dark:bg-gray-700/30 border-gray-100 dark:border-gray-700 rounded-xl border flex justify-between items-center">
                        <div>
                            <span class="font-bold text-gray-800 dark:text-gray-200 block">#{{ $act->booking_code }}</span>
                            <span class="text-[10px] text-gray-400 uppercase font-semibold">{{ $act->type ?: 'សាច់ប្រាក់' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="font-black text-emerald-600 dark:text-emerald-400 text-sm block">+${{ number_format($act->amount, 2) }}</span>
                            <span class="text-[10px] text-gray-400 font-mono">({{ number_format($act->amount * $khrRate) }} ៛)</span>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-center text-xs py-4">មិនទាន់មានការបង់ប្រាក់នៅឡើយទេ</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('adminDashboardManager', () => ({
            loading: false
        }));
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const isDarkMode = () => document.documentElement.classList.contains('dark');
    const getChartStyles = () => ({
        textColor: isDarkMode() ? '#9ca3af' : '#4b5563',
        gridColor: isDarkMode() ? '#374151' : '#f3f4f6'
    });

    const styles = getChartStyles();

    // ១. Revenue Line Chart
    const ctxRevenue = document.getElementById('revenueLineChart').getContext('2d');
    const revenueLineChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: @json($timelineLabels),
            datasets: [{
                label: 'ចំណូលសរុប',
                data: @json($revenueData6Months),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: styles.textColor } },
                y: { grid: { color: styles.gridColor, borderDash: [4, 4] }, ticks: { color: styles.textColor } }
            }
        }
    });

    // ២. Reservations Stacked Bar Chart
    const ctxReservations = document.getElementById('reservationsChart').getContext('2d');
    const reservationsChart = new Chart(ctxReservations, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'បានកក់ជោគជ័យ',
                    data: @json($chartData['booked']),
                    backgroundColor: isDarkMode() ? '#059669' : '#D1FAE5',
                    borderRadius: 4
                },
                {
                    label: 'បានបោះបង់',
                    data: @json($chartData['canceled']),
                    backgroundColor: isDarkMode() ? '#D97706' : '#FEF08A',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { stacked: true, grid: { display: false }, ticks: { color: styles.textColor } },
                y: { stacked: true, grid: { color: styles.gridColor, borderDash: [4, 4] }, ticks: { color: styles.textColor } }
            }
        }
    });

    // ៣. Booking Platform Donut Chart
    const ctxDonut = document.getElementById('platformChart').getContext('2d');
    const platformChart = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($platformData)),
            datasets: [{
                data: @json(array_values($platformData)),
                backgroundColor: isDarkMode() ? ['#10B981', '#3B82F6', '#84CC16', '#6B7280'] : ['#34D399', '#60A5FA', '#A3E635', '#E2E8F0'],
                borderWidth: 2,
                borderColor: isDarkMode() ? '#1f2937' : '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });

    const observer = new MutationObserver(() => {
        const updatedStyles = getChartStyles();
        [revenueLineChart, reservationsChart].forEach(chart => {
            chart.options.scales.x.ticks.color = updatedStyles.textColor;
            chart.options.scales.y.ticks.color = updatedStyles.textColor;
            chart.options.scales.y.grid.color = updatedStyles.gridColor;
        });
        reservationsChart.data.datasets[0].backgroundColor = isDarkMode() ? '#059669' : '#D1FAE5';
        reservationsChart.data.datasets[1].backgroundColor = isDarkMode() ? '#D97706' : '#FEF08A';
        platformChart.data.datasets[0].borderColor = isDarkMode() ? '#1f2937' : '#ffffff';

        revenueLineChart.update();
        reservationsChart.update();
        platformChart.update();
    });
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
</script>
@endsection