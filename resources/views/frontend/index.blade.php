@extends('layouts.app')
@section('title', 'ទំព័រដើម')

@section('content')

<div class="container mx-auto">

    <header class="relative h-[95vh] w-full overflow-hidden flex items-center justify-center text-center text-white rounded-xl">
        <div class="swiper Slideshow absolute inset-0 z-0">

            <div class="swiper-wrapper">
                @forelse($slides as $slide)
                <div class="swiper-slide relative">
                    <div class="absolute inset-0 bg-black/40 z-10"></div>

                    <img src="{{ asset('storage/' . $slide->image_path) }}"
                        class="w-full h-full object-cover"
                        alt="{{ $slide->title }}">

                    <div class="absolute inset-0 z-20 flex flex-col items-center justify-center px-4">
                        <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight drop-shadow-lg slide-title">
                            {{ $slide->title }}
                        </h1>
                        <p class="text-lg md:text-xl mb-10 opacity-95 font-light drop-shadow-md slide-subtitle">
                            {{ $slide->subtitle }}
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ $slide->link ?? '#rooms' }}"
                                class="bg-yellow-600 text-white px-8 py-3 rounded-full font-bold hover:bg-yellow-700 transition shadow-xl">
                                មើលបន្ទប់ទាំងអស់
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="swiper-slide relative bg-gray-900 flex items-center justify-center">
                    <p>មិនទាន់មានរូបភាពស្លាយនៅឡើយទេ</p>
                </div>
                @endforelse
            </div>

            <div class="swiper-button-next !text-white opacity-50 hover:opacity-100 transition !hidden md:!flex"></div>
            <div class="swiper-button-prev !text-white opacity-50 hover:opacity-100 transition !hidden md:!flex"></div>
            <div class="swiper-pagination"></div>
        </div>
    </header>

    <!-- search box -->
    <div class="container mx-auto px-4 -mt-16 relative z-50">
        <form action="{{ route('home') }}" method="GET">
            <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 font-['Kantumruy_Pro']">

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase ml-2 mb-2">
                        <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>
                        <span data-key="label-checkin">ថ្ងៃចូលស្នាក់នៅ</span>
                    </label>
                    <input type="date" name="check_in" id="check_in" value="{{ request('check_in', date('Y-m-d')) }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase ml-2 mb-2">
                        <i class="fas fa-calendar-check mr-1 text-blue-500"></i>
                        <span data-key="label-checkout">ថ្ងៃចាកចេញ</span>
                    </label>
                    <input type="date" name="check_out" id="check_out"
                        value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase ml-2 mb-2">
                        <i class="fas fa-bed mr-1 text-blue-500"></i>
                        <span data-key="book-room-type">ប្រភេទបន្ទប់</span>
                    </label>
                    <div class="relative">
                        <select name="room_type" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                            <option value="">គ្រប់ប្រភេទទាំងអស់</option>
                            @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" {{ request('room_type')==$type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                            @endforeach
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                </div>

                <div class="flex flex-col">
                    <label class="hidden lg:block text-[11px] mb-2 opacity-0 select-none">Search</label>

                    <button type="submit"
                        class="w-full bg-blue-900 dark:bg-blue-800 text-white font-bold rounded-xl hover:brightness-110 shadow-lg transition-all active:scale-95 h-[52px] flex items-center justify-center gap-2">
                        <i class="fas fa-search text-xs mr-1"></i>
                        ស្វែងរក
                    </button>
                </div>

            </div>
        </form>
    </div>

    <!-- featured_rooms -->
    <section class="py-24 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-6 tracking-tight">
                    បន្ទប់
                </h2>
                <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full mb-6"></div>
                <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed">
                    បន្ទប់ដែលស័ក្តិសមបំផុតសម្រាប់ដំណើរកម្សាន្តរបស់អ្នក ជាមួយរចនាប័ទ្មបែបទំនើប និងផាសុកភាពខ្ពស់បំផុត។
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @if($roomTypes->count() > 0)
                @foreach($roomTypes as $type)
                <div class="group bg-white dark:bg-gray-800 rounded-[2rem] shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col">

                    <div class="relative aspect-[4/3] overflow-hidden">
                        @php
                        // ទាញយករូបភាព Primary របស់ប្រភេទបន្ទប់នោះ
                        $image = $type->images->where('is_primary', true)->first() ?? $type->images->first();
                        @endphp

                        @if($image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="{{ $type->name }}">
                        @else
                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-4xl"></i>
                        </div>
                        @endif

                        <div class="absolute bottom-5 left-5">
                            <div class="bg-blue-600 text-white px-4 py-2 rounded-2xl shadow-lg">
                                <span class="text-xs opacity-80">ចាប់ពី</span>
                                <span class="text-xl font-bold">${{ $type->base_price }}</span>
                                <span class="text-xs opacity-80">/យប់</span>
                            </div>
                        </div>
                        <div class="relative h-72 overflow-hidden">
                            @if($type->reviews_avg_rating >= 4.5)
                            <div class="absolute top-5 left-5 bg-orange-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-lg">
                                <i class="fas fa-fire mr-1"></i> Popular
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="p-8 flex flex-col flex-grow">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $type->name }}</h3>

                        <p class="text-sm text-green-600 dark:text-green-400 mb-4 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> នៅសល់ {{ $type->rooms->where('status', 'available')->count() }} បន្ទប់ទំនេរ
                        </p>

                        <div class="grid grid-cols-3 gap-2 mb-8 text-center text-gray-500">
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-100 dark:border-gray-600">
                                <i class="fas fa-bed text-blue-500 block mb-1"></i>
                                <span class="text-[10px] font-medium">{{ $type->beds }} គ្រែ</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-100 dark:border-gray-600">
                                <i class="fas fa-users text-blue-500 block mb-1"></i>
                                <span class="text-[10px] font-medium">{{ $type->max_guests }} នាក់</span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-xl border border-gray-100 dark:border-gray-600">
                                <i class="fas fa-expand text-blue-500 block mb-1"></i>
                                <span class="text-[10px] font-medium">{{ $type->size }}m²</span>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <a href="{{ route('frontend.details', $type->id) }}"
                                class="group/btn flex items-center justify-center w-full bg-blue-900 dark:bg-blue-700 text-white font-bold py-4 rounded-2xl hover:bg-blue-600 transition-all shadow-lg hover:shadow-blue-500/25">
                                <span>មើលលម្អិត</span>
                                <i class="fas fa-arrow-right ml-2 group-hover/btn:translate-x-2 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                    <div class="bg-gray-100 dark:bg-gray-800/50 p-8 rounded-full mb-6">
                        <i class="fas fa-bed-pulse text-6xl text-gray-400 dark:text-gray-600"></i>
                    </div>
                   
                    <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        បច្ចុប្បន្នមិនទាន់មានបន្ទប់ដែលត្រូវនឹងការស្វែងរករបស់អ្នកឡើយ។
                    </p>
                    <a href="{{ route('home') }}" class="mt-8 text-blue-600 font-bold hover:underline">
                        <i class="fas fa-sync-alt mr-2"></i> បង្ហាញបន្ទប់ទាំងអស់ឡើងវិញ
                    </a>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Special Offers -->
    <section class="py-20 container mx-auto px-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-2">
                        {{ __('Special Offers') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ __('Grab these exclusive deals before they expire!') }}
                    </p>
                </div>
                <a href="#" class="mt-4 md:mt-0 text-blue-600 font-semibold flex items-center hover:underline">
                    View All Deals <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Offers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- @foreach($promotions as $promo) --}}
                <div class="relative group flex flex-col sm:flex-row bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
                    <!-- Promo Image -->
                    <div class="sm:w-1/3 h-48 sm:h-auto overflow-hidden">
                        <img src="" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 opacity-90">
                    </div>

                    <!-- Promo Content -->
                    <div class="p-8 sm:w-2/3 flex flex-col justify-center text-white">
                        <div class="inline-block bg-white/20 backdrop-blur-md text-xs font-bold px-3 py-1 rounded-full w-fit mb-4 uppercase">
                            {{-- $promo->tag --}} <!-- e.g., Summer Sale -->
                        </div>
                        <h3 class="text-2xl font-bold mb-2">{{-- $promo->title --}}</h3>
                        <p class="text-blue-100 text-sm mb-6 line-clamp-2">
                            {{-- $promo->description --}}
                        </p>

                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-sm opacity-80 line-through">${{-- $promo->original_price --}}</span>
                                <span class="text-2xl font-bold ml-2">${{-- $promo->discounted_price --}}</span>
                            </div>
                            <button class="bg-white text-blue-600 font-bold px-6 py-2 rounded-xl hover:bg-blue-50 transition-colors">
                                Claim Deal
                            </button>
                        </div>
                    </div>

                    <!-- Decorative Circle -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                </div>
                {{-- @endforeach --}}
            </div>
        </div>
    </section>

    <!-- Our Facilities -->
    <section class="py-20 container mx-auto px-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ __('Our Facilities') }}</h2>
                <p class="mt-4 text-gray-600 dark:text-gray-400">យើងផ្តល់ជូននូវសេវាកម្មដ៏ល្អបំផុត ដើម្បីផាសុកភាពរបស់អ្នក។</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- WiFi -->
                <div class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-full text-blue-600 mb-4 text-2xl">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white">Free WiFi</span>
                </div>

                <!-- Parking -->
                <div class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 flex items-center justify-center bg-green-100 dark:bg-green-900/30 rounded-full text-green-600 mb-4 text-2xl">
                        <i class="fas fa-parking"></i>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white">Free Parking</span>
                </div>

                <!-- Restaurant -->
                <div class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 flex items-center justify-center bg-orange-100 dark:bg-orange-900/30 rounded-full text-orange-600 mb-4 text-2xl">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <span class="font-semibold text-gray-900 dark:text-white">Restaurant</span>
                </div>
            </div>
        </div>
    </section>

    <!-- tours -->
    <section class="py-20 container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white" data-key="tour-title">Nearby Attractions</h2>
            <a href="#" class="text-blue-600 font-bold hover:underline" data-key="view-all-tours">
                View All <i class="fas fa-chevron-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="relative rounded-3xl overflow-hidden h-96 group shadow-lg">
                <img src="../assets/images/tourishim/រមណីយដ្ឋានទឹកធ្លាក់ហោង.jpg"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                    alt="Haong Waterfall">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
                    <h4 class="text-xl font-bold text-white" data-key="tour-1-name">Haong Waterfall</h4>
                    <p class="text-sm text-gray-300" data-key="tour-dist">15 mins from hotel</p>
                </div>
            </div>

            <div class="relative rounded-3xl overflow-hidden h-96 group shadow-lg">
                <img src="../assets/images/tourishim/រមណីយដ្ឋានហ្លួងស្តេចកន.jpg"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                    alt="Luong Sdech Kon">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
                    <h4 class="text-xl font-bold text-white" data-key="tour-2-name">Luong Sdech Kon Site</h4>
                    <p class="text-sm text-gray-300" data-key="tour-dist">15 mins from hotel</p>
                </div>
            </div>

            <div class="relative rounded-3xl overflow-hidden h-96 group shadow-lg">
                <img src="https://images.unsplash.com/photo-1529921879218-f99546d03a9d?auto=format&fit=crop&w=500&q=80"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Angkor Wat">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
                    <h4 class="text-xl font-bold text-white" data-key="tour-3-name">Angkor Wat Temple</h4>
                    <p class="text-sm text-gray-300" data-key="tour-dist">15 mins from hotel</p>
                </div>
            </div>

            <div class="relative rounded-3xl overflow-hidden h-96 group shadow-lg">
                <img src="https://images.unsplash.com/photo-1529921879218-f99546d03a9d?auto=format&fit=crop&w=500&q=80"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-500" alt="Bayon Temple">
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
                    <h4 class="text-xl font-bold text-white" data-key="tour-4-name">Bayon Temple</h4>
                    <p class="text-sm text-gray-300" data-key="tour-dist">15 mins from hotel</p>
                </div>
            </div>

        </div>
    </section>

    <!-- Gallery Preview -->
    <section class="py-20 container mx-auto px-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">
                        {{ __('Gallery Preview') }}
                    </h2>
                    <div class="h-1 w-20 bg-blue-600 mt-2"></div>
                </div>
                <a href="/gallery" class="text-blue-600 font-bold hover:text-blue-700 transition flex items-center">
                    {{ __('View All') }} <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                </a>
            </div>

            <!-- Photo Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-[600px]">
                <!-- Main Large Image -->
                <div class="md:col-span-2 md:row-span-2 relative group overflow-hidden rounded-3xl">
                    <img src="{{ asset('storage/rooms/0e0a9f51-ad78-40f4-a1b7-f886fddf87ff.jpg') }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                </div>

                <!-- Top Right -->
                <div class="relative group overflow-hidden rounded-3xl">
                    <img src="{{ asset('storage/rooms/2be072e3-3dbb-4d69-9843-b4a3d064aa58.JPG') }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent"></div>
                </div>

                <!-- Bottom Right -->
                <div class="relative group overflow-hidden rounded-3xl">
                    <img src="{{ asset('storage/rooms/19030f52-83cf-4586-89ad-5446cf543f30.JPG') }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent"></div>
                </div>

                <!-- Tall Vertical Image -->
                <div class="md:col-span-2 h-64 relative group overflow-hidden rounded-3xl">
                    <img src="{{ asset('storage/rooms/90df70cd-13cf-43c8-8d70-ed6ca5a31db0.jpg') }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-bold border-2 border-white px-6 py-2 rounded-full">Explore Resort</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- customer review -->
    <section class="py-20 container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 dark:text-white" data-key="testi-title">
                Guest Experiences
            </h2>
            <p class="text-gray-500 mt-4" data-key="testi-subtitle">Hear what our lovely guests have to say about their
                stay.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div
                class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] relative shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <i
                    class="fas fa-quote-left text-blue-500/10 text-7xl absolute top-6 left-6 group-hover:text-blue-500/20 transition-colors"></i>

                <div class="relative z-10 flex gap-1 mb-4 text-yellow-400 text-sm">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                        class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>

                <p class="relative z-10 italic text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                    "The service was exceptional, and the staff were incredibly welcoming. The room comfort exceeded my
                    expectations. I will definitely return!"
                </p>

                <div class="flex items-center gap-4 mt-8 relative z-10">
                    <div
                        class="w-14 h-14 bg-gradient-to-tr from-blue-500 to-blue-300 text-white rounded-full flex items-center justify-center font-bold shadow-lg shadow-blue-500/20">
                        S.A
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-800 dark:text-white">Sok An</h5>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-500" data-key="guest-local">
                            Local
                            Guest</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-900 p-8 rounded-[2rem] relative shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-xl hover:-translate-y-2 transition-all duration-300 group">
                <i
                    class="fas fa-quote-left text-blue-500/10 text-7xl absolute top-6 left-6 group-hover:text-blue-500/20 transition-colors"></i>

                <div class="relative z-10 flex gap-1 mb-4 text-yellow-400 text-sm">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                        class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>

                <p class="relative z-10 italic text-gray-600 dark:text-gray-300 leading-relaxed text-lg">
                    "A truly peaceful getaway. The attention to detail and hospitality made our trip unforgettable.
                    Highly
                    recommended for everyone!"
                </p>

                <div class="flex items-center gap-4 mt-8 relative z-10">
                    <div
                        class="w-14 h-14 bg-gradient-to-tr from-sky-500 to-sky-300 text-white rounded-full flex items-center justify-center font-bold shadow-lg shadow-sky-500/20">
                        M.J
                    </div>
                    <div>
                        <h5 class="font-bold text-gray-800 dark:text-white">Michael Jordan</h5>
                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-500" data-key="guest-inter">
                            International Guest</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- បន្ទប់ដែលមានទំនេរ -->
    <section class="py-20 container mx-auto px-4">
        <h2 class="text-3xl font-black mb-10 dark:text-white">បន្ទប់ដែលមានទំនេរ</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($availableRooms as $room)
            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-sm border dark:border-gray-800 overflow-hidden group">
                <div class="relative h-64 overflow-hidden">
                    @php
                    // ទាញយករូប Primary បើអត់មាន យករូបទី១ បើអត់ទៀតប្រើ Placeholder
                    $image = $room->roomType->images->where('is_primary', true)->first()
                    ?? $room->roomType->images->first();
                    @endphp

                    @if($image)
                    <img src="{{ asset('storage/' . $image->image_path) }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        alt="{{ $room->roomType->name }}">
                    @else
                    <div class="w-full h-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                    </div>
                    @endif

                    <div class="absolute top-4 right-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-3 py-1 rounded-full shadow-sm">
                        <span class="text-emerald-600 font-bold">${{ number_format($room->roomType->base_price, 2) }}</span>
                        <span class="text-[10px] text-gray-500 uppercase">/យប់</span>
                    </div>
                </div>

                <div class="p-5">
                    <h3 class="text-lg font-bold dark:text-white mb-1">{{ $room->roomType->name }}</h3>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $room->roomType->description }}</p>

                    <div class="flex items-center justify-between border-t dark:border-gray-800 pt-4">
                        <span class="text-xs text-gray-400">
                            <i class="fas fa-users mr-1"></i> {{ $room->roomType->max_guests }} នាក់
                        </span>
                        {{-- route('frontend.show', $room->id) --}}
                        <a href=""
                            class="inline-flex items-center px-4 py-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-bold rounded-xl hover:bg-blue-600 hover:text-white transition-all duration-300">
                            មើលលម្អិត <i class="fas fa-arrow-right ml-2 text-xs"></i>
                        </a>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <!-- about us -->
    <section class="py-24 container mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="flex-1 space-y-6">
                <span class="text-blue-600 font-bold uppercase tracking-widest text-sm" data-key="about-tag">About PNT
                    Hotel</span>
                <h2 class="text-3xl md:text-4xl font-bold" data-key="about-title">បទពិសោធន៍ស្នាក់នៅ <br>លើសពីការរំពឹងទុក
                </h2>
                <p class="text-gray-500 dark:text-gray-400 leading-loose" data-key="about-desc">
                    សណ្ឋាគារយើងខ្ញុំត្រូវបានរចនាឡើងយ៉ាងពិសេស ដើម្បីផ្តល់នូវផាសុកភាពខ្ពស់បំផុត។
                    រាល់បន្ទប់ទាំងអស់ត្រូវបានបំពាក់ដោយបរិក្ខារទំនើបៗ និងរចនាប័ទ្មខ្មែរពិតៗ។</p>
                <div class="grid grid-cols-2 gap-6 pt-4">
                    <div class="flex items-center gap-3"><i class="fas fa-award text-gold-500 text-2xl"></i> <span
                            class="font-bold" data-key="feat-award">ពានរង្វាន់លំដាប់ពិភពលោក</span></div>
                    <div class="flex items-center gap-3"><i class="fas fa-user-shield text-gold-500 text-2xl"></i> <span
                            class="font-bold" data-key="feat-security">សុវត្ថិភាពខ្ពស់ 24/7</span></div>
                </div>
            </div>
            <div class="flex-1 relative">
                <img src="../assets/images/rooms/100_Meeting_Rooms.JPG" class="rounded-3xl shadow-2xl relative z-10"
                    alt="Hotel Detail">
                <div class="absolute -bottom-6 -right-6 w-64 h-64 bg-blue-600 rounded-3xl -z-0 opacity-20"></div>
            </div>
        </div>
    </section>

    <!-- rooms type -->
    <section id="rooms" class="py-24 bg-gray-50 dark:bg-gray-900/50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-center mb-10 underline decoration-blue-500 decoration-4 underline-offset-8"
                    data-key="room-section-title">បន្ទប់ដែលពេញនិយមបំផុត
                </h2>
                <p class="text-gray-500 dark:text-gray-400" data-key="room-section-subtitle">
                    ជ្រើសរើសប្រភេទបន្ទប់ដែលស័ក្តិសមបំផុតសម្រាប់លោកអ្នក</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                <div
                    class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 border dark:border-gray-800">
                    <div
                        class="relative h-56 overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                        <img src="../assets/images/rooms/Standard Single.jpg" alt="Standard Single"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <span class="absolute top-4 left-4 bg-blue-600 text-white px-4 py-1 rounded-full text-sm">13.5៛
                            /យប់</span>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold mb-2 dark:text-white">គ្រែ ១ (Standard Single)</h3>
                        <p class="text-gray-500 text-sm mb-6">បន្ទប់សន្សំសំចៃ ផាសុកភាពសម្រាប់ម្នាក់</p>
                        <button
                            class="w-full border-2 border-blue-900 dark:border-blue-400 text-blue-900 dark:text-blue-400 py-2 rounded-xl font-bold hover:bg-blue-900 hover:text-white transition">មើលលម្អិត</button>
                    </div>
                </div>

                <div
                    class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 border dark:border-gray-800">
                    <div
                        class="relative h-56 overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                        <i class="fas fa-bed text-5xl text-gray-400 group-hover:scale-110 transition duration-500"></i>
                        <img src="../assets/images/rooms/Standard Double.jpg" alt="Standard Double"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <span class="absolute top-4 left-4 bg-blue-600 text-white px-4 py-1 rounded-full text-sm">15៛
                            /យប់</span>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold mb-2 dark:text-white">គ្រែ ២ (Standard Double)</h3>
                        <p class="text-gray-500 text-sm mb-6">បន្ទប់ធំទូលាយសម្រាប់គ្នា ២នាក់</p>
                        <button
                            class="w-full border-2 border-blue-900 dark:border-blue-400 text-blue-900 dark:text-blue-400 py-2 rounded-xl font-bold hover:bg-blue-900 hover:text-white transition">មើលលម្អិត</button>
                    </div>
                </div>

                <div
                    class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 border dark:border-gray-800">
                    <div
                        class="relative h-56 overflow-hidden bg-gray-200 dark:bg-gray-800 flex items-center justify-center">
                        <img src="../assets/images/rooms/VIPPremiumSuite.jpg" alt="VIP Premium Suite"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <span class="absolute top-4 left-4 bg-yellow-500 text-white px-4 py-1 rounded-full text-sm">$15
                            /យប់</span>
                        <span
                            class="absolute top-4 right-4 bg-black/50 text-white px-2 py-1 rounded text-[10px] uppercase tracking-widest">VIP</span>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold mb-2 dark:text-white">VIP Premium Suite</h3>
                        <p class="text-gray-500 text-sm mb-6">គ្រឿងសង្ហារឹមទំនើប និងម៉ាស៊ីនត្រជាក់</p>
                        <button
                            class="w-full border-2 border-yellow-500 text-yellow-600 py-2 rounded-xl font-bold hover:bg-yellow-500 hover:text-white transition">មើលលម្អិត</button>
                    </div>
                </div>

                <div
                    class="group bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-500 border-2 border-yellow-400 relative">
                    <div class="relative h-56 overflow-hidden">
                        <img src="../assets/images/rooms/VVIP.JPG" alt="VVIP"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        <span
                            class="absolute top-4 left-4 bg-red-600 text-white px-4 py-1 rounded-full text-sm font-bold">$40
                            /យប់</span>
                        <div class="absolute top-4 right-4 bg-yellow-400 text-blue-900 p-2 rounded-full shadow-lg">
                            <i class="fas fa-crown"></i>
                        </div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold mb-2 dark:text-white">VVIP ផ្ការំដួល</h3>
                        <p class="text-gray-500 text-sm mb-6 italic text-blue-600 dark:text-blue-400">
                            បទពិសោធន៍ដ៏ប្រណីតបំផុត
                        </p>
                        <button
                            class="w-full bg-[#002B5B] text-white py-2 rounded-xl font-bold hover:bg-blue-800 shadow-lg shadow-blue-500/20 transition">កក់ឥឡូវនេះ</button>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

@endsection