@extends('layouts.app')
@section('title', $roomType->name . ' - ព័ត៌មានលម្អិត')
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
            មើលលម្អិត <span class="text-blue-600">{{ $roomType->name }} </span>
        </h1>

        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">

        </p>
        <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
    </div>

    <section class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <div class="relative overflow-hidden rounded-2xl md:rounded-2xl shadow-xl group border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
                            @if($roomType->images->count() > 0)
                            <img id="mainImage"
                                src="{{ asset('storage/' . $roomType->images->first()->image_path) }}"
                                class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover duration-700 group-hover:scale-105">
                            @else
                            <img id="mainImage" src="{{ asset('storage/' . $roomType->image_path) }}"
                                class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover">
                            @endif

                            <button type="button" onclick="openImageModal()"
                                class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-3 py-2 rounded-xl shadow hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white text-gray-700 dark:text-gray-300 duration-300 focus:outline-none">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>

                        @if($roomType->images->count() > 1)
                        <div class="relative">
                            <button onclick="scrollThumbs(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-800 dark:text-white shadow-lg w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white border border-gray-100 dark:border-gray-700 transition-colors">
                                <i class="fas fa-chevron-left text-sm"></i>
                            </button>

                            <div id="thumbContainer" class="flex gap-2 md:gap-3 overflow-x-auto scroll-smooth px-10 py-2 scrollbar-hide">
                                @foreach($roomType->images as $index => $img)
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    onclick="changeImage(this)"
                                    class="thumbItem flex-shrink-0 w-24 h-16 md:w-32 md:h-20 rounded-xl object-cover cursor-pointer border-2 {{ $index == 0 ? 'border-blue-600' : 'border-transparent' }} hover:border-blue-600 duration-300 shadow-sm">
                                @endforeach
                            </div>

                            <button onclick="scrollThumbs(1)" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-800 dark:text-white shadow-lg w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white border border-gray-100 dark:border-gray-700 transition-colors">
                                <i class="fas fa-chevron-right text-sm"></i>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="sticky top-24 bg-white dark:bg-gray-900 rounded-2xl md:rounded-2xl shadow-xl p-4 md:p-6 space-y-5 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                        <div class="flex items-baseline justify-between border-b border-gray-50 dark:border-gray-800/60 pb-4">
                            <div class="flex items-baseline gap-1">
                                <h2 class="text-2xl md:text-3xl font-black text-blue-600 dark:text-blue-400">
                                    ${{ number_format($roomType->base_price,0) }}
                                </h2>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">/ យប់ </p>
                            </div>
                        </div>

                        <div id="totalPriceWrapper" class="hidden">
                            <div id="totalPriceDisplay"
                                class="w-full bg-green-50 dark:bg-green-950/40 border border-green-100 dark:border-green-900/50 p-3.5 rounded-xl text-green-700 dark:text-green-400 font-bold text-sm flex items-center">
                            </div>
                        </div>

                        <form action="{{ route('cart.add.hotel') }}" method="POST" class="space-y-4">
                            @csrf

                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                            <input type="hidden" name="promo_price" value="{{ $roomType->discounted_price }}">

                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូលស្នាក់នៅ
                                    </label>
                                    <input type="date" name="check_in" id="check_in" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]" required>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                        <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-500 mr-1"></i> ថ្ងៃចាកចេញ
                                    </label>
                                    <input type="date" name="check_out" id="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]" required>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                        <i class="fas fa-comments text-blue-600 dark:text-blue-500 mr-1"></i>សំណូមពរ ឬមតិផ្សេងៗ
                                    </label>
                                    <textarea name="special_requests" rows="2"
                                        class="w-full p-3.5 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 ring-blue-500 outline-none text-sm placeholder-gray-400 dark:placeholder-gray-500"
                                        placeholder="បញ្ចូលចំនួនមនុស្សត្រូវស្នាក់នៅ ឬសំណូមពរពិសេស (បើមាន)..."></textarea>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full h-12 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95">
                                កក់ឥឡូវនេះ
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BOTTOM: DETAILS & FACILITIES --}}
            <div class="mt-10 md:mt-14 bg-white dark:bg-gray-900 rounded-2xl md:rounded-3xl shadow-lg border border-gray-100 dark:border-gray-800 p-5 md:p-8 transition-colors duration-300">
                <h1 class="text-2xl md:text-4xl font-black mb-4 text-gray-900 dark:text-white">
                    {{ $roomType->name }}
                </h1>

                <div class="flex flex-wrap gap-3 mb-8 text-gray-700 dark:text-gray-300">
                    <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                        <i class="fas fa-bed mr-2 text-blue-600 dark:text-blue-400"></i>គ្រែ៖ {{ $roomType->beds ?? 1 }} គ្រែ
                    </span>
                    <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                        <i class="fas fa-users mr-2 text-blue-600 dark:text-blue-400"></i>ស្នាក់នៅបាន៖ {{ $roomType->max_guests }} នាក់
                    </span>
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
                    <div class="bg-gray-50 dark:bg-gray-800/40 rounded-2xl p-4 flex items-center gap-3 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                        <i class="{{ $facility->icon ?? 'fas fa-check-circle' }} text-blue-600 dark:text-blue-400 text-lg flex-shrink-0"></i>
                        <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">
                            {{ $facility->name }}
                        </span>
                    </div>
                    @empty
                    <div class="text-sm text-gray-400 dark:text-gray-500 col-span-full py-6 text-center bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <i class="fas fa-info-circle mr-1"></i> មិនទាន់មានទិន្នន័យគ្រឿងបរិក្ខារនៅឡើយទេ។
                    </div>
                    @endforelse
                </div>
            </div>
    </section>

    <section class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-10">
        <div class="mt-10 bg-white dark:bg-gray-900 rounded-2xl md:rounded-3xl shadow-lg border border-gray-100 dark:border-gray-800 p-5 md:p-8 transition-colors duration-300">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-5 mb-6 gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold border-l-4 border-blue-600 dark:border-blue-500 pl-3 text-gray-900 dark:text-white">
                        ការវាយតម្លៃ និងមតិយោបល់
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 pl-4">
                        សរុបមានការវាយតម្លៃចំនួន {{ $roomType->reviews->count() }} ជើង
                    </p>
                </div>

                @if($roomType->reviews->count() > 0)
                @php $avgRating = round($roomType->reviews->avg('rating'), 1); @endphp
                <div class="flex items-center gap-3 bg-blue-50 dark:bg-blue-950/30 px-4 py-2 rounded-2xl border border-blue-100 dark:border-blue-900/40 w-fit">
                    <span class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $avgRating }}</span>
                    <div>
                        <div class="flex text-amber-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $avgRating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">ពិន្ទុពេញ ៥ ផ្កា</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4 max-h-[500px] overflow-y-auto pr-2 scrollbar-hide">
                    @forelse($roomType->reviews as $review)
                    <div class="bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-600 text-white font-bold rounded-xl flex items-center justify-center uppercase text-sm shadow-sm">
                                    {{ substr($review->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $review->name }}</h4>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 text-xs gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                            </div>
                        </div>
                        @if(!empty($review->comment))
                        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed pl-12">
                            {{ $review->comment }}
                        </p>
                        @else
                        <p class="text-gray-400 dark:text-gray-500 text-xs italic pl-12">
                            (មិនមានការសរសេរមតិយោបល់ទេ)
                        </p>
                        @endif
                    </div>
                    @empty
                    <div class="text-sm text-gray-400 dark:text-gray-500 py-12 text-center bg-gray-50 dark:bg-gray-800/20 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <i class="fas fa-comment-slash text-xl mb-2 block"></i> មិនទាន់មានការវាយតម្លៃសម្រាប់ប្រភេទបន្ទប់នេះនៅឡើយទេ។
                    </div>
                    @endforelse
                </div>

                <div class="bg-gray-50 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800/80 p-5 rounded-2xl h-fit">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-pen-square text-blue-600"></i> ចែករំលែកបទពិសោធន៍របស់អ្នក
                    </h3>

                    @if(session('success'))
                    <div class="mb-4 p-3 bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 text-xs font-semibold rounded-xl border border-green-100 dark:border-green-900/30">
                        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('frontend.meeting_details.store', $roomType->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 ml-1">ឈ្មោះរបស់អ្នក *</label>
                            <input type="text" name="name" required placeholder="ឧទាហរណ៍៖ កក្កដា"
                                class="w-full bg-white dark:bg-gray-800 border-none p-3 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm border border-gray-100 dark:border-gray-700 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 ml-1">ផ្តល់ពិន្ទុផ្កា *</label>
                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-2.5 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm">
                                <select name="rating" required class="bg-transparent text-sm font-bold text-amber-500 outline-none w-full cursor-pointer">
                                    <option value="5" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟🌟🌟🌟 (ល្អឥតខ្ចោះ)</option>
                                    <option value="4" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟🌟🌟 (ល្អណាស់)</option>
                                    <option value="3" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟🌟 (មធ្យម)</option>
                                    <option value="2" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟 (ខ្សោយ)</option>
                                    <option value="1" class="bg-white dark:bg-gray-800 text-amber-500">🌟 (ខ្សោយខ្លាំង)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 ml-1">មតិយោបល់បន្ថែម</label>
                            <textarea name="comment" rows="3" placeholder="សរសេរការចាប់អារម្មណ៍របស់អ្នកពីបន្ទប់នេះ..."
                                class="w-full bg-white dark:bg-gray-800 border-none p-3 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm border border-gray-100 dark:border-gray-700 shadow-sm resize-none"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full h-11 flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-all active:scale-95 shadow-md shadow-blue-500/10">
                            ផ្ញើការវាយតម្លៃ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- MODAL GALLERY --}}
