<!-- ADD SLIDE MODAL -->
<div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">បន្ថែម Slide ថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New Slideshow Entry</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('slideshows.store') }}" method="POST" enctype="multipart/form-data" x-data="{ imagePreview: null }">
                @csrf
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង</label>
                        <input type="text" name="title" placeholder="ឧ. ស្វាគមន៍មកកាន់សណ្ឋាគារ ភីអេនធី ផាលេស"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើងរង</label>
                        <input type="text" name="subtitle" placeholder="ឧ. បទពិសោធន៍ស្នាក់នៅដ៏ល្អឥតខ្ចោះ"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                            រូបភាព Slide <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-4 transition-all hover:border-blue-500 bg-gray-50/50 dark:bg-gray-800/50">
                            <input type="file" name="image" required accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { imagePreview = URL.createObjectURL(file); }"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <div x-show="!imagePreview" class="text-center py-4">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/10 rounded-full flex items-center justify-center mx-auto text-blue-600 mb-2">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-600 dark:text-gray-300">ចុច ឬ អូសរូបភាព Slide ដាក់ទីនេះ</p>
                                <p class="text-[10px] text-gray-400 uppercase mt-1">PNG, JPG, WEBP</p>
                            </div>

                            <div x-show="imagePreview" x-cloak class="relative h-40 rounded-xl overflow-hidden shadow-md">
                                <img :src="imagePreview" class="w-full h-full object-cover">
                                <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur px-3 py-1 rounded-lg text-white text-[10px] font-bold">
                                    រូបភាពជ្រើសរើសរួច
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">លំដាប់បង្ហាញ (Order)</label>
                        <input type="number" name="order_column" value="1"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
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

<!-- EDIT SLIDE MODAL -->
<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">កែប្រែ Slide</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Slide Details</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/slideshows') }}/${currentSlide.id}`" method="POST" enctype="multipart/form-data" x-data="{ editPreview: null }">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង</label>
                        <input type="text" name="title" x-model="currentSlide.title"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើងរង</label>
                        <input type="text" name="subtitle" x-model="currentSlide.subtitle"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">រូបភាព Slide</label>
                        
                        <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-4 transition-all hover:border-blue-500 bg-gray-50/50 dark:bg-gray-800/50">
                            <input type="file" name="image" accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { editPreview = URL.createObjectURL(file); }"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <div class="relative h-40 rounded-xl overflow-hidden shadow-md">
                                <img :src="editPreview ? editPreview : ('/storage/' + currentSlide.image_path)" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="px-3 py-1 bg-white/90 text-gray-800 rounded-lg text-xs font-bold shadow">
                                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> ប្តូររូបភាពថ្មី
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">លំដាប់បង្ហាញ (Order)</label>
                        <input type="number" name="order_column" x-model="currentSlide.order_column"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" :checked="currentSlide.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ms-3 text-xs font-bold text-gray-700 dark:text-gray-300">បើកដំណើរការ (Active)</span>
                        </label>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
                        បោះបង់
                    </button>
                    <button type="submit" class="px-8 h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md active:scale-95 transition-all">
                        ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DETAIL SLIDE MODAL -->
<div x-show="showDetailModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-md relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="p-8 space-y-6 max-h-[60vh] overflow-y-auto custom-scrollbar text-center">
                <img :src="'/storage/' + currentSlide.image_path" class="w-full h-48 rounded-2xl object-cover shadow-md border dark:border-gray-700">
                <div>
                    <h4 class="text-xl font-black dark:text-white uppercase tracking-tight" x-text="currentSlide.title || 'គ្មានចំណងជើង'"></h4>
                    <p class="text-xs text-gray-400 font-bold mt-1" x-text="currentSlide.subtitle || 'គ្មានការបរិយាយ'"></p>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex gap-3 border-t dark:border-gray-800">
                <button @click="showDetailModal = false"
                    class="flex-1 py-2.5 bg-white dark:bg-gray-800 border dark:border-gray-700 font-bold text-xs rounded-xl hover:bg-gray-100 transition-all dark:text-white">
                    បិទវិញ
                </button>
                <button @click="showDetailModal = false; showEditModal = true"
                    class="flex-1 py-2.5 bg-blue-600 text-white font-bold text-xs rounded-xl shadow-md hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-pen-to-square"></i> កែសម្រួល
                </button>
            </div>
        </div>
    </div>
</div>