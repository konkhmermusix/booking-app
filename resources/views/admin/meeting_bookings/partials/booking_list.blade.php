@php
$statusColors = [
    'pending' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
    'confirmed' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
    'completed' => 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400',
    'cancelled' => 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
];
$statusLabels = [
    'pending' => 'រង់ចាំ',
    'confirmed' => 'បានបញ្ជាក់',
    'completed' => 'បានបញ្ចប់',
    'cancelled' => 'បោះបង់',
];
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
@endphp

{{-- 1. GRID VIEW --}}
<div x-show="!viewMode || viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition>
    @forelse($bookings as $booking)
    @php
        $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);
        $customerName = $booking->customer_name ?: ($booking->user->name ?? 'អតិថិជនអនឡាញ');
        $roomNumber = $booking->room->room_number ?? 'N/A';
        $roomTypeName = $booking->room->roomType->name ?? 'សាលប្រជុំ';
        $startDateFormatted = $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : 'N/A';
        $endDateFormatted = $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : 'N/A';
        $daysCount = 1;
        if ($booking->start_date && $booking->end_date) {
            $s = \Carbon\Carbon::parse($booking->start_date)->startOfDay();
            $e = \Carbon\Carbon::parse($booking->end_date)->startOfDay();
            $daysCount = max(1, $s->diffInDays($e) + 1);
        }
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border border-transparent dark:border-gray-800 relative overflow-hidden">

        <div class="absolute top-4 left-4 z-10">
            @if($isOnline)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-600 text-white shadow-md">
                <i class="fa-solid fa-globe text-[9px]"></i> អនឡាញ
            </span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-600 text-white shadow-md">
                <i class="fa-solid fa-store text-[9px]"></i> ផ្ទាល់
            </span>
            @endif
        </div>

        <div class="absolute top-4 right-4 z-10">
            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $statusLabels[$booking->status] ?? $booking->status }}
            </span>
        </div>

        <div class="h-40 -mx-5 -mt-5 mb-4 overflow-hidden relative group/img">
            @if($booking->room && $booking->room->roomType && $booking->room->roomType->images && $booking->room->roomType->images->count() > 0)
            <img src="{{ asset('storage/' . $booking->room->roomType->images->first()->image_path) }}"
                class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500" alt="Meeting Room Image">
            @else
            <div class="w-full h-full bg-purple-50 dark:bg-purple-950/40 flex items-center justify-center">
                <i class="fa-solid fa-people-roof text-purple-300 dark:text-purple-600 text-4xl"></i>
            </div>
            @endif

            <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity">
                <a href="{{ route('meeting-bookings.print-invoice', $booking->id) }}" target="_blank" class="w-9 h-9 bg-white text-purple-600 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="ព្រីនវិក្កយបត្រ (Print Invoice)">
                    <i class="fas fa-print"></i>
                </a>
                @if($booking->payment && $booking->payment->payment_slip)
                <button @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="w-9 h-9 bg-white text-emerald-600 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="មើល Slip បង់ប្រាក់">
                    <i class="fas fa-file-image"></i>
                </button>
                @endif
                <button @click="viewDetail({{ $booking->toJson() }})" class="w-9 h-9 bg-white text-purple-600 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="មើលលម្អិត">
                    <i class="fas fa-eye"></i>
                </button>
                <button @click="editBooking({{ $booking->toJson() }})" class="w-9 h-9 bg-white text-amber-500 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="កែសម្រួល">
                    <i class="fas fa-edit"></i>
                </button>
                <button @click="deleteBooking({{ $booking->id }})" class="w-9 h-9 bg-white text-rose-600 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="លុប">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <h3 class="font-black text-gray-800 dark:text-gray-100 text-sm">#{{ $booking->booking_code }}</h3>
                <p class="text-xs font-black text-purple-600 dark:text-purple-400 mt-0.5">
                    សាលប្រជុំ {{ $roomNumber }} <span class="text-gray-400 font-medium text-[11px]">({{ $roomTypeName }})</span>
                </p>
            </div>

            <div class="flex items-center gap-2 py-2 border-y border-gray-100 dark:border-gray-700">
                <div class="flex-1">
                    <p class="text-[9px] text-gray-400 uppercase font-black">ថ្ងៃចាប់ផ្តើម</p>
                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $startDateFormatted }}</p>
                </div>
                <div class="flex flex-col items-center gap-0.5">
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-purple-50 text-purple-600 dark:bg-purple-900/40 dark:text-purple-300 border border-purple-200/50">
                        {{ $daysCount }} ថ្ងៃ | {{ $booking->total_hours }}h
                    </span>
                    <span class="text-[9px] font-bold text-gray-400">{{ formatKhmerTime($booking->start_time) }} - {{ formatKhmerTime($booking->end_time) }}</span>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-[9px] text-gray-400 uppercase font-black">ថ្ងៃបញ្ចប់</p>
                    <p class="text-xs font-bold text-rose-600 dark:text-rose-400">{{ $endDateFormatted }}</p>
                </div>
            </div>

            @php
                $payStatus = $booking->payment ? $booking->payment->status : 'paid';
                $payMethod = $booking->payment_method ?: ($booking->payment ? $booking->payment->method : 'cash');
            @endphp
            <div class="flex justify-between items-end pt-2 border-t border-gray-100 dark:border-gray-700">
                <div class="space-y-0.5">
                    <span class="text-xs font-bold text-gray-600 dark:text-gray-300 truncate max-w-[120px] block">
                        <i class="fa-regular fa-user mr-1 text-gray-400"></i>{{ $customerName }}
                    </span>
                    <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 block">
                        <i class="fas fa-users text-[9px] mr-1"></i>{{ $booking->attendees_count ?? 10 }} នាក់
                    </span>
                    @if($booking->payment && $booking->payment->payment_slip)
                    <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1 mt-1 cursor-pointer">
                        <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                    </button>
                    @endif
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-gray-400 font-bold uppercase block">
                        {{ in_array($payMethod, ['qr', 'khqr']) ? 'ឃ្យូអរកូដ' : 'សាច់ប្រាក់' }} ({{ $payStatus === 'paid' ? 'បានបង់រួច' : 'រង់ចាំ' }})
                    </span>
                    <span class="text-lg font-black text-purple-600">${{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">@include('admin.room_bookings.partials.empty_state')</div>
    @endforelse
</div>

{{-- 2. LIST VIEW --}}
<div x-show="viewMode === 'list'" class="space-y-3" x-transition>
    @forelse($bookings as $booking)
    @php
        $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);
        $customerName = $booking->customer_name ?: ($booking->user->name);
        $roomNumber = $booking->room->room_number ?? 'N/A';
        $roomTypeName = $booking->room->roomType->name;
        $startDateFormatted = $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : 'N/A';
        $endDateFormatted = $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : 'N/A';
        $daysCount = 1;
        if ($booking->start_date && $booking->end_date) {
            $s = \Carbon\Carbon::parse($booking->start_date)->startOfDay();
            $e = \Carbon\Carbon::parse($booking->end_date)->startOfDay();
            $daysCount = max(1, $s->diffInDays($e) + 1);
        }
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border border-transparent dark:border-gray-800">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-purple-50 dark:bg-purple-900/20 flex flex-col items-center justify-center text-purple-600 dark:text-purple-400 font-black text-xs">
                <i class="fa-solid fa-people-roof text-sm"></i>
                <span class="text-[10px] mt-0.5">#{{ substr($booking->booking_code, -4) }}</span>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100">
                        សាលប្រជុំ {{ $roomNumber }} <span class="text-purple-600 font-black">({{ $roomTypeName }})</span>
                    </h4>
                    @if($isOnline)
                    <span class="px-2 py-0.5 rounded text-[9px] font-black bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 border border-indigo-200/50">
                        <i class="fa-solid fa-globe mr-0.5"></i> អនឡាញ
                    </span>
                    @else
                    <span class="px-2 py-0.5 rounded text-[9px] font-black bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200/50">
                        <i class="fa-solid fa-store mr-0.5"></i> ផ្ទាល់
                    </span>
                    @endif
                    <span class="px-2 py-0.5 rounded text-[9px] font-black bg-purple-50 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 border border-purple-200/50">
                        <i class="fas fa-users mr-0.5"></i> {{ $booking->attendees_count ?? 10 }} នាក់
                    </span>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <i class="fa-regular fa-user mr-1"></i><strong>{{ $customerName }}</strong>
                    <span class="mx-2 text-gray-300">|</span>
                    <i class="fa-regular fa-calendar-days text-emerald-500 mr-1"></i>{{ $startDateFormatted }} ដល់ {{ $endDateFormatted }} (<strong class="text-emerald-600 dark:text-emerald-400">{{ $daysCount }} ថ្ងៃ</strong>) ({{ formatKhmerTime($booking->start_time) }} - {{ formatKhmerTime($booking->end_time) }})
                    <span class="mx-2 text-gray-300">|</span>
                    សរុប: <strong class="text-purple-600 font-black">${{ number_format($booking->total_price, 2) }}</strong>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex flex-col items-end gap-0.5">
                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ $statusLabels[$booking->status] ?? $booking->status }}
                </span>
                <span class="text-[10px] text-gray-400 font-bold uppercase">
                    {{ in_array($payMethod, ['qr', 'khqr']) ? 'ឃ្យូអរកូដ' : 'សាច់ប្រាក់' }} ({{ $payStatus === 'paid' ? 'បានបង់រួច' : 'រង់ចាំ' }})
                </span>
                @if($booking->payment && $booking->payment->payment_slip)
                <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="mt-1 px-2 py-0.5 rounded text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center justify-center gap-1 cursor-pointer">
                    <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                </button>
                @endif
            </div>
            <div class="flex gap-1 items-center">
                <a href="{{ route('meeting-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2 text-purple-600 hover:text-purple-800 dark:text-purple-400 transition-colors cursor-pointer" title="ព្រីនវិក្កយបត្រ (Print Invoice)">
                    <i class="fas fa-print text-sm"></i>
                </a>
                @if($booking->payment && $booking->payment->payment_slip)
                <button @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="p-2 text-emerald-500 hover:text-emerald-600 transition-colors cursor-pointer" title="មើល Slip បង់ប្រាក់">
                    <i class="fas fa-file-image text-sm"></i>
                </button>
                @endif
                <button @click="viewDetail({{ $booking->toJson() }})" class="p-2 text-gray-400 hover:text-purple-500 transition-colors cursor-pointer" title="មើលលម្អិត">
                    <i class="fas fa-eye text-sm"></i>
                </button>
                <button @click="editBooking({{ $booking->toJson() }})" class="p-2 text-gray-400 hover:text-amber-500 transition-colors cursor-pointer" title="កែសម្រួល">
                    <i class="fas fa-edit text-sm"></i>
                </button>
                <button @click="deleteBooking({{ $booking->id }})" class="p-2 text-gray-400 hover:text-rose-500 transition-colors cursor-pointer" title="លុប">
                    <i class="fas fa-trash-alt text-sm"></i>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="w-full">@include('admin.room_bookings.partials.empty_state')</div>
    @endforelse
