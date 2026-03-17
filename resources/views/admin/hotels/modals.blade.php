<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមសណ្ឋាគារថ្មី</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('hotels.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ឈ្មោះសណ្ឋាគារ</label>
                        <input type="text" name="name" required placeholder="បញ្ចូលឈ្មោះសណ្ឋាគារ"
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 focus:ring-blue-500/50 transition-all">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">អ៊ីមែល</label>
                        <input type="email" name="email" placeholder="example@gmail.com" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" placeholder="012 345 678" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">អាសយដ្ឋាន</label>
                        <input type="text" name="address" placeholder="ផ្ទះលេខ... ផ្លូវ... ខណ្ឌ... រាជធានីភ្នំពេញ" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Latitude (រយៈទទឹង)</label>
                        <input type="text" name="latitude" placeholder="ឧទាហរណ៍៖ 11.5564" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">Longitude (រយៈបណ្ដោយ)</label>
                        <input type="text" name="longitude" placeholder="ឧទាហរណ៍៖ 104.9282" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">រូបភាព Logo (1x1)</label>
                        <input type="file" name="logo" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ស្ថានភាព</label>
                        <select name="status" class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none cursor-pointer">
                            <option value="1">សកម្ម (បើកដំណើរការ)</option>
                            <option value="0">ផ្អាក (ផ្អាកដំណើរការ)</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-300">ព័ត៌មានលម្អិតសណ្ឋាគារ</label>
                        <textarea name="description" rows="3" placeholder="រៀបរាប់ខ្លីៗអំពីសណ្ឋាគាររបស់អ្នក..."
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2 text-gray-500 hover:text-gray-700 font-medium">បោះបង់</button>
                    <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-500/20 transition-all active:scale-95">
                        <i class="fas fa-save mr-2"></i>រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែសណ្ឋាគារ៖ <span x-text="currentHotel.name" class="text-blue-500"></span></h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/hotels') }}/${currentHotel.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-400">ឈ្មោះសណ្ឋាគារ</label>
                        <input type="text" name="name" x-model="currentHotel.name" required
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-emerald-500/50 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-400">អ៊ីមែល</label>
                        <input type="email" name="email" x-model="currentHotel.email"
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-400">លេខទូរស័ព្ទ</label>
                        <input type="text" name="phone" x-model="currentHotel.phone"
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-400">ស្ថានភាព</label>
                        <select name="status" x-model="currentHotel.status"
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                            <option value="1">សកម្ម (បើកដំណើរការ)</option>
                            <option value="0">ផ្អាក (ផ្អាកដំណើរការ)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-400">Latitude</label>
                        <input type="text" name="latitude" x-model="currentHotel.latitude"
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1 dark:text-gray-400">Longitude</label>
                        <input type="text" name="longitude" x-model="currentHotel.longitude"
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none">
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium mb-1 dark:text-gray-400">អាសយដ្ឋាន</label>
                        <textarea name="address" x-model="currentHotel.address" rows="2"
                            class="w-full px-4 py-2 rounded-xl border dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none"></textarea>
                    </div>

                    <div class="col-span-2 border-t dark:border-gray-700 pt-4 mt-2">
                        <label class="block text-sm font-medium mb-2 dark:text-gray-400">រូបភាព Logo</label>
                        <div class="flex items-center gap-4">
                            <template x-if="currentHotel.logo">
                                <div class="relative">
                                    <img :src="`{{ asset('storage') }}/${currentHotel.logo}`"
                                        class="w-16 h-16 rounded-xl object-cover border dark:border-gray-600 shadow-sm">
                                    <span class="absolute -top-2 -right-2 bg-emerald-500 text-white text-[8px] px-1.5 py-0.5 rounded-full">បច្ចុប្បន្ន</span>
                                </div>
                            </template>

                            <div class="flex-1">
                                <input type="file" name="logo"
                                    class=" w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                                <p class="text-[10px] text-gray-400 mt-1">* ទុកវាឱ្យទំនេរ ប្រសិនបើមិនចង់ប្តូររូបភាព</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 rounded-b-2xl border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-5 py-2 text-gray-500 hover:text-gray-700 font-medium transition-colors">បោះបង់</button>
                    <button type="submit"
                        class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-500/20 transition-all active:scale-95">
                        <i class="fas fa-sync-alt mr-2"></i>ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = true"></div>
        <div class="bg-white dark:bg-gray-900 rounded-[2.5rem] shadow-2xl w-full max-w-2xl relative border dark:border-gray-800 overflow-hidden">
            <div class="px-8 py-6 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">
                    ព័ត៌មានលម្អិត៖ <span x-text="currentHotel.name" class="text-blue-500"></span>
                </h3>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all">&times;</button>
            </div>

            <div class="h-28 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700"></div>
            <div class="px-8 pb-8">
                <div class="relative -mt-14 mb-6 flex items-end gap-5">
                    <img :src="currentHotel.logo ? `{{ asset('storage') }}/${currentHotel.logo}` : 'https://ui-avatars.com/api/?background=random&name=' + currentHotel.name"
                        class="w-28 h-28 rounded-[2rem] border-4 border-white dark:border-gray-900 shadow-2xl object-cover bg-white">

                    <div class="mb-2">
                        <h2 class="text-2xl font-black dark:text-white leading-none mb-2" x-text="currentHotel.name"></h2>
                        <template x-if="currentHotel.status == 1">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">● សកម្ម (Active)</span>
                        </template>
                        <template x-if="currentHotel.status == 0">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-500/10 dark:text-gray-400">● ផ្អាក (Inactive)</span>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">អ៊ីមែល</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-500 text-sm"></i>
                            <p class="font-bold dark:text-gray-200 text-sm" x-text="currentHotel.email || 'មិនមាន'"></p>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">លេខទូរស័ព្ទ</p>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-phone-alt text-emerald-500 text-sm"></i>
                            <p class="font-bold dark:text-gray-200 text-sm" x-text="currentHotel.phone"></p>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-1">អាសយដ្ឋាន</p>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-red-500 text-sm mt-1"></i>
                            <p class="font-medium dark:text-gray-200 text-sm leading-relaxed" x-text="currentHotel.address"></p>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 p-4 bg-blue-50/50 dark:bg-blue-500/5 rounded-2xl border border-blue-100 dark:border-blue-500/20">
                        <div class="flex justify-between items-center">
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
                                    class="px-4 py-2 bg-white dark:bg-gray-800 shadow-sm border dark:border-gray-700 rounded-xl text-xs font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-directions mr-1"></i> បើកផែនទី
                                </a>
                            </template>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 p-4">
                        <p class="text-[10px] uppercase font-black text-gray-400 tracking-widest mb-2">ការពិពណ៌នា</p>
                        <p class="text-sm dark:text-gray-400 leading-relaxed italic" x-text="currentHotel.description || 'មិនមានការពិពណ៌នា...'"></p>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button @click="showDetailModal = false"
                        class="flex-1 py-4 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-2xl font-bold hover:bg-gray-200 transition-all">
                        បិទ
                    </button>
                    <button @click="showDetailModal = false; showEditModal = true"
                        class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all">
                        <i class="fas fa-edit mr-2"></i> កែសម្រួល
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>