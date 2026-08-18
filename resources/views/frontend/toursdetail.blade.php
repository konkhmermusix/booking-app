@extends('layouts.app')
@section('title', $tour->name . ' - គោលដៅពេញនិយម')
@section('content')

@php
$mainImg = is_array($tour->image) ? ($tour->image[0] ?? 'default.jpg') : ($tour->image ?? 'default.jpg');
@endphp

<div class="w-full bg-gray-50 dark:bg-[#0b1120] min-h-screen pb-16 transition-colors duration-300">
    {{-- HERO BANNER --}}
    <div class="relative h-[45vh] md:h-[55vh] overflow-hidden bg-gray-900">
        <img src="{{ asset('storage/' . $mainImg) }}"
            class="w-full h-full object-cover" alt="{{ $tour->name }}">

        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-black/40 backdrop-blur-md border border-white/20 text-white text-xs md:text-sm font-semibold mb-4 shadow-lg">
                <i class="fas fa-map-marker-alt text-red-500"></i>
                <span>{{ $tour->distance }} គីឡូម៉ែត្រពីសណ្ឋាគារ ភីអេនធី ផាលេស</span>
            </div>

            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight uppercase mb-4 drop-shadow-md">
                {{ $tour->name }}
            </h1>

            <div class="flex items-center gap-3">
                <a href="{{ $tour->google_map_link }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl text-xs md:text-sm shadow-lg shadow-blue-600/30 transition-all active:scale-95 whitespace-nowrap">
                    <i class="fas fa-directions"></i>
                    <span>មើលទីតាំងលើផែនទី (Google Maps)</span>
                </a>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT GRID --}}
    <div class="container mx-auto px-4 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start ">

            {{-- LEFT SIDEBAR: OTHER DESTINATIONS --}}
            <div class="lg:col-span-1 order-last lg:order-first w-full">
                <div class="bg-white dark:bg-gray-900 p-5 md:p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 sticky top-24 transition-colors duration-300">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="h-6 w-1.5 bg-blue-600 rounded-full shrink-0"></div>
                        <h2 class="text-base md:text-lg font-extrabold text-gray-900 dark:text-white uppercase tracking-tight whitespace-nowrap">
                            គោលដៅផ្សេងៗទៀត
                        </h2>
                    </div>

                    <div class="space-y-3">
                        @foreach($otherTours as $item)
                        @php
                        $itemImg = is_array($item->image) ? ($item->image[0] ?? 'default.jpg') : ($item->image ?? 'default.jpg');
                        @endphp
                        <a href="{{ route('toursdetail', $item->id) }}"
                            class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-all border border-transparent hover:border-gray-100 dark:hover:border-gray-800 group w-full">
                            <img src="{{ asset('storage/' . $itemImg) }}"
                                class="w-14 h-14 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform shrink-0"
                                alt="{{ $item->name }}">

                            <div class="min-w-0 flex-grow">
                                <h3 class="text-xs md:text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors truncate">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1 flex items-center whitespace-nowrap">
                                    <i class="fas fa-map-marker-alt text-red-400 mr-1 text-[10px]"></i>
                                    {{ $item->distance }} គីឡូម៉ែត្រ
                                </p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- MAIN COLUMN: TOUR DETAILS --}}
            <div class="lg:col-span-2 w-full mb-10">
                <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="h-6 w-1.5 bg-blue-600 rounded-full shrink-0"></div>
                            <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight whitespace-nowrap">
                                ព័ត៌មានលម្អិតពីទីតាំង
                            </h2>
                        </div>
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 text-xs md:text-sm font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 transition-all group shrink-0 whitespace-nowrap">
                            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                            <span>ត្រឡប់ទៅទំព័រដើម</span>
                        </a>
                    </div>

                    <div class="text-gray-600 dark:text-gray-300 leading-relaxed text-sm md:text-base space-y-4 font-normal ck-content break-words [word-break:break-word]">
                        {!! nl2br(e($tour->description)) !!}
                    </div>

                    {{-- MAP BUTTON BANNER --}}
                    <div class="mt-8 p-5 bg-blue-50 dark:bg-blue-950/30 rounded-2xl border border-blue-100 dark:border-blue-900/40 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center text-lg shrink-0">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white whitespace-nowrap">ទីតាំងនៅលើផែនទី Google Maps</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ចម្ងាយ {{ $tour->distance }} គីឡូម៉ែត្រពីសណ្ឋាគារ</p>
                            </div>
                        </div>

                        <a href="{{ $tour->google_map_link }}" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-5 py-2.5 rounded-xl transition shadow-sm shrink-0 active:scale-95 whitespace-nowrap">
                            <span>បើកមើលលើ Google Maps</span>
                            <i class="fas fa-external-link-alt text-[10px]"></i>
                        </a>
                    </div>

                    {{-- GALLERY IMAGES --}}
                    @if(is_array($tour->image) && count($tour->image) > 1)
                    <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-6 w-1.5 bg-blue-600 rounded-full shrink-0"></div>
                            <h2 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight whitespace-nowrap">
                                រូបភាពពីទីតាំងនេះ
                            </h2>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($tour->image as $index => $img)
                            <div class="relative group rounded-2xl overflow-hidden h-40 md:h-48 shadow-sm border border-gray-100 dark:border-gray-800 cursor-pointer" onclick="openImageModal('{{ asset('storage/' . $img) }}')">
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="gallery-item-img w-full h-full object-cover group-hover:scale-110 transition duration-700"
                                    alt="{{ $tour->name }}">

                                <button type="button"
                                    onclick="event.stopPropagation(); openImageModal('{{ asset('storage/' . $img) }}')"
                                    class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-3 py-2 rounded-xl shadow hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white text-gray-700 dark:text-gray-300 duration-300 focus:outline-none z-10">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openImageModal(imgSrc) {
        const thumbElements = document.querySelectorAll('.gallery-item-img');
        let imagesArray = [];
        let activeIndex = 0;

        if (thumbElements.length > 0) {
            thumbElements.forEach((thumb, idx) => {
                imagesArray.push({ src: thumb.src });
                if (thumb.src === imgSrc) {
                    activeIndex = idx;
                }
            });
        } else {
            imagesArray.push({ src: imgSrc });
        }

        if (typeof Spotlight !== 'undefined') {
            Spotlight.show(imagesArray, {
                index: activeIndex + 1,
                theme: 'dark',
                infinite: true
            });
        } else {
            window.open(imgSrc, '_blank');
        }
    }
</script>

@endsection