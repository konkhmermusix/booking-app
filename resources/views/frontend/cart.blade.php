@extends('layouts.app')
@section('title', 'បញ្ជីការកក់ទុកបណ្តោះអាសន្ន | សណ្ឋាគារ ភីអេនធី ផាលេស')
@section('content')

<div class="w-full bg-gray-50 dark:bg-[#0b1120] py-10 min-h-screen transition-colors duration-300">
    <div class="container mx-auto px-4">

        {{-- ALERT BANNER: EXPIRATION --}}
        @if(count($cart) > 0)
        <div class="mb-8 p-5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
            <div class="flex items-start gap-3.5">
                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center relative">
                    <i class="fas fa-clock text-lg"></i>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full animate-ping"></span>
                    <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                </div>
                <div>
                    <h5 class="text-sm font-extrabold text-amber-900 dark:text-amber-300 mb-1">
                        ការកក់បណ្តោះអាសន្នមានកំណត់ពេល ២៤ ម៉ោង!
                    </h5>
                    <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed max-w-3xl font-medium">
                        ដើម្បីធានាថាលោកអ្នកទទួលបានបន្ទប់ស្នាក់នៅ ឬសាលប្រជុំនេះពិតប្រាកដ ប្រព័ន្ធបានរក្សាទុកវាក្នុងកន្ត្រកបណ្តោះអាសន្ន។
                        សូមបន្តទៅកាន់ទំព័រទូទាត់ប្រាក់ដើម្បីបញ្ចប់ការកក់ជាស្ថាពរ។
                    </p>
                </div>
            </div>

            @php
            $earliestCreated = null;
            foreach($cart as $cItem) {
                if(isset($cItem['created_at'])) {
                    if(!$earliestCreated || $cItem['created_at'] < $earliestCreated) {
                        $earliestCreated = $cItem['created_at'];
                    }
                }
            }
            $targetCarbon = $earliestCreated 
                ? \Carbon\Carbon::createFromTimestamp($earliestCreated)->setTimezone('Asia/Phnom_Penh')->addHours(24)
                : \Carbon\Carbon::now('Asia/Phnom_Penh')->addHours(24);

            $targetCarbon->locale('km');
            $dateFormatted = $targetCarbon->isoFormat('D MMMM YYYY');
            $timeFormatted = $targetCarbon->format('h:i');
            $hour24 = (int)$targetCarbon->format('H');

            $period = 'ព្រឹក';
            if ($hour24 >= 12 && $hour24 < 17) {
                $period = 'រសៀល';
            } elseif ($hour24 >= 17 && $hour24 < 19) {
                $period = 'ល្ងាច';
            } elseif ($hour24 >= 19 || $hour24 < 5) {
                $period = 'យប់';
            }

            $expireTime = "ថ្ងៃទី{$dateFormatted}, ម៉ោង {$timeFormatted} {$period}";
            @endphp
            <div class="sm:text-right border-t sm:border-t-0 pt-3 sm:pt-0 border-amber-200 dark:border-amber-900/50 shrink-0">
                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                    * កន្ត្រកនឹងហួសកំណត់នៅ៖
                </p>
                <span class="text-xs font-bold text-red-600 dark:text-red-400 block mt-0.5">{{ $expireTime }}</span>
            </div>
        </div>
        @endif

        {{-- PAGE HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-800 gap-4">
            <div class="flex items-center gap-3">
                <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                    បញ្ជីការកក់ទុកបណ្ដោះអាសន្នរបស់អ្នក
                </h1>
            </div>
            <span class="text-xs font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-full border border-blue-100 dark:border-blue-900/40 w-fit">
                សរុបមាន {{ count($cart) }} មុខ
            </span>
        </div>

        @if(count($cart) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- LEFT COLUMN: CART ITEMS LIST --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($cart as $key => $item)
                @php
                $itemType = $item['type'] ?? 'hotel';
                $isPromo = !empty($item['is_promo']);
                $itemImg = $item['image'] ?? null;
                @endphp

                <div class="group bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5 transition-all duration-300">
                    <div class="flex items-start gap-4 w-full sm:w-auto flex-1">
                        {{-- ITEM THUMBNAIL --}}
                        <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-2xl overflow-hidden shrink-0 flex items-center justify-center border border-gray-100 dark:border-gray-800 relative">
                            @if($itemImg)
                                <img src="{{ Str::startsWith($itemImg, ['http://', 'https://']) ? $itemImg : asset('storage/' . $itemImg) }}"
                                    alt="{{ $item['name'] ?? 'Room' }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                    <i class="{{ $itemType === 'hotel' ? 'fas fa-bed' : 'fas fa-handshake' }} text-2xl"></i>
                                </div>
                            @endif

                            @if($isPromo)
                            <div class="absolute top-1 left-1 bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded uppercase">
                                PROMO
                            </div>
                            @endif
                        </div>

                        {{-- ITEM DETAILS --}}
                        <div class="space-y-2 flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($itemType == 'hotel')
                                <span class="inline-flex items-center bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-[11px] font-bold px-2.5 py-0.5 rounded-full border border-blue-100 dark:border-blue-900/30">
                                    <i class="fas fa-bed mr-1 text-[10px]"></i> បន្ទប់ស្នាក់នៅ
                                </span>
                                @else
                                <span class="inline-flex items-center bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-[11px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-100 dark:border-emerald-900/30">
                                    <i class="fas fa-handshake mr-1 text-[10px]"></i> សាលប្រជុំ
                                </span>
                                @endif
                            </div>

                            <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug truncate">
                                {{ $item['name'] ?? 'មិនមានឈ្មោះ' }}
                            </h3>

                            @if($itemType == 'hotel')
                            {{-- Hotel Dates --}}
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <span class="flex items-center gap-1 font-medium">
                                    <i class="fas fa-calendar-alt text-blue-500"></i>
                                    ថ្ងៃស្នាក់នៅ៖ <span class="font-mono font-bold text-gray-900 dark:text-white">{{ isset($item['check_in']) ? \Carbon\Carbon::parse($item['check_in'])->format('d-m-Y') : 'N/A' }}</span> ដល់ <span class="font-mono font-bold text-gray-900 dark:text-white">{{ isset($item['check_out']) ? \Carbon\Carbon::parse($item['check_out'])->format('d-m-Y') : 'N/A' }}</span>
                                </span>
                            </div>

                            <div class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">
                                តម្លៃ៖ ${{ number_format($item['price'] ?? 0, 2) }}/យប់ | សរុប៖ {{ $item['total_nights'] ?? 1 }} យប់
                            </div>

                            @else
                            {{-- Meeting Dates --}}
                            <div class="space-y-1 text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="flex items-center gap-1 font-medium">
                                        <i class="fas fa-calendar-alt text-blue-500"></i>
                                        កាលបរិច្ឆេទ៖ <span class="font-mono font-bold text-gray-900 dark:text-white">{{ isset($item['start_date']) ? \Carbon\Carbon::parse($item['start_date'])->format('d-m-Y') : 'N/A' }}</span> ដល់ <span class="font-mono font-bold text-gray-900 dark:text-white">{{ isset($item['end_date']) ? \Carbon\Carbon::parse($item['end_date'])->format('d-m-Y') : 'N/A' }}</span>
                                    </span>
                                </div>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-clock text-blue-500 mr-1"></i> ចន្លោះម៉ោង៖ <span class="font-bold text-gray-900 dark:text-white">{{ $item['start_time'] ?? '08:00' }} ដល់ {{ $item['end_time'] ?? '17:00' }}</span>
                                </p>
                            </div>

                            <div class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">
                                តម្លៃ៖ ${{ number_format($item['price'] ?? 0, 2) }}/ម៉ោង | សរុប៖ {{ $item['total_days'] ?? 1 }} ថ្ងៃ ({{ $item['total_hours'] ?? 1 }} ម៉ោង)
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- PRICE & REMOVE ACTION --}}
                    <div class="flex sm:flex-col justify-between sm:items-end w-full sm:w-auto pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-800 gap-3 shrink-0">
                        <div class="text-right">
                            <span class="text-2xl font-black text-blue-600 dark:text-blue-400 tracking-tight block">
                                ${{ number_format(($item['total_price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                            </span>
                            <span class="text-xs font-semibold text-gray-400 font-mono block">({{ number_format((($item['total_price'] ?? 0) * ($item['quantity'] ?? 1)) * $khrRate) }} ៛)</span>
                        </div>

                        <form action="{{ route('cart.remove', $key) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400 text-xs font-bold flex items-center gap-1.5 py-1.5 px-3 rounded-xl hover:bg-red-50 dark:hover:bg-red-950/40 transition-all duration-200 border border-transparent hover:border-red-100 dark:hover:border-red-900/40">
                                <i class="fas fa-trash-alt"></i>
                                <span>លុបចេញ</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- RIGHT COLUMN: ORDER SUMMARY --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 sticky top-24 space-y-5 transition-colors duration-300">
                    <div class="flex items-center gap-2 pb-3 border-b border-gray-100 dark:border-gray-800">
                        <div class="h-5 w-1.5 bg-blue-600 rounded-full"></div>
                        <h3 class="text-base font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">
                            សង្ខេបការកក់ទុក
                        </h3>
                    </div>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>ចំនួនកក់សរុប៖</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ count($cart) }} មុខ</span>
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-800">

                    <div class="flex justify-between items-baseline">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">តម្លៃសរុបទាំងអស់៖</span>
                        <div class="text-right">
                            <span class="text-3xl font-black text-blue-600 dark:text-blue-400 font-mono">${{ number_format($subtotal, 2) }}</span>
                            <span class="text-xs font-semibold text-gray-400 font-mono block">({{ number_format($subtotal * $khrRate) }} ៛)</span>
                        </div>
                    </div>

                    @auth
                    <a href="{{ route('checkout.index') }}"
                        class="flex items-center justify-center gap-2 w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 text-sm active:scale-95 whitespace-nowrap">
                        <span class="whitespace-nowrap">បន្តទៅកាន់ការទូទាត់ប្រាក់</span>
                    </a>
                    @else
                    <button type="button" onclick="redirectToLogin()"
                        class="flex items-center justify-center gap-2 w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 text-sm active:scale-95 whitespace-nowrap">
                        <span class="whitespace-nowrap">បន្តទៅកាន់ការទូទាត់ប្រាក់</span>
                    </button>
                    @endauth

                    <a href="{{ route('frontend.rooms') }}" class="block w-full text-center text-blue-600 dark:text-blue-400 hover:underline text-xs font-bold pt-1 transition-all">
                        បន្ថែមបន្ទប់ ឬសាលប្រជុំផ្សេងទៀត
                    </a>
                </div>
            </div>
        </div>

        @else
        {{-- EMPTY CART STATE --}}
        <div class="text-center py-16 max-w-xl mx-auto px-6">
            <div class="w-20 h-20 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-blue-100 dark:border-blue-900/30">
                <i class="fas fa-shopping-cart text-3xl"></i>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2">មិនទាន់មានការកក់ទុកក្នុងកន្ត្រកឡើយ</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">
                សូមជ្រើសរើសបន្ទប់ស្នាក់នៅ ឬសាលប្រជុំដែលលោកអ្នកពេញចិត្តដើម្បីថែមចូលក្នុងកន្ត្រក។
            </p>
            <a href="{{ route('frontend.rooms') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-7 py-3.5 rounded-xl text-xs transition-all shadow-md shadow-blue-500/20 active:scale-95">
                <span>ស្វែងរកបន្ទប់ឥឡូវនេះ</span>
            </a>
        </div>
        @endif
    </div>
</div>

<script>
    function redirectToLogin() {
        Swal.fire({
            title: 'សូមចូលប្រើប្រាស់គណនី!',
            text: 'សូមចូលប្រើប្រាស់គណនីជាមុនសិន ដើម្បីបន្តទៅកាន់ការទូទាត់ប្រាក់។',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'ចូលប្រើប្រាស់គណនី',
            cancelButtonText: 'បោះបង់',
            customClass: {
                icon: 'w-16 h-16 max-w-full flex items-center justify-center',
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs',
                cancelButton: 'rounded-xl font-bold px-4 py-2 text-xs'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(window.location.href);
            }
        });
    }

    function openDateModal(cartKey, type, start, end, timeStart = '', timeEnd = '') {
        let htmlInputs = '';
        let today = new Date().toISOString().split('T')[0];

        if (type === 'hotel') {
            htmlInputs = `
                <div class="space-y-3 text-left">
                    <div>
                        <label class="block text-xs font-bold mb-1 text-gray-600 dark:text-gray-300">ថ្ងៃចូលស្នាក់នៅ (Check-in):</label>
                        <input type="date" id="swal-check-in" min="${today}" class="w-full p-3 border rounded-xl text-sm dark:bg-gray-800 dark:text-white dark:border-gray-700" value="${start}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1 text-gray-600 dark:text-gray-300">ថ្ងៃចាកចេញ (Check-out):</label>
                        <input type="date" id="swal-check-out" min="${start}" class="w-full p-3 border rounded-xl text-sm dark:bg-gray-800 dark:text-white dark:border-gray-700" value="${end}">
                    </div>
                </div>
            `;
        } else {
            htmlInputs = `
                <div class="space-y-3 text-left">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600 dark:text-gray-300">ថ្ងៃចាប់ផ្តើម:</label>
                            <input type="date" id="swal-start-date" min="${today}" class="w-full p-3 border rounded-xl text-sm dark:bg-gray-800 dark:text-white dark:border-gray-700" value="${start}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600 dark:text-gray-300">ថ្ងៃបញ្ចប់:</label>
                            <input type="date" id="swal-end-date" min="${start}" class="w-full p-3 border rounded-xl text-sm dark:bg-gray-800 dark:text-white dark:border-gray-700" value="${end}">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600 dark:text-gray-300">ចាប់ផ្តើមម៉ោង:</label>
                            <input type="time" id="swal-start-time" class="w-full p-3 border rounded-xl text-sm dark:bg-gray-800 dark:text-white dark:border-gray-700" value="${timeStart}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600 dark:text-gray-300">បញ្ចប់ម៉ោង:</label>
                            <input type="time" id="swal-end-time" class="w-full p-3 border rounded-xl text-sm dark:bg-gray-800 dark:text-white dark:border-gray-700" value="${timeEnd}">
                        </div>
                    </div>
                </div>
            `;
        }

        Swal.fire({
            title: 'កែប្រែកាលបរិច្ឆេទស្នាក់នៅ',
            html: htmlInputs,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'រក្សាទុកការផ្លាស់ប្តូរ',
            cancelButtonText: 'បោះបង់',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'rounded-2xl dark:bg-gray-900 dark:text-white',
                confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs',
                cancelButton: 'rounded-xl font-bold px-4 py-2 text-xs'
            },
            didOpen: () => {
                if (type === 'hotel') {
                    const checkInInput = document.getElementById('swal-check-in');
                    const checkOutInput = document.getElementById('swal-check-out');
                    checkInInput.addEventListener('change', () => {
                        checkOutInput.min = checkInInput.value;
                        if (checkOutInput.value && checkOutInput.value <= checkInInput.value) {
                            checkOutInput.value = '';
                        }
                    });
                } else {
                    const startDateInput = document.getElementById('swal-start-date');
                    const endDateInput = document.getElementById('swal-end-date');
                    startDateInput.addEventListener('change', () => {
                        endDateInput.min = startDateInput.value;
                        if (endDateInput.value && endDateInput.value < startDateInput.value) {
                            endDateInput.value = startDateInput.value;
                        }
                    });
                }
            },
            preConfirm: () => {
                if (type === 'hotel') {
                    const checkIn = document.getElementById('swal-check-in').value;
                    const checkOut = document.getElementById('swal-check-out').value;

                    if (!checkIn || !checkOut) {
                        Swal.showValidationMessage('សូមបំពេញថ្ងៃខែឱ្យបានគ្រប់គ្រាន់!');
                        return false;
                    }
                    if (checkOut <= checkIn) {
                        Swal.showValidationMessage('ថ្ងៃចាកចេញត្រូវតែលើសពីថ្ងៃចូលស្នាក់នៅ!');
                        return false;
                    }
                    return { check_in: checkIn, check_out: checkOut };
                } else {
                    const startDate = document.getElementById('swal-start-date').value;
                    const endDate = document.getElementById('swal-end-date').value;
                    const startTime = document.getElementById('swal-start-time').value;
                    const endTime = document.getElementById('swal-end-time').value;

                    if (!startDate || !endDate || !startTime || !endTime) {
                        Swal.showValidationMessage('សូមបំពេញថ្ងៃខែ និងម៉ោងឱ្យបានគ្រប់គ្រាន់!');
                        return false;
                    }
                    if (endDate < startDate) {
                        Swal.showValidationMessage('ថ្ងៃបញ្ចប់មិនអាចតូចជាងថ្ងៃចាប់ផ្តើមឡើយ!');
                        return false;
                    }
                    if (startDate === endDate && endTime <= startTime) {
                        Swal.showValidationMessage('ម៉ោងបញ្ចប់ត្រូវតែធំជាងម៉ោងចាប់ផ្តើម!');
                        return false;
                    }
                    return {
                        start_date: startDate,
                        end_date: endDate,
                        start_time: startTime,
                        end_time: endTime
                    };
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.action = `/cart/update-date/${cartKey}`;
                form.method = 'POST';

                let tokenElement = document.querySelector('meta[name="csrf-token"]');
                let tokenValue = tokenElement ? tokenElement.content : '{{ csrf_token() }}';

                let csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = tokenValue;
                form.appendChild(csrfToken);

                let methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PATCH';
                form.appendChild(methodInput);

                Object.keys(result.value).forEach(key => {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = result.value[key];
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>

@endsection