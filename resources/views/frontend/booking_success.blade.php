@extends('layouts.app')
@section('title', 'ការកក់ទទួលបានជោគជ័យ | សណ្ឋាគារ ភីអេនធី ផាលេស')
@section('content')

@php
    $khrRate = \App\Models\ContactSetting::getExchangeRate(4050);
    $cName = $customerName ?? (Auth::check() ? Auth::user()->name : ($booking->customer_name ?? 'ភ្ញៀវស្នាក់នៅ'));
    $cPhone = $customerPhone ?? (Auth::check() ? Auth::user()->phone : ($booking->customer_phone ?? 'N/A'));
    $cEmail = $customerEmail ?? (Auth::check() ? Auth::user()->email : ($booking->customer_email ?? 'N/A'));
    $displayTotal = isset($grandTotal) && $grandTotal > 0 ? $grandTotal : ($booking->total_price ?? 0);
    $displayCodes = isset($codes) && count($codes) > 0 ? $codes : [$booking->booking_code ?? 'PNT-SUCCESS'];
@endphp

<div class="w-full bg-gray-50 dark:bg-[#0b1120] min-h-screen py-5 md:py-16 transition-colors duration-300">
    <div class="container mx-auto px-4 max-w-3xl">

        {{-- MAIN SUCCESS CARD --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300">
            
            {{-- TOP HERO BANNER WITH GRADIENT --}}
            <div class="relative bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white p-3 md:p-5 text-center overflow-hidden">
                <div class="absolute -top-12 -right-12 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-40 h-40 bg-blue-400/20 rounded-full blur-2xl pointer-events-none"></div>

                {{-- ANIMATED CHECK ICON --}}
                <div class="relative w-20 h-20 bg-white/15 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20 shadow-lg shrink-0">
                    <div class="w-16 h-16 bg-white text-blue-600 rounded-xl flex items-center justify-center text-3xl shadow-md">
                        <i class="fas fa-check-circle animate-pulse"></i>
                    </div>
                </div>

                <h1 class="text-2xl md:text-3xl font-black mb-2 tracking-tight drop-shadow-sm">
                    ការកក់ទទួលបានជោគជ័យ!
                </h1>
                <p class="text-xs md:text-sm text-blue-100 max-w-md mx-auto leading-relaxed">
                    សូមអរគុណសម្រាប់ការជ្រើសរើស <strong class="text-white">សណ្ឋាគារ ភីអេនធី ផាលេស</strong>។ ព័ត៌មានការកក់របស់លោកអ្នកត្រូវបានបញ្ចូលក្នុងប្រព័ន្ធដោយជោគជ័យ។
                </p>
            </div>

            <div class="p-6 md:p-8 space-y-8">

                {{-- BOOKING CODE PILLS --}}
                <div class="bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 p-2 rounded-2xl space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-wider flex items-center gap-2">
                            <i class="fas fa-barcode text-blue-600 dark:text-blue-400"></i> លេខកូដសម្គាល់ការកក់
                        </span>
                        <span class="text-[11px] font-bold bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 px-2.5 py-0.5 rounded-full">
                            {{ isset($allItems) ? count($allItems) : count($displayCodes) }} មុខ
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2.5">
                        @foreach($displayCodes as $bCodeItem)
                        <div class="flex items-center gap-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-3.5 py-2 rounded-xl shadow-xs group hover:border-blue-500 transition-all">
                            <span class="font-mono font-black text-blue-600 dark:text-blue-400 text-sm tracking-wide">
                                {{ $bCodeItem }}
                            </span>
                            <button type="button" onclick="copySingleCode('{{ $bCodeItem }}', this)"
                                class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors p-0"
                                title="ចម្លងកូដ">
                                <i class="fas fa-copy text-xs"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- CUSTOMER INFORMATION CARD --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-left">
                    <div class="p-2 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center gap-3.5">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-user text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider">ឈ្មោះអតិថិជន</p>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ $cName }}</h4>
                        </div>
                    </div>

                    <div class="p-2 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center gap-3.5">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-phone text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider">លេខទូរស័ព្ទ</p>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm font-mono truncate">{{ $cPhone }}</h4>
                        </div>
                    </div>

                    <div class="p-2 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800 flex items-center gap-3.5">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-envelope text-sm"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-wider">អ៊ីមែល</p>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm font-mono truncate">{{ $cEmail }}</h4>
                        </div>
                    </div>
                </div>

                {{-- BOOKED ITEMS SECTION --}}
                <div class="space-y-4 text-left">
                    <div class="flex items-center justify-between pb-1 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-sm font-black uppercase text-gray-900 dark:text-white tracking-wider flex items-center gap-2">
                            <i class="fas fa-list-check text-blue-600 dark:text-blue-400"></i> បញ្ជីបន្ទប់ឬសាលដែលបានកក់
                        </h3>
                    </div>

                    @if(isset($allItems) && count($allItems) > 0)
                        <div class="space-y-3">
                            @foreach($allItems as $idx => $sItem)
                            <div class="p-2 bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-100 dark:border-gray-700/80 shadow-xs hover:shadow-md transition-all space-y-3">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 dark:border-gray-700/60 pb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ $idx + 1 }}
                                        </span>
                                        <h4 class="font-extrabold text-gray-900 dark:text-white text-sm md:text-base">
                                            {{ $sItem['name'] }}
                                        </h4>
                                        @if($sItem['type'] === 'hotel')
                                            <span class="bg-blue-100 dark:bg-blue-950/80 text-blue-600 dark:text-blue-300 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border border-blue-200 dark:border-blue-800">
                                                <i class="fas fa-bed mr-1"></i> បន្ទប់ស្នាក់នៅ
                                            </span>
                                        @else
                                            <span class="bg-purple-100 dark:bg-purple-950/80 text-purple-600 dark:text-purple-300 text-[10px] font-extrabold px-2.5 py-0.5 rounded-full border border-purple-200 dark:border-purple-800">
                                                <i class="fas fa-handshake mr-1"></i> សាលប្រជុំ
                                            </span>
                                        @endif
                                    </div>
                                    <span class="font-mono text-xs text-gray-400">
                                        Code: <strong class="text-gray-700 dark:text-gray-300">{{ $sItem['code'] }}</strong>
                                    </span>
                                </div>

                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                                    <div class="text-gray-600 dark:text-gray-300 space-y-1">
                                        @if($sItem['type'] === 'hotel')
                                            <p class="flex items-center gap-1.5 font-medium">
                                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                                ថ្ងៃស្នាក់នៅ៖ <span class="font-mono font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($sItem['check_in'])->format('d-m-Y') }}</span> ដល់ <span class="font-mono font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($sItem['check_out'])->format('d-m-Y') }}</span>
                                            </p>
                                        @else
                                            <p class="flex items-center gap-1.5 font-medium">
                                                <i class="fas fa-calendar-alt text-blue-500"></i>
                                                កាលបរិច្ឆេទ៖ <span class="font-mono font-bold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($sItem['start_date'])->format('d-m-Y') }}</span>
                                                <span class="font-mono text-gray-500">({{ $sItem['start_time'] }} - {{ $sItem['end_time'] }})</span>
                                            </p>
                                        @endif
                                    </div>

                                    <div class="sm:text-right shrink-0">
                                        <span class="text-xs text-gray-400 block">តម្លៃបន្ទប់ឬសាល</span>
                                        <span class="font-black text-blue-600 dark:text-blue-400 text-base">
                                            ${{ number_format($sItem['total_price'], 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-2 bg-white dark:bg-gray-800/80 rounded-2xl border border-gray-100 dark:border-gray-700 text-xs space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-500">ប្រភេទកក់៖</span>
                                <span class="font-bold text-gray-900 dark:text-white">{{ $details->type_name ?? 'បន្ទប់ស្នាក់នៅ / សាលប្រជុំ' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">កាលបរិច្ឆេទ៖</span>
                                <span class="font-bold text-gray-900 dark:text-white">
                                    @if(isset($type) && $type === 'hotel')
                                        {{ \Carbon\Carbon::parse($booking->check_in)->format('d-m-Y') }} ដល់ {{ \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y') }}
                                    @else
                                        {{ \Carbon\Carbon::parse($booking->start_date)->format('d-m-Y') }} ({{ $booking->start_time }} - {{ $booking->end_time }})
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- PAYMENT METHOD & TOTAL SUMMARY CARD --}}
                <div class="p-2 bg-blue-50/50 dark:bg-blue-950/30 rounded-2xl border border-blue-100 dark:border-blue-900/40 space-y-4 text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-blue-100 dark:border-blue-900/40 pb-4">
                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300 flex items-center gap-2">
                            <i class="fas fa-wallet text-blue-600 dark:text-blue-400"></i> វិធីសាស្ត្រទូទាត់ប្រាក់ ៖
                        </span>
                        <div>
                            @if((isset($paymentMethod) && $paymentMethod === 'qr') || (isset($payment->method) && $payment->method === 'qr'))
                                <span class="bg-red-500 text-white font-extrabold px-3 py-1 rounded-xl text-xs shadow-xs inline-flex items-center gap-1.5">
                                    <i class="fas fa-qrcode"></i> ស្កែនឃ្យូអរកូដ
                                </span>
                            @else
                                <span class="bg-emerald-600 text-white font-extrabold px-3 py-1 rounded-xl text-xs shadow-xs inline-flex items-center gap-1.5">
                                    <i class="fas fa-money-bill-wave"></i> ទូទាត់សាច់ប្រាក់នៅសណ្ឋាគារ
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-1">
                        <div>
                            <span class="text-xs font-extrabold uppercase text-gray-500 dark:text-gray-400 tracking-wider block">
                                តម្លៃសរុបទាំងអស់
                            </span>
                            <span class="text-xs font-semibold text-gray-400 font-mono">
                                គិតជាប្រាក់រៀល (~ {{ number_format($displayTotal * $khrRate) }} ៛)
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-black text-blue-600 dark:text-blue-400 font-mono tracking-tight block">
                                ${{ number_format($displayTotal, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                    <a href="{{ route('receipt', $displayCodes[0] ?? $booking->booking_code) }}?codes={{ implode(',', $displayCodes) }}"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold py-3.5 px-4 rounded-2xl text-xs transition-all shadow-md shadow-blue-500/20 flex items-center justify-center gap-2 active:scale-95">
                        <span>មើលវិក្កយបត្រ</span>
                    </a>

                    <a href="{{ route('mybookings') }}"
                        class="w-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold py-3.5 px-4 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 active:scale-95">
                        <span>ប្រវត្តិកក់របស់ខ្ញុំ</span>
                    </a>

                    <a href="{{ route('home') }}"
                        class="w-full bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold py-3.5 px-4 rounded-2xl text-xs transition-all flex items-center justify-center gap-2 active:scale-95">
                        <span>ត្រឡប់ទៅទំព័រដើម</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function copySingleCode(code, btnElement) {
        navigator.clipboard.writeText(code).then(() => {
            const originalHtml = btnElement.innerHTML;
            btnElement.innerHTML = '<i class="fas fa-check text-emerald-500 text-xs"></i>';
            setTimeout(() => {
                btnElement.innerHTML = originalHtml;
            }, 2000);
        });
    }
</script>

@endsection