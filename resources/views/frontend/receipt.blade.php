@extends('layouts.app')
@section('title', 'វិក្កយបត្រ #' . $booking->booking_code)
@section('content')

<div class="container mx-auto py-10 px-4">

    <div class="flex justify-center gap-4 mb-8 no-print">
        <a href="{{ route('mybookings') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all flex items-center gap-2">
            ត្រឡប់ក្រោយ
        </a>
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
            ព្រីនវិក្កយបត្រ
        </button>
    </div>

    <div class="pos-receipt bg-white text-black p-6 mx-auto border border-gray-200 shadow-sm font-mono text-sm max-w-[380px]">

        <div class="text-center space-y-1 mb-4">
            <div class="text-xl font-black uppercase">សណ្ឋាគារ ភីអេនធី ផាលេស</div>
            <div class="text-[11px] text-gray-650">ត្បូងឃ្មុំ, កម្ពុជា</div>
            <div class="text-[11px] text-gray-650">Tel: 0964301974</div>
        </div>

        <div class="border-b border-dashed border-gray-400 my-3"></div>

        <div class="space-y-1 text-xs">
            <div class="flex justify-between">
                <span>កូដយោង :</span>
                <span class="font-bold">{{ $booking->booking_code }}</span>
            </div>
            <div class="flex justify-between">
                <span>កាលបរិច្ឆេទ :</span>
                <span>{{ date('d-M-Y H:i A', strtotime($booking->created_at ?? now())) }}</span>
            </div>
            <div class="flex justify-between">
                <span>អតិថិជន :</span>
                <span class="font-bold">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="border-b border-dashed border-gray-400 my-3"></div>

        <div class="text-xs space-y-3">
            <div class="grid grid-cols-12 font-bold uppercase border-b border-gray-200 pb-1">
                <div class="col-span-7">ពិពណ៌នា</div>
                <div class="col-span-5 text-right">សរុប</div>
            </div>

            <div class="grid grid-cols-12 items-start">
                <div class="col-span-7 space-y-0.5">
                    <div class="text-[11px] text-gray-600">({{ $details->type_name ?? 'N/A' }})</div>

                    <div class="text-[10px] text-gray-500 italic bg-gray-50 p-1 rounded mt-1">
                        @if($type === 'hotel')
                        <span>In: {{ $booking->check_in }}<br>Out: {{ $booking->check_out }}</span>
                        @else
                        <span>Date: {{ $booking->start_date }}<br>Time: {{ $booking->start_time }} - {{ $booking->end_time }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-span-5 text-right font-bold text-sm pt-0.5">
                    ${{ number_format($booking->total_price, 2) }}
                </div>
            </div>
        </div>

        <div class="border-b border-dashed border-gray-400 my-4"></div>

        <div class="space-y-1.5">
            <div class="flex justify-between text-xs">
                <span>សរុបទឹកប្រាក់ :</span>
                <span>${{ number_format($booking->total_price, 2) }}</span>
            </div>
            <div class="flex justify-between text-xs">
                <span>បញ្ចុះតម្លៃ :</span>
                <span>$0.00</span>
            </div>
            <div class="flex justify-between font-black text-xs pt-1 border-t border-gray-200">
                <span>សរុបត្រូវទូទាត់ :</span>
                <span>${{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>

        <div class="border-b border-dashed border-gray-400 my-4"></div>

        <div class="text-center space-y-2 py-2 bg-gray-50 border border-gray-200 rounded-xl">
            <div class="text-[11px] font-black text-blue-700">
                គណនីវេរប្រាក់
            </div>

            <div class="w-28 h-28 bg-white p-1 mx-auto border border-gray-200 rounded-lg flex items-center justify-center shadow-inner">
                <img src="{{ asset('images/qr/ac.jpg') }}" alt="QRcode" class="w-full h-full object-contain">
            </div>
        </div>

        <div class="border-b border-dashed border-gray-400 my-4"></div>

        <div class="text-center space-y-2">
            <div class="text-xs font-bold uppercase">
                ស្ថានភាព៖
                <span class="{{ $payment && $payment->status === 'paid' ? 'text-green-600' : 'text-amber-600' }}">
                    {{ $payment && $payment->status === 'paid' ? 'បង់ប្រាក់រួច' : 'រង់ចាំពិនិត្យ' }}
                </span>
            </div>

            <div class="text-[10px] text-gray-500 italic mt-4 space-y-1">
                <div>Thank you for choosing our services!</div>
            </div>
        </div>

    </div>
</div>

<style>
    /* កំណត់ទម្រង់ពុម្ពអក្សរស្រដៀងម៉ាស៊ីនគិតលុយសម្រាប់ទំព័រទាំងមូល */
    .pos-receipt {
        font-family: 'Courier New', Courier, monospace, 'Khmer OS Battambang';
    }

    @media print {

        .no-print,
        header,
        footer,
        nav,
        .sidebar {
            display: none !important;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            background: white !important;
            color: black !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .container {
            max-w: 100% !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .pos-receipt {
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            max-w: 100% !important;
            padding: 8px !important;
            margin: 0 !important;
            height: auto !important;
        }

        .bg-gray-50 {
            background-color: #f9fafb !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

@endsection