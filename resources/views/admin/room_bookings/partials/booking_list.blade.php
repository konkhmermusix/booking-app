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
@endphp

{{-- 1. GRID VIEW --}}
<div x-show="!viewMode || viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition>
    @forelse($bookings as $booking)
    @php
        $isOnline = ($booking->booking_type === 'online') || ($booking->user_id && !$booking->customer_name);
        $customerName = $booking->customer_name ?: ($booking->user->name ?? 'អតិថិជនអនឡាញ');
        
        $detailsCount = ($booking->details && $booking->details->count() > 0) ? $booking->details->count() : 1;
        if ($booking->details && $booking->details->count() > 0) {
            $roomNumbersArray = $booking->details->map(function($d) {
                return $d->room->room_number ?? null;
            })->filter()->values()->all();

            $roomTypesArray = $booking->details->map(function($d) {
                return $d->roomType->name ?? ($d->room->roomType->name ?? null);
            })->filter()->unique()->values()->all();

            $roomNumber = count($roomNumbersArray) > 0 ? implode(', ', $roomNumbersArray) : ($booking->room->room_number ?? 'N/A');
            $roomTypeName = count($roomTypesArray) > 0 ? implode(', ', $roomTypesArray) : ($booking->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ');
        } else {
            $roomNumber = $booking->room->room_number ?? 'N/A';
            $roomTypeName = $booking->room->roomType->name ?? ($booking->room->room_type->name ?? 'បន្ទប់ស្នាក់នៅ');
        }

        $checkInFormatted = $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : 'N/A';
        $checkOutFormatted = $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : 'N/A';

        $nightsCount = 1;
        if ($booking->check_in && $booking->check_out) {
            $cIn = \Carbon\Carbon::parse($booking->check_in);
            $cOut = \Carbon\Carbon::parse($booking->check_out);
            $nightsCount = max(1, $cIn->diffInDays($cOut));
        }
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border border-transparent dark:border-gray-800 relative overflow-hidden">

        <div class="absolute top-4 left-4 z-10 flex items-center gap-1.5 flex-wrap">
            @if($isOnline)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-indigo-600 text-white shadow-md">
                <i class="fa-solid fa-globe text-[9px]"></i> អនឡាញ
            </span>
            @else
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-600 text-white shadow-md">
                <i class="fa-solid fa-store text-[9px]"></i> ផ្ទាល់
            </span>
            @endif

            @if($detailsCount > 1)
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-600 text-white shadow-md">
                <i class="fa-solid fa-layer-group text-[9px]"></i> {{ $detailsCount }} បន្ទប់
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
                class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500" alt="Room Image">
            @else
            <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <i class="fa-solid fa-hotel text-gray-300 dark:text-gray-600 text-3xl"></i>
            </div>
            @endif

            <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity">
                <a href="{{ route('room-bookings.print-invoice', $booking->id) }}" target="_blank" class="w-9 h-9 bg-white text-indigo-600 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="ព្រីនវិក្កយបត្រ">
                    <i class="fas fa-print"></i>
                </a>
                <button @click="viewDetail({{ $booking->toJson() }})" class="w-9 h-9 bg-white text-blue-600 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="មើលលម្អិត">
                    <i class="fas fa-eye"></i>
                </button>
                @if($booking->payment && $booking->payment->payment_slip)
                <button @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="w-9 h-9 bg-white text-emerald-600 rounded-xl hover:scale-110 transition flex items-center justify-center cursor-pointer" title="មើលបង្កាន់ដៃបង់ប្រាក់">
                    <i class="fas fa-file-image"></i>
                </button>
                @endif
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
                <p class="text-xs font-black text-blue-600 dark:text-blue-400 mt-0.5 flex items-center gap-1.5 flex-wrap">
                    <span>បន្ទប់ {{ $roomNumber }}</span>
                    <span class="text-gray-400 font-medium text-[11px]">({{ $roomTypeName }})</span>
                </p>
            </div>

            <div class="flex items-center gap-2 py-2 border-y border-gray-100 dark:border-gray-700">
                <div class="flex-1">
                    <p class="text-[9px] text-gray-400 uppercase font-black">ចូលស្នាក់នៅ</p>
                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $checkInFormatted }}</p>
                </div>
                <div class="flex flex-col items-center">
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200/50">
                        {{ $nightsCount }} យប់
                    </span>
                    <i class="fas fa-arrow-right text-[9px] text-gray-300 mt-0.5"></i>
                </div>
                <div class="flex-1 text-right">
                    <p class="text-[9px] text-gray-400 uppercase font-black">ចាកចេញ</p>
                    <p class="text-xs font-bold text-rose-600 dark:text-rose-400">{{ $checkOutFormatted }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center">
                <span class="text-xs font-bold text-gray-600 dark:text-gray-300 truncate max-w-[120px]">
                    <i class="fa-regular fa-user mr-1 text-gray-400"></i>{{ $customerName }}
                </span>
                <span class="text-lg font-black text-blue-600">${{ number_format($booking->total_price, 2) }}</span>
            </div>
            @if($booking->payment && $booking->payment->payment_slip)
            <div class="pt-2 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                <span class="text-[10px] font-bold text-gray-400">បង្កាន់ដៃ:</span>
                <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="px-2.5 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 rounded-lg text-[10px] font-black hover:bg-emerald-100 transition flex items-center gap-1 cursor-pointer">
                    <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                </button>
            </div>
            @endif
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
        $customerName = $booking->customer_name ?: ($booking->user->name ?? 'អតិថិជនអនឡាញ');
        
        $detailsCount = ($booking->details && $booking->details->count() > 0) ? $booking->details->count() : 1;
        if ($booking->details && $booking->details->count() > 0) {
            $roomNumbersArray = $booking->details->map(function($d) {
                return $d->room->room_number ?? null;
            })->filter()->values()->all();

            $roomTypesArray = $booking->details->map(function($d) {
                return $d->roomType->name ?? ($d->room->roomType->name ?? null);
            })->filter()->unique()->values()->all();

            $roomNumber = count($roomNumbersArray) > 0 ? implode(', ', $roomNumbersArray) : ($booking->room->room_number ?? 'N/A');
            $roomTypeName = count($roomTypesArray) > 0 ? implode(', ', $roomTypesArray) : ($booking->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ');
        } else {
            $roomNumber = $booking->room->room_number ?? 'N/A';
            $roomTypeName = $booking->room->roomType->name ?? ($booking->room->room_type->name ?? 'បន្ទប់ស្នាក់នៅ');
        }

        $checkInFormatted = $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : 'N/A';
        $checkOutFormatted = $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : 'N/A';

        $nightsCount = 1;
        if ($booking->check_in && $booking->check_out) {
            $cIn = \Carbon\Carbon::parse($booking->check_in);
            $cOut = \Carbon\Carbon::parse($booking->check_out);
            $nightsCount = max(1, $cIn->diffInDays($cOut));
        }
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border border-transparent dark:border-gray-800">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex flex-col items-center justify-center text-blue-600 dark:text-blue-400 font-black text-xs">
                <i class="fa-solid fa-bed text-sm"></i>
                <span class="text-[10px] mt-0.5">#{{ substr($booking->booking_code, -4) }}</span>
            </div>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100">
                        បន្ទប់ {{ $roomNumber }} <span class="text-blue-600 font-black">({{ $roomTypeName }})</span>
                    </h4>
                    @if($detailsCount > 1)
                    <span class="px-2 py-0.5 rounded text-[9px] font-black bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200/50">
                        <i class="fa-solid fa-layer-group mr-0.5"></i> {{ $detailsCount }} បន្ទប់
                    </span>
                    @endif
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
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    <i class="fa-regular fa-user mr-1"></i><strong>{{ $customerName }}</strong>
                    <span class="mx-2 text-gray-300">|</span>
                    <i class="fa-regular fa-calendar-days text-emerald-500 mr-1"></i>{{ $checkInFormatted }} ដល់ {{ $checkOutFormatted }}
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300 ml-1 border border-blue-200/50">
                        {{ $nightsCount }} យប់
                    </span>
                    <span class="mx-2 text-gray-300">|</span>
                    សរុប: <strong class="text-blue-600 font-black">${{ number_format($booking->total_price, 2) }}</strong>
                </p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                {{ $statusLabels[$booking->status] ?? $booking->status }}
            </span>
            <div class="flex gap-1 items-center">
                <a href="{{ route('room-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 transition-colors cursor-pointer" title="ព្រីនវិក្កយបត្រ (Print Invoice)">
                    <i class="fas fa-print text-sm"></i>
                </a>
                @if($booking->payment && $booking->payment->payment_slip)
                <button @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="p-2 text-emerald-500 hover:text-emerald-600 transition-colors cursor-pointer" title="មើលបង្កាន់ដៃបង់ប្រាក់">
                    <i class="fas fa-file-image text-sm"></i>
                </button>
                @endif
                <button @click="viewDetail({{ $booking->toJson() }})" class="p-2 text-gray-400 hover:text-blue-500 transition-colors cursor-pointer" title="មើលលម្អិត">
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
                    <th class="px-6 py-5">បន្ទប់ & ប្រភេទបន្ទប់</th>
                    <th class="px-6 py-5 text-center">រយៈពេលស្នាក់នៅ</th>
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
                    $customerName = $booking->customer_name ?: ($booking->user->name ?? 'អតិថិជនអនឡាញ');
                    
                    $detailsCount = ($booking->details && $booking->details->count() > 0) ? $booking->details->count() : 1;
                    if ($booking->details && $booking->details->count() > 0) {
                        $roomNumbersArray = $booking->details->map(function($d) {
                            return $d->room->room_number ?? null;
                        })->filter()->values()->all();

                        $roomTypesArray = $booking->details->map(function($d) {
                            return $d->roomType->name ?? ($d->room->roomType->name ?? null);
                        })->filter()->unique()->values()->all();

                        $roomNumber = count($roomNumbersArray) > 0 ? implode(', ', $roomNumbersArray) : ($booking->room->room_number ?? 'N/A');
                        $roomTypeName = count($roomTypesArray) > 0 ? implode(', ', $roomTypesArray) : ($booking->room->roomType->name ?? 'បន្ទប់ស្នាក់នៅ');
                    } else {
                        $roomNumber = $booking->room->room_number ?? 'N/A';
                        $roomTypeName = $booking->room->roomType->name ?? ($booking->room->room_type->name ?? 'បន្ទប់ស្នាក់នៅ');
                    }

                    $checkInFormatted = $booking->check_in ? \Carbon\Carbon::parse($booking->check_in)->format('d/m/Y') : 'N/A';
                    $checkOutFormatted = $booking->check_out ? \Carbon\Carbon::parse($booking->check_out)->format('d/m/Y') : 'N/A';

                    $nightsCount = 1;
                    if ($booking->check_in && $booking->check_out) {
                        $cIn = \Carbon\Carbon::parse($booking->check_in);
                        $cOut = \Carbon\Carbon::parse($booking->check_out);
                        $nightsCount = max(1, $cIn->diffInDays($cOut));
                    }

                    $payStatus = $booking->payment ? $booking->payment->status : 'paid';
                    $payMethod = $booking->payment_method ?: ($booking->payment ? $booking->payment->method : 'cash');
                @endphp
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4 font-black text-blue-600">#{{ $booking->booking_code }}</td>

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
                        <div class="font-extrabold text-blue-600 dark:text-blue-400 flex items-center gap-1.5 flex-wrap">
                            <span>បន្ទប់ {{ $roomNumber }}</span>
                            @if($detailsCount > 1)
                            <span class="px-2 py-0.5 rounded text-[9px] font-black bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200/50">
                                <i class="fa-solid fa-layer-group mr-0.5"></i> {{ $detailsCount }} បន្ទប់
                            </span>
                            @endif
                        </div>
                        <div class="text-xs font-bold text-gray-500 dark:text-gray-400">{{ $roomTypeName }}</div>
                    </td>

                    {{-- DATES (dd/mm/yyyy) & NIGHTS --}}
                    <td class="px-6 py-4 text-center">
                        <div class="inline-flex items-center gap-2 bg-gray-50 dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-gray-200/60 dark:border-gray-700/60">
                            <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">{{ $checkInFormatted }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-gray-400"></i>
                            <span class="text-xs font-extrabold text-rose-600 dark:text-rose-400">{{ $checkOutFormatted }}</span>
                            <span class="ml-1 px-2 py-0.5 rounded-md text-[10px] font-black bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                                {{ $nightsCount }} យប់
                            </span>
                        </div>
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
                                    <i class="fas fa-image text-[10px]"></i> មើលបង្កាន់ដៃបង់ប្រាក់
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
                            <a href="{{ route('room-bookings.print-invoice', $booking->id) }}" target="_blank" class="p-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 transition-colors cursor-pointer" title="ព្រីនវិក្កយបត្រ">
                                <i class="fas fa-print text-sm"></i>
                            </a>
                            @if($booking->payment && $booking->payment->payment_slip)
                            <button type="button" @click="viewSlip('{{ asset('storage/' . $booking->payment->payment_slip) }}')" class="p-2 text-emerald-500 hover:text-emerald-600 transition-colors cursor-pointer" title="មើលបង្កាន់ដៃបង់ប្រាក់">
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
                    <td colspan="8" class="text-center py-8">
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