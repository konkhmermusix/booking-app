@extends('layouts.app')
@section('title', 'វិក្កយបត្រ #' . ($primaryCode ?? $booking->booking_code) . ' | សណ្ឋាគារ ភីអេនធី ផាលេស')
@section('content')

@php
    $hotelAddress = \App\Models\ContactSetting::where('key', 'address')->where('status', 1)->value('value') ?? 'ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្តត្បូងឃ្មុំ (ខាងកើតរង្វង់មូល ប្រាំមួយមករា)';
    $hotelPhone   = \App\Models\ContactSetting::where('key', 'phone')->where('status', 1)->value('value') ?? '096 711 9798 / 071 4 711 979';
    $hotelEmail   = \App\Models\ContactSetting::where('key', 'email')->where('status', 1)->value('value') ?? 'info@pnt-hotel.com';
    $khrRate      = \App\Models\ContactSetting::getExchangeRate();

    $bookingUser = null;
    if (!empty($booking->user_id)) {
        $bookingUser = \App\Models\User::find($booking->user_id);
    }

    $cName  = $customerName ?? (!empty($booking->customer_name) ? $booking->customer_name : ($bookingUser->name ?? (Auth::check() ? Auth::user()->name : 'ភ្ញៀវស្នាក់នៅ')));
    $cPhone = $customerPhone ?? (!empty($booking->customer_phone) ? $booking->customer_phone : ($bookingUser->phone ?? (Auth::check() ? Auth::user()->phone : 'N/A')));
    $cEmail = $customerEmail ?? (!empty($booking->customer_email) ? $booking->customer_email : ($bookingUser->email ?? (Auth::check() ? Auth::user()->email : 'N/A')));
    $displayCode = $primaryCode ?? ($booking->booking_code ?? 'PNT-RECEIPT');
    $items = isset($allReceiptItems) && count($allReceiptItems) > 0 ? $allReceiptItems : (isset($allDetails) && count($allDetails) > 0 ? $allDetails : collect([$details]));
    $totalAmount = isset($grandTotal) && $grandTotal > 0 ? $grandTotal : ($booking->total_price ?? 0);
@endphp

