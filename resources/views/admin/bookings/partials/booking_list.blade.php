@php
$setupMap = [
    'Classroom' => 'ថ្នាក់រៀន',
    'Theater' => 'មហោស្រព',
    'Theatre' => 'មហោស្រព',
    'U-Shape' => 'អក្សរ យូ',
    'Boardroom' => 'ប្រជុំក្រុមប្រឹក្សា',
    'Banquet' => 'តុមូលពិធី',
    'Cocktail' => 'ជប់លៀងឈរ',
    'Hollow Square' => 'ការ៉េចតុកោណ',
    'Cabaret' => 'តុមូលកន្លះវង់',
    'Custom' => 'រៀបចំពិសេស'
];

if (!function_exists('formatKhmerTimeCombined')) {
    function formatKhmerTimeCombined($timeStr) {
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
@endphp

{{-- 1. GRID VIEW --}}
<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" x-transition>
    @forelse($bookings as $booking)
    @php
        $isMeeting = isset($booking->meeting_room_id);
        $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);
        $checkInDate = $isMeeting ? ($booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : '') : ($booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : '');
        $checkOutDate = $isMeeting ? ($booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : '') : ($booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : '');
        
        $durationCount = 1;
        if ($isMeeting) {
            if ($booking->start_date && $booking->end_date) {
                $s = \Carbon\Carbon::parse($booking->start_date)->startOfDay();
                $e = \Carbon\Carbon::parse($booking->end_date)->startOfDay();
                $durationCount = max(1, $s->diffInDays($e) + 1);
            }
        } else {
            if ($booking->check_in && $booking->check_out) {
                $s = \Carbon\Carbon::parse($booking->check_in)->startOfDay();
                $e = \Carbon\Carbon::parse($booking->check_out)->startOfDay();
                $durationCount = max(1, $s->diffInDays($e));
            }
        }

        $roomNumber = $booking->room->room_number ?? 'N/A';
        $roomTypeName = $booking->room->roomType->name ?? '';
        $customerName = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវមកផ្ទាល់');

        $payStatus = $booking->payment ? $booking->payment->status : 'paid';
        $payMethod = $booking->payment_method ?: ($booking->payment ? $booking->payment->method : 'cash');
    @endphp
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border border-gray-100 dark:border-gray-800 relative overflow-hidden flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between mb-4">
                <span class="font-black text-blue-600 dark:text-blue-400 text-sm tracking-wider">#{{ $booking->booking_code }}</span>
                @php
                $statusBadges = [
                    'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                    'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                    'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                ];
                $statusText = [
                    'pending' => 'រង់ចាំពិនិត្យ',
                    'confirmed' => 'បានបញ្ជាក់',
                    'completed' => 'បានបញ្ចប់',
                    'cancelled' => 'បានបោះបង់',
                ];
                @endphp
                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $statusBadges[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statusText[$booking->status] ?? $booking->status }}
                </span>
            </div>

            <div class="h-36 -mx-5 -mt-1 mb-4 overflow-hidden relative group/img bg-gray-100 dark:bg-gray-800">
                @if($booking->room && $booking->room->roomType && $booking->room->roomType->images && $booking->room->roomType->images->count() > 0)
                <img src="{{ asset('storage/' . $booking->room->roomType->images->first()->image_path) }}"
                    class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500" alt="Room Image">
                @else
                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                    <i class="{{ $isMeeting ? 'fas fa-users' : 'fas fa-bed' }} text-3xl mb-1 text-blue-500"></i>
                    <span class="text-[10px] font-bold">{{ $isMeeting ? 'សាលប្រជុំ' : 'បន្ទប់ស្នាក់នៅ' }}</span>
                </div>
                @endif

                <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity">
                    @if($isMeeting)
                    <a href="{{ route('meeting-bookings.print-invoice', $booking->id) }}" target="_blank" class="w-9 h-9 bg-white text-purple-600 rounded-xl hover:scale-110 transition flex items-center justify-center shadow-lg" title="ព្រីនវិក្កយបត្រ">
                        <i class="fas fa-print"></i>
                    </a>
                    @else
                    <a href="{{ route('room-bookings.print-invoice', $booking->id) }}" target="_blank" class="w-9 h-9 bg-white text-blue-600 rounded-xl hover:scale-110 transition flex items-center justify-center shadow-lg" title="ព្រីនវិក្កយបត្រ">
                        <i class="fas fa-print"></i>
                    </a>
                    @endif
                    @if($booking->payment && $booking->payment->payment_slip)
                    <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="w-9 h-9 bg-white text-emerald-600 rounded-xl hover:scale-110 transition flex items-center justify-center shadow-lg" title="មើលបង្កាន់ដៃបង់ប្រាក់">
                        <i class="fas fa-file-image"></i>
                    </button>
                    @endif
                </div>
            </div>

            <div class="space-y-2.5">
                <div>
                    <div class="flex items-center justify-between gap-1">
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1">
                            <i class="fas fa-user text-xs text-gray-400 mr-1"></i> {{ $customerName }}
                        </h4>
                        @if($isOnline)
                        <span class="px-2 py-0.5 rounded text-[9px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200/50">
                            អនឡាញ
                        </span>
                        @else
                        <span class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200/50">
                            ផ្ទាល់
                        </span>
                        @endif
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1">
                        @if($isMeeting)
                        <span class="text-purple-600 dark:text-purple-400 font-bold"><i class="fas fa-users mr-1"></i> សាលប្រជុំ {{ $roomNumber }}</span>
                        @else
                        <span class="text-blue-600 dark:text-blue-400 font-bold"><i class="fas fa-door-closed mr-1"></i> បន្ទប់ {{ $roomNumber }}</span>
                        @endif
                        @if($roomTypeName)
                        <span class="text-gray-400 font-normal">({{ $roomTypeName }})</span>
                        @endif
                    </p>
                    @if($isMeeting && $booking->setup_style)
                        <p class="text-[10px] text-purple-600 dark:text-purple-400 font-semibold mt-0.5">
                            <i class="fas fa-chair text-[9px]"></i> {{ $setupMap[$booking->setup_style] ?? $booking->setup_style }} @if($booking->attendees_count) ({{ $booking->attendees_count }} នាក់) @endif
                        </p>
                    @endif
                </div>

                <div class="flex items-center justify-between text-xs py-2 border-y border-gray-100 dark:border-gray-800">
                    <div>
                        <p class="text-[9px] text-gray-400 font-black uppercase">ចាប់ផ្តើម</p>
                        <p class="font-bold text-emerald-600 dark:text-emerald-400">{{ $checkInDate }}</p>
                    </div>
                    <div class="flex flex-col items-center">
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-purple-50 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                            {{ $durationCount }} {{ $isMeeting ? 'ថ្ងៃ' : 'យប់' }}
                        </span>
                        <i class="fas fa-arrow-right text-[9px] text-gray-300 mt-0.5"></i>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-gray-400 font-black uppercase">បញ្ចប់</p>
                        <p class="font-bold text-rose-500">{{ $checkOutDate }}</p>
                    </div>
                </div>

                @if($isMeeting)
                <div class="text-center text-[10px] font-bold text-gray-400">
                    <i class="far fa-clock text-amber-500 mr-1"></i>{{ formatKhmerTimeCombined($booking->start_time) }} - {{ formatKhmerTimeCombined($booking->end_time) }}
                </div>
                @endif
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 space-y-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[9px] text-gray-400 font-black uppercase">តម្លៃសរុប</p>
                    <p class="text-base font-black text-emerald-600 dark:text-emerald-400">${{ number_format($booking->total_price, 2) }}</p>
                    <p class="text-[10px] font-bold text-gray-400 font-mono">({{ number_format($booking->total_price * $khrRate) }} ៛)</p>
                </div>
                
                <div class="text-right">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-600 dark:bg-blue-950/30 dark:text-blue-400 uppercase block">
                        {{ in_array($payMethod, ['qr', 'khqr']) ? 'ឃ្យូអរកូដ' : 'សាច់ប្រាក់' }}
                    </span>
                    <span class="text-[9px] font-bold text-gray-400 mt-0.5 block">
                        {{ $payStatus === 'paid' ? 'បានបង់រួច' : 'រង់ចាំបង់' }}
                    </span>
                </div>
            </div>

            @if($booking->payment && $booking->payment->payment_slip)
            <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="w-full py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white rounded-lg text-[10px] font-bold transition flex items-center justify-center gap-1 cursor-pointer">
                <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
            </button>
            @endif
        </div>
    </div>
    @empty
    <div class="col-span-full">
        @include('admin.bookings.partials.empty_state')
    </div>
    @endforelse
</div>

{{-- 2. LIST VIEW --}}
<div x-show="viewMode === 'list'" class="space-y-3" x-transition>
    @forelse($bookings as $booking)
    @php
        $isMeeting = isset($booking->meeting_room_id);
        $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);
        $checkInDate = $isMeeting ? ($booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : '') : ($booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : '');
        $checkOutDate = $isMeeting ? ($booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : '') : ($booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : '');
        
        $durationCount = 1;
        if ($isMeeting) {
            if ($booking->start_date && $booking->end_date) {
                $s = \Carbon\Carbon::parse($booking->start_date)->startOfDay();
                $e = \Carbon\Carbon::parse($booking->end_date)->startOfDay();
                $durationCount = max(1, $s->diffInDays($e) + 1);
            }
        } else {
            if ($booking->check_in && $booking->check_out) {
                $s = \Carbon\Carbon::parse($booking->check_in)->startOfDay();
                $e = \Carbon\Carbon::parse($booking->check_out)->startOfDay();
                $durationCount = max(1, $s->diffInDays($e));
            }
        }

        $roomNumber = $booking->room->room_number ?? 'N/A';
        $customerName = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវមកផ្ទាល់');

        $payStatus = $booking->payment ? $booking->payment->status : 'paid';
        $payMethod = $booking->payment_method ?: ($booking->payment ? $booking->payment->method : 'cash');
    @endphp
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-gray-50 dark:hover:bg-gray-850 transition-all border border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-xs shrink-0">
                #{{ substr($booking->booking_code, -4) }}
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="font-bold text-sm text-gray-900 dark:text-white uppercase">{{ $customerName }}</h4>
                    <span class="text-xs text-gray-400 font-semibold">({{ $booking->booking_code }})</span>
                    @if($isOnline)
                    <span class="px-2 py-0.5 rounded text-[9px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200/50">
                        <i class="fa-solid fa-globe mr-0.5"></i> អនឡាញ
                    </span>
                    @else
                    <span class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200/50">
                        <i class="fa-solid fa-store mr-0.5"></i> ផ្ទាល់
                    </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    @if($isMeeting)
                    <span class="font-bold text-purple-600 dark:text-purple-400">សាលប្រជុំ {{ $roomNumber }}</span>
                    @else
                    <span class="font-bold text-blue-600 dark:text-blue-400">បន្ទប់ {{ $roomNumber }}</span>
                    @endif
                    <span class="mx-1">•</span>
                    <i class="far fa-calendar-alt text-gray-400"></i> {{ $checkInDate }} ដល់ {{ $checkOutDate }} ({{ $durationCount }} {{ $isMeeting ? 'ថ្ងៃ' : 'យប់' }})
                    @if($isMeeting)
                    <span class="mx-1">•</span>
                    <i class="far fa-clock text-amber-500"></i> {{ formatKhmerTimeCombined($booking->start_time) }} - {{ formatKhmerTimeCombined($booking->end_time) }}
                    @endif
                </p>
            </div>
        </div>

        <div class="flex items-center justify-between md:justify-end gap-6 shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100 dark:border-gray-800">
            <div class="text-right">
                <p class="text-base font-black text-emerald-600 dark:text-emerald-400">${{ number_format($booking->total_price, 2) }}</p>
                <div class="flex flex-col items-end gap-0.5 mt-0.5">
                    @php
                    $statusBadges = [
                        'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'confirmed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'cancelled' => 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                    ];
                    $statusText = [
                        'pending' => 'រង់ចាំ',
                        'confirmed' => 'បានបញ្ជាក់',
                        'completed' => 'រួចរាល់',
                        'cancelled' => 'បានបោះបង់',
                    ];
                    @endphp
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $statusBadges[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusText[$booking->status] ?? $booking->status }}
                    </span>
                    @if($booking->payment && $booking->payment->payment_slip)
                    <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1 cursor-pointer">
                        <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                    </button>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-1">
                @if($isMeeting)
                <a href="{{ route('meeting-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2.5 bg-purple-50 text-purple-600 hover:bg-purple-600 hover:text-white dark:bg-purple-950/30 dark:text-purple-400 rounded-xl transition-all" title="ព្រីនវិក្កយបត្រ">
                    <i class="fas fa-print text-xs"></i>
                </a>
                @else
                <a href="{{ route('room-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-blue-950/30 dark:text-blue-400 rounded-xl transition-all" title="ព្រីនវិក្កយបត្រ">
                    <i class="fas fa-print text-xs"></i>
                </a>
                @endif
                <button @click="deleteBooking({{ $booking->id }})" class="p-2.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white dark:bg-rose-950/30 dark:text-rose-400 rounded-xl transition-all" title="លុប">
                    <i class="fas fa-trash-alt text-xs"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        @include('admin.bookings.partials.empty_state')
    </div>
    @endforelse
