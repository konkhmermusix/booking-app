@extends('layouts.app')
@section('title', 'រកមិនឃើញទំព័រ (404 Not Found)')

@section('content')
<div class="min-h-[75vh] flex items-center justify-center py-16 bg-gray-50 dark:bg-[#0b1120] transition-colors">
    <div class="container mx-auto px-4 text-center max-w-2xl space-y-6">
        
        {{-- BADGE --}}
        <div class="relative inline-block">
            <h1 class="text-8xl md:text-9xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-slate-800 dark:from-blue-400 dark:to-indigo-300 tracking-tighter select-none">
                404
            </h1>
            <div class="absolute -top-2 -right-2 w-10 h-10 rounded-2xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg shadow-sm backdrop-blur-md animate-bounce">
                <i class="fa-solid fa-compass"></i>
            </div>
        </div>

        {{-- TEXT CONTENT --}}
        <div class="space-y-3">
            <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white">
                សុំទោស! រកមិនឃើញទំព័រដែលអ្នកកំពុងស្វែងរកទេ
            </h2>
            <p class="text-xs md:text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                ទំព័រនេះអាចត្រូវ​បាន​លុបចេញ ផ្លាស់ប្តូរឈ្មោះ ឬមិនទាន់មានក្នុងប្រព័ន្ធឡើយ។ សូមពិនិត្យមើលអាសយដ្ឋាន URL ឡើងវិញ។
            </p>
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="pt-4 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ url('/') }}" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-xs font-bold shadow-lg shadow-blue-500/25 transition-all active:scale-95 flex items-center gap-2">
                <i class="fa-solid fa-house"></i>
                <span>ត្រឡប់ទៅទំព័រដើម</span>
            </a>
            <a href="{{ route('frontend.contact') }}" class="px-6 py-3 rounded-2xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold border border-gray-200 dark:border-gray-700 transition shadow-xs flex items-center gap-2">
                <i class="fa-solid fa-headset"></i>
                <span>ទាក់ទងជំនួយ</span>
            </a>
        </div>

    </div>
</div>
@endsection
