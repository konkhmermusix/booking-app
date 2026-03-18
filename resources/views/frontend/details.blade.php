@extends('layouts.app')

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen pb-20">

    <div class="relative group">
        <div class="swiper roomGallerySwiper h-[400px] md:h-[600px]">
            <div class="swiper-wrapper">
                @foreach($roomType->images as $image)
                <div class="swiper-slide">
                    <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover" alt="Room Image">
                </div>
                @endforeach
            </div>
            <div class="swiper-button-next text-white after:text-2xl"></div>
            <div class="swiper-button-prev text-white after:text-2xl"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <div class="container mx-auto px-4 mt-8">
        <div class="grid lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 space-y-12">

                <div class="border-b border-gray-100 dark:border-gray-800 pb-8">
                    <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-4">{{ $roomType->name }}</h1>
                    <div class="flex flex-wrap gap-6 text-gray-600 dark:text-gray-400">
                        <span class="flex items-center gap-2 font-medium">
                            <i class="fas fa-bed text-blue-600"></i> {{ $roomType->beds }} គ្រែគេង
                        </span>
                        <span class="flex items-center gap-2 font-medium">
                            <i class="fas fa-users text-blue-600"></i> ភ្ញៀវអតិបរមា {{ $roomType->max_guests }} នាក់
                        </span>
                        <span class="flex items-center gap-2 font-medium">
                            <i class="fas fa-star text-yellow-400"></i> {{ number_format($roomType->reviews->avg('rating'), 1) }} ({{ $roomType->reviews->count() }} មតិ)
                        </span>
                    </div>
                </div>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">ពណ៌នាអំពីបន្ទប់</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed text-lg">
                        {{ $roomType->description }}
                    </p>
                </section>

                <section>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">គ្រឿងសម្រួល (Amenities)</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-y-6">
                        @foreach($roomType->facilities as $facility)
                        <div class="flex items-center gap-4 group">
                            <div class="w-10 h-10 flex items-center justify-center bg-blue-50 dark:bg-blue-900/20 rounded-full text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                                <i class="{{ $facility->icon ?? 'fas fa-check' }}"></i>
                            </div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ $facility->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </section>

                <section class="border-t border-gray-100 dark:border-gray-800 pt-12">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">មតិយោបល់ និងការវាយតម្លៃ</h2>
                        <button onclick="document.getElementById('reviewForm').scrollIntoView({behavior: 'smooth'})" class="text-blue-600 font-bold hover:underline">សរសេរមតិ</button>
                    </div>

                    <div class="space-y-6 mb-10">
                        @forelse($roomType->reviews as $review)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-50 dark:border-gray-700">
                            <div class="flex justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center font-bold text-blue-600">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white">{{ $review->user->name }}</h4>
                                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                                <div class="text-yellow-400 text-sm">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                        @endfor
                                </div>
                            </div>
                            <p class="text-gray-600 dark:text-gray-400 italic">"{{ $review->comment }}"</p>
                        </div>
                        @empty
                        <p class="text-gray-400 italic">មិនទាន់មានការវាយតម្លៃនៅឡើយទេ</p>
                        @endforelse
                    </div>

                    <div id="reviewForm" class="bg-gray-100 dark:bg-gray-800/50 p-8 rounded-[2.5rem]">
                        <h3 class="text-xl font-bold mb-6">ផ្ដល់មតិយោបល់របស់អ្នក</h3>
                        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                            <div class="flex gap-2 mb-4 text-2xl text-gray-300" id="starRating">
                                <i class="fas fa-star cursor-pointer hover:text-yellow-400" data-val="1"></i>
                                <i class="fas fa-star cursor-pointer hover:text-yellow-400" data-val="2"></i>
                                <i class="fas fa-star cursor-pointer hover:text-yellow-400" data-val="3"></i>
                                <i class="fas fa-star cursor-pointer hover:text-yellow-400" data-val="4"></i>
                                <i class="fas fa-star cursor-pointer hover:text-yellow-400" data-val="5"></i>
                                <input type="hidden" name="rating" id="ratingInput" value="5">
                            </div>
                            <textarea name="comment" rows="4" class="w-full bg-white dark:bg-gray-900 border-none rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition" placeholder="តើអ្នកយល់យ៉ាងណាចំពោះបន្ទប់នេះ?"></textarea>
                            <button class="bg-gray-900 dark:bg-blue-600 text-white px-8 py-3 rounded-xl font-bold">ផ្ញើមតិ</button>
                        </form>
                    </div>
                </section>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-gray-700 p-8">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-3xl font-black text-blue-600">${{ number_format($roomType->base_price, 0) }}<span class="text-sm text-gray-400 font-normal"> / យប់</span></span>
                        </div>

                        <form id="bookingForm" class="space-y-4">

                            @csrf
                            <input type="hidden" name="hotel_id" value="{{ $roomType->hotel_id }}">
                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase ml-1">កាលបរិច្ឆេទ</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="date" name="check_in" class="bg-gray-50 dark:bg-gray-900 border-none rounded-xl p-3 text-sm font-bold dark:text-white" required>
                                    <input type="date" name="check_out" class="bg-gray-50 dark:bg-gray-900 border-none rounded-xl p-3 text-sm font-bold dark:text-white" required>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="text-xs font-bold text-gray-400 uppercase ml-1">ចំនួនភ្ញៀវ</label>
                                <select name="guests" class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-xl p-3 text-sm font-bold dark:text-white">
                                    @for($i=1; $i<=$roomType->max_guests; $i++)
                                        <option value="{{ $i }}">{{ $i }} នាក់</option>
                                        @endfor
                                </select>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl transition-all shadow-lg shadow-blue-200 dark:shadow-none active:scale-95">
                                កក់បន្ទប់ឥឡូវនេះ
                            </button>
                        </form>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/10 p-6 rounded-3xl border border-blue-100 dark:border-blue-800">
                        <h4 class="font-bold text-blue-800 dark:text-blue-400 mb-2"><i class="fas fa-calendar-alt mr-2"></i> ស្ថានភាពទំនេរ</h4>
                        <p class="text-sm text-blue-600/80">ប្រភេទនេះនៅសល់ <strong>{{ $roomType->rooms->where('status','available')->count() }} បន្ទប់</strong> សម្រាប់ថ្ងៃនេះ។</p>
                    </div>
                </div>
            </div>
        </div>

        @if($similarRooms->count() > 0)
        <div class="mt-24">
            <h2 class="text-3xl font-bold mb-10 text-gray-900 dark:text-white">ប្រភេទបន្ទប់ប្រហាក់ប្រហែល</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($similarRooms as $room)
                <a href="{{ route('frontend.details', $room->id) }}" class="group">
                    <div class="relative h-64 overflow-hidden rounded-[2rem] mb-4">
                        <img src="{{ asset('storage/' . $room->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <div class="absolute bottom-4 left-4 bg-white/90 backdrop-blur px-4 py-1 rounded-full font-bold text-sm text-gray-900">
                            ${{ number_format($room->base_price, 0) }} / យប់
                        </div>
                    </div>
                    <h3 class="text-xl font-bold group-hover:text-blue-600 transition">{{ $room->name }}</h3>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    // Initialize Swiper
    const swiper = new Swiper('.roomGallerySwiper', {
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        autoplay: {
            delay: 5000
        },
    });

    // Star Rating System
    const stars = document.querySelectorAll('#starRating i');
    const ratingInput = document.getElementById('ratingInput');
    stars.forEach(star => {
        star.onclick = function() {
            let val = this.dataset.val;
            ratingInput.value = val;
            stars.forEach(s => {
                s.classList.replace(s.dataset.val <= val ? 'far' : 'fas', s.dataset.val <= val ? 'fas' : 'far');
                if (s.dataset.val <= val) s.classList.add('text-yellow-400');
                else s.classList.remove('text-yellow-400');
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('bookingForm').onsubmit = async function(e) {
        e.preventDefault();

        // ១. បង្ហាញ Loading លើប៊ូតុង
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> កំពុងដំណើរការ...';

        const formData = new FormData(this);

        try {
            const response = await fetch("{{ route('booking.storecart') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                // ២. បើជោគជ័យ បញ្ជូនទៅទំព័រ Checkout
                window.location.href = "/bookings/" + result.booking_id + "/checkout";
            } else {
                // ៣. បើមានបញ្ហា (ដូចជាបាត់ទិន្នន័យ ឬបន្ទប់ពេញ)
                Swal.fire({
                    icon: 'error',
                    title: 'បរាជ័យ',
                    text: result.message,
                    confirmButtonColor: '#2563eb'
                });
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (error) {
            console.error(error);
            alert("មានបញ្ហាបច្ចេកទេស! សូមព្យាយាមម្ដងទៀត។");
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    };
</script>
@endsection