@extends('layouts.app')

@section('title', 'រូបភាពសណ្ឋាគារ | Gallery')

@section('content')
<div class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-20" x-data="{ activeFilter: 'all' }">
    <div class="container mx-auto px-4">

        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
                រូបភាពទាំងអស់អំពីសណ្ឋាគារ <span class="text-blue-600">ភីអេនធី</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                ទស្សនារូបភាពដ៏ស្រស់ស្អាតនៃបន្ទប់ និងទិដ្ឋភាពទូទៅនៃសណ្ឋាគារដៃគូរបស់យើងទាំងអស់។
            </p>
            <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button @click="activeFilter = 'all'"
                :class="activeFilter === 'all' ? 'bg-blue-600 text-white shadow-blue-500/50' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100'"
                class="px-6 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg">
                ទាំងអស់
            </button>
            @foreach($hotels as $hotel)
            <button @click="activeFilter = 'hotel-{{ $hotel->id }}'"
                :class="activeFilter === 'hotel-{{ $hotel->id }}' ? 'bg-blue-600 text-white shadow-blue-500/50' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100'"
                class="px-6 py-2.5 rounded-full font-bold text-sm transition-all shadow-lg">
                {{ $hotel->name }}
            </button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($galleries as $item)
            <div x-show="activeFilter === 'all' || activeFilter === 'hotel-{{ $item->hotel_id }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-90"
                x-transition:enter-end="opacity-100 transform scale-100"
                class="group relative aspect-[4/5] overflow-hidden rounded-2xl bg-gray-200 dark:bg-gray-800 shadow-xl">

                <img src="{{ asset('storage/' . $item->image) }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    alt="Gallery Image">

                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-6">
                    <span class="text-blue-400 text-xs font-black uppercase tracking-widest mb-1">
                        {{ $item->hotel->name ?? 'N/A' }}
                    </span>
                    <h3 class="text-white font-bold text-lg leading-tight uppercase">
                        ទិដ្ឋភាពសណ្ឋាគារ
                    </h3>

                    <a href="{{ asset('storage/' . $item->image) }}"
                        class="spotlight mt-4 w-10 h-10 bg-white/20 backdrop-blur-md text-white rounded-full flex items-center justify-center hover:bg-white hover:text-blue-600 transition-all">
                        <i class="fas fa-search-plus"></i>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-2xl mb-4">
                    <i class="fas fa-images text-3xl text-gray-400"></i>
                </div>
                <p class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest">មិនទាន់មានរូបភាពបង្ហាញឡើយ</p>
            </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $galleries->links() }}
        </div>
    </div>
</div>

@endsection