<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>វិក្កយបត្រ #{{ $booking->booking_code }}</title>
    <style>
        @php
            $fontRegularPath = str_replace('\\', '/', public_path('Kantumruy_Pro/KantumruyPro-Regular.ttf'));
            $fontBoldPath    = str_replace('\\', '/', public_path('Kantumruy_Pro/KantumruyPro-Bold.ttf'));
            $fontRegularData = file_exists(public_path('Kantumruy_Pro/KantumruyPro-Regular.ttf')) ? base64_encode(file_get_contents(public_path('Kantumruy_Pro/KantumruyPro-Regular.ttf'))) : '';
            $fontBoldData    = file_exists(public_path('Kantumruy_Pro/KantumruyPro-Bold.ttf')) ? base64_encode(file_get_contents(public_path('Kantumruy_Pro/KantumruyPro-Bold.ttf'))) : '';
        @endphp

        @font-face {
            font-family: 'KantumruyPro';
            src: url("{{ $fontRegularPath }}") format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'KantumruyPro';
            src: url("{{ $fontBoldPath }}") format('truetype');
            font-weight: bold;
            font-style: normal;
        }

        @if(!empty($fontRegularData))
        @font-face {
            font-family: 'KantumruyProBase64';
            src: url(data:font/truetype;charset=utf-8;base64,{{ $fontRegularData }}) format('truetype');
        }
        @endif

        * {
            font-family: 'KantumruyPro', 'KantumruyProBase64', DejaVu Sans, sans-serif !important;
        }

        body {
            font-family: 'KantumruyPro', 'KantumruyProBase64', DejaVu Sans, sans-serif !important;
            font-size: 13px;
            color: #1f2937;
            line-height: 1.5;
            padding: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #e5e7eb;
            padding: 25px;
            border-radius: 12px;
        }

        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .hotel-name {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 6px 0;
            vertical-align: top;
            font-size: 13px;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .item-table th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }

        .item-table td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .total-row {
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            color: #059669;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #6b7280;
            border-top: 1px dashed #e5e7eb;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    @php
        $hotelName = $booking->hotel->name ?? ($booking->room->roomType->hotel->name ?? 'សណ្ឋាគារ ភីអេនធី ផាលេស (PNT Palace Hotel)');
        $customerName = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវមកផ្ទាល់');
        $customerContact = $booking->customer_phone ?: ($booking->user->phone ?? ($booking->customer_email ?: ($booking->user->email ?? 'N/A')));
        
        $isMeeting = isset($booking->meeting_room_id);
        $checkIn = $isMeeting ? ($booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : 'N/A') : ($booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : 'N/A');
        $checkOut = $isMeeting ? ($booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : 'N/A') : ($booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : 'N/A');
        
        $roomInfo = $booking->room ? (($isMeeting ? 'សាលប្រជុំលេខ ' : 'បន្ទប់លេខ ') . $booking->room->room_number . ($booking->room->roomType ? ' (' . $booking->room->roomType->name . ')' : '')) : 'N/A';
    @endphp

    <div class="invoice-box">
        <div class="header">
            <table width="100%">
                <tr>
                    <td class="hotel-name">{{ $hotelName }}</td>
                    <td align="right">លេខកូដកក់: <strong>#{{ $booking->booking_code }}</strong></td>
                </tr>
            </table>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <strong>ព័ត៌មានអតិថិជន:</strong><br>
                    ឈ្មោះ: {{ $customerName }}<br>
                    ទាក់ទង: {{ $customerContact }}
                </td>
                <td align="right">
                    <strong>ព័ត៌មានការកក់:</strong><br>
                    ថ្ងៃកក់: {{ $booking->created_at ? $booking->created_at->format('d/m/Y') : date('d/m/Y') }}<br>
                    ស្ថានភាព: {{ strtoupper($booking->status ?? 'PENDING') }}
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th>បរិយាយ (Description)</th>
                    <th>ថ្ងៃចូល (Check-In)</th>
                    <th>ថ្ងៃចេញ (Check-Out)</th>
                    <th align="right">តម្លៃសរុប</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $roomInfo }}</td>
                    <td>{{ $checkIn }}</td>
                    <td>{{ $checkOut }}</td>
                    <td align="right">${{ number_format($booking->total_price, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-row">
            សរុបដែលត្រូវបង់: ${{ number_format($booking->total_price, 2) }}
        </div>

        <div class="footer">
            សូមអរគុណសម្រាប់ការជ្រើសរើសសេវាកម្មរបស់យើង!<br>
            នេះគឺជាឯកសារបង្កើតដោយប្រព័ន្ធ មិនត្រូវការហត្ថលេខាឡើយ។
        </div>
    </div>
</body>
</html>