<div class="w-full bg-gray-100 dark:bg-[#0b1120] min-h-screen py-8 transition-colors duration-300">
    <div class="container mx-auto px-4 max-w-3xl">

        {{-- TOP ACTION BAR --}}
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <a href="{{ route('mybookings') }}"
                class="inline-flex items-center gap-2 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-200 px-4 py-2.5 rounded-xl font-bold text-xs shadow-xs border border-gray-200 dark:border-gray-800 transition-all active:scale-95">
                <i class="fas fa-arrow-left text-xs"></i>
                <span>ត្រឡប់ទៅប្រវត្តិកក់</span>
            </a>

            @if(Auth::check() && isset($booking->id) && in_array($booking->status ?? '', ['pending', 'confirmed', 'approved']))
            <form id="cancel-form-receipt" action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="inline-block">
                @csrf
                <button type="button" onclick="confirmCancelReceipt('cancel-form-receipt')"
                    class="inline-flex items-center gap-2 bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white px-4 py-2.5 rounded-xl font-bold text-xs border border-red-200 dark:border-red-900/40 transition-all active:scale-95 cursor-pointer">
                    <i class="fas fa-times-circle text-xs"></i>
                    <span>បោះបង់</span>
                </button>
            </form>
            @endif
        </div>

        {{-- INVOICE/RECEIPT CARD --}}
        <div id="receipt-card" class="invoice-card bg-white dark:bg-gray-900 rounded-xl shadow-lg border border-gray-200 dark:border-gray-800 p-8 md:p-10 transition-colors duration-300 text-gray-800 dark:text-gray-200">

            {{-- HEADER --}}
            <div class="border-b border-gray-200 dark:border-gray-800 pb-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-16 h-16 shrink-0">
                            <img src="{{ asset('images/logo/P&t Palace Hotel.png') }}" alt="PNT Palace Hotel Logo" class="w-full h-full object-contain">
                        </div>
                        <div>
                            <h1 class="text-xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tight">សណ្ឋាគារ ភីអេនធី ផាលេស</h1>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">PNT PALACE HOTEL & RESORT</p>
                        </div>
                    </div>

                    <div class="sm:text-right">
                        <h2 class="text-lg font-black text-blue-600 dark:text-blue-400 uppercase tracking-wide">វិក្កយបត្រ / RECEIPT</h2>
                        <p class="text-xs font-mono font-bold text-gray-700 dark:text-gray-300 mt-0.5">លេខ ៖ #{{ $displayCode }}</p>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">
                            កាលបរិច្ឆេទ ៖ <span class="font-medium text-gray-700 dark:text-gray-300">{{ date('d/m/Y h:i A', strtotime($booking->created_at ?? now())) }}</span>
                        </p>
                    </div>
                </div>

                {{-- HOTEL CONTACT INFO --}}
                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800/60 flex flex-wrap items-center justify-between text-xs text-gray-500 dark:text-gray-400 gap-2">
                    <div><i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> {{ $hotelAddress }}</div>
                    <div class="flex items-center gap-4">
                        <span><i class="fas fa-phone text-blue-500 mr-1"></i> {{ $hotelPhone }}</span>
                        <span><i class="fas fa-envelope text-blue-500 mr-1"></i> {{ $hotelEmail }}</span>
                    </div>
                </div>
            </div>

            {{-- INFORMATION GRID: CUSTOMER & BOOKING --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6 p-4 bg-gray-50 dark:bg-gray-800/40 rounded-xl border border-gray-100 dark:border-gray-800 text-xs">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white uppercase text-[11px] tracking-wider mb-2 text-blue-600 dark:text-blue-400">
                        ព័ត៌មានអតិថិជន (Customer Information)
                    </h3>
                    <div class="space-y-1 text-gray-700 dark:text-gray-300">
                        <p><span class="text-gray-500 dark:text-gray-400">ឈ្មោះ ៖</span> <strong class="text-gray-900 dark:text-white">{{ $cName }}</strong></p>
                        <p><span class="text-gray-500 dark:text-gray-400">ទូរស័ព្ទ ៖</span> {{ $cPhone }}</p>
                        <p><span class="text-gray-500 dark:text-gray-400">អ៊ីមែល ៖</span> {{ $cEmail }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white uppercase text-[11px] tracking-wider mb-2 text-blue-600 dark:text-blue-400">
                        ព័ត៌មានការកក់
                    </h3>
                    <div class="space-y-1 text-gray-700 dark:text-gray-300">
                        <p><span class="text-gray-500 dark:text-gray-400">ប្រភេទ ៖</span>
                            <strong>{{ $type === 'combined' ? 'បន្ទប់ស្នាក់នៅ និងសាលប្រជុំ' : ($type === 'hotel' ? 'បន្ទប់សណ្ឋាគារ' : 'សាលប្រជុំ') }}</strong>
                        </p>
                        <p><span class="text-gray-500 dark:text-gray-400">វិធីសាស្ត្រទូទាត់ ៖</span>
                            {{ isset($payment->method) && $payment->method === 'qr' ? 'ស្កែនឃ្យូអរកូដ' : 'ទូទាត់សាច់ប្រាក់នៅសណ្ឋាគារ' }}
                        </p>
                        <p><span class="text-gray-500 dark:text-gray-400">ស្ថានភាពការទូទាត់ ៖</span>
                            @if($payment && ($payment->status === 'paid' || (isset($payment->payment_status) && $payment->payment_status === 'paid')))
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">បានទូទាត់រួច</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="font-bold text-red-600 dark:text-red-400">បានបោះបង់</span>
                            @else
                                <span class="font-bold text-amber-600 dark:text-amber-400">រង់ចាំការផ្ទៀងផ្ទាត់</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- ITEMS TABLE --}}
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-bold uppercase text-[11px] border-y border-gray-200 dark:border-gray-700">
                            <th class="py-3 px-3 w-10 text-center">ល.រ</th>
                            <th class="py-3 px-3">ប្រភេទ/បរិយាយ</th>
                            <th class="py-3 px-3">កាលបរិច្ឆេទ</th>
                            <th class="py-3 px-3 text-right">តម្លៃសរុប</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @foreach($items as $index => $item)
                        <tr>
                            <td class="py-3.5 px-3 text-center text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-3">
                                <div class="font-bold text-gray-900 dark:text-white text-sm">
                                    {{ $item->type_name ?? (($item->item_type ?? $type) === 'hotel' ? 'បន្ទប់ស្នាក់នៅ' : 'សាលប្រជុំ') }}
                                </div>
                            </td>
                            <td class="py-3.5 px-3 text-gray-700 dark:text-gray-300">
                                @if(($item->item_type ?? $type) === 'hotel')
                                    <span>{{ \Carbon\Carbon::parse($item->check_in ?? $booking->check_in)->format('d/m/Y') }} ដល់ {{ \Carbon\Carbon::parse($item->check_out ?? $booking->check_out)->format('d/m/Y') }}</span>
                                @else
                                    <span>{{ \Carbon\Carbon::parse($item->start_date ?? $booking->start_date)->format('d/m/Y') }} ({{ $item->start_time ?? $booking->start_time }} - {{ $item->end_time ?? $booking->end_time }})</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-3 text-right font-bold text-sm text-gray-900 dark:text-white font-mono">
                                ${{ number_format($item->price ?? ($totalAmount / count($items)), 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- SUMMARY --}}
            <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-t border-gray-200 dark:border-gray-800 pt-5">
                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                    <p class="font-bold text-gray-700 dark:text-gray-300 uppercase">ចំណាំ ៖</p>
                    <p>• សូមរក្សាទុកវិក្កយបត្រនេះសម្រាប់បង្ហាញជូនបុគ្គលិកពេលចូលស្នាក់នៅ។</p>
                    <p>• អត្រាប្តូរប្រាក់ ៖ ១ ដុល្លារ = {{ number_format($khrRate) }} រៀល</p>
                </div>

                <div class="w-full sm:w-64 space-y-2 text-xs">
                    <div class="flex justify-between text-gray-600 dark:text-gray-400">
                        <span>សរុបទឹកប្រាក់ ៖</span>
                        <span class="font-bold text-gray-900 dark:text-white font-mono">${{ number_format($totalAmount, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-2 flex justify-between items-baseline">
                        <span class="font-extrabold text-gray-900 dark:text-white text-sm">ទឹកប្រាក់សរុប ៖</span>
                        <div class="text-right">
                            <span class="text-xl font-black text-blue-600 dark:text-blue-400 font-mono block">${{ number_format($totalAmount, 2) }}</span>
                            <span class="text-[11px] text-gray-500 dark:text-gray-400 font-mono font-semibold">(~ {{ number_format($totalAmount * $khrRate) }} ៛)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800/80 text-center text-xs text-gray-500 dark:text-gray-400">
                <p class="font-bold text-gray-700 dark:text-gray-300">សូមអរគុណសម្រាប់ការជ្រើសរើស សណ្ឋាគារ ភីអេនធី ផាលេស !</p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">Thank you for choosing PNT Palace Hotel & Resort</p>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmCancelReceipt(formId) {
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