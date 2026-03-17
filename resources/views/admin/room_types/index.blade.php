@extends('layouts.admin')
@section('title', 'បញ្ជីប្រភេទបន្ទប់')

@section('content')
<div class="space-y-6" x-data="{  viewMode: localStorage.getItem('roomTypeView') || 'list', showAddModal: false, showEditModal: false, showDetailModal: false,  currentType: { hotel: {}, images: [], facilities: [], newPreviews: [] }}">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-5 rounded-[2rem] border dark:border-gray-800 shadow-sm">
        <div class="shrink-0">
            <h2 class="text-2xl font-bold dark:text-white tracking-tight">គ្រប់គ្រងប្រភេទបន្ទប់</h2>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <form action="{{ route('room_types.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="ស្វែងរកឈ្មោះប្រភេទបន្ទប់..."
                        class="w-full pl-10 pr-4 h-[52px] bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
                </div>


                <div class="w-full sm:w-48">
                    <select name="sort_price" onchange="this.form.submit()"
                        class="w-full h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-gray-300 font-medium">
                        <option value="">តម្រៀបតាមតម្លៃ</option>
                        <option value="asc" {{ request('sort_price') == 'asc' ? 'selected' : '' }}>តម្លៃទាប ទៅខ្ពស់</option>
                        <option value="desc" {{ request('sort_price') == 'desc' ? 'selected' : '' }}>តម្លៃខ្ពស់ ទៅទាប</option>
                    </select>
                </div>
            </form>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1.5 rounded-2xl h-[52px] items-center">
                <button @click="viewMode = 'list'; localStorage.setItem('roomTypeView', 'list')"
                    :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                    class="w-10 h-full rounded-xl transition-all flex items-center justify-center">
                    <i class="fas fa-list-ul text-sm"></i>
                </button>
                <button @click="viewMode = 'grid'; localStorage.setItem('roomTypeView', 'grid')"
                    :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                    class="w-10 h-full rounded-xl transition-all flex items-center justify-center">
                    <i class="fas fa-th-large text-sm"></i>
                </button>
            </div>

            <button @click="showAddModal = true"
                class="w-full sm:w-auto h-[52px] px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-lg shadow-blue-500/30 transition-all flex items-center justify-center gap-2 font-bold active:scale-95">
                <i class="fas fa-plus-circle text-lg"></i>
                <span class="whitespace-nowrap">បន្ថែមប្រភេទបន្ទប់</span>
            </button>

        </div>
    </div>

    <div class="relative">
        <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" class="bg-white dark:bg-gray-900 rounded-3xl border dark:border-gray-800 overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold tracking-wider">
                    <tr>
                        <th class="px-6 py-5">ឈ្មោះ / ពិរពណ៍នា</th>
                        <th class="px-6 py-5">ឈ្មោះសណ្ឋាគារ</th>
                        <th class="px-6 py-5">ចំនួននាក់</th>
                        <th class="px-6 py-5">តម្លៃ</th>
                        <th class="px-6 py-5 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y dark:divide-gray-800">
                    @foreach($roomTypes as $type)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg overflow-hidden border dark:border-gray-700 bg-gray-100 flex-shrink-0">
                                    @php
                                    // ទាញយករូប Primary បើអត់មាន យករូបទី១
                                    $displayImage = $type->images->where('is_primary', true)->first() ?? $type->images->first();
                                    @endphp

                                    @if($displayImage)
                                    <img src="{{ asset('storage/' . $displayImage->image_path) }}"
                                        class="w-full h-full object-cover hover:scale-150 transition-transform duration-300 cursor-zoom-in">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                                        <i class="fas fa-image text-xs"></i>
                                    </div>
                                    @endif
                                </div>

                                <div>
                                    <div class="font-bold dark:text-white">{{ $type->name }}</div>
                                    <div class="text-xs text-gray-400">{{ Str::limit($type->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 dark:text-gray-300 font-medium">{{ $type->hotel->name }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 rounded-md text-sm">
                                <i class="fas fa-users mr-1"></i> {{ $type->max_guests }} នាក់
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-600 dark:text-emerald-400">
                            ${{ number_format($type->base_price, 2) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <button @click="currentType = {{ json_encode($type) }}; showEditModal = true"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-500/10 transition-all"><i class="fas fa-eye"></i>
                                </button>

                                <button @click="currentType = {{ json_encode($type) }}; showEditModal = true"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:bg-amber-50 hover:text-amber-600 dark:hover:bg-amber-500/10 transition-all"><i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('room_types.destroy', $type->id) }}" method="POST" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
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

        <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($roomTypes as $type)
            <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] border dark:border-gray-800 overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all group shadow-sm">
                <div class="relative h-44 bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">

                    @if($displayImage)
                    <img src="{{ asset('storage/' . $displayImage->image_path) }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-50">
                        <i class="fas fa-image text-xs"></i>
                    </div>
                    @endif

                    <div class="absolute top-4 right-4">
                        <span class="text-[10px] font-bold px-2 py-1 bg-gray-100 dark:bg-gray-800 dark:text-gray-400 rounded-md">
                            តម្លៃ ${{ number_format($type->base_price, 2) }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-black dark:text-white">{{ $type->name }}</h3>
                            <p class="text-xs text-gray-400 font-medium uppercase tracking-widest">{{ Str::limit($type->description, 50) }}</p>
                        </div>
                        <span class="px-4 py-1 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 rounded-md text-sm">
                            {{ $type->max_guests }}នាក់
                        </span>
                    </div>

                    <div class="flex items-center justify-between border-t dark:border-gray-800 pt-4 mt-4">
                        <span class="text-blue-600 dark:text-blue-400 font-bold text-xs uppercase">{{-- $room->roomType->name --}}</span>
                        <div class="flex gap-1">
                            <button @click="currentType = {{ json_encode($type) }}; showEditModal = true"
                                class="p-2 text-gray-400 hover:text-blue-500">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button @click="currentType = {{ json_encode($type) }}; showEditModal = true"
                                class="p-2 text-gray-400 hover:text-amber-500">
                                <i class="fas fa-edit"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class=" lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-5 rounded-[2rem] border dark:border-gray-800 shadow-sm">
        <div>
            {{ $roomTypes->links() }}
        </div>
    </div>
    @include('admin.room_types.modals')
</div>
@endsection