@extends('layouts.error')
@section('title', 'រកមិនឃើញទំព័រ - 404 Not Found')

@section('content')
<div class="flex flex-center items-center justify-center px-4 transition-colors duration-300">
    <div class="max-w-md w-full text-center space-y-6 animate-fade-in">

        <div class="relative">
            <h1 class="text-9xl font-black text-blue-600/50 dark:text-blue-500 tracking-widest select-none">
                404
            </h1>
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fa-solid fa-compass-drafting text-6xl text-blue-600 dark:text-blue-500 animate-bounce"></i>
            </div>
        </div>

        <div class="space-y-2">
            <h2 class="text-2xl font-black text-gray-900 dark:text-white md:text-3xl font-សៀមរាប">
                រកមិនឃើញទំព័រឡើយ!
            </h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed max-w-sm mx-auto">
                ទំព័រដែលលោកអ្នកកំពុងស្វែងរកប្រហែលជាត្រូវបានលុប ផ្លាស់ប្តូរ។
            </p>
        </div>

        <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ url('/') }}"
                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-lg shadow-blue-600/15 active:scale-[0.98] transition-all">
                <span>ត្រឡប់ទៅទំព័រដើម</span>
            </a>

            <button onclick="window.history.back()"
                class="w-full inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold active:scale-[0.98] transition-all">
                <span>ថយក្រោយវិញ</span>
            </button>
        </div>
    </div>
</div>