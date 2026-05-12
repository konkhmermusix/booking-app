<div x-show="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" x-transition>
    @forelse($roomTypes as $type)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all group border-none">

        <div class="h-44 -mx-5 -mt-5 mb-4 overflow-hidden rounded-t-2xl relative group/img">
            @if($type->images->count() > 0)
            <img src="{{ asset('storage/' . $type->images->first()->image_path) }}"
                class="w-full h-full object-cover group-hover/img:scale-110 transition-transform duration-500">
            @else
            <div class="w-full h-full bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center">
                <i class="fa-solid fa-image text-gray-300 text-3xl mb-2"></i>
                <span class="text-[9px] text-gray-400 uppercase font-black">គ្មានរូបភាព</span>
            </div>
            @endif

            <div class="absolute inset-0 bg-black/40 flex items-center justify-center gap-2 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300">
                <button @click="currentRoomType = { 
                    id: '{{ $type->id }}', 
                    name: '{{ $type->name }}', 
                    hotel_name: '{{ $type->hotel->name ?? 'N/A' }}',
                    base_price: '{{ $type->base_price }}',
                    max_guests: '{{ $type->max_guests }}',
                    description: '{{ addslashes($type->description) }}',
                    facilities: {{ $type->facilities->toJson() }},
                    
                    images: {{ $type->images->map(fn($img) => ['id' => $img->id, 'url' => asset('storage/'.$img->image_path)])->toJson() }}
                }; showDetailModal = true"
                    class="w-10 h-10 bg-white/20 backdrop-blur-md text-white rounded-xl hover:bg-white hover:text-blue-600 transition-all">
                    <i class="fas fa-eye"></i>
                </button>

                <button @click="openEditModal({{ $type->load(['facilities', 'images']) }})"
                    class="w-10 h-10 bg-white/20 backdrop-blur-md text-white rounded-xl hover:bg-white hover:text-red-500 transition-all">
                    <i class="fas fa-edit"></i>
                </button>

                <button onclick="confirmDelete('{{ $type->id }}')"
                    class="w-10 h-10 bg-white/20 backdrop-blur-md text-white rounded-xl hover:bg-white hover:text-red-500 transition-all">
                    <i class="fas fa-trash"></i>
                </button>
            </div>

            <div class="absolute bottom-3 left-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-3 py-1 rounded-lg shadow-sm">
                <span class="text-xs font-black text-blue-600">${{ number_format($type->base_price, 0) }}</span>
            </div>
        </div>

        <h3 class="font-bold text-gray-800 dark:text-gray-100 uppercase tracking-tight truncate">{{ $type->name }}</h3>

        <div class="flex items-center gap-2 mt-2 mb-4">
            <div class="flex items-center gap-1 text-[10px] font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md">
                <i class="fa-solid fa-users"></i> {{ $type->max_guests }} នាក់
            </div>
            <div class="flex items-center gap-1 text-[10px] font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30 px-2 py-0.5 rounded-md">
                <i class="fa-solid fa-bed"></i> {{ $type->rooms_count ?? 0 }} បន្ទប់
            </div>
        </div>

        <div class="pt-3 border-t dark:border-gray-700">
            <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-widest truncate">
                <i class="fa-solid fa-hotel mr-1 text-[8px]"></i> {{ $type->hotel->name ?? 'N/A' }}
            </p>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        @include('admin.room_types.partials.empty-state')
    </div>
    @endforelse
</div>

