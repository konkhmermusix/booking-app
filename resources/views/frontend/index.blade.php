@extends('layouts.app')
@section('title', 'ទំព័រដើម')
@section('content')

<div class="container mx-auto">
    <header class="relative  sm:h-[35vh] md:h-[95vh] w-full overflow-hidden flex items-center justify-center text-center text-white rounded-2xl shadow-2xl">
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

    {{-- search box --}}
    <div class="container mx-auto px-4 -mt-10 relative z-10">
        <form action="{{ route('frontend.rooms') }}" method="GET">
            <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូល
                    </label>
                    <input type="date" name="check_in" id="check_in" value="{{ request('check_in', date('Y-m-d')) }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-calendar-check text-blue-500 mr-1"></i> ថ្ងៃចេញ
                    </label>
                    <input type="date" name="check_out" id="check_out" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                </div>

                <div class="flex flex-col">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-bed text-blue-500 mr-1"></i> ប្រភេទបន្ទប់
                    </label>
                    <div class="relative group">
                        <select name="type" 
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                            <option value="">គ្រប់ប្រភេទទាំងអស់</option>
                            @foreach($roomTypes as $category)
                            <option value="{{ $category->name }}" {{ request('type') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                    </div>
                </div>

                <div class="flex flex-col relative">
                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                        <i class="fas fa-clock text-blue-500 mr-1"></i> រយៈពេល
                    </label>
                    <div class="relative group">
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
                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                    </div>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full  bg-blue-600 hover:bg-blue-700 dark:bg-blue-800 text-white font-bold rounded-xl hover:brightness-110 shadow-lg transition-all active:scale-95 h-[52px] flex items-center justify-center gap-2">
                        ស្វែងរក
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- stay room --}}
    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-10">
                <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                    បន្ទប់ស្នាក់នៅ
                </h4>
                <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full mb-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($roomTypes as $stay)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-gray-100 dark:border-gray-700">
                    <div class="relative aspect-4/3 overflow-hidden">
                        @php
                        $image = $stay->images->where('is_primary', true)->first() ?? $stay->images->first();
                        @endphp

                        @if($image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="{{ $stay->name }}">
                        @else
                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <i class="fas fa-image text-4xl"></i>
                        </div>
                        @endif

                        <div class="absolute bottom-4 left-4 z-20">
                            <div class="bg-blue-600/95 backdrop-blur-md text-white px-3 py-1 rounded-xl shadow-lg border border-white/20">
                                <span class="text-[10px] opacity-80 block leading-none">ចាប់ពី</span>
                                <span class="text-lg font-bold">${{ number_format($stay->base_price, 2) }}</span>
                                <span class="text-[10px] opacity-80">/យប់</span>
                            </div>
                        </div>

                        @if($stay->reviews_avg_rating >= 4.5)
                        <div class="absolute top-4 left-4 z-20 bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg uppercase shadow-lg">
                            <i class="fas fa-fire mr-1 text-[8px]"></i> ពេញនិយម
                        </div>
                        @endif

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $stay->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400">
                                        {{ number_format($stay->reviews_avg_rating ?? 0, 1) }}
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-[10px] text-blue-500 font-semibold uppercase">
                                        {{ $stay->reviews_count ?? 0 }} ការវាយតម្លៃ
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col items-end">
                                <div class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-800/50">
                                    <span class="text-sm font-black">{{ number_format($stay->reviews_avg_rating ?? 0, 1) }}</span>
                                </div>
                                <span class="text-[9px] font-bold text-gray-400 mt-1 uppercase">
                                    @if($stay->reviews_count > 0)
                                    {{ $stay->reviews_avg_rating >= 4 ? 'បានណែនាំ' : 'ល្អ' }}
                                    @else
                                    មិនទាន់មានការវាយតម្លៃ
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1 mb-2">
                            <p class="text-[11px] text-green-600 dark:text-green-400 font-medium flex items-center">
                                <i class="fas fa-check-circle mr-1 text-[10px]"></i> អាចកក់បានភ្លាមៗ
                            </p>
                            @if($stay->available_rooms_count <= 5)
                                <p class="text-red-600 text-[10px] font-bold animate-pulse flex items-center">
                                <i class="fas fa-home mr-1"></i> នៅសល់តែ {{ $stay->available_rooms_count }} បន្ទប់
                                </p>
                                @endif
                        </div>

                        <div class="flex flex-wrap gap-2 mb-2">
                            @foreach($stay->facilities->take(3) as $facility)
                            <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-md border border-gray-100 dark:border-gray-600">
                                <i class="{{ $facility->icon }} text-blue-500 text-[10px]"></i>
                                <span class="text-[10px] text-gray-600 dark:text-gray-300 font-medium">{{ $facility->name }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 gap-3 border-t border-b border-gray-100 dark:border-gray-700 mt-auto mb-3">
                            <div class="text-center border-r border-gray-100 dark:border-gray-700">
                                <i class="fas fa-users text-blue-500 text-[10px] block mb-0.5"></i>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ $stay->max_guests }} នាក់</span>
                            </div>

                            <div class="text-center">
                                <i class="fas fa-bed text-blue-500 text-[10px] block mb-0.5"></i>
                                <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ $stay->name }}</span>
                            </div>
                        </div>

                        <div x-data="{ 
                            isHotelModalOpen: false, 
                            selectedRoomTypeId: null, 
                            openHotelModal(id) {
                                this.selectedRoomTypeId = id;
                                this.isHotelModalOpen = true;
                            }
                        }">
                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <button @click="openHotelModal({{ $stay->id }})"
                                    class="flex items-center justify-center bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all text-sm active:scale-95">
                                    <span>កក់ឥឡូវនេះ</span>
                                </button>

                                <a href="{{ route('frontend.room_details', $stay->id) }}"
                                    class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm ">
                                    <span>មើលលម្អិត</span>
                                </a>
                            </div>

                            <div x-show="isHotelModalOpen" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
                                <div class="flex items-center justify-center min-h-screen px-4 py-10">
                                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isHotelModalOpen = true"></div>

                                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
                                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                                        <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                                            <div class="flex items-center gap-4">
                                                <div>
                                                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ជ្រើសរើសថ្ងៃខែស្នាក់នៅ</h3>
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីថែមទៅកន្ដ្រក</p>
                                                </div>
                                            </div>
                                            <button @click="isHotelModalOpen = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
                                        </div>

                                        <form action="{{ route('cart.add.hotel') }}" method="POST" class="space-y-4">
                                            @csrf

                                            <input type="hidden" name="room_type_id" :value="selectedRoomTypeId">

                                            <div class="p-4 space-y-2 max-h-[60vh] overflow-y-auto custom-scrollbar">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                    <div class="space-y-1">
                                                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                                            <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូលស្នាក់នៅ
                                                        </label>
                                                        <input type="date" name="check_in" min="{{ date('Y-m-d') }}" required placeholder="ជ្រើសរើសថ្ងៃចូលស្នាក់នៅ"
                                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                                            <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃចាកចេញ
                                                        </label>
                                                        <input type="date" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required placeholder="ជ្រើសរើសថ្ងៃចាកចេញ"
                                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                                    </div>

                                                    <div class="space-y-1 md:col-span-2">
                                                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">
                                                            <i class="fas fa-comments text-blue-600 mr-1"></i>មតិផ្សេងៗ
                                                        </label>
                                                        <textarea name="special_requests" rows="2"
                                                            class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"
                                                            placeholder="ចំនួនមនុស្សត្រូវស្នាក់នៅ ឬមិនចាំបាច់ក៏បាន ទុកឱ្យទំនេរ..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                                                <button type="button"
                                                    @click="isHotelModalOpen = false"
                                                    class="px-6 h-11 flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all text-sm active:scale-95">
                                                    បោះបង់
                                                </button>

                                                <button type="submit"
                                                    class="px-6 h-11 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                                                    <div class="flex items-center gap-2">
                                                        <span>ថែមចូលកន្ត្រក</span>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20 bg-white rounded-3xl shadow-inner">
                    <i class="fas fa-bed text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">មិនទាន់មានបន្ទប់ស្នាក់ដែលអាចរកបាននៅឡើយទេ។</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- meeting room --}}
    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-10">
                <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                    សាលប្រជុំ
                </h4>
                <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full mb-4"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                @forelse($roomMeeting as $meeting)
                <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex flex-col border border-gray-100 dark:border-gray-700">
                    <div class="relative aspect-video overflow-hidden">
                        @php
                        $image = $meeting->images->where('is_primary', true)->first() ?? $meeting->images->first();
                        @endphp

                        @if($image)
                        <img src="{{ asset('storage/' . $image->image_path) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                            alt="{{ $meeting->name }}">
                        @else
                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                            <i class="fas fa-handshake text-4xl"></i>
                        </div>
                        @endif

                        <div class="absolute bottom-4 left-4 z-20">
                            <div class="bg-blue-600/95 backdrop-blur-md text-white px-3 py-1 rounded-xl shadow-lg border border-white/20">
                                <span class="text-[10px] opacity-80 block leading-none">តម្លៃចាប់ពី</span>
                                <span class="text-lg font-bold">${{ number_format($meeting->base_price, 2) }}</span>
                                <span class="text-[10px] opacity-80">/ចរចា</span>
                            </div>
                        </div>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>

                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1 group-hover:text-blue-600 transition-colors line-clamp-1">
                                    {{ $meeting->name }}
                                </h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                                    <span class="text-xs font-bold text-gray-600 dark:text-gray-400">
                                        {{ number_format($meeting->reviews_avg_rating ?? 0, 1) }}
                                    </span>
                                    <span class="text-gray-300">|</span>
                                    <span class="text-[10px] text-blue-500 font-semibold uppercase">
                                        {{ $meeting->reviews_count ?? 0 }} ការវាយតម្លៃ
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col items-end">
                                <div class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-800/50">
                                    <span class="text-sm font-black">{{ number_format($meeting->reviews_avg_rating ?? 0, 1) }}</span>
                                </div>
                                <span class="text-[9px] font-bold text-gray-400 mt-1 uppercase">
                                    @if($meeting->reviews_count > 0)
                                    {{ $meeting->reviews_avg_rating >= 4 ? 'បានណែនាំ' : 'ល្អ' }}
                                    @else
                                    មិនទាន់មានការវាយតម្លៃ
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mb-4">
                            <p class="text-[12px] text-green-600 dark:text-green-400 font-medium flex items-center">
                                <i class="fas fa-calendar-check mr-1.5"></i> ទំនេរសម្រាប់កក់
                            </p>
                            @if($meeting->available_rooms_count <= 2)
                                <p class="text-red-600 text-[11px] font-bold animate-pulse flex items-center">
                                <i class="fas fa-exclamation-circle mr-1.5"></i> នៅសល់តែ {{ $meeting->available_rooms_count }} បន្ទប់
                                </p>
                                @endif
                        </div>

                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach($meeting->facilities->take(4) as $facility)
                            <div class="flex items-center gap-2 bg-gray-50 dark:bg-gray-700/50 px-3 py-1.5 rounded-lg border border-gray-100 dark:border-gray-600">
                                <i class="{{ $facility->icon }} text-blue-500 text-[11px]"></i>
                                <span class="text-[11px] text-gray-600 dark:text-gray-300 font-medium">{{ $facility->name }}</span>
                            </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-4 border-t border-gray-100 dark:border-gray-700 mt-auto">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                                    <i class="fas fa-users text-blue-500 text-xs"></i>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-bold">{{ $meeting->max_guests }} នាក់</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center">
                                    <i class="fas fa-tv text-blue-500 text-xs"></i>
                                </div>
                                <span class="text-xs text-gray-600 dark:text-gray-400 font-bold">កញ្ចក់បញ្ចាំង</span>
                            </div>
                        </div>

                        <div x-data="{ 
                            isMeetingModalOpen: false, 
                            selectedMeetingRoomTypeId: null,
                            openMeetingModal(id) {
                                this.selectedMeetingRoomTypeId = id;
                                this.isMeetingModalOpen = true;
                            }
                        }">

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <button @click="openMeetingModal({{ $meeting->id }})"
                                    class="flex items-center justify-center bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all text-sm active:scale-95">
                                    <span>កក់ឥឡូវនេះ</span>
                                </button>

                                <a href="{{ route('frontend.meeting_details', $meeting->id) }}"
                                    class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm ">
                                    <span>មើលលម្អិត</span>
                                </a>
                            </div>

                            <div x-show="isMeetingModalOpen" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
                                <div class="flex items-center justify-center min-h-screen px-4 py-10">
                                    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isMeetingModalOpen = true"></div>

                                    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
                                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                                        <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                                            <div class="flex items-center gap-4">
                                                <div>
                                                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">រៀបចំម៉ោងពេលប្រជុំ</h3>
                                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីថែមទៅកន្ដ្រក</p>
                                                </div>
                                            </div>
                                            <button @click="isMeetingModalOpen = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
                                        </div>

                                        <form action="{{ route('cart.add.meeting') }}" method="POST" class="space-y-4">
                                            @csrf

                                            <input type="hidden" name="room_type_id" :value="selectedMeetingRoomTypeId">

                                            <div class="p-4 space-y-2 max-h-[60vh] overflow-y-auto custom-scrollbar">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                            <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចាប់ផ្តើមប្រជុំ
                                                        </label>
                                                        <input type="date" name="start_date" min="{{ date('Y-m-d') }}" required
                                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                            <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃបញ្ចប់ប្រជុំ
                                                        </label>
                                                        <input type="date" name="end_date" min="{{ date('Y-m-d') }}" required
                                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                            <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងចាប់ផ្តើម (រាល់ថ្ងៃ)
                                                        </label>
                                                        <input type="time" name="start_time" required
                                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                                    </div>

                                                    <div class="space-y-1">
                                                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                            <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងបញ្ចប់ (រាល់ថ្ងៃ)
                                                        </label>
                                                        <input type="time" name="end_time" required
                                                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                                    </div>

                                                    <div class="space-y-1 md:col-span-2">
                                                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">
                                                            <i class="fas fa-comments text-blue-600 mr-1"></i>មតិផ្សេងៗ
                                                        </label>
                                                        <textarea name="special_requests" rows="2"
                                                            class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"
                                                            placeholder="ចំនួនមនុស្សត្រូវស្នាក់នៅ ឬមិនចាំបាច់ក៏បាន ទុកឱ្យទំនេរ..."></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                                                <button type="button"
                                                    @click="isMeetingModalOpen = false"
                                                    class="px-6 h-11 flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all text-sm active:scale-95">
                                                    បោះបង់
                                                </button>

                                                <button type="submit"
                                                    class="px-6 h-11 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                                                    <div class="flex items-center gap-2">
                                                        <span>ថែមចូលកន្ត្រក</span>
                                                    </div>
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20 bg-white dark:bg-gray-800 rounded-3xl shadow-inner border-2 border-dashed border-gray-200 dark:border-gray-700">
                    <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500">មិនទាន់មានសាលប្រជុំដែលអាចរកបាននៅឡើយទេ។</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- facilities --}}
    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-10">
                <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                    សេវាកម្ម និងសម្ភារៈ
                </h4>
                <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full mb-4"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 justify-items-center">
                @forelse($facilities as $facility)
                <div class="group relative flex flex-col items-center justify-center w-full p-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent dark:from-blue-900/10 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl"></div>

                    <div class="relative z-10 w-16 h-16 flex items-center justify-center bg-blue-50 dark:bg-blue-900/20 rounded-2xl text-blue-600 dark:text-blue-400 mb-4 text-3xl group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                        <i class="{{ $facility->icon }}"></i>
                    </div>

                    <span class="relative z-10 font-bold text-gray-800 dark:text-gray-200 text-sm md:text-base text-center">
                        {{ $facility->name }}
                    </span>

                    <div class="mt-3 w-0 group-hover:w-8 h-1 bg-blue-600 rounded-full transition-all duration-500"></div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-white dark:bg-gray-800 rounded-full shadow-sm flex items-center justify-center mb-4 border border-gray-100 dark:border-gray-700">
                        <i class="fas fa-box-open text-gray-300 text-4xl"></i>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">មិនទាន់មានសម្ភារៈត្រូវបានបញ្ចូលនៅឡើយទេ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- toursm --}}
    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center mb-10">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                    <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                        គោលដៅពេញនិយម
                    </h4>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                @forelse($tours as $tour)
                <div class="relative rounded-2xl overflow-hidden h-72 md:h-96 group shadow-lg cursor-pointer">
                    <a href="{{ route('toursdetail', $tour->id) }}">
                        <img src="{{ asset('storage/' . ($tour->image[0] ?? 'default.jpg')) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                            alt="{{ $tour->name }}">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-4 md:p-6">
                            <h4 class="text-lg md:text-xl font-bold text-white line-clamp-1">{{ $tour->name }}</h4>
                            <p class="text-[10px] md:text-sm text-gray-300 mb-5">{{ $tour->distance }} គីឡូម៉ែត្រពីសណ្ឋាគារ</p>

                            <a href="{{ $tour->google_map_link }}" target="_blank"
                                class="flex items-center justify-center w-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm">
                                <span>មើលលើផែនទី</span>
                            </a>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 text-center">
                    <i class="fas fa-map-marked-alt text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">មិនទាន់មានគោលដៅទេសចរណ៍នៅឡើយទេ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- gallary --}}
    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                        រូបភាពសណ្ឋាគារ
                    </h4>
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
                            <a href="{{ route('frontend.gallery') }}"
                                class="inline-flex items-center mt-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-full transition shadow-lg">
                                មើលរូបភាពទាំងអស់
                                <i class="fas fa-arrow-right ml-2 text-xs"></i>
                            </a>
                        </div>
                    </div>

                    <div class="absolute top-4 left-4">
                        <span class="bg-white/90 backdrop-blur-sm text-gray-900 text-[11px] font-bold px-3 py-1 rounded-full uppercase shadow-sm">
                            {{ $item->hotel->name ?? 'រូបភាព' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="col-span-4 py-20 text-center bg-gray-100 dark:bg-gray-800 rounded-3xl">
                    <p class="text-gray-500">មិនទាន់មានរូបភាពឡើយ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- promotion --}}
    @if($promotions->isNotEmpty())
    <div x-data="{ 
    // រក្សាទុកទិន្នន័យបណ្តោះអាសន្នពេលចុច
    isHotelModalOpen: false,
    isMeetingModalOpen: false,
    selectedId: null, 
    selectedPromoPrice: 0,
    
    // ទិន្នន័យសម្រាប់សណ្ឋាគារ
    checkIn: '{{ date('Y-m-d') }}',
    checkOut: '{{ date('Y-m-d', strtotime('+1 day')) }}',
    
    // ទិន្នន័យសម្រាប់សាលប្រជុំ
    startDate: '{{ date('Y-m-d') }}',
    endDate: '{{ date('Y-m-d') }}',
    startTime: '07:00',
    endTime: '17:00',
    
    isSubmitting: false,

    // មុខងារពេលចុចប៊ូតុង ទទួលយកការផ្តល់ជូន
    openPromo(type, id, price) {
        this.selectedId = id;
        this.selectedPromoPrice = price;
        if(type === 'hotel') {
            this.isHotelModalOpen = true;
        } else {
            this.isMeetingModalOpen = true;
        }
    },

    // ផ្ញើទៅកន្ត្រកបន្ទប់ស្នាក់នៅ
    submitHotelPromo() {
        this.isSubmitting = true;
        fetch('{{ route('cart.add.hotel') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                room_type_id: this.selectedId,
                promo_price: this.selectedPromoPrice,
                check_in: this.checkIn,
                check_out: this.checkOut,
                specialRequests: ''
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') { Swal.fire('ជោគជ័យ', data.message, 'success'); this.isHotelModalOpen = false; }
            else { Swal.fire('បរាជ័យ', data.message, 'error'); }
        }).finally(() => this.isSubmitting = false);
    },

    // ផ្ញើទៅកន្ត្រកសាលប្រជុំ
    submitMeetingPromo() {
        this.isSubmitting = true;
        fetch('{{ route('cart.add.meeting') }}', { // ត្រូវប្រាកដថាមាន Route នេះ
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({
                room_type_id: this.selectedId, // ឬប្រើ meeting_room_id តាមកូដចាស់បង
                promo_price: this.selectedPromoPrice,
                start_date: this.startDate,
                end_date: this.endDate,
                start_time: this.startTime,
                end_time: this.endTime,
                specialRequests: ''
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') 
            { Swal.fire('ជោគជ័យ', data.message, 'success'); 
             this.isMeetingModalOpen = false; 
            } else {
                Swal.fire('បរាជ័យ', data.message, 'error'); 
            }
        }).finally(() => this.isSubmitting = false);
    }
}">
        <section class="py-10 bg-[#fdff6c] dark:bg-[#0b1120]">
            <div class="container mx-auto px-4">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10">
                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                            <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                                ការផ្តល់ជូនពិសេស
                            </h4>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 ml-5">
                            ទទួលយកការផ្តល់ជូនផ្តាច់មុខទាំងនេះ មុនពេលការផ្តល់ជូននេះផុតកំណត់!
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($promotions as $promo)

                    @php
                    $category = $promo->roomType->category ?? 'stay';
                    $promoType = ($category === 'stay') ? 'hotel' : 'meeting';
                    $roomName = $promo->roomType->name ?? '';
                    $targetId = $promo->room_type_id;
                    @endphp

                    <div class="group relative flex flex-col bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-lg border border-gray-100 dark:border-gray-800">
                        <div class="h-64 overflow-hidden relative">

                            <img src="{{ asset('storage/' . $promo->image_path) }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                alt="{{ $promo->name }}">


                            <div class="absolute top-4 left-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur-md px-3 py-1.5 rounded-xl shadow-sm text-center">
                                <p class="text-[9px] font-black uppercase text-gray-400 leading-none">ផុតកំណត់ក្នុងរយៈពេល</p>
                                <p class="text-xs font-bold text-red-500">{{ \Carbon\Carbon::parse($promo->expiry_date)->diffForHumans() }}</p>
                            </div>

                            <div class="absolute top-4 right-4 bg-green-500 text-white px-3 py-1.5 rounded-xl shadow-lg flex items-center gap-2">
                                <div class="w-2 h-2 bg-white rounded-full animate-ping"></div>
                                <span class="text-[10px] font-bold">នៅសល់ {{ $promo->roomType->rooms->count() }} បន្ទប់</span>
                            </div>

                            <div class="absolute bottom-4 left-4">
                                <span class="bg-blue-600 text-white px-3 py-1 rounded-lg text-[10px] font-bold uppercase shadow-lg">
                                    {{ $roomName }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col justify-between flex-grow">
                            <div>
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase line-clamp-1">{{ $promo->title }}</h3>
                                    <div class="bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-[9px] font-black px-2 py-1 rounded-full">
                                        {{ $promo->tag ?? 'រយៈពេលមានកំណត់' }}
                                    </div>
                                </div>

                                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-6 line-clamp-2">
                                    {{ $promo->description }}
                                </p>

                                <div class="flex items-baseline gap-2 mb-6">
                                    <span class="text-2xl font-black text-blue-600">
                                        ${{ number_format($promo->discounted_price, 0) }}<span class="text-sm font-normal text-gray-500 dark:text-gray-400">/{{ $category === 'stay' ? 'យប់' : 'ម៉ោង' }}</span>
                                    </span>

                                    <span class="text-sm text-gray-400 line-through">
                                        ${{ number_format($promo->original_price, 0) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <button @click="openPromo('{{ $promoType }}', {{ $targetId }}, {{ $promo->discounted_price }})"
                                    class="flex items-center justify-center bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all text-sm active:scale-95">
                                    <span>កក់ឥឡូវនេះ</span>
                                </button>

                                <a href="{{ route('frontend.promotion_details', $promo->id) }}"
                                    class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm ">
                                    <span>មើលលម្អិត</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        <div x-show="isHotelModalOpen" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isHotelModalOpen = true"></div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                    <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                        <div class="flex items-center gap-4">
                            <div>
                                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កក់បន្ទប់ស្នាក់នៅតម្លៃប្រូម៉ូសិន</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីថែមទៅកន្ដ្រក</p>
                            </div>
                        </div>
                        <button @click="isHotelModalOpen = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
                    </div>

                    <form @submit.prevent="submitHotelPromo()" class="space-y-4">
                        @csrf

                        <input type="hidden" name="room_type_id" :value="selectedRoomTypeId">

                        <div class="p-4 space-y-2 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូលស្នាក់នៅ
                                    </label>
                                    <input type="date" x-model="checkIn" min="{{ date('Y-m-d') }}" required placeholder="ជ្រើសរើសថ្ងៃចូលស្នាក់នៅ"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃចាកចេញ
                                    </label>
                                    <input type="date" x-model="checkOut" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required placeholder="ជ្រើសរើសថ្ងៃចាកចេញ"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1 md:col-span-2">
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">
                                        <i class="fas fa-comments text-blue-600 mr-1"></i>មតិផ្សេងៗ
                                    </label>
                                    <textarea x-model="specialRequests" rows="2"
                                        class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"
                                        placeholder="ចំនួនមនុស្សត្រូវស្នាក់នៅ ឬមិនចាំបាច់ក៏បាន ទុកឱ្យទំនេរ..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="px-7 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button"
                                @click="isHotelModalOpen = false"
                                class="px-6 h-11 flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all text-sm active:scale-95">
                                បោះបង់
                            </button>

                            <button type="submit"
                                class="px-6 h-11 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                                <div class="flex items-center gap-2">
                                    <span>ថែមចូលកន្ត្រក</span>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="isMeetingModalOpen" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isMeetingModalOpen = true"></div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                    <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                        <div class="flex items-center gap-4">
                            <div>
                                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">រៀបចំម៉ោងពេលប្រជុំ</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីថែមទៅកន្ដ្រក</p>
                            </div>
                        </div>
                        <button @click="isMeetingModalOpen = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
                    </div>

                    <form @submit.prevent="submitMeetingPromo()" class="space-y-4">
                        @csrf

                        <input type="hidden" name="room_type_id" :value="selectedMeetingRoomTypeId">

                        <div class="p-4 space-y-2 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចាប់ផ្តើមប្រជុំ
                                    </label>
                                    <input type="date" x-model="startDate" min="{{ date('Y-m-d') }}" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃបញ្ចប់ប្រជុំ
                                    </label>
                                    <input type="date" x-model="endDate" min="{{ date('Y-m-d') }}" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងចាប់ផ្តើម (រាល់ថ្ងៃ)
                                    </label>
                                    <input type="time" x-model="startTime" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងបញ្ចប់ (រាល់ថ្ងៃ)
                                    </label>
                                    <input type="time" x-model="endTime" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1 md:col-span-2">
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">
                                        <i class="fas fa-comments text-blue-600 mr-1"></i>មតិផ្សេងៗ
                                    </label>
                                    <textarea name="special_requests" rows="2"
                                        class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"
                                        placeholder="ចំនួនមនុស្សត្រូវស្នាក់នៅ ឬមិនចាំបាច់ក៏បាន ទុកឱ្យទំនេរ..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="px-7 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button"
                                @click="isMeetingModalOpen = false"
                                class="px-6 h-11 flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all text-sm active:scale-95">
                                បោះបង់
                            </button>

                            <button type="submit"
                                class="px-6 h-11 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                                <div class="flex items-center gap-2">
                                    <span>ថែមចូលកន្ត្រក</span>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- customer review --}}
    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-10">
                <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                    បទពិសោធន៍របស់ភ្ញៀវ
                </h4>
                <p class="text-gray-500 mt-4">
                    ស្វែងយល់ពីចំណាប់អារម្មណ៍ដ៏ពិតប្រាកដរបស់ភ្ញៀវដែលបានមកស្នាក់នៅជាមួយយើង។
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-8">
                @forelse($reviews as $review)
                <div class="bg-white dark:bg-gray-900 p-8 rounded-3xl relative shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group flex flex-col">
                    <div class="absolute top-0 right-8 transform -translate-y-1/2">
                        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                            <i class="fas fa-quote-right text-white text-xl"></i>
                        </div>
                    </div>

                    <div class="flex gap-1 mb-5 text-yellow-400 text-sm">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                    </div>

                    <p class="italic text-gray-600 dark:text-gray-300 leading-relaxed text-lg mb-8 flex-grow">
                        "{{ $review->comment }}"
                    </p>

                    <div class="flex items-center gap-4 mt-auto pt-6 border-t border-gray-50 dark:border-gray-800">
                        <div class="w-12 h-12 relative group-hover:scale-110 transition-transform duration-300">
                            @if($review->user && $review->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $review->user->profile_photo_path) }}"
                                alt="{{ $review->name }}"
                                class="w-full h-full object-cover rounded-2xl shadow-md transform rotate-3 group-hover:rotate-0 transition-transform border-2 border-white dark:border-gray-800">
                            @else
                            <div class="w-full h-full bg-gradient-to-tr from-blue-600 to-indigo-500 text-white rounded-2xl flex items-center justify-center font-bold shadow-md uppercase transform rotate-3 group-hover:rotate-0 transition-transform">
                                <span class="text-lg">
                                    {{ mb_substr($review->name, 0, 1, 'UTF-8') }}
                                </span>
                            </div>
                            @endif
                        </div>
                        <div>
                            <h5 class="font-bold text-gray-800 dark:text-white text-base">{{ $review->name }}</h5>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                <p class="text-xs font-medium text-blue-500 uppercase tracking-wider">
                                    {{ $review->roomType->name ?? 'ភ្ញៀវកិត្តិយស' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20">
                    <i class="fas fa-comment-slash text-gray-300 text-5xl mb-4"></i>
                    <p class="text-gray-500 italic">មិនទាន់មានការវាយតម្លៃនៅឡើយទេ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</div>

<script>
    document.getElementById("duration").addEventListener("change", function() {

        let days = parseInt(this.value);
        if (!days) return;

        // 1. AUTO SET CHECK-IN = TODAY
        let today = new Date();

        let yyyy = today.getFullYear();
        let mm = String(today.getMonth() + 1).padStart(2, '0');
        let dd = String(today.getDate()).padStart(2, '0');

        let checkInDate = `${yyyy}-${mm}-${dd}`;
        document.getElementById("check_in").value = checkInDate;

        // 2. CALCULATE CHECK-OUT
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
                    let response = await axios.post('{{ route("cart.add") }}', {
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