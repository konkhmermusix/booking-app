@extends('layouts.app')
@section('title', 'ទូរទាត់កក់')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10" x-data="checkoutHandler()">
    <h1 class="text-3xl font-black text-gray-900 mb-8">ពិនិត្យ និងទូទាត់ប្រាក់</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        <div class="lg:col-span-2 space-y-8">

            <div class="bg-white dark:bg-gray-900 dark:text-white rounded-xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm">1</span>
                    ព័ត៌មានអ្នកកក់
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium dark:text-white mb-2">នាមត្រកូល</label>
                        <input type="text" x-model="formData.last_name"
                            class="w-full h-[52px] pl-5 pr-4 bg-gray-50 dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium dark:text-white mb-2">នាមខ្លួន</label>
                        <input type="text" x-model="formData.first_name"
                            class="w-full h-[52px] pl-5 pr-4 bg-gray-50 dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium dark:text-white mb-2">លេខទូរស័ព្ទ</label>
                        <input type="tel" x-model="formData.phone" placeholder="012 345 678"
                            class="w-full h-[52px] pl-5 pr-4 bg-gray-50 dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium dark:text-white mb-2">សំណូមពរពិសេស (បើមាន)</label>
                        <textarea x-model="formData.request" rows="3"
                            class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl  p-3 focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-900 dark:text-white rounded-xl p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <span class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm">2</span>
                    ជ្រើសរើសវិធីទូទាត់ប្រាក់
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="relative border-2 rounded-2xl p-4 cursor-pointer transition-all" :class="formData.payment_method === 'aba' ? 'border-blue-600 bg-blue-50' : 'border-gray-100'">
                        <input type="radio" x-model="formData.payment_method" value="aba" class="hidden">
                        <img src="https://www.ababank.com/typo3conf/ext/aba/Resources/Public/images/aba-logo.png" class="h-8 mb-2">
                        <p class="font-bold text-sm">ABA PAY / KHQR</p>
                    </label>
                    <label class="relative border-2 rounded-2xl p-4 cursor-pointer transition-all" :class="formData.payment_method === 'card' ? 'border-blue-600 bg-blue-50' : 'border-gray-100'">
                        <input type="radio" x-model="formData.payment_method" value="card" class="hidden">
                        <i class="fa-solid fa-credit-card text-2xl mb-2 text-gray-600"></i>
                        <p class="font-bold text-sm">Visa / Mastercard</p>
                    </label>
                    <label class="relative border-2 rounded-2xl p-4 cursor-pointer transition-all" :class="formData.payment_method === 'arrival' ? 'border-blue-600 bg-blue-50' : 'border-gray-100'">
                        <input type="radio" x-model="formData.payment_method" value="arrival" class="hidden">
                        <i class="fa-solid fa-hotel text-2xl mb-2 text-gray-600"></i>
                        <p class="font-bold text-sm">បង់ប្រាក់ពេលមកដល់</p>
                    </label>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-900 dark:text-white rounded-xl p-8 sticky top-6 shadow-2xl">
                <h3 class="text-xl font-bold mb-6">សេចក្តីសង្ខេបការកក់</h3>

                <div class="space-y-4 mb-8">
                    @foreach($cart as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">{{ Str::limit($item['name'], 20) }} x 1</span>
                        <span class="font-mono">${{ number_format($item['total_price'], 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-800 pt-6 space-y-3">
                    <div class="flex justify-between text-gray-400">
                        <span>តម្លៃសរុប</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-400">
                        <span>ពន្ធ & សេវាកម្ម (0%)</span>
                        <span>$0.00</span>
                    </div>
                    <div class="flex justify-between text-2xl font-black pt-4">
                        <span>សរុបរួម</span>
                        <span class="text-blue-400">${{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>

                <button @click="processBooking" :disabled="loading" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl mt-8 transition-all flex items-center justify-center gap-2">
                    <template x-if="!loading">
                        <span>បញ្ជាក់ការកក់ឥឡូវនេះ</span>
                    </template>
                    <template x-if="loading">
                        <span class="animate-spin text-xl">◌</span>
                    </template>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function checkoutHandler() {
        return {
            formData: {
                first_name: '',
                last_name: '',
                phone: '',
                request: '',
                payment_method: 'aba'
            },
            loading: false,
            processBooking() {
                if (!this.formData.phone) {
                    Swal.fire('ព្រមាន', 'សូមបំពេញលេខទូរស័ព្ទ!', 'warning');
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
                                title: 'ការកក់ទទួលបានជោគជ័យ!',
                                text: 'លេខកូដកក់របស់អ្នកគឺ៖ ' + data.booking_code,
                                icon: 'success',
                                confirmButtonText: 'ត្រឡប់ទៅទំព័រដើម'
                            }).then(() => {
                                window.location.href = '/';
                            });
                        } else {
                            Swal.fire('បរាជ័យ', data.message, 'error');
                        }
                    })
                    .finally(() => this.loading = false);
            }
        }
    }
</script>
@endsection