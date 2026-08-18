@extends('layouts.app')
@section('title', 'កំពុងថែទាំប្រព័ន្ធ (503 Maintenance Mode)')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 bg-gray-50 dark:bg-[#0b1120] transition-colors">
    <div class="container mx-auto px-4 text-center max-w-2xl space-y-6">
        
        {{-- BADGE --}}
        <div class="relative inline-block">
            <h1 class="text-8xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-slate-800 dark:from-indigo-400 dark:to-purple-300 tracking-tighter select-none">
                503
            </h1>
            <div class="absolute -top-2 -right-2 w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg shadow-sm backdrop-blur-md animate-spin-slow">
                <i class="fa-solid fa-gears"></i>
            </div>
        </div>

        {{-- TEXT CONTENT --}}
        <div class="space-y-3">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">
                ប្រព័ន្ធកំពុងអភិវឌ្ឍ និងថែទាំជាបណ្តោះអាសន្ន
            </h2>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                យើងខ្ញុំកំពុងធ្វើការអភិវឌ្ឍ និងថែទាំប្រព័ន្ធដើម្បីបង្កើនប្រសិទ្ធភាព និងសុវត្ថិភាពសេវាកម្ម។ សូមចូលមកពិនិត្យម្តងទៀតនៅពេលបន្តិចទៀតនេះ។
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
            <button onclick="window.location.reload()" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-lg shadow-blue-500/25 transition-all active:scale-95 flex items-center gap-2 cursor-pointer">
                <i class="fa-solid fa-rotate-right"></i>
                <span>ពិនិត្យឡើងវិញ (Refresh)</span>
            </button>
        </div>

    </div>
</div>
@endsection
