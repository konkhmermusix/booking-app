@extends('layouts.app')
@section('title', 'តំបន់ទេសចរណ៍')
@section('content')

<section class="bg-gray-50 dark:bg-[#0b1120] min-h-screen pb-16">
    <!-- Hero Banner Section -->
    <div class="relative h-[30vh] sm:h-[40vh] overflow-hidden flex items-center justify-center text-center text-white">
        <div class="absolute inset-0 bg-black/60 z-10"></div>
        <div class="absolute inset-0 bg-[url('/images/tours_hero_bg.jpg')] bg-cover bg-center"></div>
        
        <div class="relative z-20 px-6">
            <h1 class="text-3xl sm:text-5xl font-black mb-4 tracking-tight uppercase drop-shadow-md">
                🌴 ស្វែងយល់ពីតំបន់ទេសចរណ៍
            </h1>
            <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full mb-3"></div>
            <p class="text-sm sm:text-base text-gray-200 max-w-xl mx-auto font-medium">
                បង្កើនបទពិសោធន៍ស្នាក់នៅរបស់អ្នកជាមួយដំណើរកម្សាន្តទៅកាន់ទីតាំងល្បីៗជុំវិញសណ្ឋាគារ
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-10">
        <!-- Search and Filter Bar -->
        <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 mb-10">
            <form action="{{ route('frontend.tours') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 dark:text-white mb-1">ស្វែងរកកញ្ចប់ទេសចរណ៍</h2>
                    <p class="text-xs text-gray-400 font-semibold uppercase">Search all travel locations</p>
                </div>
                <div class="flex gap-2 w-full sm:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរកតាមឈ្មោះ..." 
                        class="w-full sm:w-80 px-4 h-12 bg-gray-50 dark:bg-gray-800 dark:text-white border-none rounded-xl focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                    <button type="submit" class="px-6 h-12 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all active:scale-95 shadow-md flex items-center gap-2">
                        <i class="fas fa-search text-xs"></i>
                        <span>ស្វែងរក</span>
                    </button>
                </div>
            </form>
        </div>

        @if($tours->isEmpty())
            <!-- Empty State -->
            <div class="text-center py-20 bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm max-w-md mx-auto">
                <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-400">
                    <i class="fas fa-map-marked-alt text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">រកមិនឃើញតំបន់ទេសចរណ៍ឡើយ</h3>
                <p class="text-sm text-gray-400 font-medium px-6">សាកល្បងស្វែងរកជាមួយពាក្យគន្លឹះផ្សេងទៀត ឬត្រឡប់ទៅមើលបញ្ជីដើមវិញ។</p>
                <a href="{{ route('frontend.tours') }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-md">
                    បង្ហាញទាំងអស់ឡើងវិញ
                </a>
            </div>
        @else
            <!-- Tours Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($tours as $tour)
                    @php
                        $tourImages = is_array($tour->image) ? $tour->image : json_decode($tour->image, true);
                        $coverImage = !empty($tourImages) ? $tourImages[0] : 'default.jpg';
                    @endphp
                    <div class="group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100/60 dark:border-gray-800 flex flex-col h-full transform hover:-translate-y-1">
                        <div class="relative h-60 overflow-hidden">
                            <img src="{{ asset('storage/' . $coverImage) }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                alt="{{ $tour->name }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            
                            @if($tour->price)
                                <div class="absolute top-4 right-4 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm text-center">
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 font-extrabold uppercase block tracking-wider leading-none">ចាប់ពី</span>
                                    <span class="text-lg font-black text-blue-600 dark:text-blue-400">${{ number_format($tour->price, 2) }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex flex-col flex-1 justify-between">
                            <div class="space-y-3">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $tour->name }}
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed line-clamp-3">
                                    {{ strip_tags($tour->description) }}
                                </p>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-50 dark:border-gray-800 flex justify-between items-center">
                                @if($tour->duration)
                                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 flex items-center gap-1.5">
                                        <i class="far fa-clock"></i>
                                        <span>{{ $tour->duration }}</span>
                                    </span>
                                @else
                                    <span></span>
                                @endif
                                <a href="{{ route('toursdetail', $tour->id) }}" class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white px-5 py-2.5 rounded-xl font-bold text-xs transition-all">
                                    <span>មើលលម្អិត</span>
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination Section -->
            <div class="mt-12 flex justify-center">
                {{ $tours->links() }}
            </div>
        @endif
    </div>
</section>

@endsection