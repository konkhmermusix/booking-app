@php
$sessionType = session('success') ? 'success' : (session('error') ? 'error' : null);
$sessionMessage = session('success') ?? session('error');
@endphp

<div x-data="{
    show: false,
    type: '{{ $sessionType ?? 'success' }}',
    message: '{{ addslashes($sessionMessage ?? '') }}',
    progress: 100,
    timer: null,

    trigger(detail) {
        if (!detail || !detail.message) return;
        this.type = detail.type || 'success';
        this.message = detail.message;
        this.show = true;
        this.progress = 100;
        
        if (this.timer) clearInterval(this.timer);
        this.timer = setInterval(() => {
            this.progress -= 2;
            if (this.progress <= 0) {
                clearInterval(this.timer);
                this.show = false;
            }
        }, 60);
    },

    init() {
        if (this.message && this.message.trim() !== '') {
            this.trigger({ type: this.type, message: this.message });
        }
    }
}"
@toast.window="trigger($event.detail)"
x-show="show"
x-transition:enter="transition duration-400 ease-out"
x-transition:enter-start="opacity-0 scale-95 translate-y-2"
x-transition:enter-end="opacity-100 scale-100 translate-y-0"
x-transition:leave="transition duration-200 ease-in"
x-transition:leave-end="opacity-0 scale-95"
class="fixed top-6 right-6 z-[200] max-w-sm w-full overflow-hidden rounded-2xl bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl shadow-2xl border-none border-gray-200/60 dark:border-gray-800/80"
x-cloak>

    <div class="p-4 flex items-center gap-4">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center shadow-inner"
            :class="type === 'success' ? 'bg-emerald-500/10 text-emerald-500 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-500 dark:text-rose-400'">
            <i class="fas text-lg" :class="type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'"></i>
        </div>

        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-black text-gray-900 dark:text-white tracking-wide" x-text="type === 'success' ? 'ជោគជ័យ' : 'មានបញ្ហា'"></h4>
            <p class="text-[13px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug truncate-2-lines" x-text="message"></p>
        </div>

        <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

    <div class="h-1 w-full bg-gray-100 dark:bg-gray-800/50">
        <div class="h-full transition-all duration-75 ease-linear"
            :class="type === 'success' ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gradient-to-r from-rose-500 to-red-500'"
            :style="`width: ${progress}%;`"></div>
    </div>
</div>