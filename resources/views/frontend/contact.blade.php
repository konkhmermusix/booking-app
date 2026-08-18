@extends('layouts.app')
@section('title', 'ទំនាក់ទំនង')
@section('content')

<div class="mx-auto">
    {{-- PAGE HEADER (MATCHING ABOUT PAGE EXACTLY) --}}
    <div class="pt-20 text-center mb-30 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            ទំនាក់ទំនងមក <span class="text-blue-600">ភីអេនធី</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            ទំនាក់ទំនងមកយើងតាមរយៈលេខទូរស័ព្ទ អ៊ីមែល ឬបំពេញទម្រង់ផ្ញើសារខាងក្រោម។ <br>
            ក្រុមការងារយើងនឹងទាក់ទងត្រឡប់ទៅលោកអ្នកវិញឱ្យបានឆាប់បំផុតតាមដែលអាចធ្វើទៅបាន។
        </p>
        <div class="h-1.5 w-30 bg-blue-600 mx-auto mt-6 rounded-full"></div>
    </div>

    {{-- MAIN CONTENT SECTION (MATCHING ABOUT PAGE EXACTLY) --}}
    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- LEFT: CONTACT INFO --}}
                <div class="bg-white space-y-3 dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 p-4 md:p-6">
                    <div class="mb-10">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                                <h4 class="text-2xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                                    ព័ត៌មានទំនាក់ទំនង
                                </h4>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">ប្រសិនបើអ្នកមានចម្ងល់ ឬចង់សាកសួរព័ត៌មានបន្ថែម សូមចុចលើបណ្ដាញសង្គមដើម្បីទំនាក់ទំនង</p>
                    </div>

                    @forelse($contacts as $item)
                    @php
                        $keyLower = strtolower($item->key ?? '');
                        $labelLower = strtolower($item->label ?? '');
                        $url = null;
                        if ($keyLower == 'phone' || str_contains($labelLower, 'phone') || str_contains($labelLower, 'ទូរស័ព្ទ')) {
                            $url = 'tel:' . preg_replace('/[^\d+]/', '', $item->value);
                        } elseif ($keyLower == 'email' || str_contains($labelLower, 'email') || str_contains($labelLower, 'អ៊ីមែល')) {
                            $url = 'mailto:' . trim($item->value);
                        } elseif (str_starts_with($item->value, 'http://') || str_starts_with($item->value, 'https://')) {
                            $url = $item->value;
                        }
                    @endphp

                    @if($url)
                    <a href="{{ $url }}" target="{{ str_starts_with($url, 'http') ? '_blank' : '_self' }}" class="block group">
                    @else
                    <div class="block group">
                    @endif
                        <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md p-4 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-5 hover:shadow-xl hover:border-blue-500/50 transition-all">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl group-hover:scale-110 transition-transform shrink-0">
                                <i class="fas {{ $item->icon }}"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $item->label }}</h4>
                                <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $item->value }}</p>
                            </div>
                        </div>
                    @if($url)
                    </a>
                    @else
                    </div>
                    @endif
                    @empty
                    <p class="text-slate-400 text-sm">មិនទាន់មានព័ត៌មានទំនាក់ទំនង។</p>
                    @endforelse
                </div>

                {{-- RIGHT: CONTACT FORM --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 p-8 md:p-10">
                    <div class="mb-10">
                        <div class="flex justify-between items-center mb-3">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                                <h4 class="text-2xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tracking-tight">
                                    ផ្ញើសារមកយើង
                                </h4>
                            </div>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">ប្រសិនបើអ្នកមានចម្ងល់ ឬចង់សាកសួរព័ត៌មានបន្ថែម សូមបំពេញទម្រង់ខាងក្រោម។</p>
                    </div>

                    <div x-data="{ loading: false }">
                        <form id="contactForm" action="{{ route('frontend.contact') }}" method="POST" class="space-y-6" x-on:submit="loading = true">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">ឈ្មោះពេញ *</label>
                                    <div class="relative">
                                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="name" value="{{ old('name') }}" placeholder="ឈ្មោះរបស់អ្នក" required
                                            class="w-full h-[52px] pl-12 pr-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                                    </div>
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">អ៊ីមែល</label>
                                    <div class="relative">
                                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="email" name="email" value="{{ old('email') }}" placeholder="example@gmail.com"
                                            class="w-full h-[52px] pl-12 pr-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                                    </div>
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">លេខទូរស័ព្ទ *</label>
                                <div class="relative">
                                    <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="tell" value="{{ old('tell') }}" placeholder="012 345 678" required
                                        class="w-full h-[52px] pl-12 pr-4 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all">
                                </div>
                                @error('tell') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300 ml-1">
                                    សាររបស់អ្នក <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-comment-alt absolute left-4 top-4 text-slate-400"></i>
                                    <textarea name="description" rows="5" required placeholder="សរសេរសាររបស់អ្នកនៅទីនេះ..."
                                        class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all resize-none">{!! old('description') !!}</textarea>
                                </div>
                                @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="group w-full md:w-max bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all active:scale-95 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-70"
                                    x-bind:disabled="loading">

                                    <svg x-show="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>

                                    <span x-text="loading ? 'កំពុងបញ្ជូន...' : 'ផ្ញើសារឥឡូវនេះ'"></span>
                                    <i x-show="!loading" class="fas fa-paper-plane text-sm transition-transform group-hover:translate-x-1"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- GOOGLE MAPS SECTION (MATCHING ABOUT PAGE STYLE) --}}
    <section class="py-10 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            @if(isset($mapData) && $mapData)
            <div class="group relative rounded-2xl overflow-hidden shadow-2xl border-8 border-white dark:border-slate-800 h-[450px]">
                <iframe src="{{ $mapData->value }}"
                    class="w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <div class="absolute bottom-4 left-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur px-4 py-2 rounded-xl text-xs font-bold shadow-lg text-slate-800 dark:text-white">
                    {{ $mapData->label }}
                </div>
            </div>
            @endif
        </div>
    </section>
</div>

@endsection