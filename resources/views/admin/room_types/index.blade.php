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
    selectedFacilities: [],

    currentRoomType: {
        id: null,
        hotel_id: '',
        name: '',
        base_price: 0,
        max_guests: 0,
        description: '',
        images: [],
        facilities: []
    },

    search: '{{ request('search') }}',
    hotel_id: '{{ request('hotel_id') }}',
    loading: false,

    openEditModal(roomType) {
        this.currentRoomType = JSON.parse(JSON.stringify(roomType));
        this.previews = [];
        this.selectedFacilities = roomType.facilities.map(f => f.id);
        this.showEditModal = true;
    },

    handleFileSelect(event) {
        const files = Array.from(event.target.files);
        this.previews = [];

        files.forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => this.previews.push(e.target.result);
            reader.readAsDataURL(file);
        });
    },

    async updateRoomType() {
        try {
            let formData = new FormData();

            formData.append('_method', 'PUT');
            formData.append('hotel_id', this.currentRoomType.hotel_id);
            formData.append('name', this.currentRoomType.name);
            formData.append('base_price', this.currentRoomType.base_price);
            formData.append('max_guests', this.currentRoomType.max_guests);
            formData.append('description', this.currentRoomType.description);

            // Facilities
            this.selectedFacilities.forEach(f => {
                formData.append('facilities[]', f);
            });

            // Images
            let files = document.getElementById('edit_images').files;
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            await axios.post(`/admin/room_types/${this.currentRoomType.id}`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            Swal.fire('ជោគជ័យ', 'កែប្រែបានជោគជ័យ!', 'success');

            this.showEditModal = false;
            this.fetchRoomTypes();

        } catch (error) {
            console.error(error);
            Swal.fire('បរាជ័យ', 'Update មិនជោគជ័យ!', 'error');
        }
    },

    async deleteExistingImage(imageId) {
        
        try {
            const response = await axios.delete(`/admin/room_types/images/${imageId}`, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });

            if (response.data.success) {
                this.currentRoomType.images =
                    this.currentRoomType.images.filter(img => img.id !== imageId);

                Swal.fire('ជោគជ័យ', 'រូបភាពត្រូវបានលុប', 'success');
            }

        } catch (error) {
            Swal.fire('បរាជ័យ', 'ការលុបមិនជោគជ័យ!', 'error');
        }
    },

    

    async fetchRoomTypes(url = null) {
        this.loading = true;

        let fetchUrl = url || '{{ route('room_types.index') }}';

        const response = await axios.get(fetchUrl, {
            params: {
                search: this.search,
                hotel_id: this.hotel_id
            }
        });

        document.getElementById('room-types-container').innerHTML = response.data;
        this.loading = false;
    }
}">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
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
                <i class="fas fa-plus-circle"></i> បន្ថែម
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

<script>
    function roomTypeAddHandler() {
        return {
            previews: [],
            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                // បើចង់ឱ្យរើសថែមរូបភាពថ្មីចូលជាមួយរូបចាស់ ដកបន្ទាត់ previews = [] ចេញ
                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => this.previews.push(e.target.result);
                    reader.readAsDataURL(file);
                });
            },
            removeFile(index) {
                // ១. លុបចេញពី UI
                this.previews.splice(index, 1);

                // ២. លុបចេញពី Input File ជាក់ស្តែង
                const input = document.querySelector('input[name="images[]"]');
                const dt = new DataTransfer();
                const files = input.files;

                for (let i = 0; i < files.length; i++) {
                    if (i !== index) dt.items.add(files[i]);
                }
                input.files = dt.files;
            },
            clearAll() {
                this.previews = [];
                document.querySelector('input[name="images[]"]').value = "";
            }
        }
    }
</script>

<script>
    function confirmDelete(id) {
        if (confirm('តើអ្នកប្រាកដថាចង់លុបប្រភេទបន្ទប់នេះមែនទេ?')) {
            // បង្កើត Form មួយដើម្បីផ្ញើ DELETE Request
            let form = document.createElement('form');
            form.action = `/admin/room_types/${id}`;
            form.method = 'POST';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

@endsection