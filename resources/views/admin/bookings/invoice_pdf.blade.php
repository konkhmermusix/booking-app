<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $booking->booking_code }}</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
        }

        .header {
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .hotel-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
        }

        .item-table th {
            background: #f8fafc;
            border-bottom: 1px solid #eee;
            padding: 10px;
            text-align: left;
        }

        .item-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .total-row {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }


        /* ការកំណត់ Font ខ្មែរ */
        @font-face {
            font-family: 'KantumruyPro';
            src: url('{{ storage_path("fonts/KantumruyPro-Regular.ttf") }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: 'KantumruyPro', sans-serif;
            /* ប្រើ Font Kantumruy Pro ជាចម្បង */
            font-size: 14px;
            color: #1a202c;
            line-height: 1.5;
        }

        /* បន្ថែម CSS សម្រាប់ភាពស្អាតនៃវិក្កយបត្រ */
        .invoice-header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .text-blue {
            color: #2563eb;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <table width="100%">
                <tr>
                    <td class="hotel-name">{{ $booking->hotel->name }}</td>
                    <td align="right italic">លេខកូដកក់: <strong>#{{ $booking->booking_code }}</strong></td>
                </tr>
            </table>
        </div>

        <table class="info-table">
            <tr>
                <td>
                    <strong>ព័ត៌មានអតិថិជន:</strong><br>
                    ឈ្មោះ: {{ $booking->user->name ?? 'ភ្ញៀវក្រៅប្រព័ន្ធ' }}<br>
                    អ៊ីមែល: {{ $booking->user->email ?? 'N/A' }}
                </td>
                <td align="right">
                    <strong>ព័ត៌មានការកក់:</strong><br>
                    ថ្ងៃកក់: {{ $booking->created_at->format('d M Y') }}<br>
                    ស្ថានភាព: {{ strtoupper($booking->status) }}
                </td>
            </tr>
        </table>

        <table class="item-table">
            <thead>
                <tr>
                    <th>បរិយាយ</th>
                    <th>ថ្ងៃចូល (Check-in)</th>
                    <th>ថ្ងៃចេញ (Check-out)</th>
                    <th align="right">តម្លៃសរុប</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ការកក់ស្នាក់នៅសណ្ឋាគារ {{ $booking->hotel->name }}</td>
                    <td>{{ $booking->check_in }}</td>
                    <td>{{ $booking->check_out }}</td>
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