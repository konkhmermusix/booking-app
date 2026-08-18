@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងប្រភេទបន្ទប់')

@section('content')
<div class="p-2 sm:p-2"
    x-data="{
        viewMode: localStorage.getItem('roomTypeView') || 'grid',
        showAddModal: {{ $errors->any() ? 'true' : 'false' }},
        showEditModal: false,
        showDetailModal: false,

        previews: [],
        selectedFacilities: [],
        editorInstance: null,

        currentRoomType: {
            id: null,
            hotel_id: '',
            name: '',
            category: 'stay',
            base_price: 0,
            max_guests: 0,
            description: '',
            images: [],
            facilities: []
        },

        search: '{{ request('search') }}',
        hotel_id: '{{ request('hotel_id') }}',
        category: '{{ request('category') }}',
        loading: false,

        init() {
            if(document.querySelector('#editor')) {
                ClassicEditor.create(document.querySelector('#editor')).catch(err => console.error(err));
            }
        },

        openDetailModal(data) {
            this.currentRoomType = data;
            this.showDetailModal = true;
        },

        openEditModal(roomType) {
            this.currentRoomType = JSON.parse(JSON.stringify(roomType));
            this.previews = [];
            this.selectedFacilities = roomType.facilities ? roomType.facilities.map(f => f.id) : [];
            this.showEditModal = true;
            if (window.editEditorInstance) {
                window.editEditorInstance.setData(this.currentRoomType.description || '');
            }
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

        removeFile(index, inputId) {
            this.previews.splice(index, 1);
            const input = document.getElementById(inputId);
            if (input) {
                const dt = new DataTransfer();
                const { files } = input;
                for (let i = 0; i < files.length; i++) {
                    if (i !== index) dt.items.add(files[i]);
                }
                input.files = dt.files;
            }
        },

        clearAllPreviews(inputId) {
            this.previews = [];
            const input = document.getElementById(inputId);
            if(input) input.value = '';
        },

        resetFilters() {
            this.search = '';
            this.hotel_id = '';
            this.category = '';
            this.fetchRoomTypes();
        },

        async updateRoomType() {
            try {
                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('hotel_id', this.currentRoomType.hotel_id);
                formData.append('name', this.currentRoomType.name);
                formData.append('category', this.currentRoomType.category || 'stay');
                formData.append('base_price', this.currentRoomType.base_price);
                formData.append('max_guests', this.currentRoomType.max_guests);
                formData.append('description', this.currentRoomType.description);

                this.selectedFacilities.forEach(f => {
                    formData.append('facilities[]', f);
                });

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

                Swal.fire('ជោគជ័យ', 'ធ្វើបច្ចុប្បន្នភាពបានជោគជ័យ!', 'success');
                this.showEditModal = false;
                this.fetchRoomTypes();
            } catch (error) {
                console.error(error);
                Swal.fire('បរាជ័យ', 'ធ្វើបច្ចុប្បន្នភាពមិនជោគជ័យ!', 'error');
            }
        },

        async deleteExistingImage(imageId) {
            try {
                const response = await axios.delete(`/admin/room_types/images/${imageId}`, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });

                if (response.data.success) {
                    this.currentRoomType.images = this.currentRoomType.images.filter(img => img.id !== imageId);
                    Swal.fire('ជោគជ័យ', 'រូបភាពត្រូវបានលុប', 'success');
                }
            } catch (error) {
                Swal.fire('បរាជ័យ', 'ការលុបមិនជោគជ័យ!', 'error');
            }
        },

        async fetchRoomTypes(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('room_types.index') }}';

            try {
                const response = await axios.get(fetchUrl, {
                    params: {
                        search: this.search,
                        hotel_id: this.hotel_id,
                        category: this.category
                    }
                });
                document.getElementById('room-types-container').innerHTML = response.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        }
     }">

    @if(session('success'))
    <div class="mb-4 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-sm font-bold flex items-center gap-3 shadow-sm">
        <i class="fas fa-check-circle text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-sm font-bold flex items-center gap-3 shadow-sm">
        <i class="fas fa-exclamation-triangle text-lg"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-4 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-sm font-bold shadow-sm">
        <div class="flex items-center gap-2 mb-2 font-black text-rose-600 dark:text-rose-400">
            <i class="fas fa-exclamation-circle text-lg"></i>
            <span>មិនអាចរក្សាទុកបានទេ! សូមពិនិត្យមើលបញ្ហាខាងក្រោម៖</span>
        </div>
        <ul class="list-disc list-inside space-y-1 text-xs font-semibold text-rose-500">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Top KPI Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between border-none">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">ប្រភេទសរុប</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($totalRoomTypes ?? 0) }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Total Categories</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-500 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between border-none">
            <div>
                <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">ប្រភេទស្នាក់នៅ (Stay)</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($stayTypesCount ?? 0) }}</h3>
                <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest mt-1">Room Categories</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-500 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-bed"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between border-none">
            <div>
                <p class="text-xs font-semibold text-purple-600 dark:text-purple-400 uppercase tracking-wider">ប្រភេទប្រជុំ (Meeting)</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">{{ number_format($meetingTypesCount ?? 0) }}</h3>
                <p class="text-[10px] text-purple-500 font-bold uppercase tracking-widest mt-1">Meeting Halls</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-900/20 text-purple-500 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-handshake"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between border-none">
            <div>
                <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">តម្លៃមធ្យម</p>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mt-1">
                    ${{ number_format($avgPrice ?? 0, 2) }}
                    <span class="text-xs font-bold text-gray-400 block font-mono">({{ number_format(($avgPrice ?? 0) * $khrRate) }} ៛)</span>
                </h3>
                <p class="text-[10px] text-amber-500 font-bold uppercase tracking-widest mt-1">Average Base Price</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-900/20 text-amber-500 flex items-center justify-center text-xl shadow-inner">
                <i class="fa-solid fa-dollar-sign"></i>
            </div>
        </div>
    </div>

    <!-- Header Action & Control Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white flex items-center gap-2">
                គ្រប់គ្រងប្រភេទបន្ទប់
            </h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Room & Hall Category Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <!-- Search Bar -->
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.400ms="fetchRoomTypes()" placeholder="ស្វែងរកឈ្មោះប្រភេទ..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <!-- Hotel Filter -->
            <div class="w-full sm:w-40">
                <div class="relative group">
                    <select x-model="hotel_id" @change="fetchRoomTypes()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">សណ្ឋាគារទាំងអស់</option>
                        @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}">{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <!-- Category Filter -->
            <div class="w-full sm:w-36">
                <div class="relative group">
                    <select x-model="category" @change="fetchRoomTypes()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">គ្រប់ប្រភេទ</option>
                        <option value="stay">បន្ទប់ស្នាក់នៅ</option>
                        <option value="meeting">សាលប្រជុំ</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <!-- Reset Filters -->
            <button x-show="search || hotel_id || category" @click="resetFilters()"
                title="សម្អាតតម្រង"
                class="h-10 px-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-300 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-rotate-left"></i>
                <span class="hidden sm:inline">សម្អាត</span>
            </button>

            <!-- View Switcher -->
            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('roomTypeView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="មើលជាតារាង"><i class="fas fa-table-list"></i></button>
                <button @click="viewMode = 'list'; localStorage.setItem('roomTypeView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="មើលជាបញ្ជី"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('roomTypeView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="មើលជាក្រឡាចត្រង្គ"><i class="fas fa-th-large"></i></button>
            </div>

            <!-- Add Room Type Button -->
            <button @click="showAddModal = true; previews = []; if(window.addEditorInstance) window.addEditorInstance.setData('');"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i>
                បន្ថែមប្រភេទបន្ទប់
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" x-cloak class="mb-10 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center">
        កំពុងដំណើរការ...
    </div>

    <!-- Main Room Types Container -->
    <div id="room-types-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.room_types.partials.roomtype_list')
    </div>

    <!-- Modals -->
    @include('admin.room_types.modals')
</div>

<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const rootElement = document.querySelector('[x-data]');
            if (rootElement && typeof Alpine !== 'undefined' && Alpine.$data) {
                Alpine.$data(rootElement).fetchRoomTypes(link.href);
            } else if (rootElement && rootElement.__x) {
                rootElement.__x.$data.fetchRoomTypes(link.href);
            }
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const requestedToolbar = [
            'heading', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'fontColor', 'fontBackgroundColor', '|',
            'alignment', '|',
            'bulletedList', 'numberedList', '|',
            'insertTable', 'blockQuote', 'horizontalLine', '|',
            'link', 'imageUpload', '|',
            'undo', 'redo'
        ];

        let checkEditorToolbar = requestedToolbar;
        if (typeof ClassicEditor !== 'undefined' && ClassicEditor.defaultConfig && ClassicEditor.defaultConfig.toolbar && Array.isArray(ClassicEditor.defaultConfig.toolbar.items)) {
            const availableItems = ClassicEditor.defaultConfig.toolbar.items;
            checkEditorToolbar = requestedToolbar.filter(item => item === '|' || availableItems.includes(item));
        }

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

        const addTextarea = document.querySelector('#add_editor');
        if (addTextarea && typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(addTextarea, {
                    toolbar: checkEditorToolbar,
                    placeholder: 'សូមបញ្ចូលការពិពណ៌នានៅទីនេះ...'
                })
                .then(editor => {
                    window.addEditorInstance = editor;
                    configureEditorFeatures(editor, addTextarea);
                })
                .catch(error => console.error('Add Editor Error:', error));
        }

        const editTextarea = document.querySelector('#edit_editor');
        if (editTextarea && typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(editTextarea, {
                    toolbar: checkEditorToolbar,
                    placeholder: 'សូមកែប្រែការពិពណ៌នានៅទីនេះ...'
                })
                .then(editor => {
                    window.editEditorInstance = editor;
                    configureEditorFeatures(editor, editTextarea);
                })
                .catch(error => console.error('Edit Editor Error:', error));
        }
    });
</script>
@endsection