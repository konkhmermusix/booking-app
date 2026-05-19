@extends('layouts.app')

@section('title', $promotion->title . ' - ព័ត៌មានលម្អិតការផ្តល់ជូនពិសេស')

@section('content')

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
</style>

<div class="container mx-auto">
    <div class="pt-20 text-center mb-30 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            ការផ្តល់ជូនពិសេសសម្រាប់ <span class="text-blue-600">{{ $roomType->category === 'stay' ? 'បន្ទប់ស្នាក់នៅ' : 'សាលប្រជុំ' }} </span>
        </h1>
        <h1 class="text-2xl md:text-3xl font-black mt-2 uppercase">{{ $promotion->title }}</h1>
        <p class="text-white/80 text-sm mt-1"></p>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            {{ $promotion->description }}
        </p>
        <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
    </div>


    <section class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <div class="relative overflow-hidden rounded-2xl md:rounded-2xl shadow-xl group">
                            @if($roomType->images->count() > 0)
                            <img id="mainImage"
                                src="{{ asset('storage/' . $roomType->images->first()->image_path) }}"
                                class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover duration-700 group-hover:scale-105">
                            @else
                            <img id="mainImage" src="{{ asset('storage/' . $promotion->image_path) }}"
                                class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover">
                            @endif

                            <button type="button" onclick="openImageModal()"
                                class="absolute top-3 right-3 bg-white dark:bg-white backdrop-blur px-3 py-2 rounded-xl shadow hover:bg-blue-600 hover:text-white ">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>

                        @if($roomType->images->count() > 1)
                        <div class="relative">
                            <button onclick="scrollThumbs(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </button>

                            <div id="thumbContainer" class="flex gap-2 md:gap-3 overflow-x-auto scroll-smooth px-10 py-2 scrollbar-hide">
                                @foreach($roomType->images as $index => $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    onclick="changeImage(this)"
                                    class="thumbItem flex-shrink-0 w-24 h-16 md:w-32 md:h-20 rounded-xl object-cover cursor-pointer border-2 {{ $index == 0 ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-600 duration-300 shadow-sm">
                                @endforeach
                            </div>

                            <button onclick="scrollThumbs(1)" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow-lg w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="sticky top-20 bg-white dark:bg-gray-900  rounded-2xl md:rounded-2xl shadow-xl p-4 md:p-6 space-y-5 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-baseline justify-between">
                            <div class="flex">
                                <h2 class="text-2xl font-black text-blue-600">
                                    ${{ number_format($promotion->discounted_price, 0) }}
                                </h2>
                                <p class="text-gray-500 text-md ml-2">/ {{ $roomType->category === 'stay' ? 'យប់' : 'ម៉ោង' }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-gray-400 line-through text-lg font-bold">${{ number_format($promotion->original_price, 0) }}</span>
                                <p class="text-red-500 text-xs font-bold bg-red-50 px-2 py-0.5 rounded-md mt-1">
                                    សន្សំបាន ${{ number_format($promotion->original_price - $promotion->discounted_price, 0) }}
                                </p>
                            </div>
                        </div>

                        <div id="totalPriceWrapper" class="hidden">
                            <span id="totalPriceDisplay"
                                class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                        </div>

                        <form action="{{ $roomType->category === 'stay' ? route('booking.store') : route('cart.add.meeting') }}" method="POST" class="space-y-4">
                            @csrf

                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                            <input type="hidden" name="promo_price" value="{{ $promotion->discounted_price }}">

                            <div class="gap-2">
                                @if($roomType->category === 'stay')
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូលស្នាក់នៅ
                                    </label>
                                    <input type="date" name="check_in" id="check_in" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃចាកចេញ
                                    </label>
                                    <input type="date" name="check_out" id="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                @else
                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចាប់ផ្តើមប្រជុំ
                                    </label>
                                    <input type="date" name="start_date" id="start_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                    <input type="hidden" name="end_date" id="end_date" value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃបញ្ចប់ប្រជុំ
                                    </label>
                                    <input type="date" name="end_date" id="end_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                    <input type="hidden" name="end_date" id="end_date" value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="space-y-2 grid grid-cols-2 gap-3">
                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងចាប់ផ្តើម (រាល់ថ្ងៃ)
                                        </label>
                                        <input type="time" name="start_time" id="start_time" value="07:00"
                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងបញ្ចប់ (រាល់ថ្ងៃ)
                                        </label>
                                        <input type="time" name="end_time" id="end_time" value="17:00"
                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                    </div>
                                </div>
                                @endif

                                <div class="space-y-2 md:col-span-2 mt-2">
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">
                                        <i class="fas fa-comments text-blue-600 mr-1"></i>មតិផ្សេងៗ
                                    </label>
                                    <textarea name="special_requests" rows="2"
                                        class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"
                                        placeholder="ចំនួនមនុស្សត្រូវស្នាក់នៅ ឬមិនចាំបាច់ក៏បាន ទុកឱ្យទំនេរ..."></textarea>
                                </div>
                            </div>

                            <button type="submit"
                                class="px-6 h-11 w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                                <div class="flex items-center gap-2">
                                    ទទួលយកប្រូម៉ូសិន និងកក់ឥឡូវនេះ
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="mt-10 md:mt-14 bg-white dark:bg-gray-900 rounded-2xl md:rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-5 md:p-8 transition-colors duration-300">
                <div class="flex justify-between items-start flex-wrap gap-4">
                    <div>
                        <h1 class="text-2xl md:text-4xl font-black mb-4 text-gray-900 dark:text-white">{{ $roomType->name }}</h1>

                        <div class="flex flex-wrap gap-3 mb-8 text-gray-600 dark:text-gray-300">
                            @if($roomType->category === 'stay')
                            <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                                <i class="fas fa-bed mr-2 text-blue-600 dark:text-blue-400"></i>គ្រែ៖ {{ $roomType->beds ?? 1 }} គ្រែ
                            </span>
                            <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                                <i class="fas fa-users mr-2 text-blue-600 dark:text-blue-400"></i>ស្នាក់នៅបាន៖ {{ $roomType->max_guests }} នាក់
                            </span>
                            @else
                            <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                                <i class="fas fa-users mr-2 text-blue-600 dark:text-blue-400"></i>ចំណុះសរុប៖ {{ $roomType->capacity }} នាក់
                            </span>
                            <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                                <i class="fas fa-expand-arrows-alt mr-2 text-blue-600 dark:text-blue-400"></i>ទំហំផ្ទៃក្រឡា៖ {{ $roomType->area }} m²
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <h2 class="text-xl md:text-2xl font-bold mb-3 border-l-4 border-blue-600 dark:border-blue-500 pl-3 text-gray-900 dark:text-white">
                    ព័ត៌មានលម្អិតពីបន្ទប់
                </h2>
                <p class="text-gray-600 dark:text-gray-400 leading-relaxed mb-10 text-sm md:text-base">
                    {{ $roomType->description }}
                </p>

                <h2 class="text-xl md:text-2xl font-bold mb-5 border-l-4 border-blue-600 dark:border-blue-500 pl-3 text-gray-900 dark:text-white">
                    គ្រឿងបរិក្ខារ និងបច្ចេកវិទ្យាដែលផ្តល់ជូន
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($roomType->facilities as $facility)
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-4 flex items-center gap-3 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                        <i class="{{ $facility->icon ?? 'fas fa-check-circle' }} text-blue-600 dark:text-blue-400 text-lg flex-shrink-0"></i>
                        <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">
                            {{ $facility->name }}
                        </span>
                    </div>
                    @empty
                    <div class="text-sm text-gray-400 dark:text-gray-500 col-span-full py-4 text-center bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <i class="fas fa-info-circle mr-1"></i> មិនទាន់មានទិន្នន័យគ្រឿងបរិក្ខារនៅឡើយទេ។
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL GALLERY --}}
<div id="imageModal" class="fixed inset-0 z-[9999] hidden bg-black/95 items-center justify-center p-4">
    <button onclick="closeImageModal()" class="absolute top-5 right-6 text-white text-5xl hover:text-red-400">&times;</button>
    <img id="modalImage" class="max-w-full max-h-[90vh] rounded-xl object-contain shadow-2xl">
