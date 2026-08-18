<style>
    .ck-editor__editable {
        min-height: 200px;
    }

    .dark .ck-editor__editable {
        background-color: #1f2937 !important;
        color: #ffffff !important;
    }

    .dark .ck-toolbar {
        background-color: #111827 !important;
        border-color: #374151 !important;
    }

    .dark .ck-button {
        color: #ffffff !important;
    }

    .dark .ck-button:hover {
        background-color: #374151 !important;
    }

    .ck-content {
        color: #000;
    }

    .ck-editor {
        width: 100%;
    }
</style>

<!-- Add Hotel Modal -->
<div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false; logoPreview = null" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមសណ្ឋាគារថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Hotel Property & Location Details</p>
                </div>
                <button @click="showAddModal = false; logoPreview = null" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះសណ្ឋាគារ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="ឧ. P&T Palace Hotel"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" required placeholder="ឧ. 012 345 678"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">អ៊ីមែល <span class="text-red-500">*</span></label>
                            <input type="email" name="email" required placeholder="ឧ. hotel@example.com"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">Latitude (ទីតាំង)</label>
                            <input type="text" name="latitude" placeholder="ឧ. 11.5564"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">Longitude (ទីតាំង)</label>
                            <input type="text" name="longitude" placeholder="ឧ. 104.9282"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">អាសយដ្ឋាន <span class="text-red-500">*</span></label>
                            <textarea name="address" rows="2" required placeholder="ទីតាំងសណ្ឋាគារ..."
                                class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium"></textarea>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">រូបភាព Logo</label>
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700">
                                <div class="w-20 h-20 rounded-2xl bg-white dark:bg-gray-900 flex items-center justify-center overflow-hidden border border-gray-200 dark:border-gray-700 shrink-0">
                                    <template x-if="logoPreview">
                                        <img :src="logoPreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!logoPreview">
                                        <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                                    </template>
                                </div>
                                <div class="flex-1">
                                    <input type="file" name="logo" class="hidden" id="hotel_logo_add"
                                        accept="image/*"
                                        @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { logoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <label for="hotel_logo_add" class="cursor-pointer px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-xs font-black uppercase rounded-xl shadow-sm hover:bg-blue-600 hover:text-white transition-all inline-flex items-center gap-2">
                                        <i class="fa-solid fa-upload"></i> ជ្រើសរើសរូបភាព
                                    </label>
                                    <p class="text-[10px] text-gray-400 mt-1">ទ្រង់ទ្រាយរូបភាព៖ JPG, PNG, WEBP (អតិបរមា 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាពដំណើរការ</label>
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-gray-50 dark:bg-gray-800">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-xs font-black uppercase text-gray-600 dark:text-gray-300 tracking-wider">សកម្ម (បើកដំណើរការ)</span>
                                </label>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">ពិពណ៌នា (Description)</label>
                            <textarea id="add_hotel_editor" name="description" class="w-full"></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false; logoPreview = null"
                        class="px-6 h-11 font-bold text-xs uppercase tracking-wider text-gray-400 hover:text-red-500 transition-all">
                        បោះបង់
                    </button>
                    <button type="submit"
                        class="px-8 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-blue-500/10 active:scale-95 transition-all">
                        រក្សាទុកទិន្នន័យ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Hotel Modal -->
<div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែសណ្ឋាគារ៖ <span x-text="currentHotel.name" class="text-blue-500"></span></h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Hotel Information</p>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/hotels') }}/${currentHotel.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះសណ្ឋាគារ <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="currentHotel.name" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" x-model="currentHotel.phone" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">អ៊ីមែល <span class="text-red-500">*</span></label>
                            <input type="email" name="email" x-model="currentHotel.email" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">Latitude</label>
                            <input type="text" name="latitude" x-model="currentHotel.latitude"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">Longitude</label>
                            <input type="text" name="longitude" x-model="currentHotel.longitude"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាព</label>
                            <div class="relative">
                                <select name="status" x-model="currentHotel.status"
                                    class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold">
                                    <option value="1">សកម្ម (បើកដំណើរការ)</option>
                                    <option value="0">ផ្អាក (ផ្អាកដំណើរការ)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">អាសយដ្ឋាន <span class="text-red-500">*</span></label>
                            <textarea name="address" x-model="currentHotel.address" rows="2" required
                                class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium"></textarea>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">រូបភាព Logo</label>
                            <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700">
                                <div class="w-20 h-20 rounded-2xl bg-white dark:bg-gray-900 overflow-hidden border border-gray-200 dark:border-gray-700 shrink-0 relative flex items-center justify-center">
                                    <template x-if="editLogoPreview">
                                        <img :src="editLogoPreview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!editLogoPreview && currentHotel.logo">
                                        <img :src="`{{ asset('storage') }}/${currentHotel.logo}`" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!editLogoPreview && !currentHotel.logo">
                                        <i class="fa-solid fa-image text-gray-300 text-3xl"></i>
                                    </template>
                                </div>

                                <div class="flex-1">
                                    <input type="file" name="logo" class="hidden" id="hotel_logo_edit" accept="image/*"
                                        @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { editLogoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                                    <label for="hotel_logo_edit" class="cursor-pointer px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-xs font-black uppercase rounded-xl shadow-sm hover:bg-emerald-600 hover:text-white transition-all inline-flex items-center gap-2">
                                        <i class="fa-solid fa-upload"></i> ជ្រើសរើសរូបភាពថ្មី
                                    </label>
                                    <p class="text-[10px] text-gray-400 mt-1">* ទុកទំនេរប្រសិនបើមិនចង់ប្តូររូបភាព</p>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">ពិពណ៌នា (Description)</label>
                            <textarea id="edit_hotel_editor" name="description" class="w-full"></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-6 py-2.5 text-gray-500 hover:text-gray-700 font-medium transition-colors">បោះបង់</button>
                    <button type="submit"
                        class="px-8 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 transition-all active:scale-95 flex items-center gap-2">
                        <i class="fa-solid fa-check"></i> ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Detail Modal -->
<div x-show="showDetailModal" class="fixed inset-0 z-[100] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            @click="showDetailModal = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all flex flex-col z-10"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="h-32 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 relative shrink-0">
                <button @click="showDetailModal = false" class="absolute top-4 right-4 w-9 h-9 flex items-center justify-center rounded-full bg-black/20 hover:bg-black/40 text-white transition-all z-10">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

                <div class="absolute -bottom-10 left-6 flex items-end gap-4 z-10">
                    <img :src="currentHotel.logo ? `{{ asset('storage') }}/${currentHotel.logo}` : 'https://ui-avatars.com/api/?background=3b82f6&color=ffffff&name=' + encodeURIComponent(currentHotel.name || 'Hotel')"
                        class="w-20 h-20 bg-white dark:bg-gray-900 rounded-2xl shadow-xl object-cover border-4 border-white dark:border-gray-900 shrink-0">
                    <div class="mb-1">
                        <h2 class="text-xl font-black text-white leading-tight drop-shadow-md" x-text="currentHotel.name"></h2>
                        <div class="mt-1 flex items-center gap-2">
                            <template x-if="currentHotel.status == 1">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 backdrop-blur-md">● សកម្ម (Active)</span>
                            </template>
                            <template x-if="currentHotel.status == 0">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 backdrop-blur-md">● ផ្អាក (Inactive)</span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-14 p-6 space-y-4 overflow-y-auto max-h-[calc(100vh-220px)] custom-scrollbar">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="p-3.5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] uppercase font-extrabold text-gray-400 tracking-wider mb-1">អ៊ីមែល</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-500 text-xs"></i>
                            <p class="font-bold dark:text-gray-200 text-xs truncate" x-text="currentHotel.email || 'មិនមាន'"></p>
                        </div>
                    </div>

                    <div class="p-3.5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] uppercase font-extrabold text-gray-400 tracking-wider mb-1">លេខទូរស័ព្ទ</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone-alt text-emerald-500 text-xs"></i>
                            <p class="font-bold dark:text-gray-200 text-xs" x-text="currentHotel.phone || 'មិនមាន'"></p>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-3.5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] uppercase font-extrabold text-gray-400 tracking-wider mb-1">អាសយដ្ឋាន</p>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-red-500 text-xs mt-0.5"></i>
                            <p class="font-medium dark:text-gray-200 text-xs leading-relaxed" x-text="currentHotel.address || 'មិនមាន'"></p>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-3.5 bg-blue-50/50 dark:bg-blue-500/5 rounded-2xl border border-blue-100 dark:border-blue-500/20">
                        <div class="flex justify-between items-center gap-4">
                            <div>
                                <p class="text-[10px] uppercase font-extrabold text-blue-500 tracking-wider mb-0.5">ទីតាំងភូមិសាស្ត្រ (Coordinates)</p>
                                <p class="text-xs dark:text-gray-300 font-medium">
                                    Lat: <span class="font-bold text-gray-800 dark:text-white" x-text="currentHotel.latitude || '0.00'"></span> |
                                    Long: <span class="font-bold text-gray-800 dark:text-white" x-text="currentHotel.longitude || '0.00'"></span>
                                </p>
                            </div>
                            <template x-if="currentHotel.latitude && currentHotel.longitude">
                                <a :href="`https://www.google.com/maps?q=${currentHotel.latitude},${currentHotel.longitude}`"
                                    target="_blank"
                                    class="px-3.5 py-1.5 bg-white dark:bg-gray-800 shadow-sm border border-gray-200 dark:border-gray-700 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white transition-all flex items-center gap-1.5 shrink-0">
                                    <i class="fas fa-directions"></i> ផែនទី
                                </a>
                            </template>
                        </div>
                    </div>

                    <div class="md:col-span-2 p-3.5 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] uppercase font-extrabold text-gray-400 tracking-wider mb-1">ការពិពណ៌នា</p>
                        <div class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed ck-content" x-html="currentHotel.description || '<i>មិនមានការពិពណ៌នា...</i>'"></div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-800 flex justify-end items-center gap-3 shrink-0">
                <button @click="showDetailModal = false"
                    class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 font-bold text-xs rounded-xl hover:bg-gray-100 transition-all text-gray-700 dark:text-gray-300">
                    បិទ
                </button>
                <button @click="showDetailModal = false; openEditModal(currentHotel)"
                    class="px-5 py-2.5 bg-blue-600 text-white font-bold text-xs rounded-xl shadow-md hover:bg-blue-700 active:scale-[0.98] transition-all flex items-center gap-1.5">
                    <i class="fa-solid fa-pen-to-square"></i> កែសម្រួល
                </button>
            </div>

        </div>
    </div>
</div>