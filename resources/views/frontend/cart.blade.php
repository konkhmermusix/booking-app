@extends('layouts.app')
@section('title', 'បញ្ជីការកក់ទុក')
@section('content')
<div class="mx-auto">
    <section class="py-10 dark:bg-[#0b1120] ">
        <div class="container mx-auto px-4">
            @if(count($cart) > 0)
            <div class="mb-6 p-4 bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 text-amber-600 dark:text-amber-400 mt-0.5 relative">
                        <i class="fas fa-clock text-lg"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full animate-ping"></span>
                    </div>
                    <div>
                        <h5 class="text-sm font-bold text-amber-900 dark:text-amber-300 mb-0.5">
                            ការកក់បណ្តោះអាសន្នមានកំណត់ពេល! ២៤ ម៉ោង
                        </h5>
                        <p class="text-xs text-amber-700 dark:text-amber-400 leading-relaxed max-w-3xl">
                            ដើម្បីធានាថាទទួលបានបន្ទប់ស្នាក់នៅនេះពិតប្រាកដ ប្រព័ន្ធបានរក្សាទុកវាក្នុងកន្ត្រកបណ្តោះអាសន្ន។
                            សូមចុចបង្កើតគណនី ឬចូលទៅការទូរទាត់ដើម្បីទទួលបានបន្ទប់។
                        </p>
                    </div>
                </div>

                @php
                $expireTime = \Carbon\Carbon::now()->addDay()->locale('km')->isoFormat('Do MMM YYYY, ម៉ោង h:mm A');
                @endphp
                <div class="sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-amber-200 dark:border-amber-900/50">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        * កន្ត្រកនេះនឹងហួសកំណត់នៅត្រឹម៖
                    </p>
                    <span class="text-xs font-bold text-red-600 dark:text-red-400">{{ $expireTime }}</span>
                </div>
            </div>
            @endif

            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-800">
                <h1 class="text-2xl font-extrabold flex items-center gap-2.5 text-gray-900 dark:text-white tracking-tight">
                    បញ្ជីការកក់ទុកបណ្ដោះអាសន្នរបស់អ្នក
                </h1>
                <span class="text-sm font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded-full border border-blue-100 dark:border-blue-900/30">
                    សរុប {{ count($cart) }} មុខ
                </span>
            </div>

            @if(count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $key => $item)
                    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700/60 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5 transition-all duration-200">
                        <div class="flex items-start gap-4 w-full sm:w-auto flex-1">
                            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-xl overflow-hidden flex-shrink-0 flex items-center justify-center border border-gray-200/50 dark:border-gray-600">
                                @if(($item['image'] ?? ''))
                                <img src="{{ $item['image'] }}" alt="Room Image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                @endif
                            </div>

                            <div class="space-y-2 flex-1">
                                @if(($item['type'] ?? '') == 'hotel')
                                <span class="inline-flex items-center bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300 text-[11px] font-bold px-2.5 py-0.5 rounded-full border border-blue-100 dark:border-blue-900/30 tracking-wide">
                                    បន្ទប់ស្នាក់នៅ
                                </span>

                                <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug">
                                    {{ $item['name'] ?? 'មិនមានឈ្មោះបន្ទប់' }}
                                </h3>

                                {{-- ថ្ងៃស្នាក់នៅ + ប៊ូតុងប្តូរថ្ងៃថ្មី --}}
                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        ថ្ងៃស្នាក់នៅ: <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $item['check_in'] ?? 'N/A' }}</span> ដល់ <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $item['check_out'] ?? 'N/A' }}</span>
                                    </span>
                                    <button type="button" onclick="openDateModal('{{ $key }}', 'hotel', '{{ $item['check_in'] ?? '' }}', '{{ $item['check_out'] ?? '' }}')" class="text-blue-600 dark:text-blue-400 hover:underline font-semibold flex items-center gap-0.5 ml-1">
                                        (ប្តូរថ្ងៃ)
                                    </button>
                                </div>

                                <div class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">
                                    តម្លៃ: ${{ number_format($item['price'] ?? 0, 2) }}/យប់ | សរុប: {{ $item['total_nights'] ?? 0 }} យប់
                                </div>

                                @elseif(($item['type'] ?? '') == 'meeting')
                                <span class="inline-flex items-center bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300 text-[11px] font-bold px-2.5 py-0.5 rounded-full border border-emerald-100 dark:border-emerald-900/30 tracking-wide">
                                    សាលប្រជុំ
                                </span>

                                <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug">
                                    {{ $item['name'] ?? 'មិនមានឈ្មោះសាលប្រជុំ' }}
                                </h3>

                                <div class="space-y-1 text-xs text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            កាលបរិច្ឆេទ: <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $item['start_date'] ?? 'N/A' }}</span> - <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $item['end_date'] ?? 'N/A' }}</span>
                                        </span>
                                    </div>
                                    <p class="flex items-center gap-1.5 pl-5 text-[11px]">
                                        ចន្លោះម៉ោង: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item['start_time'] ?? 'N/A' }} ដល់ {{ $item['end_time'] ?? 'N/A' }}</span>
                                    </p>
                                </div>

                                <div class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">
                                    តម្លៃ: ${{ number_format($item['price'] ?? 0, 2) }}/ម៉ោង | សរុប: {{ $item['total_days'] ?? 0 }} ថ្ងៃ ({{ $item['total_hours'] ?? 0 }} ម៉ោង)
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex sm:flex-col justify-between sm:items-end w-full sm:w-auto pt-4 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-700/50 gap-3">
                            <div class="text-right sm:mt-1">
                                <span class="text-xl font-black text-gray-900 dark:text-white tracking-tight block">
                                    ${{ number_format(($item['total_price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}
                                </span>

                                <form action="{{ route('cart.remove', $key) }}" method="POST" class="inline-block mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400 text-xs font-semibold flex items-center gap-1 py-1 px-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/30 transition-all duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        លុបចេញ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700/60 sticky top-6 space-y-5">
                        <h3 class="text-base font-extrabold text-gray-900 dark:text-white uppercase tracking-wider text-[13px] text-gray-400 dark:text-gray-500">សង្ខេបការកក់ទុក</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>ចំនួនកក់សរុប:</span>
                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ count($cart) }} បន្ទប់</span>
                            </div>
                        </div>

                        <hr class="border-gray-100 dark:border-gray-700">

                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">តម្លៃសរុប:</span>
                            <div class="text-right">
                                <span class="text-2xl font-black text-blue-600 dark:text-blue-400">${{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        @auth
                        <a href="{{ route('checkout.index') }}"
                            class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 text-sm active:scale-[0.98] transform duration-150">
                            ទូទាត់ប្រាក់
                        </a>
                        @else
                        <button type="button" onclick="redirectToLogin()"
                            class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-500/10 hover:shadow-blue-500/20 text-sm active:scale-[0.98] transform duration-150">
                            ទូទាត់ប្រាក់
                        </button>
                        @endauth

                        <a href="{{ route('frontend.rooms') }}" class="block w-full text-center text-blue-600 dark:text-blue-400 hover:underline text-xs font-semibold pt-1 transition-all">
                            បន្ទប់ផ្សេងទៀត
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-10 shadow-sm max-w-xl mx-auto px-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1.5">មិនទាន់មានការកក់ទុកទេ</h3>
                <a href="{{ route('frontend.rooms') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition-all shadow-md">
                    ស្វែងរកបន្ទប់ឥឡូវនេះ
                </a>
            </div>
            @endif
        </div>
    </section>
</div>

<script>
    function redirectToLogin() {
        Swal.fire({
            title: 'សូមចូលប្រើគណនី!',
            text: 'ចូលប្រើប្រាស់គណនីជាមុនសិន អាចបន្តការកក់បន្ទប់បាន។',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'ទំព័រចូលប្រើ',
            cancelButtonText: 'បោះបង់',
            customClass: {
                icon: 'w-16 h-16 max-w-full flex items-center justify-center',
                popup: 'rounded-xl',
                confirmButton: 'rounded-xl font-bold px-4 py-2',
                cancelButton: 'rounded-xl font-bold px-4 py-2'
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
        let tokenElement = document.querySelector('meta[name="csrf-token"]');
        let tokenValue = tokenElement ? tokenElement.content : '{{ csrf_token() }}';

        if (type === 'hotel') {
            htmlInputs = `
                <div class="space-y-3 text-left">
                    <div>
                        <label class="block text-xs font-bold mb-1 text-gray-600">ថ្ងៃចូលស្នាក់នៅ (Check-in):</label>
                        <input type="date" id="swal-check-in" min="${today}" class="w-full p-2.5 border rounded-xl text-sm" value="${start}">
                    </div>
                    <div>
                        <label class="block text-xs font-bold mb-1 text-gray-600">ថ្ងៃចាកចេញ (Check-out):</label>
                        <input type="date" id="swal-check-out" min="${start}" class="w-full p-2.5 border rounded-xl text-sm" value="${end}">
                    </div>
                </div>
            `;
        } else {
            htmlInputs = `
                <div class="space-y-3 text-left">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600">ថ្ងៃចាប់ផ្តើម:</label>
                            <input type="date" id="swal-start-date" min="${today}" class="w-full p-2.5 border rounded-xl text-sm" value="${start}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600">ថ្ងៃបញ្ចប់:</label>
                            <input type="date" id="swal-end-date" min="${start}" class="w-full p-2.5 border rounded-xl text-sm" value="${end}">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600">ចាប់ផ្តើមម៉ោង:</label>
                            <input type="time" id="swal-start-time" class="w-full p-2.5 border rounded-xl text-sm" value="${timeStart}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1 text-gray-600">បញ្ចប់ម៉ោង:</label>
                            <input type="time" id="swal-end-time" class="w-full p-2.5 border rounded-xl text-sm" value="${timeEnd}">
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
                    return {
                        check_in: checkIn,
                        check_out: checkOut
                    };
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
                // បង្កើត Form សម្ងាត់ទាញយក CSRF Token ពី Laravel Head Meta ផ្ទាល់
                let form = document.createElement('form');
                form.action = `/cart/update-date/${cartKey}`;
                form.method = 'POST';

                // ទាញយក Token (ការពារករណីកូដ JavaScript ដាក់នៅក្រៅ File Blade)
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
                form.submit(); // បញ្ជូនទៅកាន់ Backend
            }
        });
    }
</script>

@endsection