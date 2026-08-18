@extends('layouts.app')
@section('title', 'កំហុសប្រព័ន្ធម៉ាស៊ីនបម្រើ (500 Server Error)')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 bg-gray-50 dark:bg-[#0b1120] transition-colors">
    <div class="container mx-auto px-4 text-center max-w-2xl space-y-6">
        
        {{-- BADGE --}}
        <div class="relative inline-block">
            <h1 class="text-8xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-rose-600 via-amber-600 to-slate-800 dark:from-rose-400 dark:to-amber-300 tracking-tighter select-none">
                500
            </h1>
            <div class="absolute -top-2 -right-2 w-10 h-10 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg shadow-sm backdrop-blur-md animate-pulse">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        {{-- TEXT CONTENT --}}
        <div class="space-y-3">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">
                សុំទោស! មានបញ្ហាបច្ចេកទេសបណ្តោះអាសន្ន
            </h2>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                ម៉ាស៊ីនបម្រើ (Server) កំពុងជួបប្រទះបញ្ហាបច្ចេកទេសបណ្តោះអាសន្ន។ ក្រុមការងារបច្ចេកវិទ្យារបស់យើងត្រូវបានជូនដំណឹង និងកំពុងដោះស្រាយយ៉ាងសកម្ម។
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-lg shadow-blue-500/25 transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-rotate-right"></i>
                <span>ព្យាយាមទាញឡើងវិញ (Reload)</span>
            </button>
            <a href="{{ url('/') }}" class="px-6 py-3 rounded-2xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold border border-gray-200 dark:border-gray-700 transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-house"></i>
                <span>ត្រឡប់ទៅទំព័រដើម</span>
            </a>
        </div>

    </div>
</div>
@endsection
