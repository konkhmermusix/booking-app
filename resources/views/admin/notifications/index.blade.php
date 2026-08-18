@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងការជូនដំណឹង | សណ្ឋាគារ ភីអេនធី ផាលេស')

@section('content')
<div class="space-y-6"
     x-data="{
         filterType: 'all',
         notifications: {{ json_encode($initialNotifs ?? []) }},
         page: 1,
         hasMore: {{ (isset($hasMore) && $hasMore) ? 'true' : 'false' }},
         loading: false,
         loadMore() {
             if (this.loading || !this.hasMore) return;
             this.loading = true;
             fetch('{{ route("admin.notifications.index") }}?page=' + (this.page + 1), {
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

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white">
                    ការជូនដំណឹងក្នុងប្រព័ន្ធ
                </h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">ពិនិត្យ និងតាមដានរាល់សកម្មភាពកក់ បោះបង់ សារជជែក និងការវាយតម្លៃ</p>
            </div>
        </div>

        <template x-if="notifications.length > 0">
            <button type="button" @click="markAdminAllRead()" class="inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 text-blue-600 dark:text-blue-400 px-4 py-2.5 rounded-xl text-xs font-bold transition-all active:scale-95 cursor-pointer shadow-xs border border-blue-200 dark:border-blue-800/40">
                <i class="fas fa-check-double text-xs"></i>
                <span>សន្មតថាអានទាំងអស់</span>
            </button>
        </template>
    </div>

    {{-- FILTER TABS --}}
    <div class="grid grid-cols-2 sm:flex sm:w-fit gap-2 bg-white dark:bg-gray-800 p-1.5 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700">
        <button @click="filterType = 'all'"
            :class="filterType === 'all' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            ទាំងអស់ (<span x-text="notifications.length"></span>)
        </button>
        <button @click="filterType = 'room'"
            :class="filterType === 'room' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            កក់បន្ទប់
        </button>
        <button @click="filterType = 'meeting'"
            :class="filterType === 'meeting' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            កក់សាលប្រជុំ
        </button>
        <button @click="filterType = 'cancelled'"
            :class="filterType === 'cancelled' ? 'bg-rose-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            បានបោះបង់
        </button>
        <button @click="filterType = 'promo'"
            :class="filterType === 'promo' ? 'bg-pink-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            ប្រូម៉ូសិន
        </button>
        <button @click="filterType = 'post'"
            :class="filterType === 'post' ? 'bg-purple-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            ព័ត៌មាន & ព្រឹត្តិការណ៍
        </button>
        <button @click="filterType = 'tour'"
            :class="filterType === 'tour' ? 'bg-emerald-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            កន្លែងទេសចរណ៍
        </button>
        <button @click="filterType = 'chat'"
            :class="filterType === 'chat' ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium'"
            class="py-2.5 px-4 rounded-xl text-xs transition-all text-center">
            សារ & វាយតម្លៃ
        </button>
    </div>

    {{-- NOTIFICATION LIST --}}
    <template x-if="notifications.length === 0">
        <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700 space-y-3">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700/50 text-gray-400 rounded-2xl flex items-center justify-center mx-auto text-2xl">
                <i class="far fa-bell-slash"></i>
            </div>
            <h3 class="text-base font-bold text-gray-800 dark:text-white">គ្មានការជូនដំណឹងថ្មីឡើយ</h3>
            <p class="text-xs text-gray-400 max-w-sm mx-auto">រាល់សកម្មភាពកក់ ការបោះបង់ ឬសារពីអតិថិជននឹងបង្ហាញនៅទីនេះ។</p>
        </div>
    </template>

    <template x-if="notifications.length > 0">
        <div class="space-y-3">
            <template x-for="notif in notifications" :key="notif.id">
                <div x-show="filterType === 'all' || filterType === notif.type"
                    x-transition:enter="transition ease-out duration-200"
                    class="bg-white dark:bg-gray-800 p-4 md:p-5 rounded-2xl shadow-xs border border-gray-100 dark:border-gray-700 hover:border-blue-200 dark:hover:border-gray-600 transition-all flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                    :class="notif.is_unread ? 'ring-1 ring-blue-500/30 bg-blue-50/20 dark:bg-blue-950/20' : ''">
                    
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-11 h-11 rounded-2xl flex items-center justify-center text-base shrink-0" :class="notif.icon_bg">
                            <i :class="notif.icon"></i>
                        </div>
                        <div class="space-y-1 min-w-0">
                            <div class="flex items-center gap-2 min-w-0">
                                <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-base truncate min-w-0" x-text="notif.title"></h3>
                                <template x-if="notif.is_unread">
                                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-400 shrink-0">
                                        ថ្មី
                                    </span>
                                </template>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed line-clamp-2" x-text="notif.description"></p>
                            <span class="text-[11px] font-medium text-gray-400 block pt-0.5">
                                <i class="far fa-clock mr-1"></i><span x-text="notif.time"></span>
                            </span>
                        </div>
                    </div>

                    <div class="self-end sm:self-center shrink-0">
                        <a :href="notif.url"
                            class="inline-flex items-center gap-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white px-4 py-2 rounded-xl font-bold text-xs transition-all border border-blue-200 dark:border-blue-800/40">
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
        <button @click="loadMore()" :disabled="loading" class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-blue-600 dark:text-blue-400 font-bold px-6 py-3 rounded-xl text-xs border border-gray-200 dark:border-gray-700 shadow-xs transition-all cursor-pointer">
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

<script>
function markAdminAllRead() {
    fetch('{{ route('admin.notifications.mark-read') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        }
    });
}
</script>
@endsection
