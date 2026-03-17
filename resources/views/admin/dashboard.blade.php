@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border dark:border-slate-700 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">សណ្ឋាគារសរុប</p>
            <h3 class="text-2xl font-bold">128</h3>
        </div>
        <div class="p-3 bg-blue-100 dark:bg-blue-900/40 rounded-xl text-blue-600 dark:text-blue-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border dark:border-slate-700 flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">ការកក់ថ្មី</p>
            <h3 class="text-2xl font-bold">+24</h3>
        </div>
        <div class="p-3 bg-green-100 dark:bg-green-900/40 rounded-xl text-green-600 dark:text-green-400">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>

</div>

<div class="mt-8 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border dark:border-slate-700 overflow-hidden">
    <div class="p-6 border-b dark:border-slate-700">
        <h4 class="font-bold text-lg">ការកក់បន្ទប់ចុងក្រោយ</h4>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700/50">
                <tr class="text-xs uppercase text-gray-500 dark:text-gray-400">
                    <th class="p-4">ឈ្មោះអតិថិជន</th>
                    <th class="p-4">សណ្ឋាគារ</th>
                    <th class="p-4">ស្ថានភាព</th>
                    <th class="p-4">តម្លៃ</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-slate-700">
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30">
                    <td class="p-4 font-medium">Leav Sis</td>
                    <td class="p-4">Sokha Hotel</td>
                    <td class="p-4"><span class="px-2 py-1 text-xs bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-full">ជោគជ័យ</span></td>
                    <td class="p-4 font-bold">$45.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection