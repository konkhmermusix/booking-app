@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8" x-data="{ activeImage: '{{ asset('storage/' . ($room->roomType->images->first()->image_path ?? 'default.jpg')) }}' }">

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
        <div class="lg:col-span-2 space-y-4">
            <div class="relative h-[500px] w-full rounded-2xl overflow-hidden shadow-lg border dark:border-gray-800">
                <img :src="activeImage" class="w-full h-full object-cover transition-all duration-500">
            </div>

            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
                @foreach($room->roomType->images as $img)
                <div @click="activeImage = '{{ asset('storage/' . $img->image_path) }}'"
                    class="w-24 h-24 flex-shrink-0 rounded-2xl overflow-hidden cursor-pointer border-2 transition-all"
                    :class="activeImage === '{{ asset('storage/' . $img->image_path) }}' ? 'border-blue-600 scale-105' : 'border-transparent opacity-70 hover:opacity-100'">
                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border dark:border-gray-800 shadow-sm sticky top-24">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <span class="text-3xl font-black text-blue-600">${{ number_format($room->roomType->base_price, 2) }}</span>
                        <span class="text-gray-400 text-sm">/យប់</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs font-bold uppercase">ទំនេរ</span>
                </div>

                <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">

                    <div class="grid grid-cols-2 gap-2">
                        <div class="p-3 border dark:border-gray-700 rounded-2xl">
                            <label class="block text-[10px] uppercase font-bold text-gray-400">ថ្ងៃចូល</label>
                            <input type="date" name="check_in" required class="w-full bg-transparent dark:text-white outline-none text-sm">
                        </div>
                        <div class="p-3 border dark:border-gray-700 rounded-2xl">
                            <label class="block text-[10px] uppercase font-bold text-gray-400">ថ្ងៃចេញ</label>
                            <input type="date" name="check_out" required class="w-full bg-transparent dark:text-white outline-none text-sm">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30">
                        កក់បន្ទប់ឥឡូវនេះ
                    </button>
                </form>

                <p class="text-center text-xs text-gray-400 mt-4 italic">* អ្នកនឹងមិនទាន់ត្រូវបានកាត់លុយនៅឡើយទេ</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-8">
            <div>
                <h1 class="text-4xl font-black dark:text-white mb-2">{{ $room->roomType->name }}</h1>
                <p class="flex items-center text-gray-500">
                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i> {{ $room->hotel->address }}
                </p>
            </div>

            <div class="flex gap-6 border-y dark:border-gray-800 py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-800 flex items-center justify-center rounded-xl dark:text-white">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-sm dark:text-gray-300">{{ $room->roomType->max_guests }} នាក់</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-800 flex items-center justify-center rounded-xl dark:text-white">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </div>
                    <span class="text-sm dark:text-gray-300">35 m²</span>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold dark:text-white mb-4">បរិយាយអំពីបន្ទប់</h3>
                <p class="text-gray-600 dark:text-gray-400 leading-loose">
                    {{ $room->roomType->description }}
                </p>
            </div>

            <div>
                <h3 class="text-xl font-bold dark:text-white mb-4">សេវាកម្ម និងសម្ភារៈ</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                        <i class="fas fa-wifi text-blue-500"></i> <span>Free Wi-Fi</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                        <i class="fas fa-tv text-blue-500"></i> <span>Smart TV</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">
                        <i class="fas fa-snowflake text-blue-500"></i> <span>Air Conditioning</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection