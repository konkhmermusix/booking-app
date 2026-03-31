@extends('layouts.app')

@section('title', $roomType->name . ' - ព័ត៌មានលម្អិត')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<div class="min-h-screen pb-20">
    <div class="container mx-auto px-4 pt-6">
        <div class="grid lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2">
                <div class="relative group">
                    <div id="roomMainSwiper" class="swiper roomGallerySwiper rounded-[1.5rem] shadow-2xl w-full aspect-[16/10] md:aspect-video overflow-hidden">
                        <div class="swiper-wrapper">
                            @foreach($roomType->images as $image)
                            <div class="swiper-slide">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover select-none" alt="Room Image">
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination !bottom-4 !z-10"></div>
                    </div>
                </div>

                <div class="flex items-center justify-center gap-4 mt-6">
                    <button onclick="moveSlide(-1)" class="w-10 h-10 rounded-full border-2 border-yellow-500 text-yellow-500 flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-all active:scale-90">
                        <i class="fas fa-chevron-left"></i>
                    </button>

                    <div id="thumbnailContainer" class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide focus:outline-none" style="-webkit-overflow-scrolling: touch;">
                        @foreach($roomType->images as $index => $img)
                        <div onclick="changeSlide({{ $index }})"
                            data-index="{{ $index }}"
                            class="thumbnail-item flex-shrink-0 w-20 h-14 md:w-28 md:h-20 rounded-xl overflow-hidden cursor-pointer border-2 transition-all duration-300 {{ $index == 0 ? 'border-blue-600 opacity-100' : 'border-transparent opacity-50' }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover pointer-events-none">
                        </div>
                        @endforeach
                    </div>

                    <button onclick="moveSlide(1)" class="w-10 h-10 rounded-full border-2 border-yellow-500 text-yellow-500 flex items-center justify-center hover:bg-yellow-500 hover:text-white transition-all active:scale-90">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8">

                        <div class="flex justify-between items-start mb-8 text-left">
                            <div>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-4xl font-black text-blue-600">${{ number_format($roomType->base_price, 0) }}</span>
                                    <span class="text-gray-400 font-medium text-sm">/ យប់</span>
                                </div>
                                <div id="totalPriceWrapper" class="hidden mt-2">
                                    <span class="text-sm font-bold text-green-500 bg-green-50 dark:bg-green-900/20 px-3 py-1 rounded-lg" id="totalPriceDisplay">តម្លៃសរុប: $0</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $roomType->rooms->where('status','available')->count() > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                    {{ $roomType->rooms->where('status','available')->count() }} បន្ទប់ទំនេរ
                                </span>
                            </div>
                        </div>

                        <form id="bookingForm" action="#" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                            <div class="space-y-4 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 text-left">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[11px] font-black text-gray-400 uppercase ml-1 mb-2 block">ថ្ងៃចូល</label>
                                        <input type="date" name="check_in" id="check_in" min="{{ date('Y-m-d') }}" value="{{ request('check_in', date('Y-m-d')) }}"
                                            class="w-full bg-white dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 dark:text-white text-sm py-3 px-3 shadow-sm" required>
                                    </div>
                                    <div>
                                        <label class="text-[11px] font-black text-gray-400 uppercase ml-1 mb-2 block">ថ្ងៃចេញ</label>
                                        <input type="date" name="check_out" id="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                                            class="w-full bg-white dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 dark:text-white text-sm py-3 px-3 shadow-sm" required>
                                    </div>
                                </div>

                                <div>
                                    <label class="text-[11px] font-black text-gray-400 uppercase ml-1 mb-2 block">ចំនួនភ្ញៀវ</label>
                                    <select name="guests" class="w-full bg-white dark:bg-gray-800 border-none rounded-xl focus:ring-2 ring-blue-500 dark:text-white text-sm py-3 shadow-sm">
                                        @for($i=1; $i<=$roomType->max_guests; $i++)
                                            <option value="{{ $i }}">{{ $i }} នាក់ (អតិបរមា)</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-[1.5rem] transition-all shadow-xl shadow-blue-200 dark:shadow-none active:scale-95 text-lg">
                                កក់បន្ទប់ឥឡូវនេះ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-10 mt-12 text-left">
            <div class="lg:col-span-2 space-y-12">
                <div class="border-b border-gray-100 dark:border-gray-800 pb-8">
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-6">{{ $roomType->name }}</h1>
                    <div class="flex flex-wrap gap-6 text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-2 font-bold bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm">
                            <i class="fas fa-bed text-blue-600"></i> {{ $roomType->beds }} គ្រែគេង
                        </span>
                        <span class="flex items-center gap-2 font-bold bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm">
                            <i class="fas fa-users text-blue-600"></i> {{ $roomType->max_guests }} នាក់
                        </span>
                        <span class="flex items-center gap-2 font-bold bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm">
                            <i class="fas fa-star text-yellow-400"></i> {{ number_format($roomType->reviews->avg('rating'), 1) }} ({{ $roomType->reviews->count() }})
                        </span>
                    </div>
                </div>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 italic">ពណ៌នាអំពីបន្ទប់</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-lg italic">
                        "{{ $roomType->description }}"
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">គ្រឿងសម្រួល (Amenities)</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($roomType->facilities as $facility)
                        <div class="flex items-center gap-4 group">
                            <div class="w-12 h-12 flex items-center justify-center bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                <i class="{{ $facility->icon ?? 'fas fa-check' }} text-lg"></i>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-bold">{{ $facility->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>

                <section class="border-t border-gray-100 dark:border-gray-800 pt-12">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">មតិយោបល់ ({{ $roomType->reviews->count() }})</h2>
                        <button onclick="document.getElementById('reviewForm').scrollIntoView({behavior: 'smooth'})" class="bg-blue-50 text-blue-600 px-6 py-2 rounded-full font-bold hover:bg-blue-600 hover:text-white transition">សរសេរមតិ</button>
                    </div>

                    <div class="grid gap-6 mb-12">
                        @forelse($roomType->reviews as $review)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-[2rem] shadow-sm border border-gray-50 dark:border-gray-700">
                            <div class="flex justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center font-bold text-white shadow-lg">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $review->user->name }}</h4>
                                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-yellow-400 text-sm bg-yellow-50 dark:bg-yellow-900/20 px-3 py-1 rounded-full h-fit">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 italic leading-relaxed">"{{ $review->comment }}"</p>
                        </div>
                        @empty
                        <div class="text-center py-10 bg-gray-100 dark:bg-gray-800/50 rounded-3xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                            <p class="text-gray-400 italic font-medium">មិនទាន់មានការវាយតម្លៃនៅឡើយទេ</p>
                        </div>
                        @endforelse
                    </div>

                    <div id="reviewForm" class="bg-blue-50 dark:bg-gray-800/50 p-8 rounded-[2.5rem] border border-blue-100 dark:border-gray-700">
                        <h3 class="text-xl font-black mb-6 dark:text-white">ផ្ដល់មតិយោបល់របស់អ្នក</h3>
                        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-sm font-bold text-gray-500">ការវាយតម្លៃ៖</span>
                                <div class="flex gap-2 text-2xl text-gray-300" id="starRating">
                                    <i class="fas fa-star cursor-pointer hover:scale-110 transition" data-val="1"></i>
                                    <i class="fas fa-star cursor-pointer hover:scale-110 transition" data-val="2"></i>
                                    <i class="fas fa-star cursor-pointer hover:scale-110 transition" data-val="3"></i>
                                    <i class="fas fa-star cursor-pointer hover:scale-110 transition" data-val="4"></i>
                                    <i class="fas fa-star cursor-pointer hover:scale-110 transition" data-val="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="5">
                            </div>

                            <textarea name="comment" rows="4" class="w-full bg-white dark:bg-gray-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition shadow-sm dark:text-white" placeholder="តើអ្នកយល់យ៉ាងណាចំពោះបន្ទប់នេះ?" required></textarea>

                            <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black hover:bg-blue-700 transition shadow-lg active:scale-95">
                                ផ្ញើមតិយោបល់ <i class="fas fa-paper-plane ml-2"></i>
                            </button>
                        </form>
                    </div>
                </section>
            </div>
        </div>

        @if($similarRooms->count() > 0)
        <div class="mt-24 text-left">
            <h2 class="text-3xl font-black mb-10 text-gray-900 dark:text-white">ប្រភេទបន្ទប់ប្រហាក់ប្រហែល</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($similarRooms as $room)
                <a href="{{ route('frontend.details', $room->id) }}" class="group">
                    <div class="relative aspect-[4/3] overflow-hidden rounded-[2.5rem] mb-5 shadow-lg border border-gray-100 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur-md px-5 py-2 rounded-2xl font-black text-sm text-blue-600 shadow-sm">
                            ${{ number_format($room->base_price, 0) }} / យប់
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white group-hover:text-blue-600 transition truncate">{{ $room->name }}</h3>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .thumbnail-item {
        -webkit-tap-highlight-color: transparent;
    }

    .swiper-pagination-bullet {
        background: white !important;
        opacity: 0.5;
        width: 8px;
        height: 8px;
        transition: all 0.3s;
    }

    .swiper-pagination-bullet-active {
        background: #2563eb !important;
        width: 25px;
        border-radius: 10px;
        opacity: 1;
    }

    .swiper-button-next:after,
    .swiper-button-prev:after {
        font-family: swiper-icons;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    let roomSwiper;

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Swiper Logic
        roomSwiper = new Swiper('.roomGallerySwiper', {
            loop: true,
            grabCursor: true,
            speed: 600,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            on: {
                slideChange: function() {
                    updateThumbnailAppearance(this.realIndex);
                }
            }
        });

        // 2. Star Rating Logic
        const stars = document.querySelectorAll('#starRating i');
        const ratingInput = document.getElementById('ratingInput');

        if (stars.length > 0) {
            stars.forEach(star => {
                star.onclick = function() {
                    let val = this.dataset.val;
                    ratingInput.value = val;
                    updateStars(val);
                }
            });
            updateStars(5); // Default rating
        }

        // 3. Price Calculation Logic
        const checkIn = document.getElementById('check_in');
        const checkOut = document.getElementById('check_out');
        const priceWrapper = document.getElementById('totalPriceWrapper');
        const priceDisplay = document.getElementById('totalPriceDisplay');
        const basePrice = {
            {
                $roomType - > base_price
            }
        };

        function calculateTotalPrice() {
            const start = new Date(checkIn.value);
            const end = new Date(checkOut.value);

            if (start && end && end > start) {
                const diffTime = Math.abs(end - start);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                const total = diffDays * basePrice;

                priceWrapper.classList.remove('hidden');
                priceDisplay.innerText = `តម្លៃសរុប: $${total.toLocaleString()} (${diffDays} យប់)`;
            } else {
                priceWrapper.classList.add('hidden');
            }
        }

        checkIn.addEventListener('change', calculateTotalPrice);
        checkOut.addEventListener('change', calculateTotalPrice);
        calculateTotalPrice(); // Initial call
    });

    function changeSlide(index) {
        if (roomSwiper) roomSwiper.slideToLoop(index);
    }

    function updateThumbnailAppearance(index) {
        const thumbnails = document.querySelectorAll('.thumbnail-item');
        thumbnails.forEach((item, i) => {
            if (parseInt(item.dataset.index) === index) {
                item.classList.add('border-blue-600', 'opacity-100');
                item.classList.remove('opacity-50', 'border-transparent');
                item.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            } else {
                item.classList.add('opacity-50', 'border-transparent');
                item.classList.remove('opacity-100', 'border-blue-600');
            }
        });
    }

    function updateStars(val) {
        const stars = document.querySelectorAll('#starRating i');
        stars.forEach(s => {
            if (s.dataset.val <= val) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-gray-300');
            } else {
                s.classList.add('text-gray-300');
                s.classList.remove('text-yellow-400');
            }
        });
    }
</script>
@endsection