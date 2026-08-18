@extends('layouts.admin')
@section('title', 'ព័ត៌មានលម្អិតនៃការកក់')

@section('content')
<div class="p-2 sm:p-2 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold dark:text-white">ព័ត៌មានលម្អិតនៃការកក់៖ {{ $booking->booking_code }}</h2>
            <p class="text-xs text-gray-400">Created on {{ $booking->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bookings.index') }}" class="h-10 px-4 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-750 text-gray-600 dark:text-gray-300 rounded-xl text-sm font-bold flex items-center gap-2 transition-all">
                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
            </a>
            <a href="{{ route('bookings.edit', $booking->id) }}" class="h-10 px-4 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-sm font-bold flex items-center gap-2 transition-all">
                <i class="fas fa-edit"></i> កែសម្រួល
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Details Card -->
        <div class="md:col-span-2 space-y-6">
            <!-- Reservation Info Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 border-b border-gray-100 dark:border-gray-800 pb-3 mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i> ព័ត៌មានការកក់បន្ទប់
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 block text-xs">ថ្ងៃចូលស្នាក់នៅ (Check-In)</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $booking->check_in_date->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">ថ្ងៃចាកចេញ (Check-Out)</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $booking->check_out_date->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">រយៈពេលស្នាក់នៅ</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">
                            @php
                                $days = $booking->check_in_date->diffInDays($booking->check_out_date);
                                echo $days . ' យប់';
                            @endphp
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">ចំនួនភ្ញៀវ</span>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $booking->number_of_guests }} នាក់</span>
                    </div>
                </div>

                @if($booking->notes)
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                    <span class="text-gray-400 block text-xs mb-1">កំណត់ចំណាំបន្ថែម៖</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-850 p-3 rounded-xl">
                        {{ $booking->notes }}
                    </p>
                </div>
                @endif
            </div>

            <!-- Customer & Room Info Card -->
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Customer Details -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">
                            <i class="fas fa-user text-blue-500 mr-2"></i> ព័ត៌មានអតិថិជន
                        </h4>
                        @if($booking->customer)
                        <div class="space-y-2 text-sm">
                            <div class="font-bold text-gray-800 dark:text-white">{{ $booking->customer->name }}</div>
                            <div class="text-xs text-gray-400"><i class="far fa-envelope mr-1.5"></i> {{ $booking->customer->email }}</div>
                            @if($booking->customer->phone)
                            <div class="text-xs text-gray-400"><i class="fas fa-phone mr-1.5"></i> {{ $booking->customer->phone }}</div>
                            @endif
                        </div>
                        @else
                        <p class="text-xs text-gray-400">គ្មានព័ត៌មានអតិថិជនឡើយ</p>
                        @endif
                    </div>

                    <!-- Room Details -->
                    <div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 border-b border-gray-100 dark:border-gray-800 pb-2 mb-3">
                            <i class="fas fa-door-open text-blue-500 mr-2"></i> ព័ត៌មានបន្ទប់
                        </h4>
                        @if($booking->room)
                        <div class="space-y-2 text-sm">
                            <div class="font-bold text-gray-800 dark:text-white">បន្ទប់ {{ $booking->room->room_number }}</div>
                            <div class="text-xs text-gray-400"><i class="fas fa-bed mr-1.5"></i> {{ $booking->room->roomType->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-400"><i class="fas fa-building mr-1.5"></i> ជាន់ទី {{ $booking->room->floor ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-450 font-bold text-blue-600 dark:text-blue-400"><i class="fas fa-hotel mr-1.5"></i> {{ $booking->room->hotel->name ?? 'N/A' }}</div>
                        </div>
                        @else
                        <p class="text-xs text-gray-400">គ្មានព័ត៌មានបន្ទប់ឡើយ</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Summary Card -->
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 text-center">
                <span class="text-gray-400 block text-xs mb-1 uppercase tracking-widest font-bold">តម្លៃសរុប</span>
                <h2 class="text-3xl font-black text-gray-850 dark:text-white mb-4">
                    ${{ number_format($booking->total_price, 2) }}
                </h2>

                <div class="space-y-3 pt-4 border-t border-gray-100 dark:border-gray-800 text-left text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">ស្ថានភាពការកក់៖</span>
                        @if($booking->booking_status === 'confirmed')
                        <span class="px-2.5 py-0.5 rounded-xl text-xs font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400">
                            បានបញ្ជាក់
                        </span>
                        @elseif($booking->booking_status === 'completed')
                        <span class="px-2.5 py-0.5 rounded-xl text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                            រួចរាល់
                        </span>
                        @elseif($booking->booking_status === 'cancelled')
                        <span class="px-2.5 py-0.5 rounded-xl text-xs font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400">
                            បានបោះបង់
                        </span>
                        @else
                        <span class="px-2.5 py-0.5 rounded-xl text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                            រង់ចាំពិនិត្យ
                        </span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">ការបង់ប្រាក់៖</span>
                        @if($booking->payment_status === 'paid')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span> បានបង់រួច
                        </span>
                        @elseif($booking->payment_status === 'failed')
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400">
                            <span class="w-1 h-1 rounded-full bg-rose-500"></span> បរាជ័យ
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                            <span class="w-1 h-1 rounded-full bg-amber-500"></span> រង់ចាំ
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
