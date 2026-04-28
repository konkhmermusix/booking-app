@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងទេសចរណ៍')

@section('content')
<div class="p-2" x-data="{ 
        // ទិន្នន័យរបស់អ្នក
        viewMode: localStorage.getItem('tourView') || 'list', 
        showAddModal: false, 
        showEditModal: false,
        showDetailModal: false,
        currentTour: {},
        search: '{{ request('search') }}', 
        status: '{{ request('status') }}',
        loading: false,

        // រូបភាព (Preview & File Management)
        imagePreviews: [],

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreviews.push({
                        url: e.target.result,
                        file: file // រក្សាទុក Object file ទុកសម្រាប់ធ្វើការ sync ពេលលុប
                    });
                };
                reader.readAsDataURL(file);
            });
        },

        removePreview(index, inputId) {
            // ១. លុបចេញពី Array imagePreviews ដើម្បីបាត់រូបពីអេក្រង់
            this.imagePreviews.splice(index, 1);
            
            // ២. Sync ជាមួយ Input File ជាក់ស្តែង (ដើម្បីឱ្យពេល Submit ទៅបាត់រូបនោះមែនទែន)
            const input = document.getElementById(inputId);
            const dt = new DataTransfer();
            
            this.imagePreviews.forEach(p => {
                if(p.file) dt.items.add(p.file);
            });
            
            input.files = dt.files;
        },

        // --- Logic Fetch Data ---
        async fetchTours(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('tours.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { search: this.search, status: this.status },
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html' // ប្រាប់ Server ថាចង់បាន HTML
                    }
                });
                // បើប្រើ Axios ធម្មតា response.data នឹងក្លាយជា HTML string
                document.getElementById('tours-container').innerHTML = response.data;
            } catch (error) { 
                console.error('Error:', error); 
            }
            this.loading = false;
        },

        // បន្ថែមចូលក្នុង x-data object របស់អ្នក
        async updateTourData() {
            this.loading = true;
            try {
                // បង្កើត FormData ដើម្បីផ្ញើរូបភាព
                let formData = new FormData(event.target);
                
                // បន្ថែម _method PUT ព្រោះ Laravel ត្រូវការវាសម្រាប់ Route::put
                formData.append('_method', 'PUT');

                const response = await axios.post(`/admin/tours/${this.currentTour.id}`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                if (response.data.success) {
                    this.showEditModal = false;
                    this.fetchTours(); // ទាញទិន្នន័យថ្មីមកបង្ហាញ
                    alert('កែប្រែជោគជ័យ'); // អ្នកអាចប្រើ SweetAlert ជំនួសបាន
                }
            } catch (error) {
                console.error(error);
                alert('មានបញ្ហាក្នុងការកែប្រែ');
            }
            this.loading = false;
        },

        

        // បន្ថែម Function សម្រាប់សម្អាតរូបភាពពេលបិទ Modal
        resetForm() {
            this.imagePreviews = [];
            this.currentTour = {};
        }
    }">
    <div id="tours-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="flex flex-col md:flex-row items-start justify-between gap-4 mb-6 transition-opacity duration-300">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងកន្លែងទេសចរណ៍</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Tours Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchTours()" placeholder="ស្វែងរកឈ្មោះ..."
                    class="w-full pl-8 pr-4 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
            </div>

            <div class="w-full sm:w-32">
                <select x-model="status" @change="fetchTours()" class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium cursor-pointer focus:ring-2 focus:ring-blue-500/50">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <option value="1">សកម្ម</option>
                    <option value="0">ផ្អាក</option>
                </select>
            </div>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'list'; localStorage.setItem('tourView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('tourView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-th-large"></i></button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែម
            </button>
        </div>
    </div>

    <div id="tours-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.tours.partials.tours-list')
    </div>

    @include('admin.tours.modals')
</div>

<script>
    // ដើម្បីកុំឱ្យវា Reload Page
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a, nav a');
        if (link) {
            e.preventDefault();
            const alpine = document.querySelector('[x-data]').__x.$data;
            alpine.fetchTours(link.href);
        }
    });
</script>
@endsection