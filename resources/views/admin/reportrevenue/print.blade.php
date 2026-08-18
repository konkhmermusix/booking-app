<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>របាយការណ៍ប្រាក់ចំណូល - PNT Palace Hotel</title>
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
            <h1 class="font-bold text-gray-800 text-sm">ផ្ទាំងព្រីនរបាយការណ៍ចំណូល (Print & Export Preview)</h1>
            <p class="text-xs text-gray-500">ចុចប៊ូតុងខាងស្តាំដើម្បីព្រីន ឬ រក្សាទុកជាឯកសារ PDF ទម្រង់ A4</p>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-sm">
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
            <h1 class="text-2xl font-black text-emerald-950 uppercase tracking-wide">សណ្ឋាគារ ភីអេនធី ផាលេស</h1>
            <h2 class="text-xs font-bold text-gray-600 tracking-wider">PNT PALACE HOTEL & RESORT</h2>
            <p class="text-[11px] text-gray-500 mt-1">អាសយដ្ឋាន៖ រាជធានីភ្នំពេញ, ព្រះរាជាណាចក្រកម្ពុជា</p>
            <p class="text-[11px] text-gray-500">ទូរស័ព្ទ៖ 096 342 4789 | អ៊ីមែល៖ info@pntpalace.com</p>
        </div>
        <div class="text-right">
            <div class="inline-block p-3 bg-emerald-50 border border-emerald-200 rounded-xl mb-1">
                <h3 class="text-base font-black text-emerald-900">របាយការណ៍ប្រាក់ចំណូលប្រចាំឆ្នាំ {{ $year }}</h3>
            </div>
            <p class="text-xs text-gray-500 font-mono mt-1">កាលបរិច្ឆេទព្រីន៖ {{ date('d/m/Y H:i') }}</p>
            <p class="text-xs text-gray-500">អ្នកចេញរបាយការណ៍៖ {{ auth()->user()->name ?? 'Admin' }}</p>
        </div>
    </div>

    <!-- Summary Box -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="border border-gray-300 p-3 rounded-xl text-center bg-gray-50">
            <span class="text-[11px] text-gray-500 uppercase font-bold">ប្រតិបត្តិការសរុប</span>
            <p class="text-xl font-black text-gray-800 mt-0.5">{{ number_format($payments->count()) }} លើក</p>
        </div>
        <div class="border border-gray-300 p-3 rounded-xl text-center bg-gray-50">
            <span class="text-[11px] text-gray-500 uppercase font-bold">ចំណូលទទួលបានពិតប្រាកដ</span>
            <p class="text-xl font-black text-emerald-600 mt-0.5">${{ number_format($totalPaidRevenue, 2) }}</p>
        </div>
        <div class="border border-gray-300 p-3 rounded-xl text-center bg-gray-50">
            <span class="text-[11px] text-gray-500 uppercase font-bold">ប្រាក់រង់ចាំទូទាត់</span>
            <p class="text-xl font-black text-amber-600 mt-0.5">${{ number_format($totalPendingRevenue, 2) }}</p>
        </div>
    </div>

    <!-- Data Table -->
    <table class="w-full text-left border-collapse border border-gray-300 text-xs mb-8">
        <thead>
            <tr class="bg-gray-200 text-gray-800 font-bold border-b border-gray-300">
                <th class="p-2.5 border-r border-gray-300 text-center w-10">#</th>
                <th class="p-2.5 border-r border-gray-300">កាលបរិច្ឆេទ</th>
                <th class="p-2.5 border-r border-gray-300">លេខប្រតិបត្តិការ</th>
                <th class="p-2.5 border-r border-gray-300">លេខកូដកក់</th>
                <th class="p-2.5 border-r border-gray-300">ឈ្មោះអតិថិជន</th>
                <th class="p-2.5 border-r border-gray-300 text-center">វិធីសាស្ត្រ</th>
                <th class="p-2.5 border-r border-gray-300 text-center">ស្ថានភាព</th>
                <th class="p-2.5 text-right">ចំនួនប្រាក់ ($)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-300">
            @forelse($payments as $index => $p)
                @php
                    $statusKhmer = match($p->status) {
                        'paid'      => 'បង់រួច',
                        'pending'   => 'រង់ចាំ',
                        'refunded'  => 'បានបង្វិល',
                        default     => $p->status
                    };
                @endphp
                <tr>
                    <td class="p-2.5 border-r border-gray-300 text-center font-bold text-gray-500">{{ $index + 1 }}</td>
                    <td class="p-2.5 border-r border-gray-300 font-mono">{{ date('d/m/Y H:i', strtotime($p->created_at)) }}</td>
                    <td class="p-2.5 border-r border-gray-300 font-mono text-xs">{{ $p->transaction_id ?: 'N/A' }}</td>
                    <td class="p-2.5 border-r border-gray-300 font-bold text-emerald-900">#{{ $p->booking_code }}</td>
                    <td class="p-2.5 border-r border-gray-300 font-semibold">{{ $p->customer_name }}</td>
                    <td class="p-2.5 border-r border-gray-300 text-center font-bold uppercase text-[10px]">{{ $p->method ?: 'Cash' }}</td>
                    <td class="p-2.5 border-r border-gray-300 text-center font-bold">
                        {{ $statusKhmer }}
                    </td>
                    <td class="p-2.5 text-right font-bold text-emerald-900">${{ number_format($p->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-400 italic">មិនទាន់មានទិន្នន័យចំណូលឡើយ</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-gray-100 font-bold text-gray-800 border-t border-gray-400">
                <td colspan="7" class="p-3 text-right uppercase">សរុបប្រាក់ចំណូលទទួលបាន (Paid Revenue):</td>
                <td class="p-3 text-right text-emerald-950 font-black text-sm">${{ number_format($totalPaidRevenue, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <!-- Signature Footer -->
    <div class="grid grid-cols-2 gap-8 pt-8 text-center text-xs">
        <div>
            <p class="font-bold text-gray-700 mb-16">អ្នករៀបចំរបាយការណ៍ (Prepared By)</p>
            <p class="font-semibold text-gray-900">......................................................</p>
            <p class="text-[11px] text-gray-500 mt-1">ថ្ងៃទី........ ខែ........ ឆ្នាំ២០....</p>
        </div>
        <div>
            <p class="font-bold text-gray-700 mb-16">ប្រធានផ្នែកហិរញ្ញវត្ថុ / អ្នកអនុម័ត (Approved By)</p>
            <p class="font-semibold text-gray-900">......................................................</p>
            <p class="text-[11px] text-gray-500 mt-1">ថ្ងៃទី........ ខែ........ ឆ្នាំ២០....</p>
        </div>
    </div>

</body>
</html>
