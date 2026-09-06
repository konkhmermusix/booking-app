<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>របាយការណ៍ទិន្នន័យ - {{ date('Y-m-d H:i') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                color: black !important;
            }
            @page {
                size: A4 portrait;
                margin: 12mm;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-8 text-gray-800">

    {{-- Control Toolbar --}}
    <div class="max-w-5xl mx-auto mb-6 flex justify-between items-center no-print">
        <button onclick="window.history.back()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-xl text-xs font-bold transition-all">
            ត្រឡប់ក្រោយ
        </button>
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold shadow-lg transition-all">
            បោះពុម្ពរបាយការណ៍
        </button>
    </div>

    {{-- Printable Report Sheet --}}
    <div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-200">
        
        {{-- Header Section --}}
        <div class="flex justify-between items-start border-b border-gray-200 pb-6 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">របាយការណ៍ទិន្នន័យប្រព័ន្ធ</h1>
                <p class="text-sm text-gray-500 mt-1">ប្រភេទតារាង: 
                    <span class="font-bold text-blue-600">
                        @switch($tableType)
                            @case('room_bookings') ការកក់បន្ទប់សណ្ឋាគារ @break
                            @case('meeting_bookings') ការកក់សាលប្រជុំ @break
                            @case('payments') ប្រតិបត្តិការបង់ប្រាក់ @break
                            @case('customers') អតិថិជន និងអ្នកប្រើប្រាស់ @break
                            @case('rooms') ស្ថានភាពបន្ទប់ @break
                            @default {{ $tableType }}
                        @endswitch
                    </span>
                </p>
                <p class="text-xs text-gray-400 mt-0.5">រយៈពេល: {{ $period }} @if($startDate && $endDate) ({{ $startDate }} ដល់ {{ $endDate }}) @endif</p>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">កាលបរិច្ឆេទបង្កើត</p>
                <p class="text-sm font-mono font-bold text-gray-700">{{ date('Y-m-d H:i') }}</p>
            </div>
        </div>

        {{-- KPI Summary Box --}}
        <div class="grid grid-cols-3 gap-4 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">ចំនួនទិន្នន័យ</span>
                <p class="text-xl font-bold text-gray-900">{{ number_format($summary['total_records']) }} ជួរ</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">ទឹកប្រាក់សរុបជាដុល្លារ</span>
                <p class="text-xl font-bold text-emerald-600">${{ number_format($summary['total_amount_usd'], 2) }}</p>
            </div>
            <div>
                <span class="text-xs text-gray-400 uppercase font-semibold">ទឹកប្រាក់សរុបជាប្រាក់រៀល</span>
                <p class="text-xl font-bold text-purple-600">៛{{ number_format($summary['total_amount_usd'] * $exchangeRate) }}</p>
            </div>
        </div>

        {{-- Table Content --}}
        <table class="w-full text-left text-xs border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100 text-gray-700 font-bold border-b border-gray-200">
                    @if($tableType === 'room_bookings')
                        <th class="p-2 border">កូដកក់</th>
                        <th class="p-2 border">ឈ្មោះអតិថិជន</th>
                        <th class="p-2 border">លេខទូរស័ព្ទ</th>
                        <th class="p-2 border">បន្ទប់</th>
                        <th class="p-2 border">ថ្ងៃចូល - ថ្ងៃចេញ</th>
                        <th class="p-2 border text-center">ស្ថានភាព</th>
                        <th class="p-2 border text-right">តម្លៃសរុប ($)</th>
                    @elseif($tableType === 'meeting_bookings')
                        <th class="p-2 border">កូដកក់</th>
                        <th class="p-2 border">ឈ្មោះអតិថិជន</th>
                        <th class="p-2 border">លេខទូរស័ព្ទ</th>
                        <th class="p-2 border">សាលប្រជុំ</th>
                        <th class="p-2 border">កាលបរិច្ឆេទ</th>
                        <th class="p-2 border text-center">ស្ថានភាព</th>
                        <th class="p-2 border text-right">តម្លៃសរុប ($)</th>
                    @elseif($tableType === 'payments')
                        <th class="p-2 border">កូដប្រតិបត្តិការ</th>
                        <th class="p-2 border">ប្រភេទកក់</th>
                        <th class="p-2 border text-center">វិធីសាស្ត្រ</th>
                        <th class="p-2 border text-center">ស្ថានភាព</th>
                        <th class="p-2 border text-right">ចំនួនប្រាក់ ($)</th>
                        <th class="p-2 border text-right">ថ្ងៃបង់ប្រាក់</th>
                    @elseif($tableType === 'customers')
                        <th class="p-2 border">ID</th>
                        <th class="p-2 border">ឈ្មោះ</th>
                        <th class="p-2 border">អ៊ីមែល</th>
                        <th class="p-2 border">លេខទូរស័ព្ទ</th>
                        <th class="p-2 border text-center">តួនាទី</th>
                        <th class="p-2 border text-right">ថ្ងៃចុះឈ្មោះ</th>
                    @elseif($tableType === 'rooms')
                        <th class="p-2 border">លេខបន្ទប់</th>
                        <th class="p-2 border">ប្រភេទបន្ទប់</th>
                        <th class="p-2 border">សមត្ថភាព</th>
                        <th class="p-2 border text-center">ស្ថានភាព</th>
                        <th class="p-2 border text-right">តម្លៃ/យប់ ($)</th>
                    @else
                        <th class="p-2 border">ID</th>
                        <th class="p-2 border">ឈ្មោះ/ចំណងជើង</th>
                        <th class="p-2 border text-right">ថ្ងៃបង្កើត</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($records as $row)
                    <tr class="border-b border-gray-200">
                        @if($tableType === 'room_bookings')
                            <td class="p-2 border font-mono font-bold">{{ $row->booking_code }}</td>
                            <td class="p-2 border">{{ $row->customer_name ?: ($row->user->name ?? 'N/A') }}</td>
                            <td class="p-2 border">{{ $row->customer_phone ?: ($row->user->phone ?? 'N/A') }}</td>
                            <td class="p-2 border">{{ $row->room->room_number ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $row->check_in }} ដល់ {{ $row->check_out }}</td>
                            <td class="p-2 border text-center font-bold">{{ $row->status }}</td>
                            <td class="p-2 border text-right font-bold">${{ number_format($row->total_price, 2) }}</td>
                        @elseif($tableType === 'meeting_bookings')
                            <td class="p-2 border font-mono font-bold">{{ $row->booking_code }}</td>
                            <td class="p-2 border">{{ $row->customer_name ?: ($row->user->name ?? 'N/A') }}</td>
                            <td class="p-2 border">{{ $row->customer_phone ?: ($row->user->phone ?? 'N/A') }}</td>
                            <td class="p-2 border">{{ $row->room->room_number ?? 'សាលប្រជុំ' }}</td>
                            <td class="p-2 border">{{ $row->start_date }}</td>
                            <td class="p-2 border text-center font-bold">{{ $row->status }}</td>
                            <td class="p-2 border text-right font-bold">${{ number_format($row->total_price, 2) }}</td>
                        @elseif($tableType === 'payments')
                            <td class="p-2 border font-mono font-bold">{{ $row->transaction_id ?: 'TRX-'.$row->id }}</td>
                            <td class="p-2 border">{{ $row->hotel_booking_id ? 'កក់បន្ទប់' : ($row->meeting_booking_id ? 'កក់សាលប្រជុំ' : 'ផ្សេងៗ') }}</td>
                            <td class="p-2 border text-center uppercase">{{ $row->method }}</td>
                            <td class="p-2 border text-center font-bold">{{ $row->status }}</td>
                            <td class="p-2 border text-right font-bold">${{ number_format($row->amount, 2) }}</td>
                            <td class="p-2 border text-right">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                        @elseif($tableType === 'customers')
                            <td class="p-2 border">#{{ $row->id }}</td>
                            <td class="p-2 border font-bold">{{ $row->name }}</td>
                            <td class="p-2 border">{{ $row->email }}</td>
                            <td class="p-2 border">{{ $row->phone ?: 'N/A' }}</td>
                            <td class="p-2 border text-center font-bold capitalize">{{ $row->role }}</td>
                            <td class="p-2 border text-right">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                        @elseif($tableType === 'rooms')
                            <td class="p-2 border font-bold">បន្ទប់ {{ $row->room_number }}</td>
                            <td class="p-2 border">{{ $row->roomType->name ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $row->capacity }} នាក់</td>
                            <td class="p-2 border text-center font-bold">{{ $row->status }}</td>
                            <td class="p-2 border text-right font-bold">${{ number_format($row->price_per_night, 2) }}</td>
                        @else
                            <td class="p-2 border font-mono text-xs text-gray-400">#{{ $row->id }}</td>
                            <td class="p-2 border font-bold">{{ $row->title ?: ($row->name ?: ($row->room_number ?? 'Item #'.$row->id)) }}</td>
                            <td class="p-2 border text-right text-xs text-gray-400">{{ isset($row->created_at) ? $row->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Signature Section --}}
        @include('admin.partials.report_footer')

    </div>

</body>
</html>
