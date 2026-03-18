@extends('layouts.app')

@section('title', 'Checkout - ' . $booking->booking_code)

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">

            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('booking.history') }}" class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-sm text-gray-600 hover:text-blue-600">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-extrabold text-gray-900">ការទូទាត់ប្រាក់ (Checkout)</h1>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                            <h3 class="font-bold text-gray-800">សេចក្ដីសង្ខេបនៃការកក់</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">លេខកូដកក់</p>
                                    <p class="font-mono text-lg font-bold text-blue-600">{{ $booking->booking_code }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">ប្រភេទបន្ទប់</p>
                                    <p class="font-semibold text-gray-800">{{ $booking->room->roomType->name }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Check In</p>
                                        <p class="font-semibold text-sm">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M, Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Check Out</p>
                                        <p class="font-semibold text-sm">{{ \Carbon\Carbon::parse($booking->check_out)->format('d M, Y') }}</p>
                                    </div>
                                </div>
                                <hr class="border-gray-50">
                                <div class="flex justify-between items-center pt-2">
                                    <p class="text-gray-500 font-medium">តម្លៃសរុប</p>
                                    <p class="text-2xl font-black text-gray-900">${{ number_format($booking->total_price, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-6">ជ្រើសរើសវិធីទូទាត់</h3>

                        <form action="{{ route('bookings.payment', $booking->id) }}" method="POST">
                            @csrf
                            <div class="grid gap-4 mb-8">

                                <label class="relative flex items-center p-5 border-2 border-blue-500 bg-blue-50/50 rounded-2xl cursor-pointer group transition">
                                    <input type="radio" name="payment_method" value="khqr" checked class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-4 flex-1">
                                        <div class="flex items-center justify-between">
                                            <span class="block font-bold text-gray-900">បង់តាម KHQR (ABA / Bakong / Wing)</span>
                                            <div class="flex gap-1">
                                                <span class="px-2 py-0.5 bg-red-500 text-[10px] text-white rounded font-bold uppercase">Popular</span>
                                            </div>
                                        </div>
                                        <span class="text-sm text-gray-500 italic">ស្កេន QR Code ដើម្បីទូទាត់ភ្លាមៗ</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-5 border border-gray-200 rounded-2xl cursor-pointer hover:bg-gray-50 transition group">
                                    <input type="radio" name="payment_method" value="pay_at_hotel" class="w-5 h-5 text-blue-600 focus:ring-blue-500">
                                    <div class="ml-4 flex-1">
                                        <span class="block font-bold text-gray-700 group-hover:text-gray-900">បង់ប្រាក់នៅសណ្ឋាគារ (Pay at Hotel)</span>
                                        <span class="text-sm text-gray-500 italic">អ្នកអាចបង់ប្រាក់ផ្ទាល់នៅពេលមកដល់</span>
                                    </div>
                                </label>

                            </div>

                            <div id="qr-container" class="mb-8 p-6 bg-gray-50 rounded-3xl text-center border-2 border-dashed border-gray-200">
                                <p class="text-sm font-bold text-gray-500 mb-4 uppercase tracking-widest">Scan to Pay</p>
                                <div class="bg-white p-4 inline-block rounded-2xl shadow-sm mb-4">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=YOUR_ABA_PAY_LINK"
                                        alt="ABA KHQR" class="w-48 h-48 mx-auto">
                                </div>
                                <p class="text-xs text-gray-400">បន្ទាប់ពីបង់ប្រាក់រួច សូមរក្សាទុក Screenshot ទុកជាភស្តុតាង</p>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                                <span>យល់ព្រមបង់ប្រាក់</span>
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        </form>

                    </div>

                    <p class="text-center text-gray-400 text-xs mt-6 italic">
                        <i class="fas fa-shield-alt mr-1"></i> ការទូទាត់ប្រាក់របស់អ្នកមានសុវត្ថិភាព និងរក្សាការសម្ងាត់ខ្ពស់
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // បិទ/បើក QR Container តាមការជ្រើសរើស
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const qrContainer = document.getElementById('qr-container');

    radios.forEach(radio => {
        radio.addEventListener('change', (e) => {
            if (e.target.value === 'khqr') {
                qrContainer.classList.remove('hidden');
            } else {
                qrContainer.classList.add('hidden');
            }
        });
    });
</script>
@endsection