@extends('layouts.app')
@section('title', 'អំពីយើង')
@section('content')

<header class="group relative h-[55vh] w-full overflow-hidden flex items-center justify-center rounded-xl bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 transition-all duration-500 ease-in-out cursor-default">
    <div class="absolute inset-0 z-0 animate-grid-move opacity-60 dark:opacity-20 will-change-transform"
        style="background-image: linear-gradient(to right, #cfdae1 1px, transparent 1px), linear-gradient(to bottom, #fbd5e1 1px, transparent 1px); background-size: 80px 80px;">
    </div>

    <div class="absolute inset-0 z-[1] backdrop-blur-[2px] bg-[radial-gradient(circle_at_center,transparent_30%,rgba(255,255,255,0.9)_100%)] dark:bg-[radial-gradient(circle_at_center,transparent_30%,rgba(2,6,23,0.95)_100%)]"></div>

    <div class="relative z-10 text-center px-4">
        <h4 class="text-4xl md:text-4xl font-black mb-4 text-pnt-blue dark:text-white tracking-tight transition-all duration-500 ease-in-out group-hover:scale-105 group-hover:text-[#9e8efc] group-hover:drop-shadow-[0_0_20px_rgba(107,218,225,0.5)]">
            អំពីយើង
        </h4>
    </div>
</header>

<div class="container mx-auto px-4 mt-[-50px] relative z-20 mb-20">
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
        <div class="bg-blue-600 p-10 rounded-[1.5rem] text-white shadow-xl transform transition-hover hover:-translate-y-2">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mb-6">
                <i class="fas fa-eye"></i>
            </div>
            <h3 class="text-2xl font-bold mb-4">{{ $contents['vision']->title_kh ?? 'ចក្ខុវិស័យ (Vision)' }}</h3>
            <p class="text-blue-50 leading-relaxed italic text-lg">
                "{{ $contents['vision']->content_kh ?? 'មិនទាន់មានទិន្នន័យ' }}"
            </p>
        </div>

        {{-- Mission Card --}}
        <div class="bg-slate-900 p-10 rounded-[1.5rem] text-white shadow-xl transform transition-hover hover:-translate-y-2">
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
    <section class="bg-gray-50 dark:bg-slate-900 rounded-[1.5rem] p-10 md:p-16 border border-gray-100 dark:border-slate-800 relative overflow-hidden mb-20">
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
</div>

@endsection