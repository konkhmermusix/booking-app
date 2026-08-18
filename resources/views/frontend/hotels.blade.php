@extends('layouts.app')
@section('title', 'សណ្ឋាគាររបស់យើង')
@section('content')

<section class="bg-gray-50 dark:bg-[#0b1120] min-h-screen pb-16">
    <!-- Hero Section -->
    <div class="relative h-[30vh] sm:h-[40vh] overflow-hidden flex items-center justify-center text-center text-white">
        <div class="absolute inset-0 bg-black/60 z-10"></div>
        <div class="absolute inset-0 bg-[url('/images/hotels_hero_bg.jpg')] bg-cover bg-center"></div>
        
        <div class="relative z-20 px-6">
            <h1 class="text-3xl sm:text-5xl font-black mb-4 tracking-tight uppercase drop-shadow-md">
                🏢 សណ្ឋាគាររបស់យើង
            </h1>
            <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full mb-3"></div>
            <p class="text-sm sm:text-base text-gray-200 max-w-xl mx-auto font-medium">
                ស្វែងរកទីតាំងសណ្ឋាគារ ភីអេនធី ផាលេស ដែលផ្តល់សេវាកម្មល្អបំផុតជូនលោកអ្នក
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        @if($hotels->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-20 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm max-w-md mx-auto">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                    <i class="fas fa-hotel text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">មិនទាន់មានទីតាំងសណ្ឋាគារនៅឡើយទេ</h3>
                <p class="text-sm text-gray-400 font-medium px-6">សូមត្រឡប់មកពិនិត្យមើលឡើងវិញនៅពេលក្រោយ។</p>
            </div>
        @else
            <!-- Hotels Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($hotels as $hotel)
                    <div class="group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100/60 dark:border-gray-800 flex flex-col h-full transform hover:-translate-y-1">
                        <!-- Hotel Image/Logo Container -->
                        <div class="relative h-56 bg-gray-100 dark:bg-gray-800 overflow-hidden flex items-center justify-center">
                            @if($hotel->logo)
                                <img src="{{ asset('storage/' . $hotel->logo) }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                    alt="{{ $hotel->name }}">
                            @else
                                <div class="text-gray-300 dark:text-gray-600 text-6xl">
                                    <i class="fas fa-hotel"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            <div class="absolute bottom-4 left-4">
                                <span class="bg-blue-600/90 backdrop-blur-sm text-white text-[10px] font-black uppercase px-3 py-1 rounded-full tracking-wider">
                                    PNT Palace
                                </span>
                            </div>
                        </div>

                        <!-- Hotel Details -->
                        <div class="p-6 flex flex-col flex-1 justify-between">
                            <div class="space-y-4">
                                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $hotel->name }}
                                </h3>
                                
                                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3">
                                    {{ $hotel->description ?? 'គ្មានការពិពណ៌នាសង្ខេបឡើយសម្រាប់ទីតាំងសណ្ឋាគារនេះ។' }}
                                </p>

                                <div class="space-y-2.5 pt-2">
                                    <!-- Address -->
                                    <div class="flex items-start gap-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-map-marker-alt text-blue-600 dark:text-blue-400 mt-0.5"></i>
                                        <span class="leading-normal">{{ $hotel->address }}</span>
                                    </div>
                                    
                                    <!-- Phone -->
                                    @if($hotel->phone)
                                        <div class="flex items-center gap-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-phone-alt text-blue-600 dark:text-blue-400"></i>
                                            <span>{{ $hotel->phone }}</span>
                                        </div>
                                    @endif

                                    <!-- Email -->
                                    @if($hotel->email)
                                        <div class="flex items-center gap-2.5 text-xs font-semibold text-gray-500 dark:text-gray-400">
                                            <i class="fas fa-envelope text-blue-600 dark:text-blue-400"></i>
                                            <span>{{ $hotel->email }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-8 pt-4 border-t border-gray-50 dark:border-gray-800 flex items-center justify-between gap-3">
                                @if($hotel->latitude && $hotel->longitude)
                                    <a href="https://www.google.com/maps/search/?api=1&query={{ $hotel->latitude }},{{ $hotel->longitude }}" 
                                        target="_blank" 
                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-gray-400 dark:text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        <i class="fas fa-compass"></i>
                                        <span>មើលផែនទី</span>
                                    </a>
                                @else
                                    <span></span>
                                @endif
                                
                                <a href="{{ route('frontend.rooms', ['hotel_id' => $hotel->id]) }}" 
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-xs shadow-md transition-all active:scale-95">
                                    <span>កក់បន្ទប់ឥឡូវនេះ</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@endsection