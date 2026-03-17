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
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-5 rounded-[2rem] border dark:border-gray-800 shadow-sm">
        <div class="shrink-0">
            <h2 class="text-2xl font-bold dark:text-white tracking-tight">គ្រប់គ្រងសណ្ឋាគារ</h2>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('hotels.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ស្វែងរកឈ្មោះសណ្ឋាគារ..."
                        class="w-full pl-10 pr-4 h-[52px] bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
                </div>

                <div class="w-full sm:w-48">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-gray-300 font-medium cursor-pointer">
                        <option value="">ស្ថានភាពទាំងអស់</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>សកម្ម</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>ផ្អាក</option>
                    </select>
                </div>
            </form>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1.5 rounded-2xl h-[52px] items-center">
                <button @click="viewMode = 'list'; localStorage.setItem('hotelView', 'list')"
                    :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                    class="w-10 h-full rounded-xl transition-all flex items-center justify-center">
                    <i class="fas fa-list-ul text-sm"></i>
                </button>
                <button @click="viewMode = 'grid'; localStorage.setItem('hotelView', 'grid')"
                    :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                    class="w-10 h-full rounded-xl transition-all flex items-center justify-center">
                    <i class="fas fa-th-large text-sm"></i>
                </button>
            </div>

            <button @click="showAddModal = true"
                class="w-full sm:w-auto h-[52px] px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2 font-bold active:scale-95">
                <i class="fas fa-plus-circle text-lg"></i>
                <span class="whitespace-nowrap">បន្ថែមសណ្ឋាគារ</span>
            </button>
        </div>
    </div>

    <div>
        <template x-if="viewMode === 'list'">
            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border dark:border-gray-800 overflow-hidden shadow-sm">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-800/50 border-b dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest">សណ្ឋាគារ</th>
                            <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest">ទំនាក់ទំនង</th>
                            <th class="px-6 py-4 text-[11px] font-black text-gray-400 uppercase tracking-widest">ស្ថានភាព</th>
                            <th class="px-6 py-4 text-right text-[11px] font-black text-gray-400 uppercase tracking-widest">សកម្មភាព</th>
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
                                    <button @click="currentHotel = {{ $hotel->toJson() }}; showDetailModal = true" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-500/10 transition-all"><i class="fas fa-eye"></i>
                                    </button>
                                    <button @click="currentHotel = {{ $hotel->toJson() }}; showEditModal = true"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 transition-all"><i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('hotels.destroy', $hotel->id) }}" method="POST" class="inline delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-all">
                                            <i class="fas fa-trash"></i>
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

        <template x-if="viewMode === 'grid'">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($hotels as $hotel)
                <div class="bg-white dark:bg-gray-900 rounded-[2rem] border dark:border-gray-800 p-5 shadow-sm hover:shadow-md transition-all group">
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
    <div class=" lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-5 rounded-[2rem] border dark:border-gray-800 shadow-sm">
        <div>
            {{ $hotels->links() }}
        </div>
    </div>

    @include('admin.hotels.modals')
</div>
@endsection