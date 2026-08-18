<!-- Table View -->
<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">សណ្ឋាគារ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ទំនាក់ទំនង</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-center">ចំនួនបន្ទប់</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($hotels as $hotel)
                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-all">
                    <td class="px-6 py-4 font-bold dark:text-white uppercase text-xs flex items-center gap-4">
                        <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                            @if($hotel->logo)
                            <img src="{{ asset('storage/' . $hotel->logo) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-400">
                                <i class="fa-solid fa-hotel text-xs"></i>
                            </div>
                            @endif
                        </div>
                        <div>
                            <div class="truncate font-black text-sm text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors">{{ $hotel->name }}</div>
                            <div class="text-[11px] text-gray-400 font-normal normal-case flex items-center gap-1 mt-0.5">
                                <i class="fa-solid fa-location-dot text-[10px] text-red-400"></i>
                                <span>{{ Str::limit($hotel->address, 35) }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        <div class="text-xs text-gray-700 dark:text-gray-300 font-bold flex items-center gap-1.5">
                            <i class="fa-solid fa-phone text-[10px] text-blue-500"></i>
                            <span>{{ $hotel->phone ?? 'មិនមាន' }}</span>
                        </div>
                        <div class="text-[11px] text-gray-400 flex items-center gap-1.5 mt-0.5">
                            <i class="fa-solid fa-envelope text-[10px] text-purple-400"></i>
                            <span>{{ $hotel->email ?? 'មិនមាន' }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-center font-bold text-blue-600 dark:text-blue-400">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-xl text-xs font-black bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                            <i class="fa-solid fa-bed text-[10px]"></i>
                            {{ $hotel->rooms_count ?? count($hotel->rooms ?? []) }} បន្ទប់
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($hotel->status == 1)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> សកម្ម
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> ផ្អាក
                        </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        @include('admin.hotels.partials.actions')
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12">
                        @include('admin.hotels.partials.empty-state')
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- List View -->
<div x-show="viewMode === 'list'" class="space-y-3" x-cloak>
    @forelse($hotels as $hotel)
    <div class="group bg-white dark:bg-gray-800 rounded-2xl p-4 border border-transparent hover:border-blue-500/30 shadow-sm hover:shadow-xl transition-all duration-300">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

            <div class="flex items-center gap-4">
                <div class="relative shrink-0">
                    <img src="{{ $hotel->logo ? asset('storage/'.$hotel->logo) : 'https://ui-avatars.com/api/?background=3b82f6&color=ffffff&name='.urlencode($hotel->name) }}"
                        class="w-16 h-16 rounded-2xl object-cover shadow-inner bg-gray-100 dark:bg-gray-700">
                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-800 {{ $hotel->status == 1 ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                </div>

                <div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <h4 class="font-black text-gray-800 dark:text-white tracking-tight group-hover:text-blue-500 transition-colors">{{ $hotel->name }}</h4>
                        @if($hotel->status == 1)
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">សកម្ម</span>
                        @else
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">ផ្អាក</span>
                        @endif
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                            <i class="fa-solid fa-bed text-[9px] mr-1"></i>{{ $hotel->rooms_count ?? count($hotel->rooms ?? []) }} បន្ទប់
                        </span>
                    </div>
                    <div class="flex items-center gap-1 text-[11px] text-gray-400 mt-1">
                        <i class="fa-solid fa-location-dot text-[10px] text-red-400"></i>
                        <span>{{ Str::limit($hotel->address, 60) }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-between md:justify-end gap-4 md:gap-8 pt-3 md:pt-0 border-t md:border-t-0 dark:border-gray-700">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-xs font-medium dark:text-gray-300">
                        <div class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-phone text-[10px]"></i>
                        </div>
                        <span>{{ $hotel->phone ?? 'មិនមាន' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-medium dark:text-gray-300">
                        <div class="w-6 h-6 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-500">
                            <i class="fa-solid fa-envelope text-[10px]"></i>
                        </div>
                        <span class="text-gray-400">{{ $hotel->email ?? 'មិនមាន' }}</span>
                    </div>
                </div>

                @include('admin.hotels.partials.actions')
            </div>
        </div>
    </div>
    @empty
    @include('admin.hotels.partials.empty-state')
    @endforelse
</div>

<!-- Grid View -->
<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition x-cloak>
    @forelse($hotels as $hotel)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border-none flex flex-col justify-between">
        <div>
            <div class="h-44 -mx-5 -mt-5 mb-4 overflow-hidden rounded-t-2xl relative group/img bg-gray-100 dark:bg-gray-700">
                @if($hotel->logo)
                <img src="{{ asset('storage/' . $hotel->logo) }}"
                    class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500">
                @else
                <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center">
                    <i class="fa-solid fa-hotel text-gray-300 text-3xl mb-2"></i>
                    <span class="text-[9px] text-gray-400 uppercase font-black">គ្មានរូបភាព</span>
                </div>
                @endif

                <!-- Image Overlay Action Buttons -->
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300">
                    <button type="button"
                        @click="openDetailModal({{ json_encode([
                            'id' => $hotel->id,
                            'name' => $hotel->name,
                            'email' => $hotel->email,
                            'phone' => $hotel->phone,
                            'address' => $hotel->address,
                            'description' => $hotel->description ?? '',
                            'latitude' => $hotel->latitude,
                            'longitude' => $hotel->longitude,
                            'status' => $hotel->status,
                            'logo' => $hotel->logo,
                            'rooms_count' => $hotel->rooms_count ?? count($hotel->rooms ?? [])
                        ]) }})"
                        class="p-2.5 bg-white/90 hover:bg-white text-gray-700 hover:text-blue-600 rounded-xl transition-all shadow-md" title="មើលលម្អិត">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>

                    <button type="button"
                        @click="openEditModal({{ json_encode([
                            'id' => $hotel->id,
                            'name' => $hotel->name,
                            'email' => $hotel->email,
                            'phone' => $hotel->phone,
                            'address' => $hotel->address,
                            'description' => $hotel->description ?? '',
                            'latitude' => $hotel->latitude,
                            'longitude' => $hotel->longitude,
                            'status' => $hotel->status,
                            'logo' => $hotel->logo
                        ]) }})"
                        class="p-2.5 bg-white/90 hover:bg-white text-gray-700 hover:text-amber-600 rounded-xl transition-all shadow-md" title="កែប្រែ">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </button>
                </div>

                <!-- Status Badge Overlay -->
                <div class="absolute top-3 right-3">
                    @if($hotel->status == 1)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 dark:bg-gray-900/90 text-emerald-600 shadow-sm backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> សកម្ម
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/90 dark:bg-gray-900/90 text-amber-600 shadow-sm backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> ផ្អាក
                    </span>
                    @endif
                </div>

                <!-- Room Count Overlay -->
                <div class="absolute bottom-3 left-3">
                    <span class="px-2.5 py-1 rounded-xl text-[10px] font-black bg-blue-600 text-white shadow-md">
                        <i class="fa-solid fa-bed mr-1"></i>{{ $hotel->rooms_count ?? count($hotel->rooms ?? []) }} បន្ទប់
                    </span>
                </div>
            </div>

            <h3 class="font-bold text-base text-gray-800 dark:text-white mb-2 group-hover:text-blue-500 transition-colors">{{ $hotel->name }}</h3>

            <div class="space-y-2 mb-4">
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-phone-alt w-4 text-blue-500 shrink-0"></i>
                    <span>{{ $hotel->phone ?? 'មិនមាន' }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-envelope w-4 text-purple-500 shrink-0"></i>
                    <span class="truncate">{{ $hotel->email ?? 'មិនមាន' }}</span>
                </div>
                <div class="flex items-start gap-2 text-xs text-gray-500 dark:text-gray-400">
                    <i class="fas fa-map-marker-alt w-4 text-red-500 shrink-0 mt-0.5"></i>
                    <span>{{ Str::limit($hotel->address, 45) }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-3 border-t dark:border-gray-700">
            <span class="text-[11px] text-gray-400 font-bold">ID: #{{ $hotel->id }}</span>
            @include('admin.hotels.partials.actions')
        </div>
    </div>
    @empty
    <div class="col-span-full">
        @include('admin.hotels.partials.empty-state')
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm transition-colors">
    <div class="dark:text-white">
        {{ $hotels->links() }}
    </div>
</div>