<div id="imageModal" class="fixed inset-0 z-[9999] hidden bg-black/95 items-center justify-center p-4 backdrop-blur-sm">
    <button onclick="closeImageModal()" class="absolute top-5 right-6 text-white text-5xl hover:text-red-400 transition-colors focus:outline-none">&times;</button>
    <img id="modalImage" class="max-w-full max-h-[90vh] rounded-xl object-contain shadow-2xl">
</div>

<script>
    const categoryType = "{{ $roomType->category }}";
    const promoPrice = parseFloat("{{ $roomType->base_price }}") || 0;

    document.addEventListener('DOMContentLoaded', function() {
        if (categoryType === 'stay') {
            document.getElementById('check_in').addEventListener('change', calculateStayPrice);
            document.getElementById('check_out').addEventListener('change', calculateStayPrice);
            calculateStayPrice();
        } else {
            document.getElementById('start_time').addEventListener('change', calculateMeetingPrice);
            document.getElementById('end_time').addEventListener('change', calculateMeetingPrice);
            document.getElementById('start_date').addEventListener('change', function() {
                document.getElementById('end_date').value = this.value;
            });
            calculateMeetingPrice();
        }
    });

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
                display.innerHTML = `<i class="fas fa-moon mr-2 text-green-600 dark:text-green-400"></i> តម្លៃសរុប៖ $${total.toLocaleString()} (${diffDays} យប់)`;
            } else {
                wrapper.classList.add('hidden');
            }
        }
    }

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
                display.innerHTML = `<i class="fas fa-clock mr-2 text-green-600 dark:text-green-400"></i> តម្លៃសរុប៖ $${total.toLocaleString()} (${diffHrs.toFixed(1)} ម៉ោង)`;
            } else {
                wrapper.classList.add('hidden');
            }
        }
    }

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