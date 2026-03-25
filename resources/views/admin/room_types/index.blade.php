@extends('layouts.admin')
@section('title', 'ប្រភេទបន្ទប់')

@section('content')
<div class="p-2 sm:p-2"
    x-data="{ 
        viewMode: localStorage.getItem('roomTypeView') || 'grid', 
        showAddModal: false, 
        showEditModal: false, 
        showDetailModal: false, 
        previews: [], 
        selectedFacilities: [], // ត្រូវតែប្រកាសនៅទីនេះសម្រាប់ Edit Checkbox
        currentRoomType: { id: null, hotel_id: '', name: '', base_price: 0, max_guests: 0, description: '', images: [], facilities: [] },
        
        search: '{{ request('search') }}', 
        hotel_id: '{{ request('hotel_id') }}',
        loading: false,

        // មុខងារបើក Edit Modal និងបោះទិន្នន័យចូល
        openEditModal(roomType) {
            // បង្កើតកូពីនៃទិន្នន័យដើម្បីកុំឱ្យវាប៉ះពាល់ដល់ List ខាងក្រៅភ្លាមៗ
            this.currentRoomType = JSON.parse(JSON.stringify(roomType)); 
            this.previews = []; 
            
            // ទាញយកតែ ID របស់ Facility ដើម្បីឱ្យ Checkbox លោត Tick ស្វ័យប្រវត្តិ
            this.selectedFacilities = roomType.facilities.map(f => f.id);
            
            this.showEditModal = true;
        },

        // មុខងារសម្រាប់បង្ហាញរូបភាពដែលទើបនឹងរើសថ្មី
        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            this.previews = []; 
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => { this.previews.push(e.target.result); };
                reader.readAsDataURL(file);
            });
        },

        // មុខងារ Fetch Data តាមរយៈ Ajax
        async fetchRoomTypes(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('room_types.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { search: this.search, hotel_id: this.hotel_id },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('room-types-container').innerHTML = response.data;
            } catch (error) { 
                console.error('Error:', error); 
            }
            this.loading = false;
        },

        async deleteExistingImage(imageId) {
            try {
                const response = await axios.delete(`/admin/room_types/images/${imageId}`);
                if (response.data.success) {
                    // លុបរូបភាពចេញពី list ដែលកំពុងបង្ហាញក្នុង Modal
                    this.currentRoomType.images = this.currentRoomType.images.filter(img => img.id !== imageId);
                }
            } catch (error) {
                alert('ការលុបមិនជោគជ័យ!');
            }
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-[1.5rem] shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងប្រភេទបន្ទប់</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Room Category Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchRoomTypes()" placeholder="ស្វែងរកឈ្មោះប្រភេទ..."
                    class="w-full pl-8 pr-4 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
            </div>

            <div class="w-full sm:w-40">
                <select x-model="hotel_id" @change="fetchRoomTypes()" class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium cursor-pointer">
                    <option value="">សណ្ឋាគារទាំងអស់</option>
                    @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('roomTypeView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-table-list"></i></button>
                <button @click="viewMode = 'list'; localStorage.setItem('roomTypeView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('roomTypeView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-th-large"></i></button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមប្រភេទបន្ទប់
            </button>
        </div>
    </div>

    <div id="room-types-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.room_types.partials.room-type-list')
    </div>

    @include('admin.room_types.modals')
</div>

<script>
    // Handle Pagination for AJAX
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            // Access Alpine data
            const alpine = document.querySelector('[x-data]').__x.$data;
            alpine.fetchRoomTypes(link.href);
        }
    });
</script>
@endsection