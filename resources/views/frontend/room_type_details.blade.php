@extends('layouts.app')
@section('title', 'លម្អិតប្រភេទបន្ទប់')

@section('content')

<div class="bg-gray-50 dark:bg-[#0b1120] min-h-screen pb-20 font-['Kantumruy_Pro']">

    <div class="relative bg-white dark:bg-gray-900 shadow-sm border-b dark:border-gray-800">
        <div class="container mx-auto px-4 py-8">
            <nav class="flex mb-4 text-sm text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-blue-600">ទំព័រដើម</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-gray-300">{{ $roomType->name }}</span>
            </nav>
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
                {{ $roomType->name }}
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-[500px]">
                <div class="md:col-span-2 h-full overflow-hidden rounded-3xl group">
                    @php $mainImg = $roomType->images->where('is_primary', true)->first() ?? $roomType->images->first(); @endphp
                    <img src="{{ asset('storage/' . $mainImg->image_path) }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                </div>
                <div class="md:col-span-2 grid grid-cols-2 gap-4 h-full">
                    @foreach($roomType->images->where('id', '!=', $mainImg->id)->take(4) as $img)
                    <div class="h-full overflow-hidden rounded-2xl group">
                        <img src="{{ asset('storage/' . $img->image_path) }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 mt-12">
        <div class="flex flex-col lg:flex-row gap-12">

            <div class="lg:w-2/3">
                <div class="bg-white dark:bg-gray-900 p-8 rounded-[2.5rem] shadow-sm mb-8">
                    <h2 class="text-2xl font-bold mb-6 dark:text-white">អំពីប្រភេទបន្ទប់នេះ</h2>
                    <p class="text-gray-600 dark:text-gray-400 leading-loose mb-8 text-lg">
                        {{ $roomType->description ?? 'សូមរីករាយជាមួយការស្នាក់នៅដ៏មានផាសុកភាព ជាមួយរចនាប័ទ្មបែបទំនើប និងគ្រឿងបរិក្ខារគ្រប់បែបយ៉ាងដែលលោកអ្នកត្រូវការ។' }}
                    </p>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 py-8 border-y dark:border-gray-800">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-600 mb-2">
                                <i class="fas fa-bed"></i>
                            </div>
                            <span class="text-sm font-bold dark:text-gray-300">{{ $roomType->beds }} គ្រែ</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-green-50 dark:bg-green-900/30 rounded-full flex items-center justify-center text-green-600 mb-2">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="text-sm font-bold dark:text-gray-300">{{ $roomType->capacity }} នាក់</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/30 rounded-full flex items-center justify-center text-orange-600 mb-2">
                                <i class="fas fa-expand"></i>
                            </div>
                            <span class="text-sm font-bold dark:text-gray-300">{{ $roomType->size }} m²</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 bg-purple-50 dark:bg-purple-900/30 rounded-full flex items-center justify-center text-purple-600 mb-2">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <span class="text-sm font-bold dark:text-gray-300">Free WiFi</span>
                        </div>
                    </div>
                </div>

                <h2 class="text-2xl font-bold mb-6 dark:text-white">ជ្រើសរើសបន្ទប់ដែលមានទំនេរ</h2>
                <div class="space-y-4">
                    @foreach($roomType->rooms as $room)
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl flex items-center justify-between border border-transparent hover:border-blue-500 transition shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-900 text-white rounded-xl flex items-center justify-center font-bold">
                                {{ $room->room_number }}
                            </div>
                            <div>
                                <h4 class="font-bold dark:text-white">ជាន់ទី {{ $room->floor }}</h4>
                                <span class="text-xs text-green-500"><i class="fas fa-circle text-[8px] mr-1"></i> ទំនេរ</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xl font-black text-blue-600 mb-2">${{ $roomType->base_price }}<span class="text-xs text-gray-500">/យប់</span></div>
                            <a href="#" class="inline-block bg-gray-900 dark:bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-600 transition">កក់បន្ទប់នេះ</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="lg:w-1/3">
                <div class="sticky top-28 bg-blue-900 text-white p-8 rounded-[2.5rem] shadow-xl">
                    <h3 class="text-xl font-bold mb-4">ព័ត៌មានសង្ខេប</h3>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between border-b border-white/10 pb-2">
                            <span class="opacity-70">តម្លៃគោល</span>
                            <span class="font-bold">${{ $roomType->base_price }}</span>
                        </div>
                        <div class="flex justify-between border-b border-white/10 pb-2">
                            <span class="opacity-70">ប្រភេទ</span>
                            <span class="font-bold">{{ $roomType->name }}</span>
                        </div>
                    </div>
                    <p class="text-sm opacity-70 mb-6 italic">* តម្លៃអាចនឹងប្រែប្រួលតាមរដូវកាល និងការចុះឈ្មោះកក់ទុកមុន។</p>
                    <button class="w-full bg-white text-blue-900 py-4 rounded-2xl font-black hover:bg-blue-50 transition shadow-lg">
                        សាកសួរព័ត៌មានបន្ថែម
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection