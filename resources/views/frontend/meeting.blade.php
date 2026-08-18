@extends('layouts.app')
@section('title', 'សាលប្រជុំ')

@section('content')
<div class="mx-auto">
    <div x-data="{ 
        view: 'grid',
        loading: false, 
        currentUrl: '{{ request()->fullUrl() }}',
        filterDate: '{{ request('check_in', date('Y-m-d')) }}',
        filterGuests: '{{ request('guests', '') }}',
        filterSort: '{{ request('sort', 'asc') }}',
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
        },
        doSearch() {
            const url = new URL('{{ route('frontend.meeting') }}');
            if (this.filterDate) url.searchParams.set('check_in', this.filterDate);
            if (this.filterGuests) url.searchParams.set('guests', this.filterGuests);
            if (this.filterSort) url.searchParams.set('sort', this.filterSort);
            url.searchParams.set('page', 1);
            this.fetchMeetings(url.toString());
        }
    }" @popstate.window="location.reload()">

        <div class="pt-20 text-center mb-6 relative z-10 container mx-auto px-4">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
                សាលប្រជុំ <span class="text-blue-600">ភីអេនធី</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                ស្វាគមន៍មកកាន់សណ្ឋាគារ <span class="font-bold text-blue-600">ភីអេនធី</span> រៀបចំកម្មវិធីសិក្ខាសាលា ប្រជុំអាជីវកម្មនៅក្នុងខេត្ដត្បូងឃ្មុំ។
            </p>
            <div class="h-1.5 w-30 bg-blue-600 mx-auto mt-6 rounded-full"></div>
        </div>

        {{-- SEARCH BOX --}}
        <div class="container mx-auto px-4 mb-6 relative z-10">
            <div class="bg-white dark:bg-gray-900 p-5 md:p-6 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                            <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃកម្មវិធី
                        </label>
                        <input type="date" x-model="filterDate" id="check_in" min="{{ date('Y-m-d') }}"
                            class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                            <i class="fas fa-users text-blue-500 mr-1"></i> ចំណុះមនុស្ស
                        </label>
                        <div class="relative group">
                            <select x-model="filterGuests" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                <option value="">គ្រប់ចំនួន</option>
                                <option value="20">២០ - ៥០ នាក់</option>
                                <option value="100">១០០ នាក់ឡើង</option>
                                <option value="300">៣០០ នាក់ឡើង</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                            <i class="fas fa-sort text-blue-500 mr-1"></i> រៀបតាមតម្លៃ
                        </label>
                        <div class="relative group">
                            <select x-model="filterSort" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                <option value="asc">តម្លៃ ទាប → ខ្ពស់</option>
                                <option value="desc">តម្លៃ ខ្ពស់ → ទាប</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                    </div>

                    <div class="flex items-end">
                        <button type="button" @click="doSearch()"
                            class="w-full bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition-all active:scale-95 h-[52px] flex items-center justify-center gap-2">
                            <span>ស្វែងរក</span>
                        </button>
                    </div>
                </div>
        </div>


        {{-- meeting room --}}
        <div class="w-full bg-gray-50 dark:bg-[#0b1120] py-6">
        <section class="container mx-auto px-4 flex flex-col md:flex-row gap-6 lg:gap-10">
            <aside class="w-full md:w-[25%] lg:w-1/5 md:sticky md:top-[80px] self-start z-20">
                <section class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="flex flex-col gap-4">
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ស្វែងរក</p>
                        <div class="relative">
                            <input type="text" placeholder="ស្វែងរកសាលប្រជុំ..."
                                @input.debounce.500ms="let url = new URL(currentUrl); url.searchParams.set('search',$event.target.value); url.searchParams.set('page',1); fetchMeetings(url.toString());"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 ring-blue-500 outline-none transition dark:text-white">
                            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        </div>
                        <div class="h-px bg-gray-100 dark:bg-gray-800"></div>
                        <a href="{{ route('frontend.meeting') }}"
                            class="w-full py-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-600 dark:hover:text-red-400 text-sm font-semibold transition-all flex items-center justify-center gap-2 border border-gray-200 dark:border-gray-700">
                            <i class="fa-solid fa-rotate-right text-xs"></i>
                            <span>សម្អាតការស្វែងរក</span>
                        </a>
                    </div>
                </section>
            </aside>

            <main class="w-full md:flex-1 relative">
                <div class="flex items-center justify-between mb-4 bg-white dark:bg-gray-900 p-2.5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                    <div class="hidden sm:block">
                        <h2 class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-3 flex items-center gap-2">
                            <i class="fa-solid fa-building-columns text-blue-500 text-xs"></i>
                            បន្ទប់សាលប្រជុំទាំងអស់
                        </h2>
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                        <div class="flex items-center rounded-xl bg-gray-100 dark:bg-gray-800 p-1">
                            <button @click="view = 'grid'"
                                :class="view === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400 dark:text-gray-500'"
                                class="px-3 py-2 rounded-lg transition-all" title="Grid View">
                                <i class="fas fa-th-large text-sm"></i>
                            </button>
                            <button @click="view = 'list'"
                                :class="view === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400 dark:text-gray-500'"
                                class="px-3 py-2 rounded-lg transition-all" title="List View">
                                <i class="fas fa-list text-sm"></i>
                            </button>
                        </div>
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
        </div>{{-- end full-width bg --}}
    </div>
</div>
@endsection