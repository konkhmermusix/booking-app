@php
$type = session('success') ? 'success' : (session('error') ? 'error' : null);
$message = session('success') ?? session('error');
$config = [
'success' => [
'icon' => 'fa-check-circle',
'color' => 'from-emerald-500 to-teal-600',
'shadow' => 'shadow-emerald-500/20',
'label' => 'ជោគជ័យ'
],
'error' => [
'icon' => 'fa-exclamation-triangle',
'color' => 'from-rose-500 to-red-600',
'shadow' => 'shadow-rose-500/20',
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
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="translate-y-[-20px] opacity-0 scale-95"
    x-transition:enter-end="translate-y-0 opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed top-8 right-8 z-[200] max-w-sm w-full overflow-hidden rounded-3xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-100 dark:border-gray-800 {{ $config['shadow'] }}"
    x-cloak>

    <div class="p-5 flex items-start gap-4">
        <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-gradient-to-br {{ $config['color'] }} flex items-center justify-center text-white shadow-lg">
            <i class="fas {{ $config['icon'] }} text-xl"></i>
        </div>
        <div class="flex-1 pt-1">
            <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-none">{{ $config['label'] }}</h4>
            <p class="text-[13px] text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">{{ $message }}</p>
        </div>
        <button @click="show = false" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>
    <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800">
        <div class="h-full bg-gradient-to-r {{ $config['color'] }} transition-all duration-75 ease-linear"
            :style="`width: ${progress}%` text-align: left;"></div>
    </div>
</div>
@endif