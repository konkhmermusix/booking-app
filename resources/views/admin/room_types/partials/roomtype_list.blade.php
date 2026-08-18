<!-- Table View -->
<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ឈ្មោះប្រភេទ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ប្រភេទសេវា</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">សណ្ឋាគារ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-center">ភ្ញៀវអតិបរមា</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-center">ចំនួនបន្ទប់</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">តម្លៃគោល</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($roomTypes as $type)
                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-all">
                    <td class="px-6 py-4 font-bold dark:text-white uppercase text-xs flex items-center gap-4">
                        <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                            @if($type->images && $type->images->count() > 0)
                            <img src="{{ asset('storage/' . $type->images->first()->image_path) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-400">
                                <i class="fa-solid fa-bed text-xs"></i>
                            </div>
                            @endif
                        </div>
                        <span class="truncate font-black text-sm text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors">{{ $type->name }}</span>
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        @if(($type->category ?? 'stay') === 'meeting')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-700 dark:bg-purple-500/10 dark:text-purple-400">
                            <i class="fa-solid fa-handshake text-[9px]"></i> សាលប្រជុំ
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <i class="fa-solid fa-bed text-[9px]"></i> បន្ទប់ស្នាក់នៅ
                        </span>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 font-medium">
                        <div class="flex items-center gap-1.5 text-xs text-gray-700 dark:text-gray-300 font-semibold">
                            <i class="fa-solid fa-hotel text-[10px] text-blue-500"></i>
                            <span>{{ $type->hotel->name ?? 'មិនមាន' }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-sm text-center font-bold text-blue-600 dark:text-blue-400">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 text-xs">
                            <i class="fa-solid fa-users text-[10px]"></i> {{ $type->max_guests }} នាក់
                        </span>
                    </td>

                    <td class="px-6 py-4 text-sm text-center font-bold">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 text-xs">
                            <i class="fa-solid fa-door-open text-[10px]"></i> {{ $type->rooms_count ?? 0 }} បន្ទប់
                        </span>
                    </td>

                    <td class="px-6 py-4 font-black text-green-600 dark:text-green-400 text-sm whitespace-nowrap">
                        <div>${{ number_format($type->base_price, 2) }}</div>
                        <div class="text-[11px] text-gray-400 font-normal font-mono">({{ number_format($type->base_price * $khrRate) }} ៛)</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end items-center gap-1">
                            <button type="button"
                                @click="openDetailModal({{ json_encode([
                                    'id' => $type->id,
                                    'name' => $type->name,
                                    'hotel_id' => $type->hotel_id,
                                    'hotel' => ['name' => $type->hotel->name ?? 'មិនមាន'],
                                    'category' => $type->category ?? 'stay',
                                    'base_price' => $type->base_price,
                                    'max_guests' => $type->max_guests,
                                    'description' => $type->description ?? '',
                                    'facilities' => $type->facilities,
                                    'images' => $type->images->map(function($img) { return ['id' => $img->id, 'url' => asset('storage/'.$img->image_path)]; })->toArray()
                                ]) }})"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors"
                                title="មើលលម្អិត">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <button type="button"
                                @click="openEditModal({{ json_encode([
                                    'id' => $type->id,
                                    'name' => $type->name,
                                    'hotel_id' => $type->hotel_id,
                                    'category' => $type->category ?? 'stay',
                                    'base_price' => $type->base_price,
                                    'max_guests' => $type->max_guests,
                                    'description' => $type->description ?? '',
                                    'facilities' => $type->facilities,
                                    'images' => $type->images
                                ]) }})"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors"
                                title="កែប្រែ">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <form action="{{ route('room_types.destroy', $type->id) }}" method="POST" class="inline m-0">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="confirmDelete(this.form)"
                                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    title="លុប">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12">
                        @include('admin.room_types.partials.empty-state')
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Grid View -->
<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition x-cloak>
    @forelse($roomTypes as $type)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border-none flex flex-col justify-between">
        <div>
            <div class="h-44 -mx-5 -mt-5 mb-4 overflow-hidden rounded-t-2xl relative group/img bg-gray-100 dark:bg-gray-700">
                @if($type->images->count() > 0)
                <img src="{{ asset('storage/' . $type->images->first()->image_path) }}"
                    class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500">
                @else
                <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center">
                    <i class="fa-solid fa-image text-gray-300 text-3xl mb-2"></i>
                    <span class="text-[9px] text-gray-400 uppercase font-black">គ្មានរូបភាព</span>
                </div>
                @endif

                <!-- Image Overlay Action Buttons -->
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300">
                    <button type="button"
                        @click="openDetailModal({{ json_encode([
                            'id' => $type->id,
                            'name' => $type->name,
                            'hotel_id' => $type->hotel_id,
                            'hotel' => ['name' => $type->hotel->name ?? 'មិនមាន'],
                            'category' => $type->category ?? 'stay',
                            'base_price' => $type->base_price,
                            'max_guests' => $type->max_guests,
                            'description' => $type->description ?? '',
                            'facilities' => $type->facilities,
                            'images' => $type->images->map(function($img) { return ['id' => $img->id, 'url' => asset('storage/'.$img->image_path)]; })->toArray()
                        ]) }})"
                        class="p-2.5 bg-white/90 hover:bg-white text-gray-700 hover:text-blue-600 rounded-xl transition-all shadow-md" title="មើលលម្អិត">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </button>

                    <button type="button"
                        @click="openEditModal({{ json_encode([
                            'id' => $type->id,
                            'name' => $type->name,
                            'hotel_id' => $type->hotel_id,
                            'category' => $type->category ?? 'stay',
                            'base_price' => $type->base_price,
                            'max_guests' => $type->max_guests,
                            'description' => $type->description ?? '',
                            'facilities' => $type->facilities,
                            'images' => $type->images
                        ]) }})"
                        class="p-2.5 bg-white/90 hover:bg-white text-gray-700 hover:text-amber-600 rounded-xl transition-all shadow-md" title="កែប្រែ">
                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                    </button>
                </div>

                <!-- Price Overlay Badge -->
                <div class="absolute bottom-3 left-3 bg-white/95 dark:bg-gray-900/95 backdrop-blur px-3 py-1 rounded-xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <span class="text-xs font-black text-green-600 dark:text-green-400">${{ number_format($type->base_price, 2) }}</span>
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-400 ml-1 font-mono">({{ number_format($type->base_price * $khrRate) }} ៛)</span>
                </div>

                <!-- Category Overlay Badge -->
                <div class="absolute top-3 right-3">
                    @if(($type->category ?? 'stay') === 'meeting')
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-purple-600 text-white shadow-sm">
                        <i class="fa-solid fa-handshake mr-1"></i>Meeting
                    </span>
                    @else
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-600 text-white shadow-sm">
                        <i class="fa-solid fa-bed mr-1"></i>Stay
                    </span>
                    @endif
                </div>
            </div>

            <h3 class="font-bold text-base text-gray-800 dark:text-gray-100 uppercase tracking-tight truncate group-hover:text-blue-600 transition-colors">{{ $type->name }}</h3>

            <div class="flex items-center gap-2 mt-2 mb-4">
                <div class="flex items-center gap-1 text-[10px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md">
                    <i class="fa-solid fa-users"></i> {{ $type->max_guests }} នាក់
                </div>
                <div class="flex items-center gap-1 text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-0.5 rounded-md">
                    <i class="fa-solid fa-bed"></i> {{ $type->rooms_count ?? 0 }} បន្ទប់
                </div>
            </div>

            <div class="pt-3 border-t dark:border-gray-700 flex items-center justify-between">
                <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-widest truncate">
                    <i class="fa-solid fa-hotel mr-1 text-[8px] text-blue-500"></i> {{ $type->hotel->name ?? 'N/A' }}
                </p>

                <div class="flex items-center gap-1">
                    <button type="button"
                        @click="openDetailModal({{ json_encode([
                            'id' => $type->id,
                            'name' => $type->name,
                            'hotel_id' => $type->hotel_id,
                            'hotel' => ['name' => $type->hotel->name ?? 'មិនមាន'],
                            'category' => $type->category ?? 'stay',
                            'base_price' => $type->base_price,
                            'max_guests' => $type->max_guests,
                            'description' => $type->description ?? '',
                            'facilities' => $type->facilities,
                            'images' => $type->images->map(function($img) { return ['id' => $img->id, 'url' => asset('storage/'.$img->image_path)]; })->toArray()
                        ]) }})"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all" title="មើលលម្អិត">
                        <i class="fas fa-eye text-xs"></i>
                    </button>
                    <button type="button"
                        @click="openEditModal({{ json_encode([
                            'id' => $type->id,
                            'name' => $type->name,
                            'hotel_id' => $type->hotel_id,
                            'category' => $type->category ?? 'stay',
                            'base_price' => $type->base_price,
                            'max_guests' => $type->max_guests,
                            'description' => $type->description ?? '',
                            'facilities' => $type->facilities,
                            'images' => $type->images
                        ]) }})"
                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-all" title="កែប្រែ">
                        <i class="fas fa-edit text-xs"></i>
                    </button>
                    <form action="{{ route('room_types.destroy', $type->id) }}" method="POST" class="inline m-0">
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete(this.form)" class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all" title="លុប">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        @include('admin.room_types.partials.empty-state')
    </div>
    @endforelse
</div>

<!-- List View -->
<div x-show="viewMode === 'list'" class="space-y-3" x-transition x-cloak>
    @forelse($roomTypes as $type)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-none">
        <div class="flex items-center gap-4">
            <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                @if($type->images->count() > 0)
                <img src="{{ asset('storage/' . $type->images->first()->image_path) }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-400">
                    <i class="fa-solid fa-bed text-xs"></i>
                </div>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 uppercase">{{ $type->name }}</h4>
                    @if(($type->category ?? 'stay') === 'meeting')
                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400">Meeting</span>
                    @else
                    <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">Stay</span>
                    @endif
                </div>
                <p class="text-[11px] text-gray-400 mt-0.5"><i class="fa-solid fa-hotel text-blue-500 mr-1"></i>{{ $type->hotel->name ?? 'N/A' }} | ភ្ញៀវអតិបរមា៖ {{ $type->max_guests }} នាក់ | បន្ទប់៖ {{ $type->rooms_count ?? 0 }}</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-right">
                <span class="text-sm font-black text-green-600 dark:text-green-400">
                    ${{ number_format($type->base_price, 2) }}
                </span>
                <span class="text-[11px] text-gray-400 block font-normal font-mono">
                    ({{ number_format($type->base_price * $khrRate) }} ៛)
                </span>
            </div>

            <div class="flex items-center gap-1">
                <button type="button"
                    @click="openDetailModal({{ json_encode([
                        'id' => $type->id,
                        'name' => $type->name,
                        'hotel_id' => $type->hotel_id,
                        'hotel' => ['name' => $type->hotel->name ?? 'មិនមាន'],
                        'category' => $type->category ?? 'stay',
                        'base_price' => $type->base_price,
                        'max_guests' => $type->max_guests,
                        'description' => $type->description ?? '',
                        'facilities' => $type->facilities,
                        'images' => $type->images->map(function($img) { return ['id' => $img->id, 'url' => asset('storage/'.$img->image_path)]; })->toArray()
                    ]) }})"
                    class="p-2 text-gray-400 hover:text-blue-500 transition-colors"
                    title="មើលលម្អិត">
                    <i class="fas fa-eye text-sm"></i>
                </button>

                <button type="button"
                    @click="openEditModal({{ json_encode([
                        'id' => $type->id,
                        'name' => $type->name,
                        'hotel_id' => $type->hotel_id,
                        'category' => $type->category ?? 'stay',
                        'base_price' => $type->base_price,
                        'max_guests' => $type->max_guests,
                        'description' => $type->description ?? '',
                        'facilities' => $type->facilities,
                        'images' => $type->images
                    ]) }})"
                    class="p-2 text-gray-400 hover:text-amber-500 transition-colors"
                    title="កែប្រែ">
                    <i class="fas fa-edit text-sm"></i>
                </button>

                <form action="{{ route('room_types.destroy', $type->id) }}" method="POST" class="inline m-0">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                        onclick="confirmDelete(this.form)"
                        class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                        title="លុប">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">@include('admin.room_types.partials.empty-state')</div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm border-none">
    <div class="dark:text-white">
        {{ $roomTypes->links() }}
    </div>
</div>