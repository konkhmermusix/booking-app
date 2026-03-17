

@php
    $config = [
        'available'   => ['class' => 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400', 'label' => 'ទំនេរ'],
        'booked'      => ['class' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400', 'label' => 'មានភ្ញៀវ'],
        'maintenance' => ['class' => 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400', 'label' => 'ជួសជុល'],
    ];
    $current = $config[$status] ?? $config['available'];
@endphp

<span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider {{ $current['class'] }}">
    {{ $current['label'] }}
</span>