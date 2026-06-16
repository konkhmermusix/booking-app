<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមសណ្ឋាគារថ្មី</h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">បញ្ចូលព័ត៌មានលម្អិតខាងក្រោម</p>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">×</button>
            </div>

            <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-5">
                            <div>
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">ឈ្មោះសណ្ឋាគារ</label>
                                <input type="text" name="name" required placeholder="P&T Palace"
                                    class="w-full h-14 px-6 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white dark:focus:bg-gray-900 outline-none transition-all">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">លេខទូរស័ព្ទ</label>
                                    <input type="text" name="phone" placeholder="012 345 678"
                                        class="w-full h-14 px-6 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">អ៊ីមែល</label>
                                    <input type="email" name="email" placeholder="hotel@example.com"
                                        class="w-full h-14 px-6 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">អាសយដ្ឋាន</label>
                                <textarea name="address" rows="2" placeholder="ទីតាំងសណ្ឋាគារ..."
                                    class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all"></textarea>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">Latitude</label>
                                    <input type="text" name="latitude" placeholder="11.5564"
                                        class="w-full h-14 px-6 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">Longitude</label>
                                    <input type="text" name="longitude" placeholder="104.9282"
                                        class="w-full h-14 px-6 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">រូបភាព Logo</label>
                                <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border-2 border-dashed border-gray-200 dark:border-gray-700">
                                    <div class="w-16 h-16 rounded-xl bg-white dark:bg-gray-900 flex items-center justify-center overflow-hidden border dark:border-gray-700">
                                        <template x-if="logoPreview">
                                            <img :src="logoPreview" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!logoPreview">
                                            <i class="fa-solid fa-image text-gray-300 text-xl"></i>
                                        </template>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="logo" class="hidden" id="hotel_logo"
                                            accept="image/*"
                                            @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { logoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                                        <label for="hotel_logo" class="cursor-pointer px-4 py-2 bg-white dark:bg-gray-900 text-[10px] font-black uppercase rounded-lg shadow-sm hover:bg-blue-500 hover:text-white transition-all inline-block">
                                            ជ្រើសរើសរូបភាព
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-blue-50/50 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-500/10">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="status" value="0">
                                    <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ms-3 text-xs font-black uppercase text-gray-500 dark:text-gray-400 tracking-widest">បើកដំណើរការ</span>
                                </label>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 mb-2 tracking-widest">ព័ត៌មានលម្អិត</label>
                            <textarea name="description" rows="3" placeholder="រៀបរាប់ខ្លីៗ..."
                                class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 focus:bg-white outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-3 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false"
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

<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែសណ្ឋាគារ៖ <span x-text="currentHotel.name" class="text-blue-500"></span></h3>

                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/hotels') }}/${currentHotel.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-8 space-y-5 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ឈ្មោះសណ្ឋាគារ</label>
                            <input type="text" name="name" x-model="currentHotel.name" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">អ៊ីមែល</label>
                            <input type="email" name="email" x-model="currentHotel.email"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">លេខទូរស័ព្ទ</label>
                            <input type="text" name="phone" x-model="currentHotel.phone"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ស្ថានភាព</label>
                            <div class="relative group">
                                <select name="status" x-model="currentHotel.status"
                                    class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium text-sm relative z-0">
                                    <option value="1">សកម្ម (បើកដំណើរការ)</option>
                                    <option value="0">ផ្អាក (ផ្អាកដំណើរការ)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">Latitude</label>
                            <input type="text" name="latitude" x-model="currentHotel.latitude"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">Longitude</label>
                            <input type="text" name="longitude" x-model="currentHotel.longitude"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:font-normal text-sm font-medium">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">អាសយដ្ឋាន</label>
                            <textarea name="address" x-model="currentHotel.address" rows="2"
                                class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                placeholder="ទីតាំងសណ្ឋាគារ..."></textarea>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold mb-1.5 dark:text-gray-300">ការពិពណ៌នា</label>
                            <textarea name="description" x-model="currentHotel.description" rows="2"
                                class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                                placeholder="រៀបរាប់ខ្លីៗ..."></textarea>
                        </div>       

                        <div class="col-span-2 border-t dark:border-gray-700 pt-4 mt-2">
                            <label class="block text-sm font-medium mb-2 dark:text-gray-400">រូបភាព Logo</label>
                            <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                                <template x-if="currentHotel.logo">
                                    <div class="relative shrink-0">
                                        <img :src="`{{ asset('storage') }}/${currentHotel.logo}`"
                                            class="w-16 h-16 rounded-xl object-cover border dark:border-gray-600 shadow-sm">
                                        <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[8px] px-1.5 py-0.5 rounded-full">បច្ចុប្បន្ន</span>
                                    </div>
                                </template>

                                <div class="flex-1">
                                    <input type="file" name="logo"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                                    <p class="text-[10px] text-gray-400 mt-1">* ទុកវាឱ្យទំនេរ ប្រសិនបើមិនចង់ប្តូររូបភាព</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-5 py-2 text-gray-500 hover:text-gray-700 font-medium transition-colors">បោះបង់</button>
                    <button type="submit"
                        class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
                        ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"
            @click="showDetailModal = false"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all flex flex-col"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 relative flex-shrink-0">
                <div class="absolute inset-0 opacity-25 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>

                <button @click="showDetailModal = false" class="absolute top-5 right-5 w-9 h-9 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-all z-10">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>

                <div class="absolute -bottom-12 left-8 flex items-end gap-4 z-10">
                    <img :src="currentHotel.logo ? `{{ asset('storage') }}/${currentHotel.logo}` : 'https://ui-avatars.com/api/?background=random&name=' + encodeURIComponent(currentHotel.name || 'Hotel')"
                        class="w-24 h-24 bg-white dark:bg-gray-900 rounded-2xl shadow-xl object-cover border-4 border-white dark:border-gray-900 flex-shrink-0">
                    <div class="mb-2">
                        <h2 class="text-2xl font-black text-white dark:text-white leading-tight drop-shadow-lg" x-text="currentHotel.name"></h2>
                        <div class="mt-1">
                            <template x-if="currentHotel.status == 1">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">● សកម្ម (Active)</span>
                            </template>
                            <template x-if="currentHotel.status == 0">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-500/20 dark:text-gray-400">● ផ្អាក (Inactive)</span>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-16 p-8 space-y-6 overflow-y-auto max-h-[calc(100vh-250px)]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">អ៊ីមែល</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-500 text-sm"></i>
                            <p class="font-bold dark:text-gray-200 text-sm truncate" x-text="currentHotel.email || 'មិនមាន'"></p>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">លេខទូរស័ព្ទ</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone-alt text-emerald-500 text-sm"></i>
                            <p class="font-bold dark:text-gray-200 text-sm" x-text="currentHotel.phone || 'មិនមាន'"></p>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">អាសយដ្ឋាន</p>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-red-500 text-sm mt-1"></i>
                            <p class="font-medium dark:text-gray-200 text-sm leading-relaxed" x-text="currentHotel.address || 'មិនមាន'"></p>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 p-4 bg-blue-50/50 dark:bg-blue-500/5 rounded-2xl border border-blue-100 dark:border-blue-500/20">
                        <div class="flex justify-between items-center gap-4">
                            <div>
                                <p class="text-[10px] uppercase font-black text-blue-400 tracking-widest mb-1">ទីតាំងភូមិសាស្ត្រ</p>
                                <p class="text-xs dark:text-gray-400">
                                    Lat: <span class="font-bold" x-text="currentHotel.latitude || '0.00'"></span> |
                                    Long: <span class="font-bold" x-text="currentHotel.longitude || '0.00'"></span>
                                </p>
                            </div>
                            <template x-if="currentHotel.latitude && currentHotel.longitude">
                                <a :href="`https://www.google.com/maps?q=${currentHotel.latitude},${currentHotel.longitude}`"
                                    target="_blank"
                                    class="px-4 py-2 bg-white dark:bg-gray-800 shadow-sm border dark:border-gray-700 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 dark:hover:text-white transition-all flex items-center gap-1.5 flex-shrink-0">
                                    <i class="fas fa-directions"></i> បើកផែនទី
                                </a>
                            </template>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 p-2">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">ការពិពណ៌នា</p>
                        <p class="text-sm dark:text-gray-400 leading-relaxed italic" x-text="currentHotel.description || 'មិនមានការពិពណ៌នា...'"></p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-4 bg-gray-50 dark:bg-gray-800/40 border-t border-gray-100 dark:border-gray-800 flex justify-end items-center gap-3 flex-shrink-0">
                <button @click="showDetailModal = false"
                    class="flex-1 h-11 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 font-bold text-[11px] uppercase tracking-widest rounded-xl hover:bg-gray-50 dark:hover:bg-gray-750 transition-all text-gray-600 dark:text-gray-300">
                    បិទ
                </button>
                <button @click="showDetailModal = false; showEditModal = true"
                    class="flex-1 h-11 bg-blue-600 text-white font-bold text-[11px] uppercase tracking-widest rounded-xl shadow-md shadow-blue-500/10 hover:bg-blue-700 active:scale-[0.98] transition-all">
                    កែសម្រួលគណនី
                </button>
            </div>

        </div>
    </div>
</div>