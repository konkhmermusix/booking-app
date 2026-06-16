@extends('layouts.app')
@section('title', 'ការកក់របស់ខ្ញុំ')
@section('content')

<div class="container mx-auto my-10 p-5 max-w-5xl" x-data="{ activeTab: 'hotel' }">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">🗓️ ការកក់របស់ខ្ញុំ (My Bookings)</h1>

    <!-- ប៊ូតុងប្តូរ Tab -->
    <div class="flex border-b border-gray-200 mb-6">
        <button
            @click="activeTab = 'hotel'"
            :class="activeTab === 'hotel' ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'"
            class="py-3 px-6 border-b-2 font-medium text-sm focus:outline-none transition">
            🏨 បន្ទប់សណ្ឋាគារ ({{ $hotelBookings->count() }})
        </button>
        <button
            @click="activeTab = 'meeting'"
            :class="activeTab === 'meeting' ? 'border-blue-500 text-blue-600 font-semibold' : 'border-transparent text-gray-500 hover:text-gray-700'"
            class="py-3 px-6 border-b-2 font-medium text-sm focus:outline-none transition">
            🏢 សាលប្រជុំ ({{ $meetingBookings->count() }})
        </button>
    </div>

    <!-- ផ្នែកបង្ហាញទិន្នន័យ Tab: សណ្ឋាគារ -->
    <div x-show="activeTab === 'hotel'" class="space-y-4">
        @if($hotelBookings->isEmpty())
        <div class="text-center py-10 bg-white rounded-lg shadow border">
            <p class="text-gray-500">អ្នកមិនទាន់មានប្រវត្តិកក់បន្ទប់សណ្ឋាគារនៅឡើយទេ។</p>
        </div>
        @else
        <div class="overflow-x-auto bg-white rounded-lg shadow border">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
                    <tr>
                        <th class="px-6 py-4">កូដកក់</th>
                        <th class="px-6 py-4">លេខបន្ទប់</th>
                        <th class="px-6 py-4">ថ្ងៃចូល - ថ្ងៃចេញ</th>
                        <th class="px-6 py-4">តម្លៃសរុប</th>
                        <th class="px-6 py-4">ការទូទាត់</th>
                        <th class="px-6 py-4">ស្ថានភាពកក់</th>
                        <th class="px-6 py-4 text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($hotelBookings as $hb)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-blue-600">{{ $hb->booking_code }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">បន្ទប់លេខ #{{ $hb->room_number ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-xs">{{ $hb->check_in }} ដល់ {{ $hb->check_out }}</td>
                        <td class="px-6 py-4 font-semibold text-red-600">${{ number_format($hb->total_price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $hb->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $hb->payment_status === 'paid' ? 'បង់រួច' : 'រង់ចាំពិនិត្យ' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $hb->status === 'approved' ? 'bg-blue-100 text-blue-800' : ($hb->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $hb->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('booking.receipt', $hb->booking_code) }}" class="text-blue-600 hover:underline font-medium">👁️ មើលវិក្កយបត្រ</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <!-- ផ្នែកបង្ហាញទិន្នន័យ Tab: សាលប្រជុំ -->
    <div x-show="activeTab === 'meeting'" class="space-y-4" x-cloak>
        @if($meetingBookings->isEmpty())
        <div class="text-center py-10 bg-white rounded-lg shadow border">
            <p class="text-gray-500">អ្នកមិនទាន់មានប្រវត្តិកក់សាលប្រជុំនៅឡើយទេ។</p>
        </div>
        @else
        <div class="overflow-x-auto bg-white rounded-lg shadow border">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="bg-gray-50 text-gray-700 uppercase text-xs border-b">
                    <tr>
                        <th class="px-6 py-4">កូដកក់</th>
                        <th class="px-6 py-4">ឈ្មោះសាល</th>
                        <th class="px-6 py-4">កាលបរិច្ឆេទ និងម៉ោង</th>
                        <th class="px-6 py-4">តម្លៃសរុប</th>
                        <th class="px-6 py-4">ការទូទាត់</th>
                        <th class="px-6 py-4">ស្ថានភាពកក់</th>
                        <th class="px-6 py-4 text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($meetingBookings as $mb)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-semibold text-blue-600">{{ $mb->booking_code }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $mb->room_name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-xs">
                            <div>{{ $mb->start_date }} ដល់ {{ $mb->end_date }}</div>
                            <div class="text-gray-400 mt-0.5">({{ $mb->start_time }} - {{ $mb->end_time }})</div>
                        </td>
                        <td class="px-6 py-4 font-semibold text-red-600">${{ number_format($mb->total_price, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $mb->payment_status === 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $mb->payment_status === 'paid' ? 'បង់រួច' : 'រង់ចាំពិនិត្យ' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $mb->status === 'approved' ? 'bg-blue-100 text-blue-800' : ($mb->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $mb->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('booking.receipt', $mb->booking_code) }}" class="text-blue-600 hover:underline font-medium">👁️ មើលវិក្កយបត្រ</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection