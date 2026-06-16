@extends('layouts.app')
@section('title', 'ការកក់របស់ខ្ញុំ')
@section('content')

<div class="container mx-auto" x-data="{ activeTab: 'hotel' }">
    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-10">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                    <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                        ការកក់របស់ខ្ញុំ
                    </h4>
                </div>
            </div>

            <div class="flex gap-3 bg-gray-100 dark:bg-gray-950 p-1.5 rounded-2xl w-full sm:w-fit mb-8 border border-gray-200/50 dark:border-gray-800/50">
                <button @click="activeTab = 'hotel'"
                    :class="activeTab === 'hotel' ? 'bg-white dark:bg-gray-800 text-blue-600 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                    class="flex items-center justify-center gap-2 py-3 px-6 rounded-xl text-sm transition-all duration-200 w-full sm:w-auto">
                    បន្ទប់សណ្ឋាគារ ({{ $hotelBookings->count() }})
                </button>
                <button @click="activeTab = 'meeting'"
                    :class="activeTab === 'meeting' ? 'bg-white dark:bg-gray-800 text-blue-600 shadow-sm font-bold' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300'"
                    class="flex items-center justify-center gap-2 py-3 px-6 rounded-xl text-sm transition-all duration-200 w-full sm:w-auto">
                    សាលប្រជុំ ({{ $meetingBookings->count() }})
                </button>
            </div>

            <div x-show="activeTab === 'hotel'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                @if($hotelBookings->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-850 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-calendar-times text-xl"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">អ្នកមិនទាន់មានប្រវត្តិកក់បន្ទប់សណ្ឋាគារនៅឡើយទេ។</p>
                </div>
                @else
                <div class="overflow-hidden bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 dark:bg-gray-850 text-gray-700 dark:text-gray-300 uppercase text-xs border-b border-gray-100 dark:border-gray-800">
                                <tr>
                                    <th class="px-6 py-4 font-bold">កូដកក់</th>
                                    <th class="px-6 py-4 font-bold">បន្ទប់</th>
                                    <th class="px-6 py-4 font-bold">ថ្ងៃចូល - ថ្ងៃចេញ</th>
                                    <th class="px-6 py-4 font-bold">តម្លៃសរុប</th>
                                    <th class="px-6 py-4 font-bold">ការទូទាត់</th>
                                    <th class="px-6 py-4 font-bold">ស្ថានភាពកក់</th>
                                    <th class="px-6 py-4 font-bold text-center">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($hotelBookings as $hb)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-850/50 transition">
                                    <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400">{{ $hb->booking_code }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                        <div class="text-gray-400 font-medium">{{ $hb->room_type_name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium">{{ $hb->check_in }} ដល់ {{ $hb->check_out }}</td>
                                    <td class="px-6 py-4 font-bold text-red-600 dark:text-red-400">${{ number_format($hb->total_price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-xl 
                                    {{ $hb->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/40 dark:text-yellow-400' }}">
                                            <i class="fas {{ $hb->payment_status === 'paid' ? 'fa-check-circle' : 'fa-clock' }} mr-1 text-[10px]"></i>
                                            {{ $hb->payment_status === 'paid' ? 'បង់រួច' : 'រង់ចាំពិនិត្យ' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-xl 
                                    {{ $hb->status === 'approved' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400' : ($hb->status === 'cancelled' ? 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400' : 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-400') }}">
                                            {{ ucfirst($hb->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('receipt', $hb->booking_code) }}" class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white px-4 py-2 rounded-xl font-bold text-xs transition-all">
                                            <i class="fas fa-file-invoice-dollar text-[11px]"></i> វិក្កយបត្រ
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <div x-show="activeTab === 'meeting'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
                @if($meetingBookings->isEmpty())
                <div class="text-center py-16 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-850 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-calendar-times text-xl"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">អ្នកមិនទាន់មានប្រវត្តិកក់សាលប្រជុំនៅឡើយទេ។</p>
                </div>
                @else
                <div class="overflow-hidden bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                            <thead class="bg-gray-50 dark:bg-gray-850 text-gray-700 dark:text-gray-300 uppercase text-xs border-b border-gray-100 dark:border-gray-800">
                                <tr>
                                    <th class="px-6 py-4 font-bold">កូដកក់</th>
                                    <th class="px-6 py-4 font-bold">សាលប្រជុំ</th>
                                    <th class="px-6 py-4 font-bold">កាលបរិច្ឆេទ និងម៉ោង</th>
                                    <th class="px-6 py-4 font-bold">តម្លៃសរុប</th>
                                    <th class="px-6 py-4 font-bold">ការទូទាត់</th>
                                    <th class="px-6 py-4 font-bold">ស្ថានភាពកក់</th>
                                    <th class="px-6 py-4 font-bold text-center">សកម្មភាព</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @foreach($meetingBookings as $mb)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-850/50 transition">
                                    <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400">{{ $mb->booking_code }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                        <div class="text-gray-400 font-medium">{{ $mb->room_type_name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium">
                                        <div class="font-bold text-gray-700 dark:text-gray-300">{{ $mb->start_date }} ដល់ {{ $mb->end_date }}</div>
                                        <div class="text-gray-400 mt-0.5"><i class="far fa-clock mr-1 text-[10px]"></i>({{ $mb->start_time }} - {{ $mb->end_time }})</div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-red-600 dark:text-red-400">${{ number_format($mb->total_price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-xl 
                                    {{ $mb->payment_status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-950/40 dark:text-green-400' : 'bg-yellow-50 text-yellow-700 dark:bg-yellow-950/40 dark:text-yellow-400' }}">
                                            <i class="fas {{ $mb->payment_status === 'paid' ? 'fa-check-circle' : 'fa-clock' }} mr-1 text-[10px]"></i>
                                            {{ $mb->payment_status === 'paid' ? 'បង់រួច' : 'រង់ចាំពិនិត្យ' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1.5 text-xs font-bold rounded-xl 
                                    {{ $mb->status === 'approved' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-400' : ($mb->status === 'cancelled' ? 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-400' : 'bg-gray-50 text-gray-700 dark:bg-gray-800 dark:text-gray-400') }}">
                                            {{ ucfirst($mb->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('receipt', $mb->booking_code) }}" class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white px-4 py-2 rounded-xl font-bold text-xs transition-all">
                                            <i class="fas fa-file-invoice-dollar text-[11px]"></i> វិក្កយបត្រ
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </section>
</div>
@endsection