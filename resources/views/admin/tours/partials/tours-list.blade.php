<!-- Table View Mode -->
<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800" x-transition x-cloak>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ឈ្មោះទីតាំង</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ចម្ងាយពីសណ្ឋាគារ</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Google Maps</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-center">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($tours as $tour)
                @php
                    $imgUrl = 'https://via.placeholder.com/300';
                    if (!empty($tour->image)) {
                        $firstImg = is_array($tour->image) ? ($tour->image[0] ?? null) : $tour->image;
                        if ($firstImg) {
                            $imgUrl = asset('storage/' . $firstImg);
                        }
                    }
                @endphp
                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-all">
                    <td class="px-6 py-4 font-bold dark:text-white uppercase text-xs flex items-center gap-4">
                        <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                            <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <span class="truncate font-black text-sm text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors block">{{ $tour->name }}</span>
                            @if($tour->description)
                            <span class="text-[11px] text-gray-400 font-normal line-clamp-1 max-w-xs">{{ $tour->description }}</span>
                            @endif
                        </div>
                    </td>

                    <td class="px-6 py-4 text-xs font-bold text-gray-700 dark:text-gray-300">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
                            <i class="fa-solid fa-location-arrow text-[10px]"></i>
                            <span>{{ $tour->distance ?? 'N/A' }} គីឡូម៉ែត្រ</span>
                        </span>
                    </td>

                    <td class="px-6 py-4 text-xs text-gray-500 font-medium">
                        @if($tour->google_map_link)
                        <a href="{{ $tour->google_map_link }}" target="_blank" class="inline-flex items-center gap-1 text-blue-600 dark:text-blue-400 font-bold hover:underline">
                            <i class="fa-solid fa-map-marked-alt"></i> មើលលើផែនទី
                        </a>
                        @else
                        <span class="text-gray-400 italic">មិនមាន</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase {{ $tour->status ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400 border border-emerald-200' : 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-400 border border-rose-200' }}">
                            {{ $tour->status ? 'សកម្ម (បង្ហាញ)' : 'ផ្អាក (មិនបង្ហាញ)' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                        <div class="flex items-center justify-end gap-1">
                            <button type="button"
                                @click="currentTour = {{ $tour->toJson() }}; showDetailModal = true"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors"
                                title="មើលលម្អិត">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <button type="button"
                                @click="currentTour = {{ $tour->toJson() }}; showEditModal = true"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors"
                                title="កែប្រែ">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <button type="button"
                                @click="deleteTour({{ $tour->id }})"
                                class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                title="លុប">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        <i class="fa-solid fa-map-location-dot text-4xl mb-3 text-gray-300"></i>
                        <p class="font-bold text-sm">មិនទាន់មានទិន្នន័យកន្លែងទេសចរណ៍នៅឡើយទេ</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Grid View Mode (Matching Room Types Grid UI) -->
<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition x-cloak>
    @forelse($tours as $tour)
    @php
        $imgUrl = 'https://via.placeholder.com/300';
        if (!empty($tour->image)) {
            $firstImg = is_array($tour->image) ? ($tour->image[0] ?? null) : $tour->image;
            if ($firstImg) {
                $imgUrl = asset('storage/' . $firstImg);
            }
        }
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border-none flex flex-col justify-between">
        <div>
            <!-- Top Image Container -->
            <div class="h-44 -mx-5 -mt-5 mb-4 overflow-hidden rounded-t-2xl relative group/img bg-gray-100 dark:bg-gray-700">
                <img src="{{ $imgUrl }}"
                    class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500">

                <!-- Image Overlay Action Buttons -->
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300">
                    <button type="button"
                        @click="currentTour = {{ $tour->toJson() }}; showDetailModal = true"
                        class="p-2.5 bg-white/90 hover:bg-white text-gray-700 hover:text-blue-600 rounded-xl transition-all shadow-md" title="មើលលម្អិត">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>

                    <button type="button"
                        @click="currentTour = {{ $tour->toJson() }}; showEditModal = true"
                        class="p-2.5 bg-white/90 hover:bg-white text-gray-700 hover:text-amber-600 rounded-xl transition-all shadow-md" title="កែប្រែ">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </button>
                </div>

                <!-- Distance Pill (Bottom Left) -->
                <div class="absolute bottom-3 left-3 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <span class="text-xs font-black text-blue-600 dark:text-blue-400">
                        <i class="fa-solid fa-location-arrow text-[10px] mr-1"></i>{{ $tour->distance ?? 'N/A' }} km
                    </span>
                </div>

                <!-- Status Overlay Badge (Top Right) -->
                <div class="absolute top-3 right-3">
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase shadow-sm {{ $tour->status ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white' }}">
                        {{ $tour->status ? 'Active' : 'Disabled' }}
                    </span>
                </div>
            </div>

            <h3 class="font-bold text-base text-gray-800 dark:text-gray-100 uppercase tracking-tight truncate group-hover:text-blue-600 transition-colors">{{ $tour->name }}</h3>

            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 min-h-[36px]">
                {{ $tour->description ?? 'មិនមានការពិពណ៌នា...' }}
            </p>

            <div class="pt-3 mt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                @if($tour->google_map_link)
                <a href="{{ $tour->google_map_link }}" target="_blank" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                    <i class="fa-solid fa-map-marked-alt"></i> Google Maps
                </a>
                @else
                <span class="text-[11px] text-gray-400 italic">មិនមាន Maps</span>
                @endif

                <div class="flex items-center gap-1">
                    <button type="button" @click="currentTour = {{ $tour->toJson() }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត">
                        <i class="fas fa-eye text-sm"></i>
                    </button>
                    <button type="button" @click="currentTour = {{ $tour->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                        <i class="fas fa-edit text-sm"></i>
                    </button>
                    <button type="button" @click="deleteTour({{ $tour->id }})" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-gray-400">
        <p class="font-bold text-sm">មិនទាន់មានទិន្នន័យកន្លែងទេសចរណ៍នៅឡើយទេ</p>
    </div>
    @endforelse
</div>

<!-- List View Mode -->
<div x-show="viewMode === 'list'" class="space-y-3" x-transition x-cloak>
    @forelse($tours as $tour)
    @php
        $imgUrl = 'https://via.placeholder.com/300';
        if (!empty($tour->image)) {
            $firstImg = is_array($tour->image) ? ($tour->image[0] ?? null) : $tour->image;
            if ($firstImg) {
                $imgUrl = asset('storage/' . $firstImg);
            }
        }
    @endphp
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-none">
        <div class="flex items-center gap-4">
            <div class="w-16 h-14 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
            </div>

            <div>
                <div class="flex items-center gap-2">
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-tight">{{ $tour->name }}</h4>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $tour->status ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-400' : 'bg-rose-100 text-rose-700 dark:bg-rose-950 dark:text-rose-400' }}">
                        {{ $tour->status ? 'សកម្ម' : 'ផ្អាក' }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                    <span><i class="fa-solid fa-location-arrow text-blue-500 text-[10px]"></i> ចម្ងាយ ៖ {{ $tour->distance ?? 'N/A' }} គីឡូម៉ែត្រ</span>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-1">
            <button type="button" @click="currentTour = {{ $tour->toJson() }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត">
                <i class="fas fa-eye text-sm"></i>
            </button>
            <button type="button" @click="currentTour = {{ $tour->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                <i class="fas fa-edit text-sm"></i>
            </button>
            <button type="button" @click="deleteTour({{ $tour->id }})" class="p-2 text-gray-400 hover:text-red-500 transition-colors" title="លុប">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    </div>
    @empty
    <div class="py-12 text-center text-gray-400">
        <p class="font-bold text-sm">មិនទាន់មានទិន្នន័យកន្លែងទេសចរណ៍នៅឡើយទេ</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $tours->links() }}
</div>