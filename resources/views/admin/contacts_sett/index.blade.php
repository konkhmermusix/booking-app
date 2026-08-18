@extends('layouts.admin')

@section('title', 'គ្រប់គ្រងការកំណត់ និងអត្រាប្តូរប្រាក់')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    showDetailModal: false, 
    currentSetting: {},
    usdInput: 1,
    exchangeRate: {{ $khrRate }}
}">

    <!-- Top Section: Dynamic Exchange Rate Card (អត្រាប្តូរប្រាក់រៀល) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Main Exchange Rate Banner Card -->
        <div class="lg:col-span-2 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 text-white rounded-2xl p-6 shadow-lg relative overflow-hidden flex flex-col justify-between">
            <div class="absolute -right-6 -bottom-6 text-white/10 text-9xl pointer-events-none font-black">
                ៛
            </div>

            <div class="flex justify-between items-start z-10">
                <div>
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-full">
                        <i class="fa-solid fa-bolt text-amber-300 mr-1"></i> Dynamic Exchange Rate
                    </span>
                    <h3 class="text-2xl font-black mt-2 tracking-tight">អត្រាប្តូរប្រាក់រៀល (USD ➔ KHR)</h3>
                    <p class="text-xs text-emerald-100 mt-0.5">កំណត់តម្លៃលុយខ្មែរសម្រាប់ប្រើប្រាស់ទូទាំងប្រព័ន្ធ</p>
                </div>

                @php
                    $khrItem = $settings->first(function($s) {
                        return in_array(strtolower($s->key), ['khr_rate', 'exchange_rate', 'usd_khr_rate', 'riel_rate']);
                    });
                @endphp

                @if($khrItem)
                <button @click="currentSetting = {{ json_encode($khrItem) }}; showEditModal = true" 
                    class="px-4 py-2 bg-white text-emerald-700 hover:bg-emerald-50 rounded-xl font-bold text-xs shadow-md transition-all active:scale-95 flex items-center gap-1.5 z-10 shrink-0">
                    <i class="fa-solid fa-pen-to-square"></i> កែសម្រួលអត្រា
                </button>
                @else
                <button @click="currentSetting = { key: 'khr_rate', label: 'អត្រាប្តូរប្រាក់ (USD ទៅ KHR)', value: '4100', icon: 'fa-solid fa-coins', color: 'emerald', status: 1 }; showAddModal = true" 
                    class="px-4 py-2 bg-white text-emerald-700 hover:bg-emerald-50 rounded-xl font-bold text-xs shadow-md transition-all active:scale-95 flex items-center gap-1.5 z-10 shrink-0">
                    <i class="fa-solid fa-plus-circle"></i> កំណត់អត្រាប្តូរប្រាក់
                </button>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 z-10">
                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20">
                    <p class="text-[10px] uppercase font-bold text-emerald-200 tracking-wider">អត្រាផ្លូវការបច្ចុប្បន្ន (Current Rate)</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-3xl font-black font-mono">1 $ =</span>
                        <span class="text-3xl font-black font-mono text-amber-300">{{ number_format($khrRate) }} ៛</span>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-md rounded-xl p-4 border border-white/20 flex flex-col justify-center">
                    <p class="text-[10px] uppercase font-bold text-emerald-200 tracking-wider">ស្ថានភាពប្រព័ន្ធ (Status)</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-300 animate-pulse"></span>
                        <span class="text-sm font-bold">ដំណើរការ Dynamic ស្វ័យប្រវត្តិ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Currency Converter Tool -->
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-800 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-black text-gray-800 dark:text-white text-base flex items-center gap-2">
                        <i class="fa-solid fa-calculator text-blue-500"></i>
                        គណនាប្រាក់រហ័ស
                    </h4>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Calculator</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase mb-1">ចំនួនប្រាក់ដុល្លារ ($ USD)</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-gray-400 text-sm">$</span>
                            <input type="number" min="0" step="0.01" x-model.number="usdInput"
                                class="w-full pl-8 pr-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white font-bold outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>
                    </div>

                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 rounded-xl border border-emerald-100 dark:border-emerald-900/40">
                        <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-1">លទ្ធផលជាលុយខ្មែរ (KHR)</p>
                        <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono" x-text="(usdInput * exchangeRate).toLocaleString('en-US') + ' ៛'"></p>
                    </div>
                </div>
            </div>

            <p class="text-[10px] text-gray-400 mt-4 text-center">រាល់តម្លៃកក់ក្នុងប្រព័ន្ធនឹងបំប្លែងស្វ័យប្រវត្តិតាមអត្រានេះ</p>
        </div>
    </div>

    <!-- Header Control Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                គ្រប់គ្រងព័ត៌មានទំនាក់ទំនង & កំណត់ប្រព័ន្ធ
            </h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">System Settings, Phone Numbers, Social Links & Exchange Rate</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <button @click="currentSetting = {}; showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមព័ត៌មានថ្មី
            </button>
        </div>
    </div>

    <!-- Settings Data Table -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/70 dark:bg-gray-800/50 text-gray-400 dark:text-gray-400 text-xs uppercase font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">ព័ត៌មាន / Label</th>
                    <th class="px-6 py-4">តម្លៃ (Value)</th>
                    <th class="px-6 py-4">រូបតំណាង (Icon)</th>
                    <th class="px-6 py-4 text-center">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($settings as $item)
                <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg border shrink-0
                            {{ $item->color == 'emerald' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 border-emerald-100 dark:border-emerald-500/20' : 
                            ($item->color == 'red' ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 border-red-100 dark:border-red-500/20' : 
                            ($item->color == 'amber' ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 border-amber-100 dark:border-amber-500/20' : 
                            ($item->color == 'purple' ? 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400 border-purple-100 dark:border-purple-500/20' : 
                            'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 border-blue-100 dark:border-blue-500/20'))) }}">

                                <i class="{{ $item->icon ?? 'fas fa-info-circle' }}"></i>
                            </div>

                            <div>
                                <div class="font-bold text-sm text-gray-800 dark:text-white flex items-center gap-2">
                                    {{ $item->label }}
                                    @if(in_array(strtolower($item->key), ['khr_rate', 'exchange_rate', 'usd_khr_rate', 'riel_rate']))
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">Exchange Rate</span>
                                    @endif
                                </div>
                                <div class="text-[10px] text-gray-400 uppercase font-mono tracking-wider">Key: {{ $item->key }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-xs truncate text-xs font-bold text-gray-700 dark:text-gray-200">
                            @if(in_array(strtolower($item->key), ['khr_rate', 'exchange_rate', 'usd_khr_rate', 'riel_rate']))
                            <span class="text-emerald-600 dark:text-emerald-400 font-mono text-sm">1$ = {{ number_format((float)$item->value) }} ៛</span>
                            @else
                            {{ $item->value }}
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-xs truncate text-xs font-mono text-gray-500 dark:text-gray-400">
                            {{ $item->icon }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($item->status)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> បង្ហាញ
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> មិនបង្ហាញ
                        </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-1">
                            <button @click="currentSetting = {{ json_encode($item) }}; showDetailModal = true"
                                class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all" title="មើលលម្អិត">
                                <i class="fas fa-eye text-xs"></i>
                            </button>

                            <button @click="currentSetting = {{ json_encode($item) }}; showEditModal = true"
                                class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-all" title="កែប្រែ">
                                <i class="fas fa-edit text-xs"></i>
                            </button>

                            <form action="{{ route('contacts_sett.destroy', $item->id) }}" method="POST" class="inline-block m-0">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="confirmDelete(this.form)"
                                    class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all"
                                    title="លុប">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        មិនមានទិន្នន័យកំណត់ឡើយ
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($settings, 'links'))
    <div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
        <div class="dark:text-white">
            {{ $settings->links() }}
        </div>
    </div>
    @endif

    @include('admin.contacts_sett.modals')
</div>

@endsection