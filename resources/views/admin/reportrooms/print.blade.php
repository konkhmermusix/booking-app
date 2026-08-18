<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>របាយការណ៍ការកក់បន្ទប់សណ្ឋាគារ - PNT Palace Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Kantumruy Pro', sans-serif;
            background-color: #fff;
            color: #1f2937;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body class="p-8 max-w-4xl mx-auto">

    <!-- Action Bar (Hidden when printing) -->
    <div class="no-print mb-6 flex justify-between items-center bg-gray-100 p-4 rounded-2xl border border-gray-200">
        <div>
            <h1 class="font-bold text-gray-800 text-sm">ផ្ទាំងព្រីនរបាយការណ៍ (Print & Export Preview)</h1>
            <p class="text-xs text-gray-500">ចុចប៊ូតុងខាងស្តាំដើម្បីព្រីន ឬ រក្សាទុកជាឯកសារ PDF</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-sm">
                <i class="fas fa-print"></i> ព្រីនឯកសារ / Save PDF
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-xs font-bold rounded-xl">
                បិទ
            </button>
        </div>
    </div>

    <!-- Official Header -->
    <div class="flex justify-between items-center border-b-2 border-gray-800 pb-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-blue-950 uppercase tracking-wide">សណ្ឋាគារ ភីអេនធី ផាលេស</h1>
            <h2 class="text-xs font-bold text-gray-600 tracking-wider">PNT PALACE HOTEL & RESORT</h2>
            <p class="text-[11px] text-gray-500 mt-1">អាសយដ្ឋាន៖ រាជធានីភ្នំពេញ, ព្រះរាជាណាចក្រកម្ពុជា</p>
            <p class="text-[11px] text-gray-500">ទូរស័ព្ទ៖ 096 342 4789 | អ៊ីមែល៖ info@pntpalace.com</p>
        </div>
        <div class="text-right">
            <div class="inline-block p-3 bg-blue-50 border border-blue-200 rounded-xl mb-1">
                <h3 class="text-base font-black text-blue-900">របាយការណ៍កក់បន្ទប់</h3>
            </div>
            <p class="text-xs text-gray-500 font-mono mt-1">កាលបរិច្ឆេទព្រីន៖ {{ date('d/m/Y H:i') }}</p>
            <p class="text-xs text-gray-500">អ្នកចេញរបាយការណ៍៖ {{ auth()->user()->name ?? 'Admin' }}</p>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="border border-gray-300 p-3 rounded-xl text-center bg-gray-50">
            <span class="text-[11px] text-gray-500 uppercase font-bold">ចំនួនការកក់សរុប</span>
            <p class="text-xl font-black text-gray-800 mt-0.5">{{ number_format($bookings->count()) }} កំណត់ត្រា</p>
        </div>
        <div class="border border-gray-300 p-3 rounded-xl text-center bg-gray-50">
            <span class="text-[11px] text-gray-500 uppercase font-bold">ការកក់បានជោគជ័យ</span>
            <p class="text-xl font-black text-emerald-600 mt-0.5">{{ number_format($bookings->where('status', '!=', 'cancelled')->count()) }} កក់</p>
        </div>
        <div class="border border-gray-300 p-3 rounded-xl text-center bg-gray-50">
            <span class="text-[11px] text-gray-500 uppercase font-bold">ចំណូលសរុប (មិនរាប់លុបចោល)</span>
            <p class="text-xl font-black text-blue-700 mt-0.5">${{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <table class="w-full text-left border-collapse border border-gray-300 text-xs mb-8">
        <thead>
            <tr class="bg-gray-200 text-gray-800 font-bold border-b border-gray-300">
                <th class="p-2.5 border-r border-gray-300 text-center w-10">#</th>
                <th class="p-2.5 border-r border-gray-300">លេខកូដកក់</th>
                <th class="p-2.5 border-r border-gray-300">ឈ្មោះអតិថិជន</th>
                <th class="p-2.5 border-r border-gray-300">លេខបន្ទប់ / ប្រភេទ</th>
                <th class="p-2.5 border-r border-gray-300 text-center">ថ្ងៃចូល - ថ្ងៃចេញ</th>
                <th class="p-2.5 border-r border-gray-300 text-center">ចំនួនយប់</th>
                <th class="p-2.5 border-r border-gray-300 text-center">ស្ថានភាព</th>
                <th class="p-2.5 text-right">តម្លៃសរុប ($)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
            @forelse($bookings as $index => $b)
                @php
                    $nights = ($b->check_in && $b->check_out) ? \Carbon\Carbon::parse($b->check_in)->diffInDays(\Carbon\Carbon::parse($b->check_out)) : 1;
                    $statusKhmer = match($b->status) {
                        'pending'   => 'រង់ចាំ',
                        'confirmed' => 'បានបញ្ជាក់',
                        'completed' => 'បានបញ្ចប់',
                        'cancelled' => 'បានបោះបង់',
                        default     => $b->status
                    };
                @endphp
                <tr>
                    <td class="p-2.5 border-r border-gray-300 text-center font-mono">{{ $index + 1 }}</td>
                    <td class="p-2.5 border-r border-gray-300 font-mono font-bold text-blue-900">{{ $b->booking_code }}</td>
                    <td class="p-2.5 border-r border-gray-300 font-semibold">{{ $b->customer_name ?: ($b->user->name ?? 'ភ្ញៀវ Walk-in') }}</td>
                    <td class="p-2.5 border-r border-gray-300">No. {{ $b->room->room_number ?? 'N/A' }} ({{ $b->room->roomType->name ?? 'បន្ទប់' }})</td>
                    <td class="p-2.5 border-r border-gray-300 text-center font-mono">{{ $b->check_in }} - {{ $b->check_out }}</td>
                    <td class="p-2.5 border-r border-gray-300 text-center font-bold">{{ $nights }} យប់</td>
                    <td class="p-2.5 border-r border-gray-300 text-center font-bold">
                        @if($b->status == 'completed') <span class="text-emerald-700">បានបញ្ចប់</span>
                        @elseif($b->status == 'confirmed') <span class="text-blue-700">បានបញ្ជាក់</span>
                        @elseif($b->status == 'cancelled') <span class="text-rose-600">បានបោះបង់</span>
                        @else <span class="text-amber-600">រង់ចាំ</span> @endif
                    </td>
                    <td class="p-2.5 text-right font-black text-gray-900">${{ number_format($b->total_price, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">គ្មានទិន្នន័យការកក់បន្ទប់ត្រូវបានរកឃើញឡើយ</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-gray-100 font-black border-t-2 border-gray-400">
                <td colspan="7" class="p-3 text-right uppercase">សរុបទឹកប្រាក់រួម (Grand Total):</td>
                <td class="p-3 text-right text-base text-blue-950">${{ number_format($totalRevenue, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Signature Footer -->
    <div class="grid grid-cols-2 gap-8 pt-8 border-t border-gray-300 mt-12 text-center text-xs">
        <div>
            <p class="font-bold text-gray-700">រៀបចំដោយ</p>
            <div class="h-16"></div>
            <p class="font-bold border-t border-gray-400 inline-block px-8 pt-1 text-gray-800">{{ auth()->user()->name ?? 'អ្នករៀបចំ' }}</p>
            <p class="text-[10px] text-gray-500">បុគ្គលិកគ្រប់គ្រងទិន្នន័យ</p>
        </div>
        <div>
            <p class="font-bold text-gray-700">បានពិនិត្យ & យល់ព្រមដោយ</p>
            <div class="h-16"></div>
            <p class="font-bold border-t border-gray-400 inline-block px-8 pt-1 text-gray-800">នាយកប្រតិបត្តិសណ្ឋាគារ</p>
            <p class="text-[10px] text-gray-500">ប្រធានផ្នែកហិរញ្ញវត្ថុ / Manager</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