</div>

{{-- 3. TABLE VIEW --}}
<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-sm" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50/70 dark:bg-gray-850 border-b border-gray-100 dark:border-gray-800 text-[11px] uppercase font-black text-gray-400 tracking-wider">
                    <th class="px-6 py-3">លេខកូដ</th>
                    <th class="px-6 py-5">អតិថិជន & ប្រភព</th>
                    <th class="px-6 py-5">បន្ទប់ / សាលប្រជុំ</th>
                    <th class="px-6 py-5 text-center">កាលបរិច្ឆេទ & ម៉ោង</th>
                    <th class="px-6 py-5">តម្លៃសរុប</th>
                    <th class="px-6 py-5 text-center">ការទូទាត់ប្រាក់</th>
                    <th class="px-6 py-5 text-center">ស្ថានភាព</th>
                    <th class="px-6 py-5 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($bookings as $booking)
                @php
                    $isMeeting = isset($booking->meeting_room_id);
                    $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);
                    
                    $checkInDate = $isMeeting ? ($booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : '') : ($booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : '');
                    $checkOutDate = $isMeeting ? ($booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : '') : ($booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : '');
                    
                    $durationCount = 1;
                    if ($isMeeting) {
                        if ($booking->start_date && $booking->end_date) {
                            $s = \Carbon\Carbon::parse($booking->start_date)->startOfDay();
                            $e = \Carbon\Carbon::parse($booking->end_date)->startOfDay();
                            $durationCount = max(1, $s->diffInDays($e) + 1);
                        }
                    } else {
                        if ($booking->check_in && $booking->check_out) {
                            $s = \Carbon\Carbon::parse($booking->check_in)->startOfDay();
                            $e = \Carbon\Carbon::parse($booking->check_out)->startOfDay();
                            $durationCount = max(1, $s->diffInDays($e));
                        }
                    }
                    
                    $roomNumber = $booking->room->room_number ?? 'N/A';
                    $roomTypeName = $booking->room->roomType->name ?? '';
                    $customerName = $booking->customer_name ?: ($booking->user->name ?? 'ភ្ញៀវមកផ្ទាល់');

                    $payStatus = $booking->payment ? $booking->payment->status : 'paid';
                    $payMethod = $booking->payment_method ?: ($booking->payment ? $booking->payment->method : 'cash');
                @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-850/50 transition-colors">
                    <td class="px-6 py-4 font-black text-blue-600 dark:text-blue-400 whitespace-nowrap">
                        #{{ $booking->booking_code }}
                    </td>

                    {{-- CUSTOMER & SOURCE --}}
                    <td class="px-6 py-4">
                        <div class="font-extrabold text-gray-900 dark:text-white flex items-center gap-2">
                            <span>{{ $customerName }}</span>
                            @if($isOnline)
                            <span class="px-2 py-0.5 rounded text-[9px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200/50">
                                <i class="fa-solid fa-globe mr-0.5"></i> អនឡាញ
                            </span>
                            @else
                            <span class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200/50">
                                <i class="fa-solid fa-store mr-0.5"></i> ផ្ទាល់
                            </span>
                            @endif
                        </div>
                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $booking->customer_phone ?? ($booking->user->phone ?? 'N/A') }}</div>
                        <div class="text-[11px] text-gray-400 mt-0.5">{{ $booking->customer_email ?? ($booking->user->email ?? 'N/A') }}</div>
                    </td>

                    {{-- ROOM & SETUP --}}
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 dark:text-gray-100">
                            @if($isMeeting)
                            <span class="text-purple-600 dark:text-purple-400 font-extrabold">សាលប្រជុំ {{ $roomNumber }}</span>
                            @else
                            <span class="text-blue-600 dark:text-blue-400 font-extrabold">បន្ទប់ {{ $roomNumber }}</span>
                            @endif
                        </div>
                        <div class="text-[11px] text-gray-500 font-medium mt-0.5">{{ $roomTypeName }}</div>
                        @if($isMeeting)
                            <div class="text-[10px] text-purple-600 dark:text-purple-400 font-bold mt-1">
                                <i class="fas fa-users mr-1"></i>{{ $booking->attendees_count ?? 10 }} នាក់
                                @if($booking->setup_style)
                                    <span class="text-gray-400 font-semibold ml-1">• {{ $setupMap[$booking->setup_style] ?? $booking->setup_style }}</span>
                                @endif
                            </div>
                        @endif
                    </td>

                    {{-- DATES & TIMES --}}
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex flex-col items-center gap-1 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">{{ $checkInDate }}</span>
                                <i class="fa-solid fa-arrow-right text-[10px] text-gray-400"></i>
                                <span class="text-xs font-extrabold text-rose-600 dark:text-rose-400">{{ $checkOutDate }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 dark:bg-purple-900/60 dark:text-purple-300">
                                    {{ $durationCount }} {{ $isMeeting ? 'ថ្ងៃ' : 'យប់' }}
                                </span>
                            </div>
                            @if($isMeeting)
                            <div class="text-[10px] font-bold text-gray-400">
                                <i class="far fa-clock text-amber-500 mr-1"></i>{{ formatKhmerTimeCombined($booking->start_time) }} - {{ formatKhmerTimeCombined($booking->end_time) }} ({{ $booking->total_hours }}h)
                            </div>
                            @endif
                        </div>
                    </td>

                    {{-- TOTAL PRICE --}}
                    <td class="px-6 py-4 font-black text-gray-900 dark:text-white whitespace-nowrap">
                        <div>${{ number_format($booking->total_price, 2) }}</div>
                        <div class="text-[11px] text-gray-400 font-normal font-mono">({{ number_format($booking->total_price * $khrRate) }} ៛)</div>
                    </td>

                    {{-- PAYMENT STATUS & SLIP --}}
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <div class="flex flex-col items-center gap-1">
                            @if($payStatus === 'paid')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300 inline-flex items-center gap-1">
                                    <i class="fas fa-check-circle text-[9px]"></i> បានបង់រួច
                                </span>
                            @elseif($payStatus === 'pending')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300 inline-flex items-center gap-1">
                                    <i class="fas fa-clock text-[9px]"></i> រង់ចាំបង់
                                </span>
                            @elseif($payStatus === 'refunded')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300 inline-flex items-center gap-1">
                                    <i class="fas fa-undo text-[9px]"></i> បានសងវិញ
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-300 inline-flex items-center gap-1">
                                    <i class="fas fa-times-circle text-[9px]"></i> បរាជ័យ
                                </span>
                            @endif

                            <span class="text-[10px] text-gray-400 font-bold uppercase">
                                @if(in_array($payMethod, ['qr', 'khqr']))
                                    ឃ្យូអរកូដ
                                @else
                                    ប្រាក់សុទ្ធ
                                @endif
                            </span>

                            @if($booking->payment && $booking->payment->payment_slip)
                            <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="mt-1 px-2 py-0.5 rounded text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center justify-center gap-1 cursor-pointer" title="មើលបង្កាន់ដៃបង់ប្រាក់">
                                <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                            </button>
                            @endif
                        </div>
                    </td>

                    {{-- BOOKING STATUS --}}
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        @if($booking->status === 'confirmed')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                            បានបញ្ជាក់
                        </span>
                        @elseif($booking->status === 'completed')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                            រួចរាល់
                        </span>
                        @elseif($booking->status === 'cancelled')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400">
                            បានបោះបង់
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400">
                            រង់ចាំពិនិត្យ
                        </span>
                        @endif
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex justify-end items-center gap-1">
                            @if($isMeeting)
                            <a href="{{ route('meeting-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2 text-purple-600 hover:text-purple-800 transition-colors" title="ព្រីនវិក្កយបត្រ (Print Invoice)">
                                <i class="fas fa-print text-sm"></i>
                            </a>
                            @else
                            <a href="{{ route('room-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2 text-blue-600 hover:text-blue-800 transition-colors" title="ព្រីនវិក្កយបត្រ (Print Invoice)">
                                <i class="fas fa-print text-sm"></i>
                            </a>
                            @endif
                            <button type="button" @click="deleteBooking({{ $booking->id }})" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-12">
                        @include('admin.bookings.partials.empty_state')
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $bookings->links() }}
</div>