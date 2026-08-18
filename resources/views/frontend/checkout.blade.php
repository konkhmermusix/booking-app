@extends('layouts.app')
@section('title', 'ទូទាត់ប្រាក់ និងបញ្ជាក់ការកក់ | សណ្ឋាគារ ភីអេនធី ផាលេស')
@section('content')

<div class="w-full bg-gray-50 dark:bg-[#0b1120] min-h-screen py-10 transition-colors duration-300">
    <div class="container mx-auto px-4" x-data="checkoutHandler()">



        {{-- PAGE TITLE --}}
        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                ពិនិត្យព័ត៌មាន និងទូទាត់ប្រាក់
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start w-full">

            {{-- LEFT COLUMN: CUSTOMER FORM & PAYMENT METHOD --}}
            <div class="lg:col-span-2 space-y-8 w-full">

                {{-- SECTION 1: CUSTOMER INFORMATION --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-800 transition-colors duration-300 w-full">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-9 h-9 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-user text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-lg font-extrabold text-gray-900 dark:text-white truncate">ព័ត៌មានអ្នកកក់បន្ទប់</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">សូមបំពេញព័ត៌មានទំនាក់ទំនងឱ្យបានត្រឹមត្រូវ</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                                ឈ្មោះពេញ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-user text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 text-xs"></i>
                                <input type="text" x-model="formData.name" placeholder="ឧ. កែវ សុខា"
                                    class="w-full h-12 pl-10 pr-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                                លេខទូរស័ព្ទ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-phone text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 text-xs"></i>
                                <input type="tel" x-model="formData.phone" placeholder="012 345 678"
                                    class="w-full h-12 pl-10 pr-4 bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-800 outline-none text-gray-900 dark:text-white text-sm transition-all">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                                អ៊ីមែលទទួលសំបុត្របញ្ជាក់ <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i class="fas fa-envelope text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 text-xs"></i>
                                <input type="email" x-model="formData.email" placeholder="example@mail.com"
                                    class="w-full h-12 pl-10 pr-4 bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-800 outline-none text-gray-900 dark:text-white text-sm transition-all">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2">
                                មតិផ្សេងៗ
                            </label>
                            <textarea x-model="formData.special_requests" rows="3" placeholder="មតិផ្សេងៗ..."
                                class="w-full p-4 bg-gray-50 dark:bg-gray-800/60 border border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-800 outline-none text-gray-900 dark:text-white text-sm transition-all"></textarea>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: PAYMENT METHOD --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-800 transition-colors duration-300 w-full">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-9 h-9 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                            <i class="fas fa-wallet text-base"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-lg font-extrabold text-gray-900 dark:text-white truncate">ជ្រើសរើសវិធីសាស្ត្រទូទាត់ប្រាក់</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">សូមជ្រើសរើសជម្រើសទូទាត់ប្រាក់ដែលងាយស្រួលសម្រាប់លោកអ្នក</p>
                        </div>
                    </div>

                    {{-- PAYMENT OPTIONS SELECTOR (2 OPTIONS ONLY: KHQR & CASH) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 w-full">
                        {{-- Option 1: QR Code Pay / KHQR --}}
                        <label class="relative border-2 rounded-2xl p-4 cursor-pointer transition-all flex flex-col justify-between"
                            :class="formData.payment_method === 'qr' ? 'border-blue-600 bg-blue-50/40 dark:bg-blue-950/30 ring-2 ring-blue-500/20' : 'border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700'">
                            <input type="radio" x-model="formData.payment_method" value="qr" class="hidden">
                            <div class="flex items-center justify-between mb-3">
                                <span class="w-8 h-8 bg-red-600 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0">KHQR</span>
                                <i class="fas fa-qrcode text-blue-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-extrabold text-xs text-gray-900 dark:text-white">ស្កែនឃ្យូអរកូដ</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">Bakong Banking</p>
                            </div>
                        </label>

                        {{-- Option 2: Cash on Arrival --}}
                        <label class="relative border-2 rounded-2xl p-4 cursor-pointer transition-all flex flex-col justify-between"
                            :class="formData.payment_method === 'cash' ? 'border-blue-600 bg-blue-50/40 dark:bg-blue-950/30 ring-2 ring-blue-500/20' : 'border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700'">
                            <input type="radio" x-model="formData.payment_method" value="cash" class="hidden">
                            <div class="flex items-center justify-between mb-3">
                                <span class="w-8 h-8 bg-emerald-600 text-white rounded-lg flex items-center justify-center text-xs font-black shrink-0"><i class="fas fa-money-bill-wave"></i></span>
                                <i class="fas fa-hotel text-emerald-600 text-lg"></i>
                            </div>
                            <div>
                                <p class="font-extrabold text-xs text-gray-900 dark:text-white">ទូទាត់ជាសាច់ប្រាក់</p>
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-0.5">ទូទាត់សាច់ប្រាក់នៅសណ្ឋាគារ</p>
                            </div>
                        </label>
                    </div>

                    {{-- DYNAMIC DETAILS: QR Code Pay / KHQR --}}
                    <div x-show="formData.payment_method === 'qr'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="border border-blue-100 dark:border-blue-900/40 rounded-2xl p-6 bg-blue-50/30 dark:bg-blue-950/20 w-full">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center w-full">
                            {{-- QR Display --}}
                            <div class="flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-blue-100 dark:border-blue-900/40 pb-6 md:pb-0 md:pr-6">
                                <div class="p-3 bg-white rounded-2xl shadow-md border border-gray-100 relative group">
                                @php
                                    $qrSetting = \App\Models\ContactSetting::where('key', 'qr_code_image')->where('status', 1)->first();
                                    $qrUrl = $qrSetting && $qrSetting->value 
                                        ? (\Illuminate\Support\Str::startsWith($qrSetting->value, ['http://', 'https://', 'images/']) ? asset($qrSetting->value) : asset('storage/' . $qrSetting->value))
                                        : asset('images/qr/ac.jpg');
                                @endphp
                                <img src="{{ $qrUrl }}" alt="KHQR Code" class="w-48 h-48 object-contain rounded-xl">
                                    <div class="absolute inset-0 bg-blue-600/10 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center pointer-events-none">
                                        <span class="bg-blue-600 text-white text-[10px] font-bold px-2 py-1 rounded-md shadow">KHQR</span>
                                    </div>
                                </div>
                                <div class="mt-4 text-center">
                                    <p class="text-xs text-gray-600 dark:text-gray-300 font-medium">
                                        សូមស្កែនទូទាត់ប្រាក់ចំនួន៖
                                    </p>
                                    <p class="text-xl font-black text-blue-600 dark:text-blue-400 mt-0.5">
                                        ${{ number_format($subtotal, 2) }}
                                        <span class="text-xs text-gray-400 font-normal">({{ number_format($subtotal * $khrRate) }} ៛)</span>
                                    </p>
                                </div>
                            </div>

                            {{-- SLIP UPLOADER --}}
                            <div class="space-y-3 w-full">
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">
                                    ផ្ទុកឡើងរូបភាពបង្កាន់ដៃដែលបានបង់ប្រាក់<span class="text-red-500">*</span>
                                </label>
                                <div class="relative w-full">
                                    <div class="w-full h-48 border-2 border-dashed rounded-2xl flex flex-col items-center justify-center p-4 transition-all bg-white dark:bg-gray-900"
                                        :class="slipPreview ? 'border-green-500' : 'border-gray-300 dark:border-gray-700 hover:border-blue-500'">
                                        <input type="file" @change="handleSlipUpload" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                        <template x-if="!slipPreview">
                                            <div class="text-center pointer-events-none space-y-2">
                                                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center mx-auto">
                                                    <i class="fas fa-cloud-upload-alt text-xl"></i>
                                                </div>
                                                <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                                    ចុចទីនេះ ឬទាញទម្លាក់រូបភាពបង្កាន់ដៃ
                                                </p>
                                            </div>
                                        </template>

                                        <template x-if="slipPreview">
                                            <div class="relative w-full h-full flex items-center justify-center">
                                                <img :src="slipPreview" class="max-w-full max-h-full rounded-xl object-contain shadow-sm">
                                                <button type="button" @click.stop="removeSlipPreview()" class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full flex items-center justify-center text-xs shadow z-20 hover:bg-red-600">
                                                    &times;
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    {{-- DYNAMIC DETAILS: CASH ON ARRIVAL --}}
                    <div x-show="formData.payment_method === 'cash'"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        class="border border-emerald-100 dark:border-emerald-900/40 rounded-2xl p-6 bg-emerald-50/30 dark:bg-emerald-950/20 w-full">

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center shrink-0 text-xl">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <div class="space-y-1">
                                <h4 class="text-sm font-extrabold text-emerald-900 dark:text-emerald-300">ទូទាត់សាច់ប្រាក់ផ្ទាល់នៅសណ្ឋាគារ</h4>
                                <p class="text-xs text-emerald-700 dark:text-emerald-400 leading-relaxed font-medium">
                                    លោកអ្នកអាចធ្វើការទូទាត់សាច់ប្រាក់នៅឯសណ្ឋាគារ ភីអេនធី ផាលេស ពេលចូលស្នាក់នៅ។ មិនបាច់ភ្ជាប់បង្កាន់ដៃទូទាត់ឡើយ!
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: ORDER SUMMARY --}}
            <div class="lg:col-span-1 w-full">
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 md:p-8 shadow-sm border border-gray-100 dark:border-gray-800 sticky top-24 space-y-6 transition-colors duration-300 w-full">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="h-6 w-1.5 bg-blue-600 rounded-full"></div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tight">
                            សង្ខេបការកក់ទុក
                        </h3>
                    </div>

                    {{-- ITEM LIST --}}
                    <div class="space-y-4 max-h-72 overflow-y-auto pr-1">
                        @foreach($cart as $item)
                        @php
                        $itemType = $item['type'] ?? 'hotel';
                        @endphp
                        <div class="flex justify-between items-start text-xs border-b border-gray-50 dark:border-gray-800/60 pb-3 gap-2">
                            <div class="space-y-1 pr-2 min-w-0">
                                <span class="inline-block font-bold text-gray-900 dark:text-gray-100 text-sm truncate w-full">
                                    {{ $item['name'] ?? 'មិនមានឈ្មោះ' }}
                                </span>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    @if($itemType == 'hotel')
                                        <span>ស្នាក់នៅ៖ <span class="font-mono font-bold">{{ isset($item['check_in']) ? \Carbon\Carbon::parse($item['check_in'])->format('d-m-Y') : '' }}</span> ដល់ <span class="font-mono font-bold">{{ isset($item['check_out']) ? \Carbon\Carbon::parse($item['check_out'])->format('d-m-Y') : '' }}</span> ({{ $item['total_nights'] ?? 1 }} យប់)</span>
                                    @else
                                        <span>កាលបរិច្ឆេទ៖ <span class="font-mono font-bold">{{ isset($item['start_date']) ? \Carbon\Carbon::parse($item['start_date'])->format('d-m-Y') : '' }}</span> ដល់ <span class="font-mono font-bold">{{ isset($item['end_date']) ? \Carbon\Carbon::parse($item['end_date'])->format('d-m-Y') : '' }}</span> ({{ $item['start_time'] ?? '' }} - {{ $item['end_time'] ?? '' }})</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-black text-gray-900 dark:text-white text-sm block">
                                    ${{ number_format($item['total_price'] ?? 0, 2) }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-mono block">({{ number_format(($item['total_price'] ?? 0) * $khrRate) }} ៛)</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- CALCULATION BREAKDOWN --}}
                    <div class="border-t border-gray-100 dark:border-gray-800 pt-4 space-y-2.5 text-xs text-gray-600 dark:text-gray-400">
                        <div class="flex justify-between items-baseline">
                            <span>តម្លៃសរុប</span>
                            <div class="text-right">
                                <span class="font-bold text-gray-900 dark:text-white">${{ number_format($subtotal, 2) }}</span>
                                <span class="text-[10px] text-gray-400 font-mono block">({{ number_format($subtotal * $khrRate) }} ៛)</span>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <span>ពន្ធ & សេវាកម្ម</span>
                            <span class="font-bold text-green-600 dark:text-green-400">$0.00</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-3 border-t border-dashed border-gray-200 dark:border-gray-800">
                            <span class="text-sm font-black text-gray-900 dark:text-white">ទឹកប្រាក់សរុបរួម៖</span>
                            <div class="text-right">
                                <span class="text-2xl font-black text-blue-600 dark:text-blue-400">${{ number_format($subtotal, 2) }}</span>
                                <span class="text-[11px] text-gray-400 block font-normal">({{ number_format($subtotal * $khrRate) }} ៛)</span>
                            </div>
                        </div>
                    </div>

                    {{-- SUBMIT BUTTON --}}
                    <button @click="processBooking" :disabled="loading"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl transition-all shadow-md shadow-blue-500/20 hover:shadow-lg hover:shadow-blue-500/30 text-sm active:scale-95 flex items-center justify-center gap-2 disabled:opacity-50 whitespace-nowrap">
                        <div x-show="!loading" class="flex items-center justify-center gap-2 w-full whitespace-nowrap">
                            <i class="fas fa-check-circle text-base"></i>
                            <span class="whitespace-nowrap">បញ្ជាក់ការកក់ & មើលវិក្កយបត្រ</span>
                        </div>
                        <div x-show="loading" class="flex items-center justify-center gap-2 w-full whitespace-nowrap" x-cloak>
                            <i class="fas fa-spinner fa-spin text-base"></i>
                            <span class="whitespace-nowrap">កំពុងដំណើរការ...</span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkoutHandler() {
        return {
            formData: {
                name: '{{ auth()->user()->name ?? "" }}',
                phone: '{{ auth()->user()->phone ?? "" }}',
                email: '{{ auth()->user()->email ?? "" }}',
                payment_method: 'qr',
                special_requests: '',
                payment_slip: null
            },
            slipPreview: null,
            loading: false,

            handleSlipUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.slipPreview = URL.createObjectURL(file);

                    const reader = new FileReader();
                    reader.onloadend = () => {
                        this.formData.payment_slip = reader.result;
                    };
                    reader.readAsDataURL(file);
                }
            },

            removeSlipPreview() {
                this.slipPreview = null;
                this.formData.payment_slip = null;
            },

            processBooking() {
                if (!this.formData.name || !this.formData.email || !this.formData.phone) {
                    Swal.fire({
                        title: 'សូមបំពេញព័ត៌មាន!',
                        text: 'សូមបំពេញឈ្មោះ លេខទូរស័ព្ទ និងអ៊ីមែល ឱ្យបានត្រឹមត្រូវ!',
                        icon: 'warning',
                        confirmButtonColor: '#2563eb',
                        customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs' }
                    });
                    return;
                }

                if (this.formData.payment_method === 'qr' && !this.formData.payment_slip) {
                    Swal.fire({
                        title: 'តម្រូវឱ្យមានបង្កាន់ដៃ!',
                        text: 'សូមមេត្តាផ្ទុកឡើងរូបភាពបង្កាន់ដៃទូទាត់ប្រាក់របស់លោកអ្នកជាមុនសិន!',
                        icon: 'warning',
                        confirmButtonColor: '#2563eb',
                        customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs' }
                    });
                    return;
                }

                this.loading = true;

                fetch('{{ route("checkout.process") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.formData)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'ការកក់ទទួលបានជោគជ័យ!',
                                html: data.message,
                                icon: 'success',
                                confirmButtonText: 'បន្តទៅកាន់ទំព័របញ្ជាក់',
                                confirmButtonColor: '#2563eb',
                                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs' }
                            }).then(() => {
                                const codesParam = (data.booking_codes && data.booking_codes.length > 0) ? data.booking_codes.join(',') : data.booking_code;
                                window.location.href = `/booking/success/${data.booking_code}?codes=${encodeURIComponent(codesParam)}`;
                            });
                        } else {
                            Swal.fire({
                                title: 'ការផ្ទៀងផ្ទាត់មិនបានសម្រេច',
                                html: data.message,
                                icon: 'error',
                                confirmButtonColor: '#2563eb',
                                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs' }
                            });
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire({
                            title: 'ការផ្ទៀងផ្ទាត់មិនបានសម្រេច',
                            text: 'សូមពិនិត្យមើលរូបភាពបង្កាន់ដៃបង់ប្រាក់ឡើងវិញ ឬអាប់ឡូតរូបបង្កាន់ដៃដែលបានទូទាត់ប្រាក់នៅថ្ងៃនេះឱ្យបានគ្រប់ចំនួន!',
                            icon: 'error',
                            confirmButtonColor: '#2563eb',
                            customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-xl font-bold px-4 py-2 text-xs' }
                        });
                    })
                    .finally(() => this.loading = false);
            }
        }
    }
</script>

@endsection