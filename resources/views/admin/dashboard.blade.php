@extends('layouts.admin')
@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">ចំណូលសរុប (ខែនេះ)</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white">$12,450.00</h3>
            </div>
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-600 transition-transform group-hover:scale-110">
                <i class="fa-solid fa-money-bill-trend-up text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-[10px] font-bold text-emerald-600">+12.5%</span>
            <span class="text-[10px] text-gray-400 font-bold uppercase italic">ធៀបនឹងខែមុន</span>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">ការកក់សរុប</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white">128</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 transition-transform group-hover:scale-110">
                <i class="fa-solid fa-calendar-check text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-500/20 text-[10px] font-bold text-blue-600">Active</span>
            <span class="text-[10px] text-gray-400 font-bold uppercase italic">កក់ក្នុងប្រព័ន្ធ</span>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">បន្ទប់ទំនេរ</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white">15 / 45</h3>
            </div>
            <div class="w-12 h-12 bg-orange-50 dark:bg-orange-500/10 rounded-2xl flex items-center justify-center text-orange-600 transition-transform group-hover:scale-110">
                <i class="fa-solid fa-door-open text-xl"></i>
            </div>
        </div>
        <div class="mt-4 w-full bg-gray-100 dark:bg-gray-800 rounded-full h-1.5 overflow-hidden">
            <div class="bg-orange-500 h-full w-[33%]"></div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-6 rounded-[1.5rem] shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-md transition-all group">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">ការវាយតម្លៃ</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white">4.8 / 5</h3>
            </div>
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 transition-transform group-hover:scale-110">
                <i class="fa-solid fa-star text-xl"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-amber-500 text-[10px]">
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star-half-stroke"></i>
            <span class="ml-2 text-gray-400 font-bold uppercase italic">98 នាក់</span>
        </div>
    </div>
</div>



<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white dark:bg-gray-900 p-6 rounded-[1.5rem] border border-gray-100 dark:border-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-black text-sm uppercase tracking-wider dark:text-white">និន្នាការចំណូលប្រចាំឆ្នាំ</h4>
            <select class="text-[10px] font-bold bg-gray-50 dark:bg-gray-800 border-none rounded-xl px-4 py-2 uppercase">
                <option>ឆ្នាំ ២០២៤</option>
                <option>ឆ្នាំ ២០២៣</option>
            </select>
        </div>
        <div class="h-[300px] flex items-center justify-center bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-100 dark:border-gray-800">
            <p class="text-[10px] font-black text-gray-400 uppercase italic">ក្រាហ្វិកចំណូល (Chart.js / ApexCharts)</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 p-6 rounded-[1.5rem] border border-gray-100 dark:border-gray-800">
        <h4 class="font-black text-sm uppercase tracking-wider mb-6 dark:text-white">ស្ថានភាពបន្ទប់ថ្ងៃនេះ</h4>
        <div class="space-y-4">
            @php
            $statuses = [
            ['label' => 'ទំនេរ (Available)', 'count' => 15, 'color' => 'bg-emerald-500', 'percent' => '33%'],
            ['label' => 'មានភ្ញៀវ (Occupied)', 'count' => 25, 'color' => 'bg-blue-500', 'percent' => '55%'],
            ['label' => 'កំពុងសម្អាត (Cleaning)', 'count' => 5, 'color' => 'bg-amber-500', 'percent' => '12%'],
            ];
            @endphp
            @foreach($statuses as $status)
            <div class="space-y-2">
                <div class="flex justify-between text-[10px] font-black uppercase tracking-tight">
                    <span class="text-gray-500">{{ $status['label'] }}</span>
                    <span class="dark:text-white">{{ $status['count'] }} បន្ទប់</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-gray-800 rounded-full h-2">
                    <div class="{{ $status['color'] }} h-full rounded-full" style="width: {{ $status['percent'] }}"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


<div class="bg-white dark:bg-gray-900 rounded-[1.5rem] border border-gray-100 dark:border-gray-800 overflow-hidden">
    <div class="px-7 py-5 border-b dark:border-gray-800 flex justify-between items-center">
        <h4 class="font-black text-sm uppercase tracking-wider dark:text-white">ការកក់ដែលទើបចូលថ្មី</h4>
        <a href="#" class="text-[10px] font-black text-blue-500 uppercase hover:underline">មើលទាំងអស់</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-800/50">
                <tr>
                    <th class="px-7 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">ភ្ញៀវ</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">ប្រភេទបន្ទប់</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest">ថ្ងៃចូល - ចេញ</th>
                    <th class="px-7 py-4 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">ស្ថានភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-800 text-xs">
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-7 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center font-black text-blue-600">S</div>
                            <span class="font-bold dark:text-white">សេង ហ៊ុយ</span>
                        </div>
                    </td>
                    <td class="px-7 py-4 font-bold text-gray-500 dark:text-gray-400 uppercase italic">Deluxe King</td>
                    <td class="px-7 py-4 font-bold text-gray-500">20/03 - 22/03</td>
                    <td class="px-7 py-4 text-center">
                        <span class="px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 font-black text-[9px] uppercase">ជោគជ័យ</span>
                    </td>
                </tr>
            </tbody>
        </table>
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