<!-- ADD TOUR MODAL -->
<div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-50 dark:bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <i class="fa-solid fa-map-location-dot text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមកន្លែងទេសចរណ៍</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New Tour Destination</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('tours.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-10 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឈ្មោះកន្លែង <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="ឧ. ប្រាសាទអង្គរវត្ត"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចម្ងាយ (គីឡូម៉ែត្រ) <span class="text-red-500">*</span></label></label>
                            <input type="text" name="distance" placeholder="ចម្ងាយពីសណ្ឋាគារ"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តំណភ្ជាប់ Google Maps</label>
                            <input type="url" name="google_map_link" placeholder="https://goo.gl/maps/..."
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                រូបភាពកន្លែងទេសចរណ៍
                            </label>

                            <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-[2rem] p-4 transition-all hover:border-blue-500 bg-gray-50/50 dark:bg-gray-800/50">
                                <input type="file" name="images[]" multiple accept="image/*"
                                    @change="handleFileSelect"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <div class="text-center py-4">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/10 rounded-full flex items-center justify-center mx-auto text-blue-600 mb-2">
                                        <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                    </div>
                                    <p class="text-xs font-bold text-gray-500">ចុច ឬ អូសរូបភាពដាក់ទីនេះ</p>
                                    <p class="text-[10px] text-gray-400 uppercase mt-1">PNG, JPG</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-4 mt-4">
                                <template x-for="(img, index) in imagePreviews" :key="index">
                                    <div class="relative group aspect-square rounded-2xl overflow-hidden border dark:border-gray-700 shadow-sm">
                                        <img :src="img.url" class="w-full h-full object-cover transition-transform group-hover:scale-110">

                                        <button type="button" @click="removePreview(index)"
                                            class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                            <i class="fa-solid fa-times text-[10px]"></i>
                                        </button>
                                    </div>
                                </template>

                                <template x-if="showEditModal && currentTour.image">
                                    <template x-for="(oldImg, idx) in (Array.isArray(currentTour.image) ? currentTour.image : [currentTour.image])" :key="'old-'+idx">
                                        <div class="relative group aspect-square rounded-2xl overflow-hidden border border-blue-200">
                                            <img :src="'/storage/' + oldImg" class="w-full h-full object-cover opacity-80">
                                            <div class="absolute inset-0 flex items-center justify-center bg-blue-600/20">
                                                <span class="text-[8px] font-black text-white uppercase bg-blue-600 px-2 py-1 rounded-full">រូបភាពចាស់</span>
                                            </div>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ការពិពណ៌នា</label>
                            <textarea name="description" rows="3" class="w-full p-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 mb-4 ml-2 tracking-widest">ស្ថានភាព</label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative group">
                                    <input type="radio" name="status" value="1" class="peer sr-only" checked>
                                    <div class="flex items-center justify-center py-3 rounded-xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 cursor-pointer transition-all">
                                        <span class="text-[11px] font-black uppercase tracking-widest">បង្ហាញ</span>
                                    </div>
                                </label>
                                <label class="relative group">
                                    <input type="radio" name="status" value="0" class="peer sr-only">
                                    <div class="flex items-center justify-center py-3 rounded-xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 peer-checked:text-red-600 cursor-pointer transition-all">
                                        <span class="text-[11px] font-black uppercase tracking-widest">មិនបង្ហាញ</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 rounded-b-[1.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-8 h-10 font-black text-sm uppercase text-gray-400 hover:text-red-500 italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase rounded-2xl shadow-xl transition-all">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT TOUR MODAL -->
<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-amber-50 dark:bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <i class="fa-solid fa-pen-to-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលព័ត៌មាន</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Tour Details</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/tours') }}/${currentTour.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-10 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឈ្មោះកន្លែង</label>
                            <input type="text" name="name" x-model="currentTour.name" required class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចម្ងាយ</label>
                            <input type="text" name="distance" x-model="currentTour.distance" class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តំណភ្ជាប់ Google Maps</label>
                            <input type="url" name="google_map_link" x-model="currentTour.google_map_link" class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">
                                រូបភាពកន្លែងទេសចរណ៍
                            </label>

                            <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-[2rem] p-4 transition-all hover:border-blue-500 bg-gray-50/50 dark:bg-gray-800/50">
                                <input type="file" name="images[]" multiple accept="image/*"
                                    @change="handleFileSelect"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                                <div class="text-center py-4">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/10 rounded-full flex items-center justify-center mx-auto text-blue-600 mb-2">
                                        <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                    </div>
                                    <p class="text-xs font-bold text-gray-500">ចុច ឬ អូសរូបភាពដាក់ទីនេះ</p>
                                    <p class="text-[10px] text-gray-400 uppercase mt-1">PNG, JPG up to 5MB</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-4 mt-4">
                                <template x-for="(img, index) in imagePreviews" :key="index">
                                    <div class="relative group aspect-square rounded-2xl overflow-hidden border dark:border-gray-700 shadow-sm">
                                        <img :src="img.url" class="w-full h-full object-cover transition-transform group-hover:scale-110">

                                        <button type="button" @click="removePreview(index)"
                                            class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">
                                            <i class="fa-solid fa-times text-[10px]"></i>
                                        </button>
                                    </div>
                                </template>

                                <template x-if="showEditModal && currentTour.image">
                                    <template x-for="(oldImg, idx) in currentTour.image" :key="'old-'+idx">
                                        <div class="relative group">
                                            <img :src="'/storage/' + oldImg" class="w-20 h-20 object-cover rounded-lg">

                                            <input type="hidden" name="existing_images[]" :value="oldImg">

                                            <button type="button" @click="currentTour.image.splice(idx, 1)"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </div>
                                    </template>
                                </template>
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2 space-y-3">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ការពិពណ៌នា</label>
                            <textarea name="description" x-model="currentTour.description" rows="3" class="w-full p-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
                        </div>


                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative group">
                                <input type="radio" name="status" value="1" x-model="currentTour.status" class="peer sr-only">
                                <div class="flex items-center justify-center py-3 rounded-xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-500/10 peer-checked:text-green-600 cursor-pointer transition-all">
                                    <span class="text-[11px] font-black uppercase tracking-widest">បង្ហាញ</span>
                                </div>
                            </label>
                            <label class="relative group">
                                <input type="radio" name="status" value="0" x-model="currentTour.status" class="peer sr-only">
                                <div class="flex items-center justify-center py-3 rounded-xl border-2 border-transparent bg-gray-50 dark:bg-gray-800 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-500/10 peer-checked:text-red-600 cursor-pointer transition-all">
                                    <span class="text-[11px] font-black uppercase tracking-widest">មិនបង្ហាញ</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 rounded-b-[1.5rem] border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-8 h-10 font-black text-sm uppercase text-gray-400 hover:text-red-500 italic">បោះបង់</button>
                    <button type="submit" class="px-8 h-10 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase rounded-2xl shadow-xl transition-all">រក្សាទុកការផ្លាស់ប្តូរ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DETAIL MODAL -->
<div x-show="showDetailModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="h-48 bg-gray-200 dark:bg-gray-800 relative">
                <template x-if="currentTour.image">
                    <img :src="'/storage/' + (Array.isArray(currentTour.image) ? currentTour.image[0] : currentTour.image)"
                        class="w-full h-full object-cover">
                </template>
                <button @click="showDetailModal = false" class="absolute top-5 right-5 w-10 h-10 flex items-center justify-center rounded-full bg-black/20 hover:bg-black/40 text-white transition-all">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="p-10 space-y-6">
                <div>
                    <h3 class="text-2xl font-black dark:text-white uppercase tracking-tight" x-text="currentTour.name"></h3>
                    <div class="flex items-center gap-2 mt-2">
                        <i class="fa-solid fa-location-arrow text-blue-500 text-xs"></i>
                        <p class="text-sm text-gray-500 font-bold uppercase tracking-widest" x-text="currentTour.distance || 'មិនមានបញ្ជាក់ចម្ងាយ'"></p>
                    </div>
                </div>

                <div class="space-y-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ការពិពណ៌នា</span>
                    <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300" x-text="currentTour.description || 'មិនមានការពិពណ៌នា...'"></p>
                </div>

                <hr class="border-gray-100 dark:border-gray-800">

                <div class="flex items-center justify-between">
                    <div class="space-y-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">ស្ថានភាព</span>
                        <template x-if="currentTour.status == 1">
                            <span class="block px-3 py-1 bg-green-100 text-green-600 text-[10px] font-black uppercase rounded-lg w-max">បង្ហាញជាសាធារណៈ</span>
                        </template>
                    </div>

                    <template x-if="currentTour.google_map_link">
                        <a :href="currentTour.google_map_link" target="_blank"
                            class="flex items-center gap-2 text-blue-600 font-black text-xs uppercase hover:underline">
                            <i class="fa-solid fa-map-marked-alt"></i> មើលលើផែនទី
                        </a>
                    </template>
                </div>
            </div>

            <div class="px-10 py-8 bg-gray-50 dark:bg-gray-800/50 flex gap-3">
                <button @click="showDetailModal = false" class="flex-1 h-12 bg-white dark:bg-gray-800 border dark:border-gray-700 font-black text-[11px] uppercase rounded-xl dark:text-white">បិទវិញ</button>
                <button @click="showDetailModal = false; showEditModal = true" class="flex-1 h-12 bg-blue-600 text-white font-black text-[11px] uppercase rounded-xl shadow-lg shadow-blue-500/20 active:scale-95 transition-all">កែសម្រួល</button>
            </div>
        </div>
    </div>
</div>