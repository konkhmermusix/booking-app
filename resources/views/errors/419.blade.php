@extends('layouts.app')
@section('title', 'ផុតកំណត់សេសសិន (419 Page Expired)')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 bg-gray-50 dark:bg-[#0b1120] transition-colors">
    <div class="container mx-auto px-4 text-center max-w-2xl space-y-6">
        
        {{-- BADGE --}}
        <div class="relative inline-block">
            <h1 class="text-8xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 via-blue-600 to-slate-800 dark:from-cyan-400 dark:to-blue-300 tracking-tighter select-none">
                419
            </h1>
            <div class="absolute -top-2 -right-2 w-10 h-10 rounded-2xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center text-lg shadow-sm backdrop-blur-md">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        {{-- TEXT CONTENT --}}
        <div class="space-y-3">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">
                ទំព័រនេះបានផុតកំណត់សេសសិន (Page Expired)
            </h2>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                ដោយសារលោកអ្នកទុកទំព័រនេះចោលយូរពេក សេសសិនសុវត្ថិភាពត្រូវបានផុតកំណត់។ សូមចុចប៊ូតុងខាងក្រោមដើម្បីទាញទំព័រឡើងវិញ។
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-lg shadow-blue-500/25 transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-rotate-right"></i>
                <span>ទាញទំព័រឡើងវិញ (Reload Page)</span>
            </button>
            <a href="{{ url('/') }}" class="px-6 py-3 rounded-2xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold border border-gray-200 dark:border-gray-700 transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-house"></i>
                <span>ត្រឡប់ទៅទំព័រដើម</span>
            </a>
        </div>

    </div>
</div>
@endsection
