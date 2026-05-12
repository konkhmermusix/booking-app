@extends('layouts.app')
@section('title', 'ទំព័រដើម')

@section('content')

<div class="container mx-auto">
    <header class="relative h-[75vh] sm:h-[35vh] md:h-[95vh] w-full overflow-hidden flex items-center justify-center text-center text-white rounded-2xl shadow-2xl">
        <div class="swiper Slideshow absolute inset-0 z-0">
            <div class="swiper-wrapper">
                @forelse($slides as $slide)
                <div class="swiper-slide relative">
                    <div class="absolute inset-0 bg-black/50 z-10"></div>

                    <img src="{{ asset('storage/' . $slide->image_path) }}"
                        class=" shadow-inner"
                        alt="{{ $slide->title }}">

                    <div class="absolute inset-0 z-20 flex flex-col items-center justify-center px-6">
                        <div class="max-w-md w-full">
                            <h1 class="text-2xl sm:text-4xl md:text-6xl font-bold mb-4 leading-[1.6] drop-shadow-lg slide-title">
                                {{ $slide->title }}
                            </h1>

                            <p class="text-sm sm:text-lg mb-8 opacity-90 font-light leading-relaxed drop-shadow-md">
                                {{ $slide->subtitle }}
                            </p>

                            <div class="flex flex-col gap-3 w-full px-4 sm:px-0">
                                <a href="{{ $slide->link ?? '/rooms' }}"
                                    class="inline-block bg-yellow-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-yellow-700 active:scale-95 transition-all shadow-lg text-sm sm:text-base">
                                    មើលបន្ទប់ទាំងអស់
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="swiper-slide relative bg-gray-900 flex items-center justify-center">
                    <p class="text-gray-400">មិនមានស្លាយនៅឡើយទេ</p>
                </div>
                @endforelse
            </div>

            <div class="swiper-button-next !text-white opacity-50 hover:opacity-100 transition !hidden md:!flex"></div>

            <div class="swiper-button-prev !text-white opacity-50 hover:opacity-100 transition !hidden md:!flex"></div>

            <div class="swiper-pagination !bottom-190"></div>
        </div>
    </header>

    <!-- search box -->
    <div class="container mx-auto px-4 -mt-10 relative z-50">
        <form action="{{ route('frontend.rooms') }}" method="GET">
            <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 font-['Kantumruy_Pro']">

                {{-- CHECK IN --}}
                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូល
                    </label>
                    <input type="date" name="check_in" id="check_in" value="{{ request('check_in', date('Y-m-d')) }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                </div>

                {{-- CHECK OUT --}}
                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-calendar-check text-blue-500 mr-1"></i> ថ្ងៃចេញ
                    </label>
                    <input type="date" name="check_out" id="check_out" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                </div>

                {{-- ROOM TYPE --}}
                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-bed text-blue-500 mr-1"></i> ប្រភេទបន្ទប់
                    </label>
                    <select name="type" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                        <option value="">គ្រប់ប្រភេទទាំងអស់</option>
                        @foreach($roomTypes as $category)
                        <option value="{{ $category->name }}" {{ request('type') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- DURATION --}}
                <div class="flex flex-col relative">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-clock text-blue-500 mr-1"></i> រយៈពេល
                    </label>

                    <select id="duration"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                        <option value="">ជ្រើសរយៈពេល</option>
                        <option value="1">1 ថ្ងៃ</option>
                        <option value="2">2 ថ្ងៃ</option>
                        <option value="3">3 ថ្ងៃ</option>
                        <option value="5">5 ថ្ងៃ</option>
                        <option value="7">1 សប្ដាហ៍</option>
                        <option value="14">2 សប្ដាហ៍</option>
                        <option value="21">3 សប្ដាហ៍</option>
                        <option value="30">1 ខែ</option>
                    </select>
                </div>

                {{-- BUTTON --}}
                <div class="flex items-end">
                    <button type="submit"
                        class="w-full bg-blue-900 dark:bg-blue-800 text-white font-bold rounded-xl hover:brightness-110 shadow-lg transition-all active:scale-95 h-[52px] flex items-center justify-center gap-2">
                        ស្វែងរក
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- rooms card -->

    <section class="py-24 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-16">
                <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                    ប្រភេទបន្ទប់
                </h4>
                <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full mb-4"></div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($roomTypes as $type)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-gray-100 dark:border-gray-700">

                    <div class="relative aspect-[4/3] overflow-hidden">
                        @php
                        $image = $type->images->where('is_primary', true)->first() ?? $type->images->first();
                        @endphp

                        @if($image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="{{ $type->name }}">
                        @else
                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                        @endif

                        <div class="absolute top-4 right-4 z-20 flex items-center gap-2">
                            <div class="text-right leading-none">
                                <p class="text-[10px] font-bold text-white drop-shadow-md">
                                    {{ $type->reviews_avg_rating >= 9 ? 'ល្អឥតខ្ចោះ' : 'ល្អខ្លាំង' }}
                                </p>
                                <p class="text-[8px] text-white/90 drop-shadow-md">{{ $type->reviews_count ?? 0 }} reviews</p>
                            </div>
                            <div class="bg-blue-700 text-white font-bold w-9 h-9 flex items-center justify-center rounded-lg shadow-lg">
                                {{ number_format($type->reviews_avg_rating ?? 0, 1) }}
                            </div>
                        </div>

                        <div class="absolute bottom-4 left-4 z-20">
                            <div class="bg-blue-600/95 backdrop-blur-md text-white px-3 py-1 rounded-xl shadow-lg border border-white/20">
                                <span class="text-[10px] opacity-80 block leading-none">ចាប់ពី</span>
                                <span class="text-lg font-bold">${{ number_format($type->base_price, 2) }}</span>
                                <span class="text-[10px] opacity-80">/យប់</span>
                            </div>
                        </div>

                        @if($type->reviews_avg_rating >= 4.5)
                        <div class="absolute top-4 left-4 z-20 bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg uppercase shadow-lg">
                            <i class="fas fa-fire mr-1 text-[8px]"></i> ពេញនិយម
                        </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 transition-colors line-clamp-1">
                            {{ $type->name }}
                        </h3>

                        <div class="flex flex-col gap-1 mb-4">
                            <p class="text-[11px] text-green-600 dark:text-green-400 font-medium flex items-center">
                                <i class="fas fa-check-circle mr-1 text-[10px]"></i> អាចកក់បានភ្លាមៗ
                            </p>
                            @if($type->available_rooms_count <= 5)
                                <p class="text-red-600 text-[10px] font-bold animate-pulse flex items-center">
                                <i class="fas fa-home mr-1"></i> នៅសល់តែ {{ $type->available_rooms_count }} បន្ទប់
                                </p>
                                @endif
                        </div>

                        <div class="flex flex-wrap gap-2 mb-5">
                            @foreach($type->facilities->take(3) as $facility)
                            <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-md border border-gray-100 dark:border-gray-600">
                                <i class="{{ $facility->icon }} text-blue-500 text-[10px]"></i>
                                <span class="text-[10px] text-gray-600 dark:text-gray-300 font-medium">{{ $facility->name }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 gap-3 py-3 border-t border-gray-100 dark:border-gray-700 mt-auto">
                            <div class="text-center border-r border-gray-100 dark:border-gray-700">
                                <i class="fas fa-users text-blue-500 text-[10px] block mb-0.5"></i>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ $type->max_guests }} នាក់</span>
                            </div>

                            <div class="text-center">
                                <i class="fas fa-bed text-blue-500 text-[10px] block mb-0.5"></i>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ $type->name }}</span>
                            </div>
                        </div>

                        <!-- ផ្នែកប៊ូតុងសកម្មភាព -->
                        <div class="mt-4 flex flex-col gap-2">
                            <button @click="addToCart({{ $type->id }})"
                                class="mt-4 flex items-center justify-center w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all text-sm active:scale-95">
                                <i class="fas fa-cart-plus mr-2"></i>
                                <span>កក់បន្ទប់នេះ</span>
                            </button>

                            <a href="{{ route('frontend.room_details', $type->id) }}"
                                class="flex items-center justify-center w-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-2 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-[12px]">
                                <span>មើលលម្អិត</span>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20 bg-white rounded-3xl shadow-inner">
                    <i class="fas fa-bed text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">មិនទាន់មានប្រភេទបន្ទប់ដែលអាចរកបាននៅឡើយទេ។</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Our Facilities -->
    <section class="py-24 bg-gray-100 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">សម្ភារៈរបស់យើង</h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($facilities as $facility)
                    <div class="flex flex-col items-center p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow group">
                        <div class="w-12 h-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900/30 rounded-full text-blue-600 mb-4 text-2xl group-hover:scale-110 transition-transform">
                            <i class="{{ $facility->icon }}"></i>
                        </div>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $facility->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- tours -->
    <section class="py-24 container dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white" data-key="tour-title">គោលដៅពេញនិយម</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($tours as $tour)
                <div class="relative rounded-2xl overflow-hidden h-96 group shadow-lg cursor-pointer">
                    <a href="{{ $tour->google_map_link }}" target="_blank">
                        <img src="{{ asset('storage/' . ($tour->image[0] ?? 'default.jpg')) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                            alt="{{ $tour->name }}">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
                            <h4 class="text-xl font-bold text-white">{{ $tour->name }}</h4>
                            <p class="text-sm text-gray-300">{{ $tour->distance }} គីឡូម៉ែត្រពីសណ្ឋាគារ</p>
                        </div>
                    </a>
                </div>
                @empty
                <div class="relative rounded-2xl overflow-hidden h-96 group shadow-lg cursor-pointer">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-6">
                        <h4 class="text-xl font-bold text-white">មិនគោលដៅទាំងនេះទេ</h4>
                    </div>
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- gallery preview -->
    <section class="py-24 container dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <!-- Header -->
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">
                        រូបភាពបន្ទប់សណ្ឋាគារ
                    </h2>
                    <div class="h-1 w-20 bg-blue-600 mt-2"></div>
                </div>
                <a href="/gallery" class="text-blue-600 font-bold hover:text-blue-700 transition flex items-center">
                    ច្រើនទៀត <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                </a>
            </div>

            <!-- Photo Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-auto md:h-[600px]">
                @forelse($displayImages as $index => $item)
                @php
                $gridClass = match($index) {
                0 => "md:col-span-2 md:row-span-2 h-[450px] md:h-full",
                3 => "md:col-span-2 h-[200px] md:h-full",
                4 => "md:col-span-2 md:row-span-2 h-[250px] md:h-full",
                8 => "md:col-span-2 h-[300px] md:h-full",
                default => "h-[200px] md:h-full",
                };
                @endphp

                <div class="relative group overflow-hidden rounded-2xl {{ $gridClass }} shadow-md">
                    <img src="{{ asset('storage/' . $item->image_path) }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        alt="{{ $item->roomType->name }}">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-6">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">

                            <a href="{{ route('frontend.room_details', $item->room_type_id) }}"
                                class="inline-flex items-center mt-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-full transition shadow-lg">
                                មើលបន្ថែម
                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 text-[11px] font-bold px-3 py-1 rounded-full uppercase shadow-sm">
                            {{ $item->roomType->name }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="col-span-4 py-20 text-center bg-gray-100 dark:bg-gray-800 rounded-2xl">
                    <p class="text-gray-500">មិនទាន់មានរូបភាពក្នុង Gallery ឡើយ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallary -->
    <section class="py-24 container dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white uppercase tracking-wider">
                        រូបភាពសណ្ឋាគារ
                    </h2>
                    <div class="h-1 w-20 bg-blue-600 mt-2"></div>
                </div>
                <a href="/gallery" class="text-blue-600 font-bold hover:text-blue-700 transition flex items-center">
                    ច្រើនទៀត <i class="fas fa-external-link-alt ml-2 text-sm"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-auto md:h-[600px]">
                @forelse($galleries as $index => $item)
                @php
                // កំណត់ Style Grid ឱ្យមានទំហំធំតូចខុសគ្នាដើម្បីភាពស្រស់ស្អាត
                $gridClass = match($index) {
                0 => "md:col-span-2 md:row-span-2 h-[450px] md:h-full",
                3 => "md:col-span-2 h-[200px] md:h-full",
                4 => "md:col-span-2 md:row-span-2 h-[250px] md:h-full",
                default => "h-[200px] md:h-full",
                };
                @endphp

                <div class="relative group overflow-hidden rounded-3xl {{ $gridClass }} shadow-md">
                    <img src="{{ asset('storage/' . $item->image) }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        alt="Hotel Gallery">

                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-6">
                        <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                            <h4 class="text-white font-bold text-lg">{{ $item->hotel->name ?? 'សណ្ឋាគារ' }}</h4>

                            <a href="{{ route('frontend.gallery') }}"
                                class="inline-flex items-center mt-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-full transition shadow-lg">
                                មើលរូបភាពទាំងអស់
                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 text-[11px] font-bold px-3 py-1 rounded-full uppercase shadow-sm">
                            {{ $item->hotel->name ?? 'Gallery' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="col-span-4 py-20 text-center bg-gray-100 dark:bg-gray-800 rounded-3xl">
                    <p class="text-gray-500">មិនទាន់មានរូបភាពក្នុង Gallery ឡើយ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Special Offers -->
    <section class="py-24 container dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
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

            </div>

            <!-- Offers Grid -->
            <div class="grid grid-cols-1 gap-10">
                @forelse($promotions as $promo)
                <div class="group relative flex flex-col lg:flex-row bg-white dark:bg-gray-900 rounded-[2.5rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_60px_rgba(37,99,235,0.15)] transition-all duration-500 border border-gray-100 dark:border-gray-800">

                    <div class="lg:w-2/5 h-72 lg:h-auto overflow-hidden relative">
                        <img src="{{ asset('storage/' . $promo->image_path) }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">

                        <div class="absolute top-6 left-6 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm">
                            <p class="text-[10px] font-black uppercase text-gray-400 leading-none">Expires In</p>
                            <p class="text-sm font-bold text-red-500">{{ \Carbon\Carbon::parse($promo->expiry_date)->diffForHumans() }}</p>
                        </div>

                        <div class="absolute bottom-6 left-6 right-6">
                            <span class="bg-blue-600 text-white px-4 py-1.5 rounded-xl text-xs font-bold uppercase tracking-widest shadow-lg">
                                {{ $promo->roomType->name }}
                            </span>
                        </div>
                    </div>

                    <div class="lg:w-3/5 p-8 lg:p-12 flex flex-col justify-between relative bg-gradient-to-br from-white to-gray-50 dark:from-gray-900 dark:to-gray-800">

                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <h3 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $promo->title }}</h3>
                                <div class="bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-[10px] font-black px-3 py-1 rounded-full uppercase">
                                    {{ $promo->tag ?? 'Limited Time' }}
                                </div>
                            </div>

                            <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-8">
                                {{ $promo->description }}
                            </p>

                            <div class="space-y-4 mb-8 text-white">
                                <h4 class="text-[11px] font-black uppercase text-gray-400 tracking-[0.2em] flex items-center gap-2">
                                    <i class="fa-solid fa-hotel text-blue-500"></i> Available Rooms
                                </h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($promo->roomType->rooms->take(5) as $room)
                                    <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-2 group/room hover:border-blue-500 transition-colors">
                                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                                        <span class="text-sm font-bold text-gray-700 dark:text-gray-300">#{{ $room->room_number }}</span>
                                    </div>
                                    @empty
                                    <span class="text-xs text-orange-400 italic font-medium">Please contact us for room availability</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="pt-8 border-t border-gray-100 dark:border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-baseline gap-3">
                                <span class="text-4xl font-black text-blue-600 dark:text-blue-400">${{ number_format($promo->discounted_price, 0) }}</span>
                                <span class="text-lg text-gray-400 line-through font-medium">${{ number_format($promo->original_price, 0) }}</span>
                                <span class="text-xs font-bold text-green-500 bg-green-50 dark:bg-green-500/10 px-2 py-1 rounded-lg">
                                    Save {{ number_format((($promo->original_price - $promo->discounted_price) / $promo->original_price) * 100, 0) }}%
                                </span>
                            </div>

                            <a href="#" class="w-full sm:w-auto px-10 py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white rounded-2xl font-black uppercase text-xs tracking-[0.2em] shadow-xl hover:bg-blue-600 dark:hover:bg-blue-500 dark:hover:text-white transition-all transform hover:-translate-y-1 active:scale-95 text-center">
                                Claim This Deal
                            </a>
                        </div>

                        <div class="absolute top-0 right-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none">
                            <i class="fa-solid fa-quote-right text-9xl -mr-10 -mt-10"></i>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fa-solid fa-ticket-simple text-3xl"></i>
                    </div>
                    <h3 class="text-gray-500 font-bold">មិនទាន់មានការបញ្ចុះតម្លៃនៅឡើយទេ</h3>
                    <p class="text-gray-400 text-sm">សូមរង់ចាំ និងតាមដានការផ្តល់ជូនពិសេសៗនៅពេលក្រោយ!</p>
                </div>
                @endforelse
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
</div>

<script>
    document.getElementById("duration").addEventListener("change", function() {

        let days = parseInt(this.value);
        if (!days) return;

        // =========================
        // 1. AUTO SET CHECK-IN = TODAY
        // =========================
        let today = new Date();

        let yyyy = today.getFullYear();
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let dd = String(today.getDate()).padStart(2, '0');

        let checkInDate = `${yyyy}-${mm}-${dd}`;
        document.getElementById("check_in").value = checkInDate;

        // =========================
        // 2. CALCULATE CHECK-OUT
        // =========================
        let checkOut = new Date();
        checkOut.setDate(checkOut.getDate() + days);

        let y2 = checkOut.getFullYear();
        let m2 = String(checkOut.getMonth() + 1).padStart(2, '0');
        let d2 = String(checkOut.getDate()).padStart(2, '0');

        document.getElementById("check_out").value = `${y2}-${m2}-${d2}`;
    });
</script>

<script>
    function cartSystem() {
        return {
            count: 0,

            // ទាញយកចំនួន Cart បច្ចុប្បន្នពេល Load Page
            async getCount() {
                try {
                    let response = await axios.get('/cart-count');
                    this.count = response.data.count;
                } catch (error) {
                    console.error("Error fetching cart count");
                }
            },

            // បញ្ជូនទិន្នន័យទៅ Server តាមរយៈ Ajax (Axios)
            async addToCart(id) {
                try {
                    let response = await axios.post('{{ route('cart.add') }}', {
                            id: id
                        });

                    if (response.data.status === 'success') {
                        this.count = response.data.cart_count; // Update លេខ Badge

                        // បង្ហាញ SweetAlert2 (ដូចដែលអ្នកមានស្រាប់)
                        Swal.fire({
                            icon: 'success',
                            title: 'ជោគជ័យ!',
                            text: response.data.message,
                            showConfirmButton: false,
                            timer: 1500,
                            toast: true,
                            position: 'top-end'
                        });
                    } else {
                        Swal.fire({
                            icon: 'info',
                            text: response.data.message,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                } catch (error) {
                    console.error("Error adding to cart");
                }
            }
        }
    }

    function cartSystem() {
        return {
            count: 0,
            cartItems: [], // ទុកសម្រាប់រក្សាទុកបញ្ជីបន្ទប់

            async getCount() {
                // ទាញយកទាំងចំនួន និងទិន្នន័យបន្ទប់ពី Session
                let response = await axios.get('/cart-details');
                this.count = response.data.count;
                this.cartItems = response.data.items;
            },

            async addToCart(id) {
                let response = await axios.post('{{ route("cart.add") }}', {
                    id: id
                });
                if (response.data.status === 'success') {
                    this.getCount(); // ទាញទិន្នន័យថ្មីមកបង្ហាញភ្លាមៗ
                    // ... show sweetalert ...
                }
            },

            async removeFromCart(id) {
                // Logic សម្រាប់លុបបន្ទប់ចេញពី Cart
                let response = await axios.post('/cart-remove', {
                    id: id
                });
                this.getCount();
            }
        }
    }
</script>

@endsection