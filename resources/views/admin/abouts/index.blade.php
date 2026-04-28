@extends('layouts.admin')
@section('content')
<div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" x-cloak x-transition>
    <div class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-2xl">
        <h3 class="text-xl font-bold mb-4 text-slate-800" x-text="mode === 'add' ? 'បន្ថែមមាតិកាថ្មី' : 'កែប្រែមាតិកា'"></h3>

        <form @submit.prevent="submitForm">
            <div class="space-y-4 max-h-[70vh] overflow-y-auto px-1">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Key</label>
                        <input type="text" x-model="form.key" class="w-full border rounded-lg p-2 mt-1 focus:ring-2 focus:ring-blue-500 outline-none" :disabled="mode === 'edit'" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">ចំណងជើង (KH)</label>
                        <input type="text" x-model="form.title_kh" class="w-full border rounded-lg p-2 mt-1 focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">ខ្លឹមសារ (KH)</label>
                    <textarea x-model="form.content_kh" class="w-full border rounded-lg p-2 mt-1 focus:ring-2 focus:ring-blue-500 outline-none" rows="3"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">រូបភាព</label>
                    <div class="mt-2 flex items-center gap-4">
                        <template x-if="imagePreview">
                            <img :src="imagePreview" class="w-20 h-20 object-cover rounded-lg border">
                        </template>
                        <template x-if="!imagePreview && form.image_url">
                            <img :src="'/storage/' + form.image_url" class="w-20 h-20 object-cover rounded-lg border">
                        </template>

                        <input type="file" @change="handleFileUpload" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" x-model="form.status" id="status_toggle" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="status_toggle" class="ml-2 text-sm text-slate-700 font-medium">បង្ហាញជាសាធារណៈ</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3 border-t pt-4">
                <button type="button" @click="showModal = false" class="bg-gray-100 text-slate-600 px-5 py-2 rounded-lg font-medium hover:bg-gray-200 transition">បោះបង់</button>
                <button type="submit" :disabled="loading" class="bg-blue-600 text-white px-5 py-2 rounded-lg font-bold hover:bg-blue-700 transition flex items-center">
                    <span x-show="loading" class="mr-2 animate-spin">⏳</span>
                    <span x-text="loading ? 'កំពុងរក្សាទុក...' : 'រក្សាទុក'"></span>
                </button>
            </div>
        </form>
    </div>
</div>


<script>
    function aboutCRUD() {
        return {
            showModal: false,
            mode: 'add',
            loading: false,
            imagePreview: null,
            selectedFile: null,
            form: {
                id: '',
                key: '',
                title_kh: '',
                content_kh: '',
                status: true,
                image_url: ''
            },

            openModal(mode) {
                this.mode = mode;
                this.showModal = true;
                this.imagePreview = null;
                this.selectedFile = null;
                if (mode === 'add') {
                    this.form = {
                        id: '',
                        key: '',
                        title_kh: '',
                        content_kh: '',
                        status: true,
                        image_url: ''
                    };
                }
            },

            handleFileUpload(e) {
                const file = e.target.files[0];
                if (file) {
                    this.selectedFile = file;
                    this.imagePreview = URL.createObjectURL(file); // បង្ហាញរូបភាពភ្លាមៗមុន Upload
                }
            },

            async editItem(id) {
                try {
                    const res = await axios.get(`{{ url('admin/abouts') }}/${id}/edit`);
                    this.form = {
                        id: res.data.id,
                        key: res.data.key,
                        title_kh: res.data.title_kh,
                        content_kh: res.data.content_kh,
                        status: !!res.data.status,
                        image_url: res.data.image // រក្សាទុក path រូបភាពចាស់
                    };
                    this.openModal('edit');
                } catch (error) {
                    alert('មិនអាចទាញយកទិន្នន័យបាន!');
                }
            },

            async submitForm() {
                this.loading = true;
                try {
                    let url = `{{ url('admin/abouts') }}`;
                    let formData = new FormData();

                    // បញ្ចូលទិន្នន័យអត្ថបទ
                    formData.append('key', this.form.key);
                    formData.append('title_kh', this.form.title_kh);
                    formData.append('content_kh', this.form.content_kh);
                    formData.append('status', this.form.status ? 1 : 0);

                    // បញ្ចូលរូបភាព (បើមានការរើសថ្មី)
                    if (this.selectedFile) {
                        formData.append('image', this.selectedFile);
                    }

                    if (this.mode === 'edit') {
                        url += `/${this.form.id}`;
                        formData.append('_method', 'PUT'); // បង្ខំឱ្យ Laravel ស្គាល់ថាជា PUT
                    }

                    const response = await axios.post(url, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    });

                    // បើជោគជ័យ ធ្វើការ Refresh ទិន្នន័យ (ក្នុងករណីនេះខ្ញុំប្រើ reload ងាយស្រួលជាង)
                    window.location.reload();
                } catch (error) {
                    console.error(error);
                    alert(error.response?.data?.message || 'មានបញ្ហាពេលរក្សាទុក!');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>
@endsection