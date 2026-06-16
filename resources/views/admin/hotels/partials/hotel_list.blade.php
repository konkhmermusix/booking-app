<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">សណ្ឋាគារ</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ទំនាក់ទំនង</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($hotels as $hotel)
                <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-all">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-4">
                            <img src="{{ $hotel->logo ? asset('storage/'.$hotel->logo) : 'https://ui-avatars.com/api/?background=random&name='.urlencode($hotel->name) }}"
                                class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                            <div>
                                <div class="font-bold text-sm dark:text-white">{{ $hotel->name }}</div>
                                <div class="text-[11px] text-gray-400">{{ Str::limit($hotel->address, 30) }}</div>
                            </div>
                        </div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm dark:text-gray-300">{{ $hotel->phone }}</div>
                        <div class="text-[11px] text-gray-400">{{ $hotel->email }}</div>
                    </td>

                    <td class="px-6 py-4">
                        @if($hotel->status == 1)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> សកម្ម
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 dark:bg-gray-500/10 dark:text-gray-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> ផ្អាក
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button @click="currentHotel = {{ $hotel->toJson() }}; showDetailModal = true"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលម្អិត">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <button @click="currentHotel = {{ $hotel->toJson() }}; showEditModal = true"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.form)"
                                    class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    title="លុប">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">មិនមានទិន្នន័យឡើយ</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div x-show="viewMode === 'list'" class="space-y-3" x-cloak>
    @foreach($hotels as $hotel)
    <div class="group bg-white dark:bg-gray-900 rounded-2xl p-4 border border-transparent hover:border-blue-500/30 shadow-sm hover:shadow-xl transition-all duration-300">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ $hotel->logo ? asset('storage/'.$hotel->logo) : 'https://ui-avatars.com/api/?background=random&name='.urlencode($hotel->name) }}"
                        class="w-16 h-16 rounded-2xl object-cover shadow-inner bg-gray-100 dark:bg-gray-800">

                    <span class="absolute -top-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-gray-900 {{ $hotel->status == 1 ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="font-black text-gray-800 dark:text-white tracking-tight">{{ $hotel->name }}</h4>
                        @if($hotel->status == 1)
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">សកម្ម</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-1 text-[11px] text-gray-400 mt-0.5">
                        <i class="fa-solid fa-location-dot text-[10px]"></i>
                        <span>{{ Str::limit($hotel->address, 50) }}</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4 md:gap-8 px-2 md:px-0">
                <div class="space-y-1">
                    <div class="flex items-center gap-2 text-xs font-medium dark:text-gray-300">
                        <div class="w-6 h-6 rounded-lg bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500">
                            <i class="fa-solid fa-phone text-[10px]"></i>
                        </div>
                        <span>{{ $hotel->phone }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-medium dark:text-gray-300">
                        <div class="w-6 h-6 rounded-lg bg-purple-50 dark:bg-purple-500/10 flex items-center justify-center text-purple-500">
                            <i class="fa-solid fa-envelope text-[10px]"></i>
                        </div>
                        <span class="text-gray-400">{{ $hotel->email }}</span>
                    </div>
                </div>

                <div class="flex justify-end gap-1">
                    <button @click="currentHotel = {{ json_encode($hotel) }}; showDetailModal = true" ​​ title="មើលលម្អិត" class="p-2 text-gray-400 hover:text-blue-500"><i class="fas fa-eye text-sm"></i></button>
                    <button @click="currentHotel = {{ json_encode($hotel) }}; showEditModal = true" title="កែប្រែ" class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-edit text-sm"></i></button>
                    <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmDelete(this.form)"
                            class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"
                            title="លុប">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


<div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-cloak>
    @foreach($hotels as $hotel)
    <div class="bg-white dark:bg-gray-900 rounded-2xl dark:border-gray-800 p-5 shadow-sm hover:shadow-md transition-all group">
        <div class="relative aspect-video rounded-2xl overflow-hidden bg-gray-100 mb-4">
            <img src="{{ $hotel->logo ? asset('storage/'.$hotel->logo) : 'https://ui-avatars.com/api/?background=random&name='.urlencode($hotel->name) }}"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            <div class="absolute top-3 right-3">

                @if($hotel->status == 1)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> សកម្ម
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-600 dark:bg-gray-500/10 dark:text-gray-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> ផ្អាក
                </span>
                @endif
            </div>
        </div>
        <h3 class="font-bold text-lg dark:text-white mb-2">{{ $hotel->name }}</h3>
        <div class="space-y-2 mb-5">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i class="fas fa-phone-alt w-4 text-blue-500"></i> {{ $hotel->phone }}
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <i class="fas fa-map-marker-alt w-4 text-red-500"></i> {{ Str::limit($hotel->address, 40) }}
            </div>
        </div>

        <div class="flex gap-2">
            <button @click="currentHotel = {{ json_encode($hotel) }}; showDetailModal = true" ​​ title="មើលលម្អិត" class="p-2 text-gray-400 hover:text-blue-500"><i class="fas fa-eye text-sm"></i></button>
            <button @click="currentHotel = {{ json_encode($hotel) }}; showEditModal = true" title="កែប្រែ" class="p-2 text-gray-400 hover:text-amber-500"><i class="fas fa-edit text-sm"></i></button>
            <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete(this.form)"
                    class="btn-delete p-2 text-gray-400 hover:text-red-500 transition-colors"
                    title="លុប">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
    <div class="dark:text-white">
        {{ $hotels->links() }}
    </div>
</div>