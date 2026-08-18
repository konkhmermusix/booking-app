@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងទេសចរណ៍')

@section('content')
<div class="p-2" x-data="tourManager">
    <!-- Top KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">កន្លែងទេសចរណ៍សរុប</p>
                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ number_format($totalTours ?? 0) }}</h3>
                <p class="text-[10px] text-gray-400 mt-0.5">ទិន្នន័យក្នុងប្រព័ន្ធ</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">កំពុងសកម្ម</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($activeTours ?? 0) }}</h3>
                <p class="text-[10px] text-emerald-500 mt-0.5">បង្ហាញលើ Frontend</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ផ្អាកបង្ហាញ</p>
                <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ number_format($inactiveTours ?? 0) }}</h3>
                <p class="text-[10px] text-rose-400 mt-0.5">លាក់បណ្តោះអាសន្ន</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-eye-slash"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">មានតំណភ្ជាប់ MAPS</p>
                <h3 class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-1">{{ number_format($withMaps ?? 0) }}</h3>
                <p class="text-[10px] text-gray-400">ទីតាំងមាន Google Maps</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-map-marked-alt"></i>
            </div>
        </div>
    </div>

    <!-- Top Action Control Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-black dark:text-white uppercase tracking-tight flex items-center gap-2">
                គ្រប់គ្រងកន្លែងទេសចរណ៍
            </h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Tours & Destinations Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchTours()" placeholder="ស្វែងរកឈ្មោះ..."
                    class="w-full pl-9 pr-4 h-10 bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
            </div>

            <div class="w-full sm:w-36">
                <select x-model="status" @change="fetchTours()" class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-xs font-bold cursor-pointer focus:ring-2 focus:ring-blue-500/50 dark:text-white">
                    <option value="">ស្ថានភាពទាំងអស់</option>
                    <option value="1">សកម្ម</option>
                    <option value="0">ផ្អាក</option>
                </select>
            </div>

            <div class="flex bg-gray-100 dark:bg-gray-900 p-1 rounded-xl h-10 items-center border border-gray-100 dark:border-gray-700">
                <button @click="viewMode = 'list'; localStorage.setItem('tourView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-800 shadow-sm text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-xs"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('tourView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-800 shadow-sm text-blue-600 dark:text-blue-400 font-bold' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-xs"><i class="fas fa-th-large"></i></button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-xs font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមថ្មី
            </button>
        </div>
    </div>

    <div id="tours-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.tours.partials.tours-list')
    </div>

    @include('admin.tours.modals')
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tourManager', () => ({
            viewMode: localStorage.getItem('tourView') || 'list', 
            showAddModal: {{ $errors->any() ? 'true' : 'false' }}, 
            showEditModal: false,
            showDetailModal: false,
            currentTour: {},
            search: @json(request('search', '')), 
            status: @json(request('status', '')),
            loading: false,
            imagePreviews: [],

            handleFileSelect(event) {
                const files = Array.from(event.target.files);
                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imagePreviews.push({
                            url: e.target.result,
                            file: file
                        });
                    };
                    reader.readAsDataURL(file);
                });
            },

            removePreview(index, inputId) {
                this.imagePreviews.splice(index, 1);
                if (inputId) {
                    const input = document.getElementById(inputId);
                    if (input) {
                        const dt = new DataTransfer();
                        this.imagePreviews.forEach(p => {
                            if (p.file) dt.items.add(p.file);
                        });
                        input.files = dt.files;
                    }
                }
            },

            async fetchTours(url = null) {
                this.loading = true;
                let fetchUrl = url || @json(route('tours.index'));
                try {
                    const response = await axios.get(fetchUrl, {
                        params: { search: this.search, status: this.status },
                        headers: { 
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    });
                    const container = document.getElementById('tours-container');
                    if (container) {
                        container.innerHTML = response.data;
                    }
                } catch (error) { 
                    console.error('Error fetching tours:', error); 
                } finally {
                    this.loading = false;
                }
            },

            async updateTourData(event) {
                this.loading = true;
                try {
                    let formEl = event ? event.target : document.querySelector('#edit-tour-form');
                    let formData = new FormData(formEl);
                    formData.append('_method', 'PUT');

                    const response = await axios.post('/admin/tours/' + this.currentTour.id, formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });

                    if (response.data.success) {
                        this.showEditModal = false;
                        this.fetchTours();
                        Swal.fire({
                            icon: 'success',
                            title: 'កែប្រែជោគជ័យ',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } catch (error) {
                    console.error('Error updating tour:', error);
                    alert('មានបញ្ហាក្នុងការកែប្រែ');
                } finally {
                    this.loading = false;
                }
            },

            async deleteTour(id) {
                const isDark = document.documentElement.classList.contains('dark');
                const result = await Swal.fire({
                    title: 'តើអ្នកប្រាកដទេ?',
                    text: "ទិន្នន័យនេះនឹងត្រូវលុបជារៀងរហូត",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'លុប',
                    cancelButtonText: 'បោះបង់',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: isDark ? '#374151' : '#9ca3af',
                    background: isDark ? '#111827' : '#ffffff',
                    color: isDark ? '#f3f4f6' : '#111827',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    didOpen: () => {
                        const swalContainer = document.querySelector('.swal2-container');
                        if (swalContainer) {
                            swalContainer.style.zIndex = '9000';
                        }
                    },
                    heightAuto: false,
                    customClass: {
                        popup: 'rounded-[2rem] border-none shadow-2xl font-kantumruy',
                        title: 'text-xl font-bold',
                        htmlContainer: 'text-sm opacity-80',
                        confirmButton: 'rounded-2xl px-6 py-3 font-bold text-sm tracking-wide focus:ring-0',
                        cancelButton: 'rounded-2xl px-6 py-3 font-bold text-sm tracking-wide focus:ring-0'
                    },
                    preConfirm: async () => {
                        try {
                            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                            const response = await axios.delete('/admin/tours/' + id, {
                                headers: {
                                    'X-CSRF-TOKEN': token,
                                    'Accept': 'application/json'
                                }
                            });
                            return response.data;
                        } catch (error) {
                            const errMsg = (error.response && error.response.data && error.response.data.message) ? error.response.data.message : error.message;
                            Swal.showValidationMessage('មានបញ្ហាក្នុងការលុប ៖ ' + errMsg);
                        }
                    }
                });

                if (result.isConfirmed) {
                    Swal.fire({
                        icon: 'success',
                        title: 'បានលុបជោគជ័យ!',
                        showConfirmButton: false,
                        timer: 1500,
                        background: isDark ? '#111827' : '#ffffff',
                        color: isDark ? '#f3f4f6' : '#111827',
                        customClass: {
                            popup: 'rounded-[2rem] border-none shadow-2xl font-kantumruy'
                        }
                    });
                    this.fetchTours();
                }
            },

            resetForm() {
                this.imagePreviews = [];
                this.currentTour = {};
            }
        }));
    });

    document.addEventListener('click', function(e) {
        const link = e.target.closest('#tours-container .pagination a, #tours-container nav a');
        if (link) {
            e.preventDefault();
            const rootElement = document.querySelector('[x-data]');
            if (rootElement && typeof Alpine !== 'undefined' && Alpine.$data) {
                Alpine.$data(rootElement).fetchTours(link.href);
            } else if (rootElement && rootElement.__x) {
                rootElement.__x.$data.fetchTours(link.href);
            }
        }
    });
</script>
@endsection