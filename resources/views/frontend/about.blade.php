@extends('layouts.app')
@section('title', 'អំពីយើង')
@section('content')

<div class="container mx-auto">
    <div class="pt-20 text-center mb-30 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            អំពីយើង <span class="text-blue-600">ភីអេនធី</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            ស្វាគមន៍មកកាន់សណ្ឋាគារ ភីអេនធី ដែលជាទីតាំងដ៏ស័ក្តិសមបំផុតសម្រាប់ការសម្រាកលំហែកាយ។ យើងផ្តល់ជូននូវបទពិសោធន៍ស្នាក់នៅដ៏ប្រណីត ជាមួយបន្ទប់ដែលមានផាសុកភាពខ្ពស់ និងសេវាកម្មប្រកបដោយបដិសណ្ឋារកិច្ចយ៉ាងកក់ក្ដៅបំផុត ដើម្បីធ្វើឱ្យរាល់ការធ្វើដំណើររបស់លោកអ្នកក្លាយជាការចងចាំមិនអាចបំភ្លេចបាន។
        </p>
        <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
    </div>

    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white">
                        {{ $contents['welcome_text']->title_kh ?? 'មិនទាន់មានទិន្នន័យ' }}
                    </h2>
                    <p class="text-lg text-slate-600 dark:text-slate-400 leading-relaxed">
                        {{ $contents['welcome_text']->content_kh ?? 'មិនទាន់មានទិន្នន័យ' }}
                    </p>
                </div>
                <div class="flex justify-center items-center">
                    @if(isset($contents['welcome_text']) && $contents['welcome_text']->image)
                    <img src="{{ asset('storage/' . $contents['welcome_text']->image) }}"
                        class="rounded-2xl shadow-2xl w-full h-auto object-cover"
                        alt="{{ $contents['welcome_text']->title_kh ?? 'Welcome Image' }}">
                    @else
                    <img src=""
                        class="rounded-2xl shadow-2xl w-full h-auto object-cover"
                        alt="មិនទាន់មានទិន្នន័យ">
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Vision & Mission --}}
    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-blue-600 p-10 rounded-2xl text-white shadow-xl transform transition-hover hover:-translate-y-2">
                    <h3 class="text-2xl font-bold mb-4">{{ $contents['vision']->title_kh ?? 'ចក្ខុវិស័យ' }}</h3>
                    <p class="text-blue-50 leading-relaxed italic text-lg">
                        "{{ $contents['vision']->content_kh ?? 'មិនទាន់មានទិន្នន័យ' }}"
                    </p>
                </div>

                <div class="bg-yellow-600 p-10 rounded-2xl text-white shadow-xl transform transition-hover hover:-translate-y-2">
                    <h3 class="text-2xl font-bold mb-4">{{ $contents['mission']->title_kh ?? 'បេសកកម្ម' }}</h3>
                    <p class="text-blue-50 leading-relaxed italic text-lg">
                        "{{ $contents['mission']->content_kh ?? 'មិនទាន់មានទិន្នន័យ' }}"
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- History --}}
    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                    <h4 class="italic text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                        ប្រវត្តិរូបសង្ខេប
                    </h4>
                </div>
            </div>

            <div class="space-y-8 border-l-4 border-blue-600 pl-8">
                @forelse($histories as $history)
                <div class="relative">
                    <div class="absolute -left-[38px] top-2 w-4 h-4 bg-blue-600 rounded-full border-4 border-white dark:border-slate-900"></div>

                    <h4 class="font-bold text-blue-600 text-xl">{{ $history->year ?? 'មិនទាន់មានទិន្នន័យ' }} - {{ $history->title_kh ?? 'មិនទាន់មានទិន្នន័យ' }}</h4>
                    <p class="text-slate-600 dark:text-slate-400">{{ $history->description_kh ?? 'មិនទាន់មានទិន្នន័យ' }}</p>
                </div>
                @empty
                <p class="text-slate-500 italic">មិនទាន់មានព័ត៌មានប្រវត្តិរូបសង្ខេបនៅឡើយ។</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- gallary --}}
    <section class="py-10 dark:bg-[#0b1120]">
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
</div>


@endsection