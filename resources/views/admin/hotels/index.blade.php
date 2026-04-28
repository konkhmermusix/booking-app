@extends('layouts.admin')
@section('title', 'បញ្ជីសណ្ឋាគារ')

@section('content')
<div class="space-y-6" x-data="{ 
        viewMode: localStorage.getItem('hotelView') || 'list', 
        showAddModal: false, 
        showEditModal: false, 
        showDetailModal: false, 
        currentHotel: {}
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងសណ្ឋាគារ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Hotel Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <form action="{{ route('hotels.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-56">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ស្វែងរកឈ្មោះសណ្ឋាគារ..."
                        class="w-full pl-8 pr-4 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
                </div>

                <div class="w-full sm:w-25">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium cursor-pointer">
                        <option value="">ទាំងអស់</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>សកម្ម</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>ផ្អាក</option>
                    </select>
                </div>
            </form>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('roomView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-table-list"></i></button>
                <button @click="viewMode = 'list'; localStorage.setItem('roomView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('roomView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-th-large"></i></button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែម
            </button>
        </div>
    </div>

    <div>
        <template x-if="viewMode === 'table'">
            <div class="bg-white dark:bg-gray-900 rounded-2xl dark:border-gray-800 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800 text-sm">
                        <tr>
                            <th class="px-6 py-4 font-black text-gray-400 uppercase tracking-widest">សណ្ឋាគារ</th>
                            <th class="px-6 py-4 font-black text-gray-400 uppercase tracking-widest">ទំនាក់ទំនង</th>
                            <th class="px-6 py-4 font-black text-gray-400 uppercase tracking-widest">ស្ថានភាព</th>
                            <th class="px-6 py-4 text-right font-black text-gray-400 uppercase tracking-widest">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-800">
                        @foreach($hotels as $hotel)
                        <tr class="group hover:bg-gray-50/50 dark:hover:bg-gray-800/20 transition-all">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ $hotel->logo ? asset('storage/'.$hotel->logo) : 'https://ui-avatars.com/api/?background=random&name='.urlencode($hotel->name) }}"
                                        class="w-12 h-12 rounded-2xl object-cover shadow-sm">
                                    <div>
                                        <div class="font-bold dark:text-white">{{ $hotel->name }}</div>
                                        <div class="text-[11px] text-gray-400">{{ Str::limit($hotel->address, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </template>

        <template x-if="viewMode === 'list'">
            <div class="space-y-3">
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

                            <div class="flex items-center gap-2 ml-auto md:ml-0">
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
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </template>

        <template x-if="viewMode === 'grid'">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                        <button @click="currentHotel = {{ $hotel->toJson() }}; showEditModal = true" class="flex-1 h-10 bg-gray-100 dark:bg-gray-800 dark:text-gray-300 rounded-xl font-bold text-sm hover:bg-blue-600 hover:text-white transition-all"><i class="fas fa-edit"></i></button>
                        <button @click="currentHotel = {{ $hotel->toJson() }}; showDetailModal = true" class="w-10 h-10 flex items-center justify-center border dark:border-gray-700 rounded-xl text-gray-400 hover:text-blue-500 transition-all"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                @endforeach
            </div>
        </template>
    </div>
    <div class=" lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-5 rounded-2xl dark:border-gray-800 shadow-sm">
        <div>
            {{ $hotels->links() }}
        </div>
    </div>

    @include('admin.hotels.modals')
</div>
@endsection