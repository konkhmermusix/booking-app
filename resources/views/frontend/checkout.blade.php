@extends('layouts.app')
@section('title', 'ទូទាត់ការកក់')
@section('content')

<div class="container mx-auto">
    <section class="py-10 dark:bg-[#0b1120] ">
        <div class="container mx-auto px-4" x-data="checkoutHandler()">
            <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200 dark:border-gray-800">
                <h1 class="text-2xl font-extrabold flex items-center gap-2.5 text-gray-900 dark:text-white tracking-tight">
                    ពិនិត្យ និងទូទាត់ប្រាក់
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-gray-900 dark:text-white rounded-xl p-8 shadow-sm border border-gray-100 dark:border-gray-800">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                            ព័ត៌មានអ្នកកក់
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium dark:text-white mb-2">ឈ្មោះពេញ</label>
                                <input type="text" x-model="formData.name" placeholder="ឈ្មោះពេញ"
                                    class="w-full h-[52px] pl-5 pr-4 bg-gray-50 dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                            </div>

                            <div>
                                <label class="block text-sm font-medium dark:text-white mb-2">លេខទូរស័ព្ទ</label>
                                <input type="tel" x-model="formData.phone" placeholder="012 345 678"
                                    class="w-full h-[52px] pl-5 pr-4 bg-gray-50 dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium dark:text-white mb-2">អ៊ីមែល</label>
                                <input type="email" x-model="formData.email" placeholder="អ៊ីមែល"
                                    class="w-full h-[52px] pl-5 pr-4 bg-gray-50 dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-sm font-medium dark:text-white mb-2">មតិផ្សេងៗ</label>
                                <textarea x-model="formData.special_requests" rows="3" placeholder="មតិផ្សេងៗ..."
                                    class="w-full p-4 bg-gray-50 dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-900 dark:text-white rounded-xl p-8 shadow-sm border border-gray-100 dark:border-gray-800">
                        <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                            ជ្រើសរើសវិធីទូទាត់ប្រាក់
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <label class="relative border-2 rounded-2xl p-5 cursor-pointer transition-all flex flex-col justify-between"
                                :class="formData.payment_method === 'aba' ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/20' : 'border-gray-100 dark:border-gray-800'">
                                <input type="radio" x-model="formData.payment_method" value="aba" class="hidden">
                                <div>
                                    <p class="font-extrabold text-base text-gray-900 dark:text-white">ទូទាត់តាមឃ្យូអរ</p>
                                </div>
                            </label>

                            <label class="relative border-2 rounded-2xl p-5 cursor-pointer transition-all flex flex-col justify-between"
                                :class="formData.payment_method === 'cash' ? 'border-blue-600 bg-blue-50/50 dark:bg-blue-950/20' : 'border-gray-100 dark:border-gray-800'">
                                <input type="radio" x-model="formData.payment_method" value="cash" class="hidden">
                                <div>
                                    <p class="font-extrabold text-base text-gray-900 dark:text-white">ទូទាត់ផ្ទាល់នៅសណ្ឋាគារ</p>
                                </div>
                            </label>
                        </div>

                        <div x-show="formData.payment_method === 'aba'"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="border border-gray-200 dark:border-gray-800 rounded-2xl p-6 bg-gray-50 dark:bg-gray-900/50">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                <div class="flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-800 pb-6 md:pb-0 md:pr-6">
                                    <div class="p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
                                        <img src="{{ asset('images/qr/ac.jpg') }}"
                                            alt="ABA QR Code" class="w-44 h-44 object-contain">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-3 text-center dark:text-gray-400">
                                        សូមបើកកម្មវិធី <span class="text-blue-500 font-bold">ធនាគារ</span> ដើម្បីស្កែនទូទាត់ប្រាក់ <br>
                                        ចំនួនទឹកប្រាក់សរុប៖ <span class="text-gray-900 dark:text-white font-mono font-bold">${{ number_format($subtotal, 2) }}</span>
                                    </p>
                                </div>

                                <div class="space-y-3">
                                    <div class="relative group">
                                        <div class="w-full h-50 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl flex flex-col items-center justify-center p-4 transition-all hover:border-blue-500 dark:hover:border-blue-400 bg-white dark:bg-gray-900"
                                            :class="slipPreview ? 'border-green-500 dark:border-green-500' : ''">
                                            <input type="file" @change="handleSlipUpload" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <template x-if="!slipPreview">
                                                <div class="text-center pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-10 w-10 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400"> ភ្ជាប់វិក្កយបត្រទូទាត់ប្រាក់ <span class="text-red-500">*</span><br></p>
                                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">ចុចទីនេះ ឬទាញទម្លាក់រូបភាពបង្កាន់ដៃ</p>
                                                    <p class="text-xs text-gray-400 mt-0.5">គាំទ្រតែប្រភេទរូបភាព JPG, PNG</p>
                                                </div>
                                            </template>

                                            <template x-if="slipPreview">
                                                <div class="relative w-full h-full flex items-center justify-center pointer-events-none">
                                                    <img :src="slipPreview" class="max-w-full max-h-full rounded-xl object-contain">
                                                    <div class="absolute bottom-1 right-1 bg-green-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md shadow-sm">
                                                        បានជ្រើសរើស
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-900 dark:text-white rounded-xl p-8 sticky top-6 shadow-2xl border border-gray-100 dark:border-gray-800">
                        <h3 class="text-xl font-bold mb-6">សេចក្តីសង្ខេបការកក់</h3>

                        <div class="space-y-4 mb-8">
                            @foreach($cart as $item)
                            <div class="flex justify-between text-sm items-center">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 dark:text-gray-200 font-bold">{{ Str::limit($item['name'] ?? 'បន្ទប់មិនស្គាល់ឈ្មោះ', 25) }}</span>
                                    <span class="text-xs text-gray-400">ប្រភេទ៖ {{ isset($item['type']) && $item['type'] == 'hotel' ? 'បន្ទប់សណ្ឋាគារ' : 'សាលប្រជុំ' }}</span>
                                </div>
                                <span class="font-mono font-semibold text-gray-800 dark:text-gray-200">${{ number_format($item['total_price'] ?? 0, 2) }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-100 dark:border-gray-800 pt-6 space-y-3">
                            <div class="flex justify-between text-gray-400 text-sm">
                                <span>តម្លៃសរុប</span>
                                <span class="font-mono">${{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400 text-sm">
                                <span>ពន្ធ & សេវាកម្ម (0%)</span>
                                <span class="font-mono">$0.00</span>
                            </div>
                            <div class="flex justify-between text-2xl font-black pt-4 border-t border-dashed border-gray-200 dark:border-gray-800">
                                <span>សរុបរួម</span>
                                <span class="text-blue-600 dark:text-blue-400 font-mono">${{ number_format($subtotal, 2) }}</span>
                            </div>
                        </div>

                        <button @click="processBooking" :disabled="loading" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl mt-8 transition-all flex items-center justify-center gap-2 text-sm disabled:opacity-50">
                            <template x-if="!loading">
                                <span>បញ្ជាក់ការកក់ និងមើលវិក្កយបត្រ</span>
                            </template>
                            <template x-if="loading">
                                <span class="animate-spin text-xl">សូមរង់ចាំ...</span>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function checkoutHandler() {
        return {
            formData: {
                name: '{{ auth()->user()->name ?? "" }}',
                phone: '{{ auth()->user()->phone ?? "" }}',
                email: '{{ auth()->user()->email ?? "" }}',
                payment_method: 'aba',
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

            processBooking() {
                if (!this.formData.name || !this.formData.email || !this.formData.phone) {
                    Swal.fire('ព្រមាន', 'សូមបំពេញព័ត៌មាន ឈ្មោះពេញ​ លេខទូរស័ព្ទ អ៊ីមែល ឱ្យត្រឹមត្រូវ!', 'warning');
                    return;
                }

                if (this.formData.payment_method === 'aba' && !this.formData.payment_slip) {
                    Swal.fire('តម្រូវឱ្យមាន', 'សូមមេត្តាផ្ទុកឡើងរូបភាពបង្កាន់ដៃវេរលុយរបស់លោកអ្នកជាមុនសិន!', 'warning');
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
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'ជោគជ័យ!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'មើលវិក្កយបត្រ'
                            }).then((result) => {
                                window.location.href = `/booking/receipt/${data.booking_code}`;
                            });
                        } else {
                            Swal.fire('បរាជ័យ', data.message, 'error');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        Swal.fire('Error', 'មានបញ្ហាប្រព័ន្ធក្នុងការតភ្ជាប់ជាមួយម៉ាស៊ីនបម្រើ!', 'error');
                    })
                    .finally(() => this.loading = false);
            }
        }
    }
</script>
@endsection