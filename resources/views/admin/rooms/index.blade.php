@extends('layouts.admin')
@section('title', 'បញ្ជីបន្ទប់')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 
        viewMode: localStorage.getItem('roomView') || 'list', 
        showAddModal: false, showEditModal: false, showDetailModal: false,
        currentRoom: { hotel: {}, room_type: {} },
        search: '{{ request('search') }}', status: '{{ request('status') }}',
        loading: false,

        async fetchRooms(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('rooms.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { search: this.search, status: this.status },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('rooms-container').innerHTML = response.data;
            } catch (error) { console.error('Error:', error); }
            this.loading = false;
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-[1.5rem] border dark:border-gray-800 shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងបន្ទប់</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Live Room Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchRooms()" placeholder="ស្វែងរកលេខបន្ទប់..."
                    class="w-full pl-8 pr-4 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
            </div>

            <div class="w-full sm:w-40">
                <select x-model="status" @change="fetchRooms()" class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium cursor-pointer">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <option value="available">ស្ថានភាពទំនេរ</option>
                    <option value="booked">ស្ថានភាពមានភ្ញៀវ</option>
                    <option value="maintenance">ស្ថានភាពជួសជុល</option>
                </select>
            </div>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center border dark:border-gray-700">
                <button @click="viewMode = 'list'; localStorage.setItem('roomView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('roomView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-th-large"></i></button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមបន្ទប់
            </button>
        </div>
    </div>

    <div id="rooms-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.rooms.partials.rooms-list')
    </div>

    @include('admin.rooms.modals')
</div>

<script>
    // Handle Pagination
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const alpine = document.querySelector('[x-data]').__x.$data;
            alpine.fetchRooms(link.href);
        }
    });
</script>
@endsection