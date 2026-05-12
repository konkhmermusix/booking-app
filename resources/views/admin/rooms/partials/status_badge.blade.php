@php
$badges = [
'available' => ['color' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'text' => 'ទំនេរ'],
'booked' => ['color' => 'bg-rose-50 text-rose-600 border-rose-100', 'text' => 'មានភ្ញៀវ'],
'maintenance' => ['color' => 'bg-amber-50 text-amber-600 border-amber-100', 'text' => 'ជួសជុល'],
][$status] ?? ['color' => 'bg-gray-50 text-gray-500 border-gray-100', 'text' => $status];
@endphp

<span class="px-2 py-0.5 rounded-full border {{ $badges['color'] }} text-[13px] font-black uppercase tracking-tighter">
    {{ $badges['text'] }}
</span>


<!-- 

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
</span> -->