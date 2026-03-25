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
    x-cloak
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="translate-y-[-20px] md:translate-x-[20px] md:translate-y-0 opacity-0 scale-95"
    x-transition:enter-end="translate-y-0 md:translate-x-0 opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"

    /* Responsive Classes: លើ Mobile ដាក់នៅកណ្តាល (top-4 inset-x-4) លើ Desktop ដាក់ស្តាំ (top-6 right-6) */
    class="fixed top-4 inset-x-4 md:inset-x-auto md:top-6 md:right-6 z-[150] mx-auto md:mx-0 max-w-[calc(100%-2rem)] md:max-w-sm w-full overflow-hidden rounded-2xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-100 dark:border-gray-800 {{ $config['shadow'] }}">

    <div class="p-4 flex items-start gap-3 md:gap-4">
        <div class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 rounded-xl bg-gradient-to-br {{ $config['color'] }} flex items-center justify-center text-white shadow-lg">
            <i class="fas {{ $config['icon'] }} text-lg md:text-xl"></i>
        </div>

        <div class="flex-1 pt-0.5 md:pt-1">
            <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-none">{{ $config['label'] }}</h4>
            <p class="text-[11px] md:text-xs text-gray-500 dark:text-gray-400 mt-1.5 leading-relaxed line-clamp-2">
                {{ $message }}
            </p>
        </div>

        <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-1">
            <i class="fas fa-times text-[10px] md:text-xs"></i>
        </button>
    </div>

    <div class="h-1 w-full bg-gray-100 dark:bg-gray-800">
        <div class="h-full bg-gradient-to-r {{ $config['color'] }} transition-all duration-75 ease-linear"
            :style="`width: ${progress}%`" style="text-align: left;"></div>
    </div>
</div>
@endif





<!-- @php
$type = session('success') ? 'success' : (session('error') ? 'error' : null);
$message = session('success') ?? session('error');

// កំណត់ពណ៌ និង Icon តាមប្រភេទសារ
$config = [
'success' => ['icon' => 'fa-check-circle', 'color' => 'from-emerald-500 to-teal-600', 'shadow' => 'shadow-emerald-500/20', 'label' => 'ជោគជ័យ'],
'error' => ['icon' => 'fa-exclamation-triangle', 'color' => 'from-rose-500 to-red-600', 'shadow' => 'shadow-rose-500/20', 'label' => 'មានបញ្ហា']
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
        }, 50); {{-- 50ms * 100 = 5000ms (5 វិនាទី) --}}
     "
    x-show="show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="translate-y-[-20px] opacity-0 scale-95"
    x-transition:enter-end="translate-y-0 opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
    class="fixed top-6 right-6 z-[120] max-w-sm w-full overflow-hidden rounded-2xl bg-white dark:bg-gray-900 shadow-2xl border border-gray-100 dark:border-gray-800 {{ $config['shadow'] }}"
    x-cloak>

    <div class="p-4 flex items-start gap-4">
        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br {{ $config['color'] }} flex items-center justify-center text-white shadow-lg">
            <i class="fas {{ $config['icon'] }} text-xl"></i>
        </div>

        <div class="flex-1 pt-1">
            <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $config['label'] }}</h4>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                {{ $message }}
            </p>
        </div>

        <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors p-1">
            <i class="fas fa-times text-xs"></i>
        </button>
    </div>

    <div class="h-1 w-full bg-gray-100 dark:bg-gray-800">
        <div class="h-full bg-gradient-to-r {{ $config['color'] }} transition-all duration-75 ease-linear"
            :style="`width: ${progress}%` text-align: left;"></div>
    </div>
</div>
@endif -->





<!-- @if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
    class="fixed top-24 right-5 z-[110] max-w-sm w-full bg-green-500 text-white p-4 rounded-2xl shadow-lg flex items-center gap-3 transition-all">
    <i class="fas fa-check-circle text-2xl"></i>
    <div class="flex-1">
        <p class="font-bold text-sm">ជោគជ័យ!</p>
        <p class="text-xs opacity-90">{{ session('success') }}</p>
    </div>
    <button @click="show = false" class="hover:bg-white/20 p-1 rounded-lg">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
    class="fixed top-24 right-5 z-[110] max-w-sm w-full bg-red-500 text-white p-4 rounded-2xl shadow-lg flex items-center gap-3 transition-all">
    <i class="fas fa-exclamation-circle text-2xl"></i>
    <div class="flex-1">
        <p class="font-bold text-sm">មានបញ្ហា!</p>
        <p class="text-xs opacity-90">{{ session('error') }}</p>
    </div>
    <button @click="show = false" class="hover:bg-white/20 p-1 rounded-lg">
        <i class="fas fa-times"></i>
    </button>
</div>
@endif -->