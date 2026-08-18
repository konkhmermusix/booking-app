@extends('layouts.app')
@section('title', 'ការជូនដំណឹងរបស់ខ្ញុំ | សណ្ឋាគារ ភីអេនធី ផាលេស')
@section('content')

<div class="w-full bg-gray-50 dark:bg-[#0b1120] min-h-screen py-10 transition-colors duration-300"
     x-data="{
         filterType: 'all',
         notifications: {{ json_encode($initialNotifs ?? []) }},
         page: 1,
         hasMore: {{ (isset($hasMore) && $hasMore) ? 'true' : 'false' }},
         loading: false,
         loadMore() {
             if (this.loading || !this.hasMore) return;
             this.loading = true;
             fetch('{{ route("notifications.index") }}?page=' + (this.page + 1), {
                 headers: { 'X-Requested-With': 'XMLHttpRequest' }
             })
             .then(res => res.json())
             .then(data => {
                 if (data.notifications && data.notifications.length > 0) {
                     this.notifications.push(...data.notifications);
                 }
                 this.page = data.page;
                 this.hasMore = data.has_more;
                 this.loading = false;
             })
             .catch(() => { this.loading = false; });
         }
     }"
     @scroll.window="if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 300)) { loadMore(); }">
    <div class="container mx-auto px-4 max-w-4xl">

        {{-- PAGE HEADER --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 pb-4 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="h-8 w-1.5 bg-blue-600 rounded-full"></div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 dark:text-white tracking-tight">
                        ការជូនដំណឹងរបស់ខ្ញុំ
                    </h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ពិនិត្យមើលបច្ចុប្បន្នភាព និងសារជូនដំណឹងពីប្រព័ន្ធ</p>
                </div>
            </div>

            <template x-if="notifications.length > 0">
                <form action="{{ route('customer.notifications.mark-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800 text-blue-600 dark:text-blue-400 px-4 py-2 rounded-xl text-xs font-bold shadow-xs border border-gray-200 dark:border-gray-800 transition-all active:scale-95 cursor-pointer">
                        <i class="fas fa-check-double text-xs"></i>
                        <span>អានទាំងអស់</span>
                    </button>
                </form>
            </template>
        </div>

        {{-- CATEGORY FILTER TABS --}}
        <div class="grid grid-cols-2 sm:flex sm:w-fit gap-1.5 bg-white dark:bg-gray-900 p-1.5 rounded-2xl w-full mb-6 shadow-sm border border-gray-100 dark:border-gray-800">
            <button @click="filterType = 'all'"
                :class="filterType === 'all' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="py-2.5 px-3 sm:px-4 rounded-xl text-xs transition-all duration-200 text-center">
                ទាំងអស់ (<span x-text="notifications.length"></span>)
            </button>
            <button @click="filterType = 'hotel'"
                :class="filterType === 'hotel' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="py-2.5 px-3 sm:px-4 rounded-xl text-xs transition-all duration-200 text-center">
                បន្ទប់សណ្ឋាគារ
            </button>
            <button @click="filterType = 'meeting'"
                :class="filterType === 'meeting' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="py-2.5 px-3 sm:px-4 rounded-xl text-xs transition-all duration-200 text-center">
                សាលប្រជុំ
            </button>
            <button @click="filterType = 'promo'"
                :class="filterType === 'promo' ? 'bg-pink-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="py-2.5 px-3 sm:px-4 rounded-xl text-xs transition-all duration-200 text-center">
                ប្រូម៉ូសិន
            </button>
            <button @click="filterType = 'post'"
                :class="filterType === 'post' ? 'bg-purple-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="py-2.5 px-3 sm:px-4 rounded-xl text-xs transition-all duration-200 text-center">
                ព័ត៌មាន & ព្រឹត្តិការណ៍
            </button>
            <button @click="filterType = 'tour'"
                :class="filterType === 'tour' ? 'bg-emerald-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="py-2.5 px-3 sm:px-4 rounded-xl text-xs transition-all duration-200 text-center">
                កន្លែងទេសចរណ៍
            </button>
            <button @click="filterType = 'chat'"
                :class="filterType === 'chat' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
                class="py-2.5 px-3 sm:px-4 rounded-xl text-xs transition-all duration-200 text-center">
                សារជជែក
            </button>
        </div>

        {{-- NOTIFICATIONS LIST --}}
        <template x-if="notifications.length === 0">
            <div class="text-center py-20 bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 max-w-xl mx-auto space-y-4">
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mx-auto text-3xl">
                    <i class="far fa-bell-slash"></i>
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">មិនទាន់មានការជូនដំណឹងនៅឡើយទេ</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto">ការជូនដំណឹងអំពីបច្ចុប្បន្នភាពការកក់ ឬសារពីប្រព័ន្ធនឹងបង្ហាញនៅទីនេះ។</p>
                </div>
                <a href="{{ route('frontend.rooms') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-xl text-xs shadow-md transition-all">
                    <i class="fas fa-search"></i> ស្វែងរកបន្ទប់ស្នាក់នៅ
                </a>
            </div>
        </template>

        <template x-if="notifications.length > 0">
            <div class="space-y-3">
                <template x-for="notif in notifications" :key="notif.id">
                    <div x-show="filterType === 'all' || filterType === notif.type"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        class="bg-white dark:bg-gray-900 p-4 md:p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 hover:border-blue-200 dark:hover:border-gray-700 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                        :class="notif.is_unread ? 'ring-1 ring-blue-500/20 bg-blue-50/20 dark:bg-blue-950/10' : ''">
                        
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-lg shrink-0" :class="notif.icon_bg">
                                <i :class="notif.icon"></i>
                            </div>
                            <div class="space-y-1 min-w-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <h3 class="font-extrabold text-gray-900 dark:text-white text-sm sm:text-base truncate min-w-0" x-text="notif.title"></h3>
                                    <template x-if="notif.is_unread">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400 shrink-0">
                                            ថ្មី
                                        </span>
                                    </template>
                                </div>
                                <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed line-clamp-2" x-text="notif.description"></p>
                                <span class="text-[11px] font-medium text-gray-400 block pt-0.5">
                                    <i class="far fa-clock mr-1"></i><span x-text="notif.time"></span>
                                </span>
                            </div>
                        </div>

                        <div class="self-end sm:self-center shrink-0">
                            <a :href="notif.url"
                                class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white px-4 py-2.5 rounded-xl font-bold text-xs transition-all border border-blue-200 dark:border-blue-900/40">
                                <span>ពិនិត្យមើល</span>
                                <i class="fas fa-chevron-right text-[10px]"></i>
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- LOAD MORE BUTTON & SPINNER --}}
        <div class="mt-8 text-center" x-show="hasMore">
            <button @click="loadMore()" :disabled="loading" class="inline-flex items-center gap-2 bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 text-blue-600 dark:text-blue-400 font-bold px-6 py-3 rounded-xl text-xs border border-gray-200 dark:border-gray-800 shadow-sm transition-all cursor-pointer">
                <template x-if="loading">
                    <i class="fas fa-circle-notch fa-spin text-sm"></i>
                </template>
                <template x-if="!loading">
                    <i class="fas fa-chevron-down text-xs"></i>
                </template>
                <span x-text="loading ? 'កំពុងទាញយក...' : 'មើលបន្ថែមទៀត'"></span>
            </button>
        </div>

    </div>
</div>

@endsection
