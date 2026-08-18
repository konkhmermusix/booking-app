@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងបន្ទប់')
@section('content')
<div class="p-2 sm:p-4 space-y-6" x-data="roomManager">

    <!-- Header Action & Control Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                គ្រប់គ្រងបន្ទប់
            </h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Live Room Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-44">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchRooms()" placeholder="ស្វែងរកលេខបន្ទប់, សណ្ឋាគារ..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <!-- Room Type Filter Dropdown -->
            <div class="w-full sm:w-44">
                <div class="relative group">
                    <select x-model="room_type_id" @change="fetchRooms()" 
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">គ្រប់ប្រភេទបន្ទប់</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <!-- Status Dropdown -->
            <div class="w-full sm:w-36">
                <div class="relative group">
                    <select x-model="status" @change="fetchRooms()" 
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">គ្រប់ស្ថានភាព</option>
                        <option value="available">បន្ទប់ទំនេរ</option>
                        <option value="booked">មានភ្ញៀវ</option>
                        <option value="maintenance">ជួសជុល</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <!-- View Mode Switcher Toggle (Table, List, Grid) -->
            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('roomView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="Table View">
                    <i class="fas fa-table-list"></i>
                </button>
                <button @click="viewMode = 'list'; localStorage.setItem('roomView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="List View">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button @click="viewMode = 'grid'; localStorage.setItem('roomView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="Grid View">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>

            <!-- Add Room Button -->
            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមបន្ទប់
            </button>
        </div>
    </div>

    <!-- Rooms List Dynamic Partial Container -->
    <div id="rooms-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.rooms.partials.rooms_list')
    </div>

    <!-- Modals -->
    @include('admin.rooms.modals')
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('roomManager', () => ({
            viewMode: localStorage.getItem('roomView') || 'grid',
            showAddModal: false,
            showEditModal: false,
            showDetailModal: false,
            currentRoom: { hotel: {}, room_type: {} },
            search: '{{ request("search", "") }}',
            status: '{{ request("status", "") }}',
            room_type_id: '{{ request("room_type_id", "") }}',
            loading: false,

            init() {
                document.addEventListener('click', (e) => {
                    const link = e.target.closest('#rooms-container .pagination a, #rooms-container a.page-link, .pagination a');
                    if (link) {
                        e.preventDefault();
                        const url = link.getAttribute('href');
                        if (url && url !== '#') {
                            this.fetchRooms(url);
                        }
                    }
                });
            },

            async fetchRooms(url = null) {
                this.loading = true;
                let fetchUrl = url || '{{ route("rooms.index") }}';
                try {
                    const response = await axios.get(fetchUrl, {
                        params: { 
                            search: this.search, 
                            status: this.status,
                            room_type_id: this.room_type_id 
                        },
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    document.getElementById('rooms-container').innerHTML = response.data;
                } catch (error) {
                    console.error('Error fetching rooms:', error);
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>
@endsection