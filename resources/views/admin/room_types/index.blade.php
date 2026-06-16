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
        editorInstance: null,

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

        async updateRoomType() {
            try {
                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('hotel_id', this.currentRoomType.hotel_id);
                formData.append('name', this.currentRoomType.name);
                formData.append('category', this.currentRoomType.name);
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
                        hotel_id: this.hotel_id
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

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងប្រភេទបន្ទប់</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Room Category Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchRoomTypes()" placeholder="ស្វែងរកឈ្មោះប្រភេទ..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

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

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('roomTypeView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-table-list"></i></button>
                <button @click="viewMode = 'list'; localStorage.setItem('roomTypeView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('roomTypeView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]"><i class="fas fa-th-large"></i></button>
            </div>

            <button @click="showAddModal = true; previews = [];"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i>
                បន្ថែមប្រភេទបន្ទប់
            </button>
        </div>
    </div>

    <div x-show="loading" x-cloak class="mb-10 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center">
        កំពុងដំណើរការ...
    </div>

    <div id="room-types-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.room_types.partials.roomtype_list')
    </div>

    @include('admin.room_types.modals')
</div>

<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const rootElement = document.querySelector('[x-data]');
            if (rootElement && rootElement.__x) {
                rootElement.__x.$data.fetchRoomTypes(link.href);
            }
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // កំណត់ទម្រង់ Toolbar ឱ្យកាន់តែសម្បូរបែប និងមានសណ្តាប់ធ្នាប់
        const checkEditorToolbar = {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|', // បន្ថែម strikethrough (ឆូតចំកណ្តាល)
                'bulletedList', 'numberedList', 'outdent', 'indent', '|', // បន្ថែម outdent និង indent សម្រាប់មុខងារ Tab
                'insertTable', 'blockQuote', '|', // បន្ថែម blockQuote (សម្រង់សម្តី)
                'undo', 'redo'
            ]
        };

        // មុខងារទូទៅសម្រាប់ដោះស្រាយបញ្ហា Tab Key និងការដកឃ្លា
        function configureEditorFeatures(editor, textareaElement) {
            // ១. មុខងារ Sync ទិន្នន័យទៅកាន់ Textarea ភ្លាមៗនៅពេលវាយអត្ថបទ (ដោះស្រាយបញ្ហាជាមួយ Alpine.js / Form)
            editor.model.document.on('change:data', () => {
                textareaElement.value = editor.getData();
                // ប្រសិនបើប្រើជាមួយ Alpine.js (ឧទាហរណ៍ x-model="description") ត្រូវដាស់វាឱ្យដឹងខ្លួន
                textareaElement.dispatchEvent(new Event('input'));
            });

            // ២. មុខងារចាប់ព្រឹត្តិការណ៍ចុច Tab Key នៅលើ Keyboard
            editor.editing.view.document.on('keydown', (evt, data) => {
                // បើសិនជាចុចប៊ូតុង Tab
                if (data.keyCode === 9) {
                    // ប្រសិនបើចុច Shift + Tab ឱ្យវាថយក្រោយ (Outdent) បើមិនចាក់ទេឱ្យវាដកឃ្លាចូល (Indent)
                    const commandName = data.shiftKey ? 'outdent' : 'indent';
                    const command = editor.commands.get(commandName);

                    if (command && command.isEnabled) {
                        editor.execute(commandName);
                        data.preventDefault(); // ការពារកុំឱ្យវាលោត Focus ចេញទៅ Element ផ្សេង
                        evt.stop();
                    }
                }
            });
        }

        // ==========================================
        // Initialize CKEditor សម្រាប់ Add Modal
        // ==========================================
        const addTextarea = document.querySelector('#add_editor');
        if (addTextarea) {
            ClassicEditor
                .create(addTextarea, {
                    toolbar: checkEditorToolbar,
                    placeholder: 'សូមបញ្ចូលការពិពណ៌នានៅទីនេះ...' // បន្ថែម Placeholder មើលទៅមានវិជ្ជាជីវៈ
                })
                .then(editor => {
                    window.addEditorInstance = editor; // រក្សាទុក Instance ក្រែងលោត្រូវប្រើលុបទិន្នន័យចោល (Clear Form)
                    configureEditorFeatures(editor, addTextarea);
                })
                .catch(error => {
                    console.error('Add Editor Error:', error);
                });
        }

        // ==========================================
        // Initialize CKEditor សម្រាប់ Edit Modal
        // ==========================================
        const editTextarea = document.querySelector('#edit_editor');
        if (editTextarea) {
            ClassicEditor
                .create(editTextarea, {
                    toolbar: checkEditorToolbar,
                    placeholder: 'សូមកែប្រែការពិពណ៌នានៅទីនេះ...'
                })
                .then(editor => {
                    // រក្សាទុក Instance ក្នុង Window Object ដើម្បីងាយស្រួលហៅរុញទិន្នន័យពី Alpine.js (ដូចកូដចាស់របស់អ្នក)
                    window.editEditorInstance = editor;
                    configureEditorFeatures(editor, editTextarea);
                })
                .catch(error => {
                    console.error('Edit Editor Error:', error);
                });
        }
    });
</script>


@endsection