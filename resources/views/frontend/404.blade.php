@extends('layouts.error')
@section('title', 'រកមិនឃើញទំព័រ ៤០៤')
@section('content')

<div class="max-w-md w-full text-center bg-white p-8 rounded-2xl shadow-xl border border-slate-100">
    <div class="flex justify-center mb-6">
        <div class="bg-amber-50 p-4 rounded-full text-amber-500 animate-bounce">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z" />
            </svg>
        </div>
    </div>

    <h1 class="text-7xl font-black text-slate-800 mb-2">404</h1>
    <h2 class="text-xl font-bold text-slate-700 mb-3">រកមិនឃើញទំព័រដែលអ្នកស្នើសុំ!</h2>
    <p class="text-slate-500 text-sm leading-relaxed mb-8">
        សុំទោសផង! ទំព័រដែលអ្នកកំពុងស្វែងរកប្រហែលជាត្រូវបានលុបចោល ផ្លាស់ប្តូរឈ្មោះ ឬមិនទាន់បានបង្កើតនៅក្នុងប្រព័ន្ធប្រព័ន្ធកក់បន្ទប់ (Booking System) របស់យើងនៅឡើយទេ។
    </p>

    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors rounded-xl shadow-sm shadow-blue-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            ត្រឡប់ទៅទំព័រដើម
        </a>

        <button onclick="history.back()" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors rounded-xl">
            ថយក្រោយវិញ
        </button>
    </div>
</div>

@endsection