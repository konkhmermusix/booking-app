@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងព័ត៌មានថ្មីៗ')

@section('content')
<div class="p-2 sm:p-2"
    @editor-change.window="currentPost.content = $event.detail.content"
    x-data="{
        viewMode: localStorage.getItem('postView') || 'grid',
        showAddModal: false,
        showEditModal: false,
        showDetailModal: false,

        previews: [],
        editorInstance: null,

        currentPost: {
            id: null,
            title: '',
            slug: '',
            content: '',
            images: [],
            status: 'draft',
            views: 0
        },

        search: '{{ request('search') }}',
        status: '{{ request('status') }}',
        loading: false,

        openDetailModal(data) {
            this.currentPost = data;
            this.showDetailModal = true;
        },

        openEditModal(post) {
            this.currentPost = JSON.parse(JSON.stringify(post));
            this.previews = [];
            this.showEditModal = true;
            
            if (window.editEditorInstance) {
                window.editEditorInstance.setData(post.content);
            }
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            if ((this.previews.length + files.length) > 20) {
                Swal.fire('ព្រមាន', 'អ្នកអាចជ្រើសរើសរូបភាពបានអតិបរមាត្រឹម ២០ សន្លឹកប៉ុណ្ណោះ!', 'warning');
                return;
            }

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

        async savePost() {
            try {
                if (window.addEditorInstance) {
                    this.currentPost.content = window.addEditorInstance.getData();
                }

                if (!this.currentPost.title || !this.currentPost.content) {
                    Swal.fire('ព្រមាន', 'សូមបំពេញចំណងជើង និងខ្លឹមសារព័ត៌មាន!', 'warning');
                    return;
                }

                let formData = new FormData();
                formData.append('title', this.currentPost.title);
                formData.append('content', this.currentPost.content);
                formData.append('status', this.currentPost.status);

                let files = document.getElementById('add_images').files;
                for (let i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }

                this.loading = true;
                await axios.post(`/admin/posts`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                Swal.fire('ជោគជ័យ', 'បានបង្កើតព័ត៌មានថ្មីរួចរាល់!', 'success');
                this.showAddModal = false;
                this.resetAddForm();
                this.fetchPosts();
            } catch (error) {
                console.error(error.response ? error.response.data : error);
                let errorMsg = error.response?.data?.message || 'មិនអាចរក្សាទុកទិន្នន័យបានទេ!';
                Swal.fire('បរាជ័យ', errorMsg, 'error');
            } finally {
                this.loading = false;
            }
        },

        async updatePost() {
            try {
                if (window.editEditorInstance) {
                    this.currentPost.content = window.editEditorInstance.getData();
                }

                if (!this.currentPost.title || !this.currentPost.content) {
                    Swal.fire('ព្រមាន', 'សូមបំពេញចំណងជើង និងខ្លឹមសារព័ត៌មាន!', 'warning');
                    return;
                }

                let formData = new FormData();
                formData.append('_method', 'PUT');
                formData.append('title', this.currentPost.title);
                formData.append('content', this.currentPost.content);
                formData.append('status', this.currentPost.status);

                let files = document.getElementById('edit_images').files;
                for (let i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }

                this.loading = true;
                await axios.post(`/admin/posts/${this.currentPost.id}`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                Swal.fire('ជោគជ័យ', 'ធ្វើបច្ចុប្បន្នភាពបានជោគជ័យ!', 'success');
                this.showEditModal = false;
                this.fetchPosts();
            } catch (error) {
                console.error(error);
                let errorMsg = error.response?.data?.message || 'ការធ្វើបច្ចុប្បន្នភាពមិនជោគជ័យ!';
                Swal.fire('បរាជ័យ', errorMsg, 'error');
            } finally {
                this.loading = false;
            }
        },

        async deleteOldImage(postId, imagePath) {
            try {
                const result = await Swal.fire({
                    title: 'តើអ្នកប្រាកដទេ?',
                    text: 'រូបភាពនេះនឹងត្រូវលុបជាអចិន្ត្រៃយ៍!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'បាទ/ចាស, លុប!',
                    cancelButtonText: 'បោះបង់'
                });

                if (result.isConfirmed) {
                    this.loading = true;
                    const response = await axios.post(`/admin/posts/${postId}/images/delete`, {
                        image_path: imagePath,
                        _token: '{{ csrf_token() }}'
                    });

                    if (response.data.success) {
                        this.currentPost.images = this.currentPost.images.filter(img => img !== imagePath);
                        Swal.fire('ជោគជ័យ!', response.data.message, 'success');
                        this.fetchPosts();
                    }
                }
            } catch (error) {
                console.error(error);
                Swal.fire('បរាជ័យ', 'មិនអាចលុបរូបភាពបានទេ!', 'error');
            } finally {
                this.loading = false;
            }
        },

        resetAddForm() {
            this.currentPost = { id: null, title: '', slug: '', content: '', images: [], status: 'draft', views: 0 };
            this.clearAllPreviews('add_images');
            if (window.addEditorInstance) window.addEditorInstance.setData('');
        },

        async fetchPosts(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('posts.index') }}';

            try {
                const response = await axios.get(fetchUrl, {
                    params: {
                        search: this.search,
                        status: this.status
                    }
                });
                document.getElementById('posts-container').innerHTML = response.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },
    }">

    <!-- Top KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">អត្ថបទសរុប</p>
                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ number_format($totalPosts ?? 0) }}</h3>
                <p class="text-[10px] text-gray-400 mt-0.5">ព័ត៌មាន និងអត្ថបទ</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-newspaper"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">បោះពុម្ពផ្សាយ</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($publishedPosts ?? 0) }}</h3>
                <p class="text-[10px] text-emerald-500 mt-0.5">បង្ហាញជាសាធារណៈ</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-globe"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">សេចក្តីព្រាង</p>
                <h3 class="text-2xl font-black text-amber-600 dark:text-amber-400 mt-1">{{ number_format($draftPosts ?? 0) }}</h3>
                <p class="text-[10px] text-amber-500 mt-0.5">កំពុងរៀបចំ</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-file-pen"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ឯកជន</p>
                <h3 class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-1">{{ number_format($privatePosts ?? 0) }}</h3>
                <p class="text-[10px] text-gray-400 mt-0.5">មើលបានតែ Admin</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-lock"></i>
            </div>
        </div>
    </div>

    <!-- Top Action Control Header Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-black dark:text-white uppercase tracking-tight">គ្រប់គ្រងព័ត៌មានថ្មីៗ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">News & Articles Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchPosts()" placeholder="ស្វែងរកចំណងជើង..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-xs font-bold">
            </div>

            <div class="w-full sm:w-40">
                <div class="relative group">
                    <select x-model="status" @change="fetchPosts()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-bold relative z-0">
                        <option value="">ស្ថានភាពទាំងអស់</option>
                        <option value="published">សាធារណៈ</option>
                        <option value="draft">សេចក្តីព្រាង</option>
                        <option value="private">ឯកជន</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <!-- View Switcher Tabs (Table / Grid / List) -->
            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('postView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600 font-bold' : 'text-gray-400'" class="px-3 h-full rounded-lg flex items-center gap-1.5 text-xs transition-all">
                    <i class="fas fa-table-list"></i>
                </button>
                <button @click="viewMode = 'grid'; localStorage.setItem('postView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600 font-bold' : 'text-gray-400'" class="px-3 h-full rounded-lg flex items-center gap-1.5 text-xs transition-all">
                    <i class="fas fa-th-large"></i>
                </button>
                <button @click="viewMode = 'list'; localStorage.setItem('postView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600 font-bold' : 'text-gray-400'" class="px-3 h-full rounded-lg flex items-center gap-1.5 text-xs transition-all">
                    <i class="fas fa-list"></i>
                </button>
            </div>

            <button @click="showAddModal = true; resetAddForm();"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-xs font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i>
                <span>បង្កើតអត្ថបទថ្មី</span>
            </button>
        </div>
    </div>

    <div class="mb-10 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center" x-show="loading" x-cloak>
        កំពុងដំណើរការ...
    </div>

    <div id="posts-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.posts.partials.post_list')
    </div>

    @include('admin.posts.modals')
</div>

<script>
    function initPostCKEditors() {
        const CKEditor = window.ClassicEditor;
        if (!CKEditor) return;

        const toolbar = {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', '|',
                'bulletedList', 'numberedList', '|',
                'blockQuote', '|',
                'undo', 'redo'
            ]
        };

        function syncEditor(editor, textarea) {
            editor.model.document.on('change:data', () => {
                const data = editor.getData();
                textarea.value = data;
                textarea.dispatchEvent(new Event('input', { bubbles: true }));
                textarea.dispatchEvent(new CustomEvent('editor-change', { detail: { content: data }, bubbles: true }));
            });
        }

        const addTextarea = document.querySelector('#add_editor');
        if (addTextarea && !addTextarea.dataset.ckLoaded) {
            addTextarea.dataset.ckLoaded = '1';
            CKEditor.create(addTextarea, { toolbar, placeholder: 'សូមបញ្ចូលខ្លឹមសារព័ត៌មានលម្អិតនៅទីនេះ...' })
                .then(editor => { window.addEditorInstance = editor; syncEditor(editor, addTextarea); })
                .catch(err => console.error(err));
        }

        const editTextarea = document.querySelector('#edit_editor');
        if (editTextarea && !editTextarea.dataset.ckLoaded) {
            editTextarea.dataset.ckLoaded = '1';
            CKEditor.create(editTextarea, { toolbar, placeholder: 'សូមកែប្រែខ្លឹមសារព័ត៌មានលម្អិតនៅទីនេះ...' })
                .then(editor => { window.editEditorInstance = editor; syncEditor(editor, editTextarea); })
                .catch(err => console.error(err));
        }
    }

    document.addEventListener('DOMContentLoaded', initPostCKEditors);
    document.addEventListener('ckeditor-ready', initPostCKEditors);
</script>
@endsection