@extends('layouts.app')

@section('title', $roomType->name . ' - ព័ត៌មានលម្អិតបន្ទប់ប្រជុំ')

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

<div class="min-h-screen bg-gray-50 pb-20">
    <div class="container mx-auto px-3 sm:px-4 lg:px-6 pt-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">

            {{-- LEFT: IMAGE GALLERY --}}
            <div class="lg:col-span-2">
                <div class="space-y-4">
                    {{-- MAIN IMAGE --}}
                    <div class="relative overflow-hidden rounded-2xl md:rounded-3xl shadow-xl group">
                        <img id="mainImage"
                            src="{{ asset('storage/' . $roomType->images->first()->image_path) }}"
                            class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover duration-700 group-hover:scale-105">

                        <button type="button" onclick="openImageModal()"
                            class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-2 rounded-xl shadow hover:bg-blue-600 hover:text-white duration-300">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

                    {{-- THUMBNAILS --}}
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
                </div>
            </div>

            {{-- RIGHT: BOOKING FORM --}}
            <div>
                <div class="sticky top-20 bg-white rounded-2xl md:rounded-3xl shadow-xl p-4 md:p-6 space-y-5 border border-gray-100">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-black text-blue-600">
                            ${{ number_format($roomType->price_per_hour, 0) }}
                        </h2>
                        <p class="text-gray-500 text-sm">/ ម៉ោង</p>
                    </div>

                    <div id="totalPriceWrapper" class="hidden">
                        <span id="totalPriceDisplay" class="inline-block bg-blue-50 text-blue-700 px-4 py-2 rounded-xl font-bold text-sm"></span>
                    </div>

                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="hotel_id" value="{{ $roomType->hotel_id }}">
                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                        <input type="hidden" name="price_at_booking" value="{{ $roomType->base_price }}">
                        <input type="hidden" name="room_id" value="{{ $roomType->id }}">

                        <div class="space-y-4">
                            <div>
                                <label class="text-sm font-semibold">ថ្ងៃចូល</label>
                                <input type="date" name="check_in" id="check_in" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                    class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]"
                                    required>
                            </div>

                            <div>
                                <label class="text-sm font-semibold">ថ្ងៃចេញ</label>
                                <input type="date" name="check_out" id="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                    class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]"
                                    required>
                            </div>

                            <div>
                                <label class="text-sm font-semibold">ភ្ញៀវ</label>
                                <select name="guests" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                    @for($i=1; $i <= $roomType->max_guests; $i++)
                                        <option value="{{ $i }}">{{ $i }} នាក់</option>
                                        @endfor
                                </select>
                            </div>

                            <div>
                                <label class="text-sm font-semibold">សំណូមពរ</label>
                                <textarea name="special_requests" rows="2"
                                    class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm"
                                    placeholder="សុំបន្ទប់ស្ងាត់ ឬបន្ថែមគ្រែ..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 md:py-4 rounded-2xl font-black transition-all active:scale-95">
                                កក់បន្ទប់ប្រជុំឥឡូវនេះ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- INFO SECTION --}}
        <div class="mt-10 md:mt-14 bg-white rounded-2xl md:rounded-3xl shadow-sm border border-gray-100 p-5 md:p-8">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl md:text-4xl font-black mb-4">{{ $roomType->name }}</h1>
                    <div class="flex flex-wrap gap-3 mb-8 text-gray-600">
                        <span class="px-4 py-2 bg-gray-100 rounded-xl text-sm"><i class="fas fa-users mr-2"></i>ចំណុះ: {{ $roomType->capacity }} នាក់</span>
                        <span class="px-4 py-2 bg-gray-100 rounded-xl text-sm"><i class="fas fa-expand-arrows-alt mr-2"></i>ទំហំ: {{ $roomType->area }} m²</span>
                    </div>
                </div>
            </div>

            <h2 class="text-xl md:text-2xl font-bold mb-3 border-l-4 border-blue-600 pl-3">ព័ត៌មានលម្អិត</h2>
            <p class="text-gray-600 leading-relaxed mb-10">{{ $roomType->description }}</p>

            <h2 class="text-xl md:text-2xl font-bold mb-5 border-l-4 border-blue-600 pl-3">បរិក្ខារបច្ចេកទេស</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($roomType->facilities as $facility)
                <div class="bg-gray-50 rounded-2xl p-4 flex items-center gap-3 border border-gray-100">
                    <i class="{{ $facility->icon ?? 'fas fa-check-circle' }} text-blue-600"></i>
                    <span class="font-medium text-gray-700 text-sm">{{ $facility->name }}</span>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>

{{-- MODAL --}}
<div id="imageModal" class="fixed inset-0 z-[9999] hidden bg-black/95 items-center justify-center p-4">
    <button onclick="closeImageModal()" class="absolute top-5 right-6 text-white text-5xl">&times;</button>
    <img id="modalImage" class="max-w-full max-h-[90vh] rounded-xl object-contain">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');

        startTime.addEventListener('change', calculateMeetingPrice);
        endTime.addEventListener('change', calculateMeetingPrice);

        calculateMeetingPrice();
    });

    function calculateMeetingPrice() {
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;
        const wrapper = document.getElementById('totalPriceWrapper');
        const display = document.getElementById('totalPriceDisplay');

        const pricePerHour = parseFloat("{{ $roomType->price_per_hour }}") || 0;

        if (start && end) {
            const startTime = new Date(`2026-01-01 ${start}`);
            const endTime = new Date(`2026-01-01 ${end}`);

            // គិតជាម៉ោង (Difference in hours)
            let diffMs = endTime - startTime;
            let diffHrs = diffMs / (1000 * 60 * 60);

            if (diffHrs > 0) {
                let total = diffHrs * pricePerHour;
                wrapper.classList.remove('hidden');
                display.innerHTML = `<i class="fas fa-clock mr-2"></i> សរុប: ${diffHrs.toFixed(1)} ម៉ោង = $${total.toLocaleString()}`;
            } else {
                wrapper.classList.add('hidden');
            }
        }
    }

    // Gallery Functions
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
</script>

@endsection