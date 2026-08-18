@extends('layouts.app')
@section('title', 'គ្មានសិទ្ធិចូលប្រើប្រាស់ (403 Forbidden)')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 bg-gray-50 dark:bg-[#0b1120] transition-colors">
    <div class="container mx-auto px-4 text-center max-w-2xl space-y-6">
        
        {{-- BADGE --}}
        <div class="relative inline-block">
            <h1 class="text-8xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-600 via-orange-600 to-slate-800 dark:from-amber-400 dark:to-orange-300 tracking-tighter select-none">
                403
            </h1>
            <div class="absolute -top-2 -right-2 w-10 h-10 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg shadow-sm backdrop-blur-md">
                <i class="fa-solid fa-user-lock"></i>
            </div>
        </div>

        {{-- TEXT CONTENT --}}
        <div class="space-y-3">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">
                លោកអ្នកគ្មានសិទ្ធិចូលប្រើប្រាស់ផ្នែកនេះទេ
            </h2>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                ទំព័រនេះត្រូវបានការពារដោយប្រព័ន្ធសុវត្ថិភាព។ លោកអ្នកតម្រូវឱ្យមានសិទ្ធិជា Admin ឬ Staff ដើម្បីចូលប្រើប្រាស់។
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
            <button onclick="window.history.back()" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-lg shadow-blue-500/25 transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-arrow-left"></i>
                <span>ត្រឡប់ទៅក្រោយ</span>
            </button>
            <a href="{{ url('/') }}" class="px-6 py-3 rounded-2xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold border border-gray-200 dark:border-gray-700 transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-house"></i>
                <span>ត្រឡប់ទៅទំព័រដើម</span>
            </a>
        </div>

    </div>
</div>
@endsection
