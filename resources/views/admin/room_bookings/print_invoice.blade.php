<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>វិក្កយបត្រ #{{ $booking->booking_code }} - សណ្ឋាគារ ភីអេនធី ផាលេស</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:ital,wght@0,300..700;1,300..700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Kantumruy Pro', 'Inter', 'sans-serif'],
                        mono: ['Inter', 'monospace']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Kantumruy Pro', 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page {
            size: A4;
            margin: 15mm;
        }

        @media print {
            body {
                background-color: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .print-shadow-none {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="py-8 px-4 sm:px-6">

    @php
        if (!function_exists('formatKhmerTime')) {
            function formatKhmerTime($timeStr) {
                if (!$timeStr) return '';
                $timeStr = trim($timeStr);
                if (preg_match('/AM/i', $timeStr)) {
                    return trim(str_ireplace('AM', '', $timeStr)) . ' ព្រឹក';
                }
                if (preg_match('/PM/i', $timeStr)) {
                    return trim(str_ireplace('PM', '', $timeStr)) . ' ល្ងាច';
                }
                $parts = explode(':', $timeStr);
                if (count($parts) >= 2) {
                    $h = intval($parts[0]);
                    $m = $parts[1];
                    return $h < 12 ? sprintf('%02d:%s ព្រឹក', $h, $m) : sprintf('%02d:%s ល្ងាច', $h, $m);
                }
                return $timeStr;
            }
        }

        $hotelAddress = \App\Models\ContactSetting::where('key', 'address')->where('status', 1)->value('value') ?? 'ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្តត្បូងឃ្មុំ (ខាងកើតរង្វង់មូល ប្រាំមួយមករា)';
        $hotelPhone   = \App\Models\ContactSetting::where('key', 'phone')->where('status', 1)->value('value') ?? '096 711 9798 / 071 4 711 979';
        $hotelEmail   = \App\Models\ContactSetting::where('key', 'email')->where('status', 1)->value('value') ?? 'info@pnt-hotel.com';
        $khrRate      = \App\Models\ContactSetting::getExchangeRate() ?: 4050;

        $isMeeting = isset($booking->meeting_room_id) || ($booking instanceof \App\Models\MeetingBooking);

        $customerName  = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវទូទៅ');
        $customerPhone = $booking->customer_phone ?: ($booking->user->phone ?? 'N/A');
        $customerEmail = $booking->customer_email ?: ($booking->user->email ?? 'N/A');

        $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);

        if ($isMeeting) {
            $checkInFormatted = $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : 'N/A';
            $checkOutFormatted = $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : 'N/A';
            $durationLabel = ($booking->total_hours ?? 1) . ' ម៉ោង';
        } else {
            $checkInFormatted = $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : 'N/A';
            $checkOutFormatted = $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : 'N/A';
            $nightsCount = 1;
            if ($booking->check_in && $booking->check_out) {
                $cIn = \Carbon\Carbon::parse($booking->check_in);
                $cOut = \Carbon\Carbon::parse($booking->check_out);
                $nightsCount = max(1, $cIn->diffInDays($cOut));
            }
            $durationLabel = $nightsCount . ' យប់';
        }

        $payStatus = $booking->payment ? $booking->payment->status : 'paid';
        $payMethod = $booking->payment_method ?: ($booking->payment ? $booking->payment->method : 'cash');
        $transactionId = $booking->payment ? $booking->payment->transaction_id : null;
    @endphp

    <!-- ACTION BAR (HIDDEN WHEN PRINTING) -->
    <div class="max-w-4xl mx-auto mb-2 flex justify-between items-center no-print">
        <button onclick="window.history.back()" class="px-4 py-2 bg-white text-gray-700 hover:bg-gray-50 border border-gray-300 rounded-xl font-bold text-xs shadow-sm flex items-center gap-2 transition cursor-pointer">
            <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
        </button>

        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black text-xs shadow-md flex items-center gap-2 transition active:scale-95 cursor-pointer">
                <i class="fas fa-print text-sm"></i> ព្រីនវិក្កយបត្រ
            </button>
            <button onclick="window.close()" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl font-bold text-xs transition cursor-pointer">
                បិទ
            </button>
        </div>
    </div>

    <!-- MAIN INVOICE CONTAINER -->
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl print-shadow-none border border-gray-200 p-5 sm:p-8 relative overflow-hidden">
        
        <!-- TOP DECORATIVE BAR -->
        <div class="h-3 bg-gradient-to-r {{ $isMeeting ? 'from-purple-600 via-indigo-600 to-emerald-500' : 'from-blue-600 via-indigo-600 to-emerald-500' }} absolute top-0 left-0 right-0"></div>

        <!-- HEADER -->
        <div class="border-b border-gray-200 pb-6 mb-3">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 shrink-0 bg-yellow-50 rounded-2xl p-2 flex items-center justify-center border border-blue-100">
                        <img src="{{ asset('images/logo/P&t Palace Hotel.png') }}" alt="PNT Palace Hotel" class="w-full h-full object-contain" onerror="this.onerror=null; this.src='https://cdn-icons-png.flaticon.com/512/2983/2983780.png'">
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-gray-900 uppercase tracking-tight">សណ្ឋាគារ ភីអេនធី ផាលេស</h1>
                        <p class="text-xs {{ $isMeeting ? 'text-purple-600' : 'text-blue-600' }} font-extrabold tracking-widest uppercase mt-0.5">PNT PALACE HOTEL & RESORT</p>
                    </div>
                </div>

                <div class="sm:text-right">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight">វិក្កយបត្រ {{ $isMeeting ? '(សាលប្រជុំ)' : '' }}</h2>
                    <p class="text-sm font-mono font-extrabold {{ $isMeeting ? 'text-purple-600' : 'text-blue-600' }} mt-1">កូដ ៖ #{{ $booking->booking_code }}</p>
                    <p class="text-xs text-gray-500 font-medium mt-0.5">
                        កាលបរិច្ឆេទ ៖ <span class="font-bold text-gray-800">{{ date('d/m/Y h:i A', strtotime($booking->created_at ?? now())) }}</span>
                    </p>
                </div>
            </div>

            <!-- HOTEL ADDRESS & CONTACT -->
            <div class="mt-4 border-t-0 border-gray-100 flex flex-wrap items-center justify-between text-xs text-gray-500 gap-2">
                <div><i class="fas fa-map-marker-alt text-blue-500 mr-1.5"></i> {{ $hotelAddress }}</div>
                <div class="flex items-center gap-4">
                    <span><i class="fas fa-phone text-blue-500 mr-1.5"></i> {{ $hotelPhone }}</span>
                    <span><i class="fas fa-envelope text-blue-500 mr-1.5"></i> {{ $hotelEmail }}</span>
                </div>
            </div>
        </div>

        <!-- INFO GRID: CUSTOMER & BOOKING DETAILS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-4 p-5 bg-gray-50/80 rounded-2xl border border-gray-200/80 text-xs">
            <!-- CUSTOMER INFO -->
            <div class="space-y-2">
                <h3 class="font-black {{ $isMeeting ? 'text-purple-700' : 'text-blue-700' }} uppercase tracking-wider text-[11px] border-b border-gray-200 pb-1.5 flex items-center gap-1.5">
                    <i class="fas fa-user-tag"></i> ព័ត៌មានអតិថិជន
                </h3>
                <div class="space-y-1.5 text-gray-700 pt-1">
                    <div class="flex"><span class="w-24 text-gray-400 font-bold">ឈ្មោះអតិថិជន ៖</span> <strong class="text-gray-900 font-extrabold text-sm">{{ $customerName }}</strong></div>
                    <div class="flex"><span class="w-24 text-gray-400 font-bold">លេខទូរស័ព្ទ ៖</span> <span class="font-bold text-gray-800">{{ $customerPhone }}</span></div>
                    <div class="flex"><span class="w-24 text-gray-400 font-bold">អ៊ីមែល ៖</span> <span class="font-medium text-gray-700">{{ $customerEmail }}</span></div>
                </div>
            </div>

            <!-- BOOKING SUMMARY -->
            <div class="space-y-2">
                <h3 class="font-black {{ $isMeeting ? 'text-purple-700' : 'text-blue-700' }} uppercase tracking-wider text-[11px] border-b border-gray-200 pb-1.5 flex items-center gap-1.5">
                    <i class="fas fa-file-contract"></i> ព័ត៌មានការកក់
                </h3>
                <div class="space-y-1.5 text-gray-700 pt-1">
                    <div class="flex"><span class="w-28 text-gray-400 font-bold">ប្រភពការកក់ ៖</span> <span class="font-bold text-gray-800">{{ $isOnline ? 'កក់តាមអនឡាញ' : 'កក់ផ្ទាល់' }}</span></div>
                    <div class="flex"><span class="w-28 text-gray-400 font-bold">វិធីសាស្ត្រទូទាត់ ៖</span> <span class="font-bold text-gray-800 uppercase">{{ in_array($payMethod, ['qr', 'khqr']) ? 'ឃ្យូអរកូដ' : 'សាច់ប្រាក់' }}</span></div>
                    <div class="flex items-center"><span class="w-28 text-gray-400 font-bold">ស្ថានភាពទូទាត់ ៖</span> 
                        @if($payStatus === 'paid')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 inline-flex items-center gap-1">
                                <i class="fas fa-check-circle"></i> បានបង់រួច
                            </span>
                        @elseif($payStatus === 'pending')
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 inline-flex items-center gap-1">
                                <i class="fas fa-clock"></i> រង់ចាំពិនិត្យ
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-700 inline-flex items-center gap-1">
                                {{ strtoupper($payStatus) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- DETAILS TABLE -->
        <div class="overflow-x-auto mb-8">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 font-black uppercase text-[11px] border-y border-gray-200">
                        <th class="py-3 px-4 w-12 text-center">ល.រ</th>
                        <th class="py-3 px-4">{{ $isMeeting ? 'សាលប្រជុំ & ទម្រង់រៀបចំ' : 'បន្ទប់ & ប្រភេទបន្ទប់' }}</th>
                        <th class="py-3 px-4 text-center">{{ $isMeeting ? 'ថ្ងៃប្រជុំ - ម៉ោង' : 'ថ្ងៃចូល (Check-In) - ថ្ងៃចេញ (Check-Out)' }}</th>
                        <th class="py-3 px-4 text-center">រយៈពេល</th>
                        <th class="py-3 px-4 text-right">តម្លៃសរុប</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    @if($isMeeting)
                        @php
                            $rNum = $booking->room->room_number ?? 'N/A';
                            $rType = $booking->room->roomType->name ?? 'សាលប្រជុំ';
                            $setupMap = [
                                'Classroom' => '🏫 ថ្នាក់រៀន (Classroom Setup)',
                                'Theater' => '🎭 មហោស្រព / សាលប្រជុំ (Theater Setup)',
                                'Theatre' => '🎭 មហោស្រព / សាលប្រជុំ (Theater Setup)',
                                'U-Shape' => '🔄 អក្សរ យូ (U-Shape Setup)',
                                'Boardroom' => '👔 ប្រជុំក្រុមប្រឹក្សា (Boardroom Setup)',
                                'Banquet' => '🍽️ តុមូលពិធីលៀងសាយភោជន៍ (Banquet Setup)',
                                'Cocktail' => '🍸 ជប់លៀងឈរ (Cocktail / Reception)',
                                'Hollow Square' => '🔲 ការ៉េចតុកោណ (Hollow Square)',
                                'Cabaret' => '🎪 តុមូលកន្លះវង់ (Cabaret Setup)',
                                'Custom' => '✨ រៀបចំពិសេសតាមការស្នើសុំ'
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-4 px-4 text-center font-bold text-gray-400">1</td>
                            <td class="py-4 px-4">
                                <div class="font-extrabold text-purple-600 text-sm">សាលប្រជុំ {{ $rNum }}</div>
                                <div class="text-[11px] text-gray-500 font-medium">{{ $rType }} @if($booking->attendees_count) ({{ $booking->attendees_count }} នាក់) @endif</div>
                                @if($booking->setup_style)
                                    <div class="text-[10px] text-gray-400 font-semibold mt-0.5">ទម្រង់រៀបចំ: {{ $setupMap[$booking->setup_style] ?? $booking->setup_style }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-emerald-600">
                                <div>{{ $checkInFormatted }} @if($checkInFormatted !== $checkOutFormatted) ដល់ {{ $checkOutFormatted }} @endif</div>
                                <div class="text-[10px] text-gray-500 font-normal mt-0.5">ម៉ោង {{ formatKhmerTime($booking->start_time) }} - {{ formatKhmerTime($booking->end_time) }}</div>
                            </td>
                            <td class="py-4 px-4 text-center font-extrabold">
                                <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-lg text-[10px] border border-purple-100">
                                    {{ $durationLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-black text-sm text-gray-900 font-mono">
                                ${{ number_format($booking->total_price, 2) }}
                            </td>
                        </tr>
                    @elseif($booking->details && $booking->details->count() > 0)
                        @foreach($booking->details as $idx => $detail)
                            @php
                                $rNum = $detail->room->room_number ?? 'N/A';
                                $rType = $detail->roomType->name ?? ($detail->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ');
                                $itemPrice = $detail->price_at_booking ?? ($booking->total_price / $booking->details->count());
                            @endphp
                            <tr class="hover:bg-gray-50/50">
                                <td class="py-4 px-4 text-center font-bold text-gray-400">{{ $idx + 1 }}</td>
                                <td class="py-4 px-4">
                                    <div class="font-extrabold text-blue-600 text-sm">បន្ទប់ {{ $rNum }}</div>
                                    <div class="text-[11px] text-gray-500 font-medium">{{ $rType }}</div>
                                </td>
                                <td class="py-4 px-4 text-center font-bold text-emerald-600">{{ $checkInFormatted }} ដល់ {{ $checkOutFormatted }}</td>
                                <td class="py-4 px-4 text-center font-extrabold">
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] border border-blue-100">
                                        {{ $durationLabel }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right font-black text-sm text-gray-900 font-mono">
                                    ${{ number_format($itemPrice, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        @php
                            $rNum = $booking->room->room_number ?? 'N/A';
                            $rType = $booking->room->roomType->name ?? ($booking->room->room_type->name ?? 'បន្ទប់ស្នាក់នៅ');
                        @endphp
                        <tr class="hover:bg-gray-50/50">
                            <td class="py-4 px-4 text-center font-bold text-gray-400">1</td>
                            <td class="py-4 px-4">
                                <div class="font-extrabold text-blue-600 text-sm">បន្ទប់ {{ $rNum }}</div>
                                <div class="text-[11px] text-gray-500 font-medium">{{ $rType }}</div>
                            </td>
                            <td class="py-4 px-4 text-center font-bold text-emerald-600">{{ $checkInFormatted }} ដល់ {{ $checkOutFormatted }}</td>
                            <td class="py-4 px-4 text-center font-extrabold">
                                <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-lg text-[10px] border border-blue-100">
                                    {{ $durationLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-black text-sm text-gray-900 font-mono">
                                ${{ number_format($booking->total_price, 2) }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- PAYMENT SUMMARY & GRAND TOTAL -->
        <div class="flex flex-col sm:flex-row justify-between items-start gap-6 border-t-2 border-gray-200 pt-6 mb-8">
            <div class="text-xs text-gray-500 space-y-1 max-w-md">
                <p class="font-black text-gray-800 uppercase tracking-wider">ចំណាំ៖</p>
                <p class="leading-relaxed">• សូមរក្សាទុកវិក្កយបត្រនេះសម្រាប់ទូទាត់ប្រាក់។</p>
                <p class="leading-relaxed">• អត្រាប្តូរប្រាក់ផ្លូវការ ៖ <strong>១ ដុល្លារ = {{ number_format($khrRate) }} រៀល</strong></p>
                @if($transactionId)
                    <p class="font-mono text-gray-600">• លេខប្រតិបត្តិការ (TXN ID): <strong>{{ $transactionId }}</strong></p>
                @endif
            </div>

            <div class="w-full sm:w-72 {{ $isMeeting ? 'bg-purple-50/50 border-purple-100' : 'bg-blue-50/50 border-blue-100' }} p-2 rounded-2xl border space-y-1">
                <div class="flex justify-between items-center text-xs text-gray-600">
                    <span>សរុបទឹកប្រាក់ ៖</span>
                    <span class="font-bold text-gray-900 font-mono">${{ number_format($booking->total_price, 2) }}</span>
                </div>
                <div class="border-t {{ $isMeeting ? 'border-purple-200/80' : 'border-blue-200/80' }} pt-2 flex justify-between items-baseline">
                    <span class="font-black text-gray-900 text-sm uppercase">សរុបត្រូវបង់ ៖</span>
                    <div class="text-right">
                        <span class="text-2xl font-black {{ $isMeeting ? 'text-purple-600' : 'text-blue-600' }} font-mono block">${{ number_format($booking->total_price, 2) }}</span>
                        <span class="text-xs font-bold text-gray-600 font-mono mt-0.5 block">(~ {{ number_format($booking->total_price * $khrRate) }} ៛)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- SIGNATURE & STAMP SECTION -->
        <div class="grid grid-cols-2 gap-8 pt-8 border-t border-gray-200 text-center text-xs">
            <div class="space-y-12">
                <p class="font-bold text-gray-700 uppercase">ហត្ថលេខាអតិថិជន</p>
                <div class="border-b border-gray-300 w-48 mx-auto"></div>
                <p class="text-[11px] text-gray-400 font-medium">{{ $customerName }}</p>
            </div>

            <div class="space-y-12">
                <p class="font-bold text-gray-700 uppercase">អ្នកទទួលប្រាក់ / បុគ្គលិក</p>
                <div class="border-b border-gray-300 w-48 mx-auto"></div>
                <p class="text-[11px] text-gray-400 font-medium">សណ្ឋាគារ ភីអេនធី ផាលេស</p>
            </div>
        </div>

        <!-- FOOTER THANK YOU -->
        <div class="mt-10 pt-4 border-t border-gray-100 text-center text-xs text-gray-400">
            <p class="font-bold text-gray-600">សូមអរគុណសម្រាប់ការជ្រើសរើស សណ្ឋាគារ ភីអេនធី ផាលេស !</p>
            <p class="text-[10px] text-gray-400 mt-0.5">Thank you for choosing PNT Palace Hotel & Resort</p>
        </div>
    </div>

    @if(request()->has('auto_print'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
    @endif
</body>
</html>
