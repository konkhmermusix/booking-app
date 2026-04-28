@extends('layouts.app')
@section('title', 'អំពីយើង')
@section('content')

<div class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-20">
    <div class="container mx-auto px-4">

        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
                អំពីយើងខ្ញុំ <span class="text-blue-600">ភីអេនធី</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                ស្វាគមន៍មកកាន់សណ្ឋាគារ <span class="font-bold text-blue-600">ភីអេនធី</span> ដែលជាកន្លែងស្នាក់នៅដ៏ល្អឥតខ្ចោះសម្រាប់អ្នកដែលកំពុងស្វែងរកបទពិសោធន៍ស្នាក់នៅដ៏អស្ចារ្យនៅក្នុងទីក្រុងភ្នំពេញ។ យើងខ្ញុំមានបន្ទប់ទំនើបៗ និងសេវាកម្មលំដាប់ខ្ពស់ដែលត្រូវបានរចនាឡើងដើម្បីផ្តល់ជូននូវភាពងាយស្រួល និងសេវាកម្មដ៏ល្អឥតខ្ចោះសម្រាប់ភ្ញៀវជាតិ និងអន្តរជាតិ។
            </p>
            <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="container mx-auto px-4 mt-[-50px] relative z-20 mb-5">
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-20">
                <div class="space-y-6">

                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white">
                        {{ $contents['welcome_text']->title_kh ?? 'បទពិសោធន៍សម្រាកលំហែកាយ កម្រិតខ្ពស់បំផុត' }}
                    </h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ $contents['welcome_text']->content_kh ?? 'ព័ត៌មានមិនទាន់ត្រូវបានបញ្ចូល...' }}
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    {{-- បើមានរូបភាពក្នុង DB ឱ្យបង្ហាញរូបនោះ បើអត់ទេប្រើ Default Unsplash --}}
                    <img src="{{ $contents['welcome_text']->image ?? 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=2070' }}" class="rounded-3xl shadow-lg mt-8" alt="Lobby">
                    <img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?q=80&w=2070" class="rounded-3xl shadow-lg" alt="Pool">
                </div>
            </section>

            {{-- Section: Vision & Mission --}}
            <section class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-20">
                {{-- Vision Card --}}
                <div class="bg-blue-600 p-10 rounded-2xl text-white shadow-xl transform transition-hover hover:-translate-y-2">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">{{ $contents['vision']->title_kh ?? 'ចក្ខុវិស័យ (Vision)' }}</h3>
                    <p class="text-blue-50 leading-relaxed italic text-lg">
                        "{{ $contents['vision']->content_kh ?? 'មិនទាន់មានទិន្នន័យ' }}"
                    </p>
                </div>

                {{-- Mission Card --}}
                <div class="bg-slate-900 p-10 rounded-2xl text-white shadow-xl transform transition-hover hover:-translate-y-2">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-3xl mb-6">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">{{ $contents['mission']->title_kh ?? 'បេសកកម្ម (Mission)' }}</h3>
                    <p class="text-slate-300 leading-relaxed">
                        {{ $contents['mission']->content_kh ?? 'មិនទាន់មានទិន្នន័យ' }}
                    </p>
                </div>
            </section>

            {{-- Section: History Timeline --}}
            <section class="bg-gray-50 dark:bg-slate-900 rounded-2xl p-6 md:p-10 border border-gray-100 dark:border-slate-800 relative overflow-hidden mb-10">
                <div class="absolute top-0 right-0 p-10 opacity-5 text-8xl font-black italic"></div>
                <div class="max-w-3xl">
                    <h2 class="text-3xl font-black mb-8 dark:text-white italic">ប្រវត្តិរូបសង្ខេប (History)</h2>
                    <div class="space-y-8 border-l-4 border-blue-500 pl-8">
                        @forelse($histories as $history)
                        <div class="relative">
                            {{-- Dot on the timeline --}}
                            <div class="absolute -left-[38px] top-2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white dark:border-slate-900"></div>

                            <h4 class="font-bold text-blue-600 text-xl">{{ $history->year }} - {{ $history->title_kh }}</h4>
                            <p class="text-slate-600 dark:text-slate-400">{{ $history->description_kh }}</p>
                        </div>
                        @empty
                        <p class="text-slate-500 italic">មិនទាន់មានព័ត៌មានប្រវត្តិរូបសង្ខេបនៅឡើយ។</p>
                        @endforelse
                    </div>
                </div>
            </section>

            <!-- Gallary -->
            <section class="py-10 container dark:bg-[#0b1120]">
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
                        3 => "md:col-span-2 h-[250px] md:h-full",
                        4 => "md:col-span-2 md:row-span-2 h-[280px] md:h-full",
                        default => "h-[250px] md:h-full",
                        };
                        @endphp

                        <div class="relative group overflow-hidden rounded-2xl {{ $gridClass }} shadow-md">
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
                        <div class="col-span-4 py-20 text-center bg-gray-100 dark:bg-gray-800 rounded-2xl">
                            <p class="text-gray-500">មិនទាន់មានរូបភាពក្នុង Gallery ឡើយ</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection