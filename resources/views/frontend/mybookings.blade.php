@extends('layouts.app')
@section('title', 'ប្រវត្តិកក់របស់ខ្ញុំ | សណ្ឋាគារ ភីអេនធី ផាលេស')
@section('content')

<div class="w-full bg-gray-50 dark:bg-[#0b1120] min-h-screen py-10 transition-colors duration-300" x-data="{ activeTab: 'hotel', viewMode: 'list' }">
    <div class="container mx-auto px-4">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                        ប្រវត្តិកក់របស់ខ្ញុំ
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">គ្រប់គ្រង និងពិនិត្យមើលស្ថានភាពការកក់បន្ទប់ ឬសាលប្រជុំរបស់អ្នក</p>
                </div>
            </div>

            {{-- VIEW SWITCHER (List vs Grid) --}}
            <div class="flex items-center gap-1.5 bg-white dark:bg-gray-900 p-1.5 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
                <button @click="viewMode = 'list'"
                    :class="viewMode === 'list' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="flex items-center gap-2 py-2 px-3.5 rounded-xl text-xs transition-all duration-200" title="មើលជា List">
                    <i class="fas fa-list-ul text-xs"></i>
                </button>
                <button @click="viewMode = 'grid'"
                    :class="viewMode === 'grid' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                    class="flex items-center gap-2 py-2 px-3.5 rounded-xl text-xs transition-all duration-200" title="មើលជា Grid">
                    <i class="fas fa-th-large text-xs"></i>
                </button>
            </div>
        </div>

        {{-- TAB NAVIGATION --}}
        <div class="flex gap-2 bg-white dark:bg-gray-900 p-1.5 rounded-xl w-full sm:w-fit mb-8 shadow-sm border border-gray-100 dark:border-gray-800">
            <button @click="activeTab = 'hotel'"
                :class="activeTab === 'hotel' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="flex items-center justify-center gap-2 py-2.5 px-5 rounded-xl text-xs sm:text-sm transition-all duration-200 w-full sm:w-auto"> 
                <span>បន្ទប់សណ្ឋាគារ ({{ $hotelBookings->total() }})</span>
            </button>
            <button @click="activeTab = 'meeting'"
                :class="activeTab === 'meeting' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="flex items-center justify-center gap-2 py-2.5 px-5 rounded-xl text-xs sm:text-sm transition-all duration-200 w-full sm:w-auto">
                <span>សាលប្រជុំ ({{ $meetingBookings->total() }})</span>
            </button>
        </div>

        {{-- HOTEL BOOKINGS TAB --}}
        <div x-show="activeTab === 'hotel'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2">
            @if($hotelBookings->isEmpty())
            <div class="text-center py-16 max-w-xl mx-auto px-6">
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100 dark:border-blue-900/30">
                    <i class="fas fa-calendar-times text-3xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">មិនទាន់មានប្រវត្តិកក់បន្ទប់នៅឡើយទេ</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">
                    លោកអ្នកមិនទាន់បានធ្វើការកក់បន្ទប់ស្នាក់នៅណាមួយក្នុងប្រព័ន្ធនៅឡើយទេ។
                </p>
                <a href="{{ route('frontend.rooms') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-7 py-3.5 rounded-xl text-xs transition-all shadow-md shadow-blue-500/20 active:scale-95">
                    <span>ស្វែងរកបន្ទប់ស្នាក់នៅ</span>
                </a>
            </div>
            @else

            {{-- 1. HOTEL LIST VIEW --}}
            <div x-show="viewMode === 'list'" class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 uppercase text-[11px] font-black border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4">កូដកក់</th>
                                <th class="px-6 py-4">បន្ទប់ស្នាក់នៅ</th>
                                <th class="px-6 py-4">កាលបរិច្ឆេទចូលស្នាក់នៅ / ចាកចេញ</th>
                                <th class="px-6 py-4">តម្លៃសរុប</th>
                                <th class="px-6 py-4">ការទូទាត់</th>
                                <th class="px-6 py-4">ស្ថានភាពកក់</th>
                                <th class="px-6 py-4 text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 font-medium">
                            @foreach($hotelBookings as $hb)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-sm">
                                        {{ $hb->booking_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-gray-900 dark:text-white text-sm">
                                        {{ $hb->room_type_name ?? 'បន្ទប់ស្នាក់នៅ' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5">
                                        <div class="font-bold text-gray-800 dark:text-gray-200">
                                            <i class="far fa-calendar-alt text-blue-500 mr-1"></i>{{ \Carbon\Carbon::parse($hb->check_in)->format('d/m/Y') }} ដល់ {{ \Carbon\Carbon::parse($hb->check_out)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-base text-gray-900 dark:text-white block">
                                        ${{ number_format($hb->total_price, 2) }}
                                    </span>
                                    <span class="text-xs font-semibold text-gray-400 font-mono block">({{ number_format($hb->total_price * $khrRate) }} ៛)</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($hb->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400 border border-green-200 dark:border-green-900/30">
                                            <i class="fas fa-check-circle text-xs"></i> បង់រួច
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30">
                                            <i class="fas fa-clock text-xs"></i> រង់ចាំពិនិត្យ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($hb->status === 'approved' || $hb->status === 'confirmed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400 border border-green-200 dark:border-green-900/30">
                                            <i class="fas fa-check-circle"></i> បានបញ្ជាក់
                                        </span>
                                    @elseif($hb->status === 'cancelled')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900/30">
                                            <i class="fas fa-times-circle"></i> បានបោះបង់
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-900/30">
                                            <i class="fas fa-spinner fa-spin"></i> កំពុងដំណើរការ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('receipt', $hb->booking_code) }}"
                                            class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white px-3.5 py-2 rounded-xl font-bold text-xs transition-all border border-blue-200 dark:border-blue-900/40">
                                            <i class="fas fa-file-invoice"></i>
                                            <span>វិក្កយបត្រ</span>
                                        </a>

                                        @if(in_array($hb->status, ['pending', 'confirmed']))
                                        <form id="cancel-form-hotel-{{ $hb->id }}" action="{{ route('bookings.cancel', $hb->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="button" onclick="confirmCancelBooking('cancel-form-hotel-{{ $hb->id }}')"
                                                class="inline-flex items-center gap-1.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white px-3.5 py-2 rounded-xl font-bold text-xs transition-all border border-red-200 dark:border-red-900/40">
                                                <i class="fas fa-times-circle"></i>
                                                <span>បោះបង់</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($hotelBookings->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $hotelBookings->appends(request()->except('hotel_page'))->links() }}
                </div>
                @endif
            </div>

            {{-- 2. HOTEL GRID VIEW --}}
            <div x-show="viewMode === 'grid'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($hotelBookings as $hb)
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                        {{-- Top Header --}}
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-sm font-bold">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 uppercase font-bold block">កូដកក់</span>
                                    <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-sm">
                                        {{ $hb->booking_code }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                @if($hb->status === 'approved' || $hb->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400 border border-green-200 dark:border-green-900/30">
                                        <i class="fas fa-check-circle"></i> បានបញ្ជាក់
                                    </span>
                                @elseif($hb->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900/30">
                                        <i class="fas fa-times-circle"></i> បានបោះបង់
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-900/30">
                                        <i class="fas fa-spinner fa-spin"></i> កំពុងដំណើរការ
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="space-y-3">
                            <div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-base">
                                    {{ $hb->room_type_name ?? 'បន្ទប់ស្នាក់នៅ' }}
                                </h4>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl space-y-2 text-xs">
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400"><i class="far fa-calendar-alt text-blue-500 mr-1"></i>កាលបរិច្ឆេទ:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($hb->check_in)->format('d/m/Y') }} ដល់ {{ \Carbon\Carbon::parse($hb->check_out)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400"><i class="fas fa-credit-card text-emerald-500 mr-1"></i>ការទូទាត់:</span>
                                    @if($hb->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 font-bold">
                                            <i class="fas fa-check-circle"></i> បង់រួច
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-bold">
                                            <i class="fas fa-clock"></i> រង់ចាំពិនិត្យ
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-baseline justify-between pt-1">
                                <span class="text-xs text-gray-400 font-medium">តម្លៃសរុប:</span>
                                <div class="text-right">
                                    <span class="font-black text-lg text-blue-600 dark:text-blue-400">
                                        ${{ number_format($hb->total_price, 2) }}
                                    </span>
                                    <span class="text-[11px] font-semibold text-gray-400 font-mono block">({{ number_format($hb->total_price * $khrRate) }} ៛)</span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center gap-2">
                            <a href="{{ route('receipt', $hb->booking_code) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white py-2.5 px-3 rounded-xl font-bold text-xs transition-all border border-blue-200 dark:border-blue-900/40">
                                <i class="fas fa-file-invoice"></i>
                                <span>វិក្កយបត្រ</span>
                            </a>

                            @if(in_array($hb->status, ['pending', 'confirmed']))
                            <form id="cancel-form-hotel-grid-{{ $hb->id }}" action="{{ route('bookings.cancel', $hb->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="button" onclick="confirmCancelBooking('cancel-form-hotel-grid-{{ $hb->id }}')"
                                    class="inline-flex items-center justify-center gap-1.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white py-2.5 px-3 rounded-xl font-bold text-xs transition-all border border-red-200 dark:border-red-900/40">
                                    <i class="fas fa-times-circle"></i>
                                    <span>បោះបង់</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($hotelBookings->hasPages())
                <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    {{ $hotelBookings->appends(request()->except('hotel_page'))->links() }}
                </div>
                @endif
            </div>

            @endif
        </div>

        {{-- MEETING ROOM BOOKINGS TAB --}}
        <div x-show="activeTab === 'meeting'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2">
            @if($meetingBookings->isEmpty())
            <div class="text-center py-16 max-w-xl mx-auto px-6">
                <div class="w-20 h-20 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-purple-100 dark:border-purple-900/30">
                    <i class="fas fa-calendar-times text-3xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">មិនទាន់មានប្រវត្តិកក់សាលប្រជុំនៅឡើយទេ</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">
                    លោកអ្នកមិនទាន់បានធ្វើការកក់សាលប្រជុំណាមួយក្នុងប្រព័ន្ធនៅឡើយទេ។
                </p>
                <a href="{{ route('frontend.meeting') }}"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-7 py-3.5 rounded-xl text-xs transition-all shadow-md shadow-blue-500/20 active:scale-95">
                <span>ស្វែងរកសាលប្រជុំ</span>
                </a>
            </div>
            @else

            {{-- 1. MEETING LIST VIEW --}}
            <div x-show="viewMode === 'list'" class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                        <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 uppercase text-[11px] font-black border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4">កូដកក់</th>
                                <th class="px-6 py-4">សាលប្រជុំ</th>
                                <th class="px-6 py-4">កាលបរិច្ឆេទ និងម៉ោង</th>
                                <th class="px-6 py-4">តម្លៃសរុប</th>
                                <th class="px-6 py-4">ការទូទាត់</th>
                                <th class="px-6 py-4">ស្ថានភាពកក់</th>
                                <th class="px-6 py-4 text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800 font-medium">
                            @foreach($meetingBookings as $mb)
                            <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-sm">
                                        {{ $mb->booking_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-gray-900 dark:text-white text-sm">
                                        {{ $mb->room_type_name ?? 'សាលប្រជុំ' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-0.5">
                                        <div class="font-bold text-gray-800 dark:text-gray-200">
                                            <i class="far fa-calendar-alt text-blue-500 mr-1"></i>{{ \Carbon\Carbon::parse($mb->start_date)->format('d/m/Y') }} ដល់ {{ \Carbon\Carbon::parse($mb->end_date)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-[11px] text-gray-400">
                                            <i class="far fa-clock mr-1"></i>({{ $mb->start_time }} - {{ $mb->end_time }})
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-black text-base text-gray-900 dark:text-white block">
                                        ${{ number_format($mb->total_price, 2) }}
                                    </span>
                                    <span class="text-xs font-semibold text-gray-400 font-mono block">({{ number_format($mb->total_price * $khrRate) }} ៛)</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($mb->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400 border border-green-200 dark:border-green-900/30">
                                            <i class="fas fa-check-circle text-xs"></i> បង់រួច
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200 dark:border-amber-900/30">
                                            <i class="fas fa-clock text-xs"></i> រង់ចាំពិនិត្យ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($mb->status === 'approved' || $mb->status === 'confirmed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400 border border-green-200 dark:border-green-900/30">
                                            <i class="fas fa-check-circle"></i> បានបញ្ជាក់
                                        </span>
                                    @elseif($mb->status === 'cancelled')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900/30">
                                            <i class="fas fa-times-circle"></i> បានបោះបង់
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-900/30">
                                            <i class="fas fa-spinner fa-spin"></i> កំពុងដំណើរការ
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('receipt', $mb->booking_code) }}"
                                            class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white px-3.5 py-2 rounded-xl font-bold text-xs transition-all border border-blue-200 dark:border-blue-900/40">
                                            <i class="fas fa-file-invoice"></i>
                                            <span>វិក្កយបត្រ</span>
                                        </a>

                                        @if(in_array($mb->status, ['pending', 'confirmed']))
                                        <form id="cancel-form-meeting-{{ $mb->id }}" action="{{ route('bookings.cancel', $mb->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="button" onclick="confirmCancelBooking('cancel-form-meeting-{{ $mb->id }}')"
                                                class="inline-flex items-center gap-1.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white px-3.5 py-2 rounded-xl font-bold text-xs transition-all border border-red-200 dark:border-red-900/40">
                                                <i class="fas fa-times-circle"></i>
                                                <span>បោះបង់</span>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($meetingBookings->hasPages())
                <div class="p-4 border-t border-gray-100 dark:border-gray-800">
                    {{ $meetingBookings->appends(request()->except('meeting_page'))->links() }}
                </div>
                @endif
            </div>

            {{-- 2. MEETING GRID VIEW --}}
            <div x-show="viewMode === 'grid'" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($meetingBookings as $mb)
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-all flex flex-col justify-between space-y-4">
                        {{-- Top Header --}}
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-sm font-bold">
                                    <i class="fas fa-users-rectangle"></i>
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 uppercase font-bold block">កូដកក់</span>
                                    <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-sm">
                                        {{ $mb->booking_code }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                @if($mb->status === 'approved' || $mb->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400 border border-green-200 dark:border-green-900/30">
                                        <i class="fas fa-check-circle"></i> បានបញ្ជាក់
                                    </span>
                                @elseif($mb->status === 'cancelled')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900/30">
                                        <i class="fas fa-times-circle"></i> បានបោះបង់
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400 border border-blue-200 dark:border-blue-900/30">
                                        <i class="fas fa-spinner fa-spin"></i> កំពុងដំណើរការ
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Details --}}
                        <div class="space-y-3">
                            <div>
                                <h4 class="font-extrabold text-gray-900 dark:text-white text-base">
                                    {{ $mb->room_type_name ?? 'សាលប្រជុំ' }}
                                </h4>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-xl space-y-2 text-xs">
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400"><i class="far fa-calendar-alt text-blue-500 mr-1"></i>កាលបរិច្ឆេទ:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($mb->start_date)->format('d/m/Y') }} ដល់ {{ \Carbon\Carbon::parse($mb->end_date)->format('d/m/Y') }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400"><i class="far fa-clock text-amber-500 mr-1"></i>ម៉ោង:</span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">
                                        {{ $mb->start_time }} - {{ $mb->end_time }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between text-gray-600 dark:text-gray-300">
                                    <span class="text-gray-400"><i class="fas fa-credit-card text-emerald-500 mr-1"></i>ការទូទាត់:</span>
                                    @if($mb->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 font-bold">
                                            <i class="fas fa-check-circle"></i> បង់រួច
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-bold">
                                            <i class="fas fa-clock"></i> រង់ចាំពិនិត្យ
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-baseline justify-between pt-1">
                                <span class="text-xs text-gray-400 font-medium">តម្លៃសរុប:</span>
                                <div class="text-right">
                                    <span class="font-black text-lg text-blue-600 dark:text-blue-400">
                                        ${{ number_format($mb->total_price, 2) }}
                                    </span>
                                    <span class="text-[11px] font-semibold text-gray-400 font-mono block">({{ number_format($mb->total_price * $khrRate) }} ៛)</span>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center gap-2">
                            <a href="{{ route('receipt', $mb->booking_code) }}"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white py-2.5 px-3 rounded-xl font-bold text-xs transition-all border border-blue-200 dark:border-blue-900/40">
                                <i class="fas fa-file-invoice"></i>
                                <span>វិក្កយបត្រ</span>
                            </a>

                            @if(in_array($mb->status, ['pending', 'confirmed']))
                            <form id="cancel-form-meeting-grid-{{ $mb->id }}" action="{{ route('bookings.cancel', $mb->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="button" onclick="confirmCancelBooking('cancel-form-meeting-grid-{{ $mb->id }}')"
                                    class="inline-flex items-center justify-center gap-1.5 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white dark:hover:bg-red-600 dark:hover:text-white py-2.5 px-3 rounded-xl font-bold text-xs transition-all border border-red-200 dark:border-red-900/40">
                                    <i class="fas fa-times-circle"></i>
                                    <span>បោះបង់</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($meetingBookings->hasPages())
                <div class="p-4 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                    {{ $meetingBookings->appends(request()->except('meeting_page'))->links() }}
                </div>
                @endif
            </div>

            @endif
        </div>

    </div>
</div>

<script>
    function confirmCancelBooking(formId) {
        Swal.fire({
            title: 'បញ្ជាក់ការបោះបង់?',
            text: 'តើលោកអ្នកពិតជាចង់បោះបង់ការកក់នេះមែនទេ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'បាទ/ចាស, បោះបង់!',
            cancelButtonText: 'បោះបង់',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs',
                cancelButton: 'rounded-xl font-bold px-4 py-2 text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>

@endsection