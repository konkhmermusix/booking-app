<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice #{{ $primaryCode }}</title>

@php
    $fontRegularPath = public_path('Kantumruy_Pro/KantumruyPro-Regular.ttf');
    $fontBoldPath    = public_path('Kantumruy_Pro/KantumruyPro-Bold.ttf');
    $fontRegularData = file_exists($fontRegularPath) ? base64_encode(file_get_contents($fontRegularPath)) : '';
    $fontBoldData    = file_exists($fontBoldPath) ? base64_encode(file_get_contents($fontBoldPath)) : '';

    $hotelAddress = \App\Models\ContactSetting::where('key', 'address')->where('status', 1)->value('value') ?? 'ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្តត្បូងឃ្មុំ (ខាងកើតរង្វង់មូល ប្រាំមួយមករា)';
    $hotelPhone   = \App\Models\ContactSetting::where('key', 'phone')->where('status', 1)->value('value') ?? '096 711 9798 / 071 4 711 979';
    $hotelEmail   = \App\Models\ContactSetting::where('key', 'email')->where('status', 1)->value('value') ?? 'info@pnt-hotel.com';
    $khrRate      = \App\Models\ContactSetting::getExchangeRate();

    $cName  = $customerName ?? (!empty($booking->customer_name) ? $booking->customer_name : 'ភ្ញៀវស្នាក់នៅ');
    $cPhone = $customerPhone ?? (!empty($booking->customer_phone) ? $booking->customer_phone : 'N/A');
    $cEmail = $customerEmail ?? (!empty($booking->customer_email) ? $booking->customer_email : 'N/A');
    $displayCode = $primaryCode ?? ($booking->booking_code ?? 'PNT-RECEIPT');
    $items = isset($allReceiptItems) && count($allReceiptItems) > 0 ? $allReceiptItems : collect();
    $totalAmount = isset($grandTotal) && $grandTotal > 0 ? $grandTotal : ($booking->total_price ?? 0);