</div>

{{-- 3. TABLE VIEW --}}
<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 shadow-sm" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                <tr class="text-[11px] uppercase font-black text-gray-400 tracking-widest">
                    <th class="px-6 py-3">លេខកូដ</th>
                    <th class="px-6 py-5">អតិថិជន & ប្រភព</th>
                    <th class="px-6 py-5">សាលប្រជុំ & ប្រភេទ</th>
                    <th class="px-6 py-5 text-center">កាលបរិច្ឆេទ & ម៉ោង</th>
                    <th class="px-6 py-5 text-center">ចំនួនអ្នកចូលរួម</th>
                    <th class="px-6 py-5 text-center">តម្លៃសរុប</th>
                    <th class="px-6 py-5 text-center">ការទូទាត់ប្រាក់</th>
                    <th class="px-6 py-5 text-center">ស្ថានភាព</th>
                    <th class="px-6 py-5 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                @forelse($bookings as $booking)
                @php
                    $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);
                    $customerName = $booking->customer_name ?: ($booking->user->name);
                    $roomNumber = $booking->room->room_number ?? 'N/A';
                    $roomTypeName = $booking->room->roomType->name ?? 'សាលប្រជុំ';
                    $startDateFormatted = $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') : 'N/A';
                    $endDateFormatted = $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') : 'N/A';
                    $daysCount = 1;
                    if ($booking->start_date && $booking->end_date) {
                        $s = \Carbon\Carbon::parse($booking->start_date)->startOfDay();
                        $e = \Carbon\Carbon::parse($booking->end_date)->startOfDay();
                        $daysCount = max(1, $s->diffInDays($e) + 1);
                    }
                    
                    $payStatus = $booking->payment ? $booking->payment->status : 'paid';
                    $payMethod = $booking->payment_method ?: ($booking->payment ? $booking->payment->method : 'cash');
                @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 font-black text-purple-600">#{{ $booking->booking_code }}</td>

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

                    {{-- ROOM & ROOM TYPE --}}
                    <td class="px-6 py-4">
                        <div class="font-extrabold text-purple-600 dark:text-purple-400">សាលប្រជុំ {{ $roomNumber }}</div>
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $roomTypeName }}</div>
                    </td>

                    {{-- DATES & TIMES --}}
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex flex-col items-center gap-1 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">{{ $startDateFormatted }}</span>
                                <i class="fa-solid fa-arrow-right text-[10px] text-gray-400"></i>
                                <span class="text-xs font-extrabold text-rose-600 dark:text-rose-400">{{ $endDateFormatted }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-purple-100 text-purple-700 dark:bg-purple-900/60 dark:text-purple-300">
                                    {{ $daysCount }} ថ្ងៃ
                                </span>
                            </div>
                            <div class="text-[10px] font-bold text-gray-400">
                                <i class="far fa-clock text-amber-500 mr-1"></i>{{ formatKhmerTime($booking->start_time) }} - {{ formatKhmerTime($booking->end_time) }} ({{ $booking->total_hours }}h)
                            </div>
                        </div>
                    </td>

                    {{-- ATTENDEES & SETUP --}}
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-xl bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 font-bold text-xs">
                            <i class="fas fa-users text-[10px] mr-1"></i> {{ $booking->attendees_count ?? 10 }} នាក់
                        </span>
                        @if($booking->setup_style)
                        <div class="text-[10px] text-gray-400 mt-1 font-semibold">រៀបចំ: {{ $setupMap[$booking->setup_style] ?? $booking->setup_style }}</div>
                        @endif
                    </td>

                    {{-- TOTAL PRICE --}}
                    <td class="px-6 py-4 text-center font-black text-gray-900 dark:text-white">
                        ${{ number_format($booking->total_price, 2) }}
                    </td>

                    {{-- PAYMENT STATUS --}}
                    <td class="px-6 py-4 text-center">
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
                            <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="mt-1 px-2 py-0.5 rounded text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center justify-center gap-1 cursor-pointer">
                                <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                            </button>
                            @endif
                        </div>
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $statusLabels[$booking->status] ?? $booking->status }}
                        </span>
                    </td>

                    {{-- ACTIONS --}}
                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex justify-end items-center gap-1">
                            <a href="{{ route('meeting-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2 text-purple-600 hover:text-purple-800 dark:text-purple-400 transition-colors cursor-pointer" title="ព្រីនវិក្កយបត្រ (Print Invoice)">
                                <i class="fas fa-print text-sm"></i>
                            </a>
                            @if($booking->payment && $booking->payment->payment_slip)
                            <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="p-2 text-emerald-500 hover:text-emerald-600 transition-colors cursor-pointer" title="មើល Slip បង់ប្រាក់">
                                <i class="fas fa-file-image text-sm"></i>
                            </button>
                            @endif
                            <button type="button" @click="viewDetail({{ $booking->toJson() }})" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button type="button" @click="editBooking({{ $booking->toJson() }})" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button type="button" @click="deleteBooking({{ $booking->id }})" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-8">
                        @include('admin.room_bookings.partials.empty_state')
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 pagination-container">
    <div class="dark:text-white html-pagination">
        {{ $bookings->links() }}
    </div>
</div>
