@extends('layouts.app')
@section('title', 'បន្ទប់ស្នាក់នៅ')

@section('content')

<div x-data="{ 
    view: 'grid',
    loading: false, 
    currentUrl: '{{ request()->fullUrl() }}',
    fetchRooms(url) {
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

    <div class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-10">
        <div class="container mx-auto px-4">

            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
                    បន្ទប់ស្នាក់នៅ <span class="text-blue-600">ភីអេនធី</span>
                </h1>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                    ស្វាគមន៍មកកាន់សណ្ឋាគារ <span class="font-bold text-blue-600">ភីអេនធី</span> ដែលជាកន្លែងស្នាក់នៅ & សាលប្រជុំដ៏ល្អឥតខ្ចោះសម្រាប់អ្នកដែលកំពុងស្វែងរកការស្នាក់នៅដ៏អស្ចារ្យនៅក្នុងខេត្ដត្បូងឃ្មុំ។ យើងខ្ញុំមានបន្ទប់ទំនើបៗ និងសេវាកម្មលំដាប់ខ្ពស់ដែលត្រូវបានរចនាឡើងដើម្បីផ្តល់ជូននូវភាពងាយស្រួល និងសេវាកម្មដ៏ល្អឥតខ្ចោះសម្រាប់ភ្ញៀវជាតិ និងអន្តរជាតិ។
                </p>
                <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
            </div>

            {{-- search box  --}}
            <section class="container mx-auto px-4 -mt-10 relative z-10">
                <form action="{{ route('frontend.rooms') }}" method="GET">
                    <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                        <div class="flex flex-col">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូល
                            </label>
                            <input type="date" name="check_in" id="check_in" value="{{ request('check_in', date('Y-m-d')) }}"
                                class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                <i class="fas fa-calendar-check text-blue-500 mr-1"></i> ថ្ងៃចេញ
                            </label>
                            <input type="date" name="check_out" id="check_out" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}"
                                class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                <i class="fas fa-bed text-blue-500 mr-1"></i> ប្រភេទបន្ទប់
                            </label>
                            <div class="relative group">
                                <select name="type" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                    <option value="">គ្រប់ប្រភេទទាំងអស់</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->name }}" {{ request('type') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>
                        <div class="flex flex-col relative">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                <i class="fas fa-clock text-blue-500 mr-1"></i> រយៈពេល
                            </label>
                            <div class="relative group">
                                <select id="duration" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                    <option value="">ជ្រើសរយៈពេល</option>
                                    <option value="1">1 ថ្ងៃ</option>
                                    <option value="2">2 ថ្ងៃ</option>
                                    <option value="3">3 ថ្ងៃ</option>
                                    <option value="5">5 ថ្ងៃ</option>
                                    <option value="7">1 សប្ដាហ៍</option>
                                    <option value="14">2 សប្ដាហ៍</option>
                                    <option value="21">3 សប្ដាហ៍</option>
                                    <option value="30">1 ខែ</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                class="w-full bg-blue-900 dark:bg-blue-800 text-white font-bold rounded-xl hover:brightness-110 shadow-lg transition-all active:scale-95 h-[52px] flex items-center justify-center gap-2">
                                ស្វែងរក
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            <div class="container mx-auto px-4 flex flex-col md:flex-row gap-6 lg:gap-10 py-6">
                <aside class="w-full md:w-[25%] lg:w-1/5 md:sticky md:top-[80px] self-start z-20">
                    <section class="bg-white dark:bg-gray-900 p-5 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                        <h3 class="font-bold text-lg mb-4 dark:text-white flex items-center">
                            តម្រងស្វែងរក
                        </h3>

                        <div class="flex flex-col gap-5">
                            <div class="relative">
                                <input type="text" placeholder="ស្វែងរកបន្ទប់" @input.debounce.500ms="let url = new URL(currentUrl); url.searchParams.set('search',$event.target.value); url.searchParams.set('page',1); fetchRooms(url.toString());"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm focus:ring-2 ring-blue-500 outline-none transition">
                                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            </div>

                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 block px-1">ប្រភេទបន្ទប់</span>

                                <div class="flex flex-row md:flex-col gap-2 overflow-x-auto md:overflow-visible pb-3 md:pb-0 scrollbar-hide">
                                    <a href="{{ route('frontend.rooms', request()->except(['type','page'])) }}"
                                        @click.prevent="fetchRooms($el.href)"
                                        :class="!currentUrl.includes('type=')  ? 'bg-blue-600 text-white shadow-md ring-2 ring-offset-2 dark:ring-offset-gray-900' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                                        class="px-5 py-2.5 rounded-xl text-sm font-semibold whitespace-nowrap transition-all duration-200 border border-transparent">
                                        ទាំងអស់
                                    </a>

                                    @foreach($categories as $category)
                                    @php
                                    $catUrl = route('frontend.rooms', array_merge(
                                    request()->query(),
                                    ['type' => $category->name, 'page' => 1]
                                    ));
                                    $activeCheck = 'type=' . urlencode($category->name);
                                    @endphp

                                    <a href="{{ $catUrl }}"
                                        @click.prevent="fetchRooms($el.href)"
                                        :class="currentUrl.includes('{{ $activeCheck }}')  ? 'bg-blue-600 text-white shadow-md ring-2 ring-offset-2 dark:ring-offset-gray-900' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                                        class="px-5 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all duration-200 border border-transparent">
                                        {{ $category->name }}
                                    </a>
                                    @endforeach

                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">កំណត់តាម</label>
                                <div class="grid grid-cols-2 md:grid-cols-1 gap-3">
                                    <div class="relative">
                                        <select
                                            @change="let url = new URL(currentUrl); url.searchParams.set('guests',$event.target.value); url.searchParams.set('page',1); fetchRooms(url.toString());"
                                            class="appearance-none w-full px-4 py-2.5 pr-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 ring-blue-500 outline-none transition cursor-pointer">

                                            <option value="">ភ្ញៀវ</option>

                                            @for($i=1; $i<=5; $i++)
                                                <option value="{{ $i }}" {{ request('guests') == $i ? 'selected' : '' }}>
                                                {{ $i }} នាក់{{ $i==5 ? '+' : '' }}
                                                </option>
                                                @endfor
                                        </select>


                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <select
                                            @change="let url = new URL(currentUrl); url.searchParams.set('sort',$event.target.value); fetchRooms(url.toString());"
                                            class="appearance-none w-full px-4 py-2.5 pr-10 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm focus:ring-2 ring-blue-500 outline-none transition cursor-pointer">

                                            <option value="asc" {{ request('sort')=='asc' ? 'selected' : '' }}>
                                                តម្លៃ ទាប → ខ្ពស់
                                            </option>

                                            <option value="desc" {{ request('sort')=='desc' ? 'selected' : '' }}>
                                                តម្លៃ ខ្ពស់ → ទាប
                                            </option>

                                        </select>

                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>

                <main class="w-full md:flex-1 relative">
                    <div class="flex items-center justify-between mb-6 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <div class="hidden sm:block">
                            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                បន្ទប់ស្នាក់នៅ
                            </span>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center rounded-lg bg-gray-100 dark:bg-gray-800 p-1">
                                    <button @click="view = 'grid'"
                                        :class="view === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                                        class="p-2 px-3 rounded-lg transition-all">
                                        <i class="fas fa-th-large"></i>
                                    </button>
                                    <button @click="view = 'list'"
                                        :class="view === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                                        class="p-2 px-3 rounded-lg transition-all">
                                        <i class="fas fa-list"></i>
                                    </button>
                                </div>
                            </div>

                            <button
                                @click="fetchRooms('{{ route('frontend.rooms') }}')"
                                title="Reset Filters"
                                class="px-3 py-2 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 text-sm font-medium transition-all shadow-sm flex items-center">
                                <span class="hidden sm:inline">សម្អាត</span>
                            </button>
                        </div>
                    </div>

                    <div class="relative min-h-[400px]">
                        <div x-show="loading"
                            x-transition:enter="transition opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="absolute inset-0 bg-white/60 dark:bg-gray-900/60 z-10 flex justify-center pt-20 backdrop-blur-[2px] rounded-2xl">
                            <div class="flex flex-col items-center gap-3">
                                <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent shadow-lg"></div>
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">កំពុងផ្ទុក...</span>
                            </div>
                        </div>

                        <div id="room-list-container">
                            @include('frontend.partials.room_list')
                        </div>
                    </div>
                </main>
            </div>
        </div>

        @include('frontend.partials.booking_modal')
    </div>
</div>

<script>
    const modalContainer = document.getElementById('modalContainer');
    const bookingForm = document.getElementById('bookingForm');

    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'កំពុងផ្ញើទិន្នន័យ...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });
    })
</script>

@endsection