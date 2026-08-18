<!-- ADD ABOUT CONTENT MODAL -->
<div x-show="showAddAbout" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddAbout = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">បន្ថែមមាតិកាថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New About Entry</p>
                    </div>
                </div>
                <button @click="showAddAbout = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('about.store') }}" method="POST" enctype="multipart/form-data" x-data="{ aboutImagePreview: null }">
                @csrf
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">Key (សម្គាល់) <span class="text-red-500">*</span></label>
                            <input type="text" name="key" placeholder="ឧ. vision" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ស្ថានភាព</label>
                            <div class="relative group">
                                <select name="status" class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold text-sm">
                                    <option value="1" selected>បង្ហាញ</option>
                                    <option value="0">មិនបង្ហាញ</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង (KH) <span class="text-red-500">*</span></label>
                        <input type="text" name="title_kh" required placeholder="ឧ. ចក្ខុវិស័យរបស់យើង"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ខ្លឹមសារ (KH) <span class="text-red-500">*</span></label>
                        <textarea name="content_kh" rows="4" required placeholder="រៀបរាប់ខ្លឹមសារ..."
                            class="w-full p-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">រូបភាព</label>
                        
                        <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-4 transition-all hover:border-blue-500 bg-gray-50/50 dark:bg-gray-800/50">
                            <input type="file" name="image" accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { aboutImagePreview = URL.createObjectURL(file); }"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <div x-show="!aboutImagePreview" class="text-center py-4">
                                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-500/10 rounded-full flex items-center justify-center mx-auto text-blue-600 mb-2">
                                    <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-600 dark:text-gray-300">ចុច ឬ អូសរូបភាពដាក់ទីនេះ</p>
                                <p class="text-[10px] text-gray-400 uppercase mt-1">PNG, JPG, WEBP</p>
                            </div>

                            <div x-show="aboutImagePreview" x-cloak class="relative h-40 rounded-xl overflow-hidden shadow-md">
                                <img :src="aboutImagePreview" class="w-full h-full object-cover">
                                <div class="absolute bottom-2 right-2 bg-black/60 backdrop-blur px-3 py-1 rounded-lg text-white text-[10px] font-bold">
                                    រូបភាពជ្រើសរើសរួច
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddAbout = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
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

<!-- EDIT ABOUT CONTENT MODAL -->
<div x-show="showEditAbout" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditAbout = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">កែប្រែមាតិកា</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update About Entry</p>
                    </div>
                </div>
                <button @click="showEditAbout = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/about/update') }}/${currentAbout.id}`" method="POST" enctype="multipart/form-data" x-data="{ editAboutImagePreview: null }">
                @csrf
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">Key</label>
                            <input type="text" name="key" x-model="currentAbout.key" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ស្ថានភាព</label>
                            <div class="relative group">
                                <select name="status" x-model="currentAbout.status" class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all appearance-none font-bold text-sm">
                                    <option value="1">បង្ហាញ</option>
                                    <option value="0">មិនបង្ហាញ</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង (KH)</label>
                        <input type="text" name="title_kh" x-model="currentAbout.title_kh" required
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ខ្លឹមសារ (KH)</label>
                        <textarea name="content_kh" x-model="currentAbout.content_kh" rows="4" required
                            class="w-full p-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-amber-500 outline-none transition-all text-sm font-medium"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">រូបភាព</label>
                        
                        <div class="relative group border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl p-4 transition-all hover:border-amber-500 bg-gray-50/50 dark:bg-gray-800/50">
                            <input type="file" name="image" accept="image/*"
                                @change="const file = $event.target.files[0]; if (file) { editAboutImagePreview = URL.createObjectURL(file); }"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                            <div class="relative h-40 rounded-xl overflow-hidden shadow-md">
                                <img :src="editAboutImagePreview ? editAboutImagePreview : (currentAbout.image ? '/storage/' + currentAbout.image : 'https://via.placeholder.com/400')" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="px-3 py-1 bg-white/90 text-gray-800 rounded-lg text-xs font-bold shadow">
                                        <i class="fa-solid fa-cloud-arrow-up mr-1"></i> ប្តូររូបភាពថ្មី
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditAbout = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
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

<!-- ADD HISTORY MODAL -->
<div x-show="showAddHistory" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddHistory = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-clock-rotate-left text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">បន្ថែមប្រវត្តិថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New History Entry</p>
                    </div>
                </div>
                <button @click="showAddHistory = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('history.store') }}" method="POST">
                @csrf
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឆ្នាំ <span class="text-red-500">*</span></label>
                            <input type="text" name="year" placeholder="ឧ. ២០២៦" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">អាទិភាព (លំដាប់)</label>
                            <input type="number" name="order_priority" value="1" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង (KH) <span class="text-red-500">*</span></label>
                        <input type="text" name="title_kh" required placeholder="ឧ. បង្កើតដំណាក់កាលដំបូង"
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ការពិពណ៌នា (KH) <span class="text-red-500">*</span></label>
                        <textarea name="description_kh" rows="4" required placeholder="រៀបរាប់ការប្រវត្តិ..."
                            class="w-full p-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-medium"></textarea>
                    </div>
                    <input type="hidden" name="status" value="1">
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddHistory = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
                        បោះបង់
                    </button>
                    <button type="submit" class="px-8 h-11 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md active:scale-95 transition-all">
                        រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT HISTORY MODAL -->
<div x-show="showEditHistory" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditHistory = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-500/10 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                        <i class="fa-solid fa-pen-to-square text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-lg dark:text-white uppercase tracking-tight">កែប្រែប្រវត្តិ</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update History Entry</p>
                    </div>
                </div>
                <button @click="showEditHistory = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/history/update') }}/${currentHistory.id}`" method="POST">
                @csrf
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឆ្នាំ</label>
                            <input type="text" name="year" x-model="currentHistory.year" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">អាទិភាព</label>
                            <input type="number" name="order_priority" x-model="currentHistory.order_priority" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-sm">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំណងជើង (KH)</label>
                        <input type="text" name="title_kh" x-model="currentHistory.title_kh" required
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all font-bold text-sm">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ការពិពណ៌នា (KH)</label>
                        <textarea name="description_kh" x-model="currentHistory.description_kh" rows="4" required
                            class="w-full p-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-indigo-500 outline-none transition-all text-sm font-medium"></textarea>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditHistory = false" class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-colors">
                        បោះបង់
                    </button>
                    <button type="submit" class="px-8 h-11 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md active:scale-95 transition-all">
                        ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>