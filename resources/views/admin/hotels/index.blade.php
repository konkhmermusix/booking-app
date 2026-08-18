@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងសណ្ឋាគារ')

@section('content')
<div class="p-2 sm:p-2"
    x-data="{
        viewMode: localStorage.getItem('hotelView') || 'grid',
        showAddModal: false,
        showEditModal: false,
        showDetailModal: false,

        logoPreview: null,
        editLogoPreview: null,

        currentHotel: {
            id: null,
            name: '',
            email: '',
            phone: '',
            address: '',
            description: '',
            latitude: '',
            longitude: '',
            status: 1,
            logo: null,
            rooms_count: 0
        },

        search: '{{ request('search') }}',
        status: '{{ request('status') }}',
        loading: false,

        openDetailModal(hotel) {
            this.currentHotel = hotel;
            this.showDetailModal = true;
        },

        openEditModal(hotel) {
            this.currentHotel = JSON.parse(JSON.stringify(hotel));
            this.editLogoPreview = null;
            if (window.editEditorInstance) {
                window.editEditorInstance.setData(this.currentHotel.description || '');
            }
            this.showEditModal = true;
        },

        async fetchHotel(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('hotels.index') }}';

            try {
                const response = await axios.get(fetchUrl, {
                    params: {
                        search: this.search,
                        status: this.status
                    }
                });
                const container = document.getElementById('hotels-container');
                if (container) {
                    container.innerHTML = response.data;
                }
            } catch (error) {
                console.error('Error fetching hotels:', error);
            } finally {
                this.loading = false;
            }
        }
     }">

    <!-- Top KPI Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between border-none">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">សណ្ឋាគារសរុប</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($totalHotels ?? 0) }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Total Properties</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between border-none">
            <div>
                <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">សកម្ម</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($activeHotels ?? 0) }}</h3>
                <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest mt-1">Active Hotels</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between border-none">
            <div>
                <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">ផ្អាកដំណើរការ</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($inactiveHotels ?? 0) }}</h3>
                <p class="text-[10px] text-amber-500 font-bold uppercase tracking-widest mt-1">Inactive Hotels</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-pause-circle"></i>
            </div>
        </div>
    </div>

    <!-- Header Action & Control Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                គ្រប់គ្រងសណ្ឋាគារ
            </h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Hotel Property Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <!-- Search Input -->
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.400ms="fetchHotel()" placeholder="ស្វែងរកឈ្មោះសណ្ឋាគារ..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <!-- Status Filter -->
            <div class="w-full sm:w-36">
                <div class="relative group">
                    <select x-model="status" @change="fetchHotel()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">ស្ថានភាពទាំងអស់</option>
                        <option value="1">សកម្ម</option>
                        <option value="0">ផ្អាក</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <!-- View Switcher Toggle -->
            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('hotelView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="មើលជាតារាង">
                    <i class="fas fa-table-list"></i>
                </button>
                <button @click="viewMode = 'list'; localStorage.setItem('hotelView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="មើលជាបញ្ជី">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button @click="viewMode = 'grid'; localStorage.setItem('hotelView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="មើលជាក្រឡាចត្រង្គ">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>

            <!-- Add Hotel Button -->
            <button @click="showAddModal = true; logoPreview = null; if (window.addEditorInstance) window.addEditorInstance.setData('');"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i>
                បន្ថែមសណ្ឋាគារ
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" x-cloak class="mb-10 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center">
        កំពុងដំណើរការ...
    </div>

    <!-- Main Content Container -->
    <div id="hotels-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.hotels.partials.hotel_list')
    </div>

    <!-- Modals -->
    @include('admin.hotels.modals')
</div>

<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const rootElement = document.querySelector('[x-data]');
            if (rootElement && typeof Alpine !== 'undefined' && Alpine.$data) {
                Alpine.$data(rootElement).fetchHotel(link.href);
            } else if (rootElement && rootElement.__x) {
                rootElement.__x.$data.fetchHotel(link.href);
            }
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkEditorToolbar = {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                'insertTable', 'blockQuote', '|',
                'undo', 'redo'
            ]
        };

        function configureEditorFeatures(editor, textareaElement) {
            editor.model.document.on('change:data', () => {
                textareaElement.value = editor.getData();
                textareaElement.dispatchEvent(new Event('input'));
            });

            editor.editing.view.document.on('keydown', (evt, data) => {
                if (data.keyCode === 9) {
                    const commandName = data.shiftKey ? 'outdent' : 'indent';
                    const command = editor.commands.get(commandName);
                    if (command && command.isEnabled) {
                        editor.execute(commandName);
                        data.preventDefault();
                        evt.stop();
                    }
                }
            });
        }

        const addTextarea = document.querySelector('#add_hotel_editor');
        if (addTextarea && typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(addTextarea, {
                    toolbar: checkEditorToolbar,
                    placeholder: 'សូមបញ្ចូលព័ត៌មានលម្អិតពីសណ្ឋាគារនៅទីនេះ...'
                })
                .then(editor => {
                    window.addEditorInstance = editor;
                    configureEditorFeatures(editor, addTextarea);
                })
                .catch(error => console.error('Add Hotel Editor Error:', error));
        }

        const editTextarea = document.querySelector('#edit_hotel_editor');
        if (editTextarea && typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(editTextarea, {
                    toolbar: checkEditorToolbar,
                    placeholder: 'សូមកែប្រែព័ត៌មានលម្អិតសណ្ឋាគារ...'
                })
                .then(editor => {
                    window.editEditorInstance = editor;
                    configureEditorFeatures(editor, editTextarea);
                })
                .catch(error => console.error('Edit Hotel Editor Error:', error));
        }
    });
</script>
@endsection