<div x-show="viewMode === 'list'" class="space-y-3" x-transition>
    @forelse($roomTypes as $type)
    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all border-none">
        <div class="flex items-center gap-4">
            <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                @if($type->images->count() > 0)
                <img src="{{ asset('storage/' . $type->images->first()->image_path) }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-400">
                    <i class="fa-solid fa-bed text-xs"></i>
                </div>
                @endif
            </div>
            <div>
                <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 uppercase">{{ $type->name }}</h4>
                <p class="text-[10px] text-gray-400 italic">{{ $type->hotel->name ?? 'N/A' }} | ភ្ញៀវអតិបរមា៖ {{ $type->max_guests }} នាក់</p>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <span class="text-sm font-black text-green-600 dark:text-green-400">
                ${{ number_format($type->base_price, 2) }}
            </span>

            <div class="flex gap-2">
                <button @click="currentRoomType = { 
                    id: '{{ $type->id }}', 
                    name: '{{ $type->name }}', 
                    hotel_name: '{{ $type->hotel->name ?? 'N/A' }}', 
                    base_price: '{{ $type->base_price }}',
                    max_guests: '{{ $type->max_guests }}',
                    description: '{{ addslashes($type->description) }}',
                    facilities: {{ $type->facilities->toJson() }},
                           
                    images: {{ $type->images->map(fn($img) => ['id' => $img->id, 'url' => asset('storage/'.$img->image_path)])->toJson() }}
                    }; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                <button @click="openEditModal({{ $type->load(['facilities', 'images']) }})"
                    class="p-2 text-gray-400 hover:text-amber-500 transition-colors">
                    <i class="fas fa-edit text-sm"></i>
                </button>
                <button onclick="confirmDelete('{{ $type->id }}')" class="btn-delete hover:text-red-500 dark:hover:text-red-400 p-2 transition-colors"><i class="fas fa-trash text-sm"></i></button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">@include('admin.room_types.partials.empty-state')</div>
    @endforelse
</div>

<div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden" x-transition>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="text-[11px] bg-gray-50/50 dark:bg-gray-900/50 ">
                <tr>
                    <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest">ឈ្មោះប្រភេទ</th>
                    <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest">សណ្ឋាគារ</th>
                    <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest text-center">ភ្ញៀវ</th>
                    <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest">តម្លៃគោល</th>
                    <th class="px-6 py-4 font-bold text-gray-400 uppercase tracking-widest text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($roomTypes as $type)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                    <td class="px-6 py-4 font-bold dark:text-white uppercase text-xs flex items-center gap-4">
                        <div class="w-16 h-12 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                            @if($type->images->count() > 0)
                            <img src="{{ asset('storage/' . $type->images->first()->image_path) }}" class="w-full h-full object-cover">
                            @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-400">
                                <i class="fa-solid fa-bed text-xs"></i>
                            </div>
                            @endif
                        </div>
                        {{ $type->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $type->hotel->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-sm text-center font-bold text-blue-600">{{ $type->max_guests }} នាក់</td>
                    <td class="px-6 py-4 font-black text-green-600">${{ number_format($type->base_price, 2) }}</td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <button @click="currentRoomType = { 
                        id: '{{ $type->id }}', 
                            name: '{{ $type->name }}', 
                            hotel_name: '{{ $type->hotel->name ?? 'N/A' }}',
                            base_price: '{{ $type->base_price }}',
                            max_guests: '{{ $type->max_guests }}',
                            description: '{{ addslashes($type->description) }}',
                            facilities: {{ $type->facilities->toJson() }},
                            
                            images: {{ $type->images->map(fn($img) => ['id' => $img->id, 'url' => asset('storage/'.$img->image_path)])->toJson() }}
                         }; showDetailModal=true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                        <button @click="openEditModal({{ $type->load(['facilities', 'images']) }})"
                            class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                            <i class="fas fa-edit text-sm"></i>
                        </button>
                        <button onclick="confirmDelete('{{ $type->id }}')" class="btn-delete hover:text-red-500 dark:hover:text-red-400 p-2 transition-colors"><i class="fas fa-trash text-sm"></i></button>
                    </td>
                </tr>
                @empty
                <div class="col-span-full">@include('admin.room_types.partials.empty-state')</div>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none">
    <div class="dark:text-white">
        {{ $roomTypes->links() }}
    </div>
</div>