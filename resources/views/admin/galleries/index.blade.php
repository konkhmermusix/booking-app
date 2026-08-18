@extends('layouts.admin')

@section('title', 'គ្រប់គ្រងរូបភាព')

@section('content')
<div class="p-2 sm:p-2" x-data="{ previews: [], showUploadModal: false, showEditModal: false, currentGallery: {} }">

    <!-- Top KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">រូបភាពសរុប</p>
                <h3 class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">{{ number_format($totalPhotos ?? 0) }}</h3>
                <p class="text-[10px] text-gray-400 mt-0.5">រូបភាពក្នុង Album</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-images"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">កំពុងបង្ហាញ</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ number_format($activePhotos ?? 0) }}</h3>
                <p class="text-[10px] text-emerald-500 mt-0.5">បង្ហាញលើ Frontend</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ផ្អាកបង្ហាញ</p>
                <h3 class="text-2xl font-black text-rose-600 dark:text-rose-400 mt-1">{{ number_format($inactivePhotos ?? 0) }}</h3>
                <p class="text-[10px] text-rose-400 mt-0.5">លាក់បណ្តោះអាសន្ន</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-eye-slash"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 shadow-sm hover:shadow-md transition-all flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">សណ្ឋាគារ</p>
                <h3 class="text-lg font-black text-purple-600 dark:text-purple-400 mt-1 truncate max-w-[150px]">{{ $hotel->name }}</h3>
                <p class="text-[10px] text-gray-400">ម្ចាស់ Gallery</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl">
                <i class="fa-solid fa-hotel"></i>
            </div>
        </div>
    </div>

    <!-- Top Action Control Header Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-black dark:text-white uppercase tracking-tight flex items-center gap-2">
                គ្រប់គ្រងរូបភាព (Galleries)
            </h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">សណ្ឋាគារ ៖ {{ $hotel->name }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <button @click="showUploadModal = true"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-xs font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fa-solid fa-plus-circle"></i>
                <span>បន្ថែមរូបភាពថ្មី</span>
            </button>
        </div>
    </div>

    <!-- UPLOAD IMAGES MODAL -->
    <div x-show="showUploadModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 py-10">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showUploadModal = false"></div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">បន្ថែមរូបភាពទៅ Gallery</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Upload Multiple Images</p>
                    </div>
                    <button @click="showUploadModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
                </div>

                <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                    <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                        <!-- File Drop Zone -->
                        <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-8 text-center transition-all hover:border-blue-500 bg-gray-50/50 dark:bg-gray-800/50">
                            <input type="file" name="images[]" multiple accept="image/*" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                @change="previews = Array.from($event.target.files).map(file => URL.createObjectURL(file))">

                            <div class="space-y-3">
                                <div class="w-16 h-16 bg-blue-100 dark:bg-blue-500/10 text-blue-600 rounded-full flex items-center justify-center mx-auto transition-transform group-hover:scale-110">
                                    <i class="fa-solid fa-cloud-arrow-up text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-black uppercase tracking-widest text-xs text-gray-600 dark:text-gray-300">ទាញរូបភាព ឬ ចុចទីនេះដើម្បីជ្រើសរើស</p>
                                    <p class="text-[10px] text-gray-400 uppercase mt-1">PNG, JPG, WEBP</p>
                                </div>
                            </div>
                        </div>

                        <!-- Image Previews Container -->
                        <div>
                            <label class="block text-[11px] font-black uppercase text-gray-400 mb-3 tracking-widest ml-2">
                                រូបភាពដែលបានជ្រើសរើស (<span x-text="previews.length"></span>)
                            </label>
                            <div class="grid grid-cols-4 sm:grid-cols-5 gap-3 max-h-48 overflow-y-auto p-2 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                                <template x-for="(src, index) in previews" :key="index">
                                    <div class="aspect-square rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm relative group">
                                        <img :src="src" class="w-full h-full object-cover">
                                    </div>
                                </template>
                                <template x-if="previews.length === 0">
                                    <div class="col-span-full py-8 text-center">
                                        <span class="text-xs font-bold text-gray-400">មិនទាន់មានរូបភាពជ្រើសរើសនៅឡើយទេ</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Active Switch Toggle -->
                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                <span class="ms-3 text-xs font-bold text-gray-700 dark:text-gray-300">បង្ហាញលើ Frontend ភ្លាមៗ (Active)</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                        <button type="button" @click="showUploadModal = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
                            បោះបង់
                        </button>
                        <button type="submit" class="px-8 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md active:scale-95 transition-all">
                            រក្សាទុក
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT GALLERY MODAL -->
    <div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 py-10">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">កែប្រែរូបភាព</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Gallery Image</p>
                    </div>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
                </div>

                <form :action="`{{ url('admin/galleries') }}/${currentGallery.id}`" method="POST" enctype="multipart/form-data" x-data="{ editPreview: null }">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">រូបភាព (ជ្រើសរើសបើចង់ប្តូរ)</label>
                            
                            <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-4 transition-all hover:border-amber-500 bg-gray-50/50 dark:bg-gray-800/50">
                                <input type="file" name="image" accept="image/*"
                                    @change="const file = $event.target.files[0]; if (file) { editPreview = URL.createObjectURL(file); }"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <div class="relative h-48 rounded-xl overflow-hidden shadow-md">
                                    <img :src="editPreview ? editPreview : currentGallery.image" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="px-3 py-1.5 bg-white/90 text-gray-800 rounded-lg text-xs font-bold shadow flex items-center gap-1.5">
                                            <i class="fa-solid fa-cloud-arrow-up"></i> ចុចទីនេះដើម្បីប្តូររូបភាពថ្មី
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" :checked="currentGallery.is_active == 1" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="ms-3 text-xs font-bold text-gray-700 dark:text-gray-300">បង្ហាញលើ Frontend (Active)</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                        <button type="button" @click="showEditModal = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
                            បោះបង់
                        </button>
                        <button type="submit" class="px-8 h-11 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md active:scale-95 transition-all">
                            ធ្វើបច្ចុប្បន្នភាព
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Gallery Grid Display -->
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($hotel->galleries as $gallery)
        <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden bg-white dark:bg-gray-800 shadow-sm transition-all hover:shadow-xl hover:-translate-y-1.5 border border-gray-100 dark:border-gray-800">

            <img src="{{ asset('storage/' . $gallery->image) }}"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110 {{ !$gallery->is_active ? 'opacity-40 grayscale' : '' }}">

            <!-- Status Overlay Badge -->
            <div class="absolute top-3 left-3">
                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider shadow-sm {{ $gallery->is_active ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white' }}">
                    {{ $gallery->is_active ? 'សកម្ម' : 'ផ្អាក' }}
                </span>
            </div>

            <!-- Hover Action Overlay -->
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2 p-3">
                <button type="button"
                    @click="currentGallery = { id: {{ $gallery->id }}, image: '{{ asset('storage/' . $gallery->image) }}', is_active: {{ $gallery->is_active ? 1 : 0 }} }; showEditModal = true"
                    class="p-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold shadow transition-all" title="កែប្រែ / ប្តូររូបភាព">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

                <form action="{{ route('galleries.update', $gallery->id) }}" method="POST" class="inline">
                    @csrf @method('PUT')
                    <input type="hidden" name="is_active" value="{{ $gallery->is_active ? 0 : 1 }}">

                    <button type="submit" class="p-2.5 bg-white/90 hover:bg-white text-gray-800 rounded-xl text-xs font-bold shadow transition-all" title="{{ $gallery->is_active ? 'លាក់រូបភាព' : 'បង្ហាញរូបភាព' }}">
                        <i class="fa-solid {{ $gallery->is_active ? 'fa-eye-slash text-rose-500' : 'fa-eye text-emerald-500' }}"></i>
                    </button>
                </form>

                <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmDelete(this.form)"
                        class="p-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold shadow transition-all" title="លុបរូបភាព">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
            <div class="text-gray-300 dark:text-gray-600 mb-3 text-5xl">
                <i class="fa-solid fa-images"></i>
            </div>
            <h3 class="text-gray-400 font-bold uppercase tracking-widest text-sm">មិនទាន់មានរូបភាពក្នុង Gallery នៅឡើយទេ</h3>
            <button @click="showUploadModal = true" class="mt-4 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition-all">
                + បន្ថែមរូបភាពដំបូង
            </button>
        </div>
        @endforelse
    </div>
</div>

@endsection