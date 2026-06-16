@extends('layouts.app')
@section('title', 'សាលប្រជុំ')

@section('content')
<div class="mx-auto">
    <div x-data="{ 
        view: 'grid',
        loading: false, 
        currentUrl: '{{ request()->fullUrl() }}',
        fetchMeetings(url) {
            this.loading = true;
            this.currentUrl = url;
            
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('room-list-container').innerHTML = html;
                    window.history.pushState({ path: url }, '', url);
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                });
        }
    }" @popstate.window="location.reload()">

        <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
            <div class="container mx-auto px-4">
                <div class="text-center mb-16">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4 font-['Kantumruy_Pro']">
                        សាលប្រជុំ <span class="text-blue-600">ភីអេនធី</span>
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                        ស្វាគមន៍មកកាន់សណ្ឋាគារ <span class="font-bold text-blue-600">ភីអេនធី</span> រៀបចំកម្មវិធីសិក្ខាសាលា ប្រជុំអាជីវកម្មនៅក្នុងខេត្ដត្បូងឃ្មុំ។
                    </p>
                    <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
                </div>
            </div>
        </section>

        {{-- SEARCH BOX --}}
        <div class="container mx-auto px-4 -mt-10 relative z-10">
            <form action="{{ route('frontend.meeting') }}" method="GET">
                <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                            <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃកម្មវិធី
                        </label>
                        <input type="date" name="check_in" id="check_in" value="{{ request('check_in', date('Y-m-d')) }}"
                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                            <i class="fas fa-users text-blue-500 mr-1"></i> ចំណុះមនុស្ស
                        </label>
                        <div class="relative group">
                            <select name="guests" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                <option value="">គ្រប់ចំនួន</option>
                                <option value="20" {{ request('guests') == '20' ? 'selected' : '' }}>២០ - ៥០ នាក់</option>
                                <option value="100" {{ request('guests') == '100' ? 'selected' : '' }}>១០០ នាក់ឡើង</option>
                                <option value="300" {{ request('guests') == '300' ? 'selected' : '' }}>៣០០ នាក់ឡើង</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                            <i class="fas fa-sort text-blue-500 mr-1"></i> រៀបតាមតម្លៃ
                        </label>
                        <div class="relative group">
                            <select name="sort" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>តម្លៃ ទាប → ខ្ពស់</option>
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>តម្លៃ ខ្ពស់ → ទាប</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                    </div>

                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full bg-blue-900 dark:bg-blue-800 text-white font-bold rounded-xl hover:brightness-110 shadow-lg transition-all active:scale-95 h-[52px] flex items-center justify-center gap-2">
                            <span>ស្វែងរក</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- meeting room --}}
        <section class="container mx-auto px-4 flex flex-col md:flex-row gap-6 lg:gap-10 py-10">
            <aside class="w-full md:w-[25%] lg:w-1/5 md:sticky md:top-[80px] self-start z-20">
                <section class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="flex flex-col gap-5">
                        <div class="relative">
                            <input type="text" placeholder="ស្វែងរកសាលប្រជុំ"
                                @input.debounce.500ms="let url = new URL(currentUrl); url.searchParams.set('search',$event.target.value); url.searchParams.set('page',1); fetchRooms(url.toString());"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 ring-blue-500 outline-none transition">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>

                        <div class="h-px bg-gray-100 dark:bg-gray-800"></div>

                        <div class="text-xs text-gray-400 font-bold uppercase">ជម្រើសបន្ថែម</div>
                        <button @click="fetchMeetings('{{ route('frontend.meeting') }}')"
                            class="w-full py-2.5 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 text-sm font-semibold transition-all flex items-center justify-center gap-2">
                            សម្អាត
                        </button>
                    </div>
                </section>
            </aside>

            <main class="w-full md:flex-1 relative">
                <div class="flex items-center justify-between mb-6 bg-white dark:bg-gray-900 p-2.5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="hidden sm:block">
                        <h1 class="text-md font-bold text-gray-700 dark:text-gray-300 ml-3">
                            បន្ទប់សាលប្រជុំ
                        </h1>
                    </div>

                    <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                        <div class="flex items-center rounded-xl bg-gray-100 dark:bg-gray-800 p-1">
                            <button @click="view = 'grid'"
                                :class="view === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400 dark:text-gray-500'"
                                class="px-3.5 py-2 rounded-lg transition-all">
                                <i class="fas fa-th-large text-sm"></i>
                            </button>
                            <button @click="view = 'list'"
                                :class="view === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400 dark:text-gray-500'"
                                class="px-3.5 py-2 rounded-lg transition-all">
                                <i class="fas fa-list text-sm"></i>
                            </button>
                        </div>

                        <button
                            @click="fetchMeetings('{{ route('frontend.meeting') }}')"
                            title="Reset Filters"
                            class="px-3 py-3 rounded-xl bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 hover:bg-blue-100 dark:hover:bg-red-900/40 text-sm font-medium transition-all duration-200 shadow-sm flex items-center gap-1.5 border border-red-100 dark:border-blue-900/30">
                            <span>សម្អាត</span>
                        </button>
                    </div>
                </div>

                <div class="relative min-h-[400px]">
                    <div x-show="loading"
                        x-transition:enter="transition opacity-0 duration-300"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition opacity-100 duration-300"
                        x-transition:leave-end="opacity-0"
                        class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 z-30 flex justify-center pt-24 backdrop-blur-[2px] rounded-2xl" x-cloak>
                        <div class="flex flex-col items-center gap-3">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent shadow-lg"></div>
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400 animate-pulse">កំពុងផ្ទុក...</span>
                        </div>
                    </div>

                    <div id="room-list-container">
                        @include('frontend.partials.meeting_list')
                    </div>
                </div>
            </main>
        </section>
    </div>
</div>
@endsection