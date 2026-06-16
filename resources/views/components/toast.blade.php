@php
$type = session('success') ? 'success' : (session('error') ? 'error' : null);
$message = session('success') ?? session('error');
$config = [
'success' => [
'icon' => 'fa-check-circle',
'color' => 'from-emerald-500 to-teal-500',
'shadow' => 'shadow-emerald-500/10 dark:shadow-emerald-500/5',
'glow' => 'bg-emerald-500/10 text-emerald-500 dark:text-emerald-400',
'label' => 'ជោគជ័យ'
],
'error' => [
'icon' => 'fa-exclamation-triangle',
'color' => 'from-rose-500 to-red-500',
'shadow' => 'shadow-rose-500/10 dark:shadow-rose-500/5',
'glow' => 'bg-rose-500/10 text-rose-500 dark:text-rose-400',
'label' => 'មានបញ្ហា'
]
][$type] ?? null;
@endphp

@if($type && $config)
<div x-data="{ show: false, progress: 100 }"
    x-init="
        setTimeout(() => show = true, 100);
        let timer = setInterval(() => {
            progress -= 1;
            if (progress <= 0) {
                clearInterval(timer);
                show = false;
            }
        }, 50);
     "
    x-show="show"
    x-transition:enter="transition duration-400 ease-out"
    x-transition:enter-start="opacity-0 scale-95 -rotate-2"
    x-transition:enter-end="opacity-100 scale-100 rotate-0"
    x-transition:leave="transition duration-200 ease-in"
    x-transition:leave-end="opacity-0 scale-95"
    class="fixed top-6 right-6 z-[200] max-w-sm w-full overflow-hidden rounded-2xl bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-xl border border-gray-200/50 dark:border-gray-800/60 {{ $config['shadow'] }}"
    x-cloak>

    <div class="p-4 flex items-center gap-4">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl {{ $config['glow'] }} flex items-center justify-center shadow-inner">
            <i class="fas {{ $config['icon'] }} text-lg"></i>
        </div>

        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wide">{{ $config['label'] }}</h4>
            <p class="text-[13px] text-gray-500 dark:text-gray-400 mt-0.5 leading-snug truncate-2-lines">{{ $message }}</p>
        </div>

        <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

    <div class="h-1 w-full bg-gray-100 dark:bg-gray-800/50">
        <div class="h-full bg-gradient-to-r {{ $config['color'] }} transition-all duration-75 ease-linear"
            :style="`width: ${progress}%; text-align: left;` columns: 1;"></div>
    </div>
</div>
@endif