</div>

<script>
    const categoryType = "{{ $roomType->category }}";
    const promoPrice = parseFloat("{{ $promotion->discounted_price }}") || 0;

    document.addEventListener('DOMContentLoaded', function() {
        if (categoryType === 'stay') {
            document.getElementById('check_in').addEventListener('change', calculateStayPrice);
            document.getElementById('check_out').addEventListener('change', calculateStayPrice);
            calculateStayPrice();
        } else {
            document.getElementById('start_time').addEventListener('change', calculateMeetingPrice);
            document.getElementById('end_time').addEventListener('change', calculateMeetingPrice);
            document.getElementById('start_date').addEventListener('change', function() {
                // រក្សាទុកថ្ងៃបញ្ចប់ឱ្យស្មើថ្ងៃចាប់ផ្តើម
                document.getElementById('end_date').value = this.value;
            });
            calculateMeetingPrice();
        }
    });

    // គណនាតម្លៃស្នាក់នៅតាមចំនួនយប់ (Stay Calculation)
    function calculateStayPrice() {
        const checkInVal = document.getElementById('check_in').value;
        const checkOutVal = document.getElementById('check_out').value;
        const wrapper = document.getElementById('totalPriceWrapper');
        const display = document.getElementById('totalPriceDisplay');

        if (checkInVal && checkOutVal) {
            let start = new Date(checkInVal);
            let end = new Date(checkOutVal);
            let diffDays = (end - start) / (1000 * 60 * 60 * 24);

            if (diffDays > 0) {
                let total = diffDays * promoPrice;
                wrapper.classList.remove('hidden');
                display.innerHTML = `<i class="fas fa-moon mr-2"></i> តម្លៃសរុប៖ $${total.toLocaleString()} (${diffDays} យប់)`;
            } else {
                wrapper.classList.add('hidden');
            }
        }
    }

    // គណនាតម្លៃសាលប្រជុំតាមចំនួនម៉ោង (Meeting Calculation)
    function calculateMeetingPrice() {
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;
        const wrapper = document.getElementById('totalPriceWrapper');
        const display = document.getElementById('totalPriceDisplay');

        if (start && end) {
            const startTime = new Date(`2026-01-01 ${start}`);
            const endTime = new Date(`2026-01-01 ${end}`);
            let diffHrs = (endTime - startTime) / (1000 * 60 * 60);

            if (diffHrs > 0) {
                let total = diffHrs * promoPrice;
                wrapper.classList.remove('hidden');
                display.innerHTML = `<i class="fas fa-clock mr-2"></i> តម្លៃសរុប៖ $${total.toLocaleString()} (${diffHrs.toFixed(1)} ម៉ោង)`;
            } else {
                wrapper.classList.add('hidden');
            }
        }
    }

    // Gallery Controls
    function changeImage(el) {
        document.getElementById('mainImage').src = el.src;
        document.querySelectorAll('.thumbItem').forEach(item => item.classList.replace('border-blue-600', 'border-transparent'));
        el.classList.replace('border-transparent', 'border-blue-600');
    }

    function scrollThumbs(dir) {
        document.getElementById('thumbContainer').scrollBy({
            left: dir * 280,
            behavior: 'smooth'
        });
    }

    function openImageModal() {
        document.getElementById('modalImage').src = document.getElementById('mainImage').src;
        document.getElementById('imageModal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeImageModal();
    });
</script>

@endsection