@endphp

    <style>
        @if(!empty($fontRegularData))
        @font-face {
            font-family: 'KantumruyPro';
            src: url(data:font/truetype;charset=utf-8;base64,{{ $fontRegularData }}) format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @endif

        @if(!empty($fontBoldData))
        @font-face {
            font-family: 'KantumruyPro';
            src: url(data:font/truetype;charset=utf-8;base64,{{ $fontBoldData }}) format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @endif

        * {
            font-family: 'KantumruyPro', sans-serif !important;
        }

        @page {
            margin: 15px 20px;
        }
        body {
            font-family: 'KantumruyPro', sans-serif !important;
            font-size: {{ $paperSize === 'a5' ? '11px' : '13px' }};
            color: #1f2937;
            line-height: 1.4;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .hotel-name {
            font-size: {{ $paperSize === 'a5' ? '16px' : '20px' }};
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .hotel-sub {
            font-size: {{ $paperSize === 'a5' ? '9px' : '11px' }};
            color: #2563eb;
            font-weight: bold;
        }
        .invoice-title {
            font-size: {{ $paperSize === 'a5' ? '14px' : '18px' }};
            font-weight: bold;
            color: #2563eb;
            text-align: right;
        }
        .invoice-code {
            font-size: {{ $paperSize === 'a5' ? '12px' : '15px' }};
            font-weight: bold;
            color: #111827;
            text-align: right;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            vertical-align: top;
        }
        .section-title {
            font-size: {{ $paperSize === 'a5' ? '10px' : '11px' }};
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .items-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            text-transform: uppercase;
            font-size: {{ $paperSize === 'a5' ? '9px' : '11px' }};
            padding: 6px 8px;
            border-top: 1px solid #cbd5e1;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .items-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: {{ $paperSize === 'a5' ? '10px' : '12px' }};
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-box {
            float: right;
            width: {{ $paperSize === 'a5' ? '200px' : '250px' }};
            text-align: right;
        }
        .total-price {
            font-size: {{ $paperSize === 'a5' ? '16px' : '20px' }};
            font-weight: bold;
            color: #2563eb;
        }
        .clear { clear: both; }
        .footer {
            margin-top: 25px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
            font-size: {{ $paperSize === 'a5' ? '9px' : '11px' }};
            color: #64748b;
        }
    </style>
</head>
<body>

    {{-- HEADER TABLE --}}
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="hotel-name">សណ្ឋាគារ ភីអេនធី ផាលេស</div>
                <div class="hotel-sub">PNT PALACE HOTEL & RESORT</div>
                <div style="font-size: {{ $paperSize === 'a5' ? '9px' : '11px' }}; color: #475569; margin-top: 4px;">
                    អាសយដ្ឋាន ៖ {{ $hotelAddress }}<br>
                    ទូរស័ព្ទ ៖ {{ $hotelPhone }} | អ៊ីមែល ៖ {{ $hotelEmail }}
                </div>
            </td>
            <td style="width: 40%; text-align: right; vertical-align: top;">
                <div class="invoice-title">វិក្កយបត្រ / RECEIPT</div>
                <div class="invoice-code">#{{ $displayCode }}</div>
                <div style="font-size: {{ $paperSize === 'a5' ? '9px' : '11px' }}; color: #64748b; margin-top: 2px;">
                    កាលបរិច្ឆេទ ៖ {{ date('d/m/Y h:i A', strtotime($booking->created_at ?? now())) }}
                </div>
            </td>
        </tr>
    </table>

    {{-- CUSTOMER & BOOKING INFO --}}
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td style="width: 50%;">
                    <div class="section-title">ព័ត៌មានអតិថិជន (Customer Info)</div>
                    <div><strong>ឈ្មោះ ៖</strong> {{ $cName }}</div>
                    <div><strong>ទូរស័ព្ទ ៖</strong> {{ $cPhone }}</div>
                    <div><strong>អ៊ីមែល ៖</strong> {{ $cEmail }}</div>
                </td>
                <td style="width: 50%;">
                    <div class="section-title">ព័ត៌មានការកក់ (Booking Summary)</div>
                    <div><strong>ប្រភេទ ៖</strong> {{ $type === 'combined' ? 'បន្ទប់ស្នាក់នៅ និងសាលប្រជុំ' : ($type === 'hotel' ? 'បន្ទប់សណ្ឋាគារ' : 'សាលប្រជុំ') }}</div>
                    <div><strong>វិធីសាស្ត្រទូទាត់ ៖</strong> {{ isset($payment->method) && $payment->method === 'qr' ? 'ស្កែន KHQR Code' : 'ទូទាត់សាច់ប្រាក់នៅសណ្ឋាគារ' }}</div>
                    <div><strong>ស្ថានភាព ៖</strong> 
                        @if($payment && ($payment->status === 'paid' || (isset($payment->payment_status) && $payment->payment_status === 'paid')))
                            <span style="color: #16a34a; font-weight: bold;">បានទូទាត់រួច (Paid)</span>
                        @elseif($booking->status === 'cancelled')
                            <span style="color: #dc2626; font-weight: bold;">បានបោះបង់ (Cancelled)</span>
                        @else
                            <span style="color: #d97706; font-weight: bold;">រង់ចាំផ្ទៀងផ្ទាត់ (Pending)</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- ITEMS TABLE --}}
    <table class="items-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 8%;">ល.រ</th>
                <th style="width: 42%;">មុខទំនិញ / បរិយាយ</th>
                <th style="width: 32%;">កាលបរិច្ឆេទ</th>
                <th class="text-right" style="width: 18%;">តម្លៃសរុប</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->type_name ?? (($item->item_type ?? $type) === 'hotel' ? 'បន្ទប់ស្នាក់នៅ' : 'សាលប្រជុំ') }}</strong>
                    @if(!empty($item->name))
                        <div style="font-size: {{ $paperSize === 'a5' ? '9px' : '11px' }}; color: #64748b;">
                            {{ ($item->item_type ?? $type) === 'hotel' ? 'លេខបន្ទប់' : 'ឈ្មោះសាល' }} ៖ {{ $item->name }}
                        </div>
                    @endif
                </td>
                <td>
                    @if(($item->item_type ?? $type) === 'hotel')
                        {{ \Carbon\Carbon::parse($item->check_in ?? $booking->check_in)->format('d/m/Y') }} ដល់ {{ \Carbon\Carbon::parse($item->check_out ?? $booking->check_out)->format('d/m/Y') }}
                    @else
                        {{ \Carbon\Carbon::parse($item->start_date ?? $booking->start_date)->format('d/m/Y') }} ({{ $item->start_time ?? $booking->start_time }} - {{ $item->end_time ?? $booking->end_time }})
                    @endif
                </td>
                <td class="text-right" style="font-weight: bold;">
                    ${{ number_format($item->price ?? ($totalAmount / count($items)), 2) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- TOTAL SUMMARY --}}
    <div>
        <div style="float: left; width: 50%; font-size: {{ $paperSize === 'a5' ? '9px' : '11px' }}; color: #64748b;">
            <strong>ចំណាំ ៖</strong><br>
            • សូមរក្សាទុកវិក្កយបត្រនេះសម្រាប់បង្ហាញជូនបុគ្គលិកពេលចូលស្នាក់នៅ។<br>
            • អត្រាប្តូរប្រាក់ ៖ 1 USD = {{ number_format($khrRate) }} KHR
        </div>
        <div class="total-box">
            <div style="color: #475569;">សរុបទឹកប្រាក់ ៖ <strong>${{ number_format($totalAmount, 2) }}</strong></div>
            <div style="border-top: 1.5px solid #cbd5e1; margin-top: 4px; padding-top: 4px;">
                <span style="font-weight: bold;">ទឹកប្រាក់សរុប ៖</span><br>
                <span class="total-price">${{ number_format($totalAmount, 2) }}</span><br>
                <span style="font-size: {{ $paperSize === 'a5' ? '10px' : '12px' }}; color: #64748b;">(~ {{ number_format($totalAmount * $khrRate) }} ៛)</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        <strong>សូមអរគុណសម្រាប់ការជ្រើសរើស សណ្ឋាគារ ភីអេនធី ផាលេស !</strong><br>
        Thank you for choosing PNT Palace Hotel & Resort
    </div>

</body>
</html>
