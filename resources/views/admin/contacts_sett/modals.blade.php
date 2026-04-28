<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showAddModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative dark:border-gray-800 overflow-hidden font-kantumruy">
            <div class="px-4 py-3 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមព័ត៌មានថ្មី</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('contacts_sett.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Key (សម្គាល់)</label>
                            <input type="text" name="key" placeholder="phone" required
                                class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Label (បង្ហាញ)</label>
                            <input type="text" name="label" placeholder="លេខទូរស័ព្ទ" required
                                class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">តម្លៃ (Value)</label>
                        <textarea name="value" placeholder="បញ្ចូលលេខ ឬ Link ផែនទី..." required rows="3"
                            class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">រូបតំណាង (Icon)</label>
                            <input type="text" name="icon" placeholder="fas fa-phone"
                                class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">ពណ៌ (Theme)</label>
                            <select name="color" class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer">
                                <option value="blue">Blue</option>
                                <option value="emerald">Emerald</option>
                                <option value="red">Red</option>
                                <option value="amber">Amber</option>
                                <option value="purple">Purple</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium dark:text-gray-300">បើកបង្ហាញ</span>
                        </label>
                    </div>
                </div>

                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-4 py-2 text-gray-500 font-medium">បោះបង់</button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/20 font-bold">រក្សាទុក</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showEditModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative dark:border-gray-800 overflow-hidden font-kantumruy">
            <div class="px-4 py-3 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែប្រែ៖ <span x-text="currentSetting.label" class="text-blue-500"></span></h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/contacts_sett') }}/${currentSetting.id}`" method="POST">
                @csrf
                @method('PUT')

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Key (សម្គាល់)</label>
                                <input type="text" name="key" x-model="currentSetting.key"
                                    class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Label (ចំណងជើង)</label>
                                <input type="text" name="label" x-model="currentSetting.label" required
                                    class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">តម្លៃ (Value)</label>
                            <textarea name="value" x-model="currentSetting.value" required rows="3"
                                class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all resize-none"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">Icon (FontAwesome)</label>
                                <div class="relative">
                                    <input type="text" name="icon" x-model="currentSetting.icon"
                                        class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                        <i :class="currentSetting.icon || 'fas fa-question-circle'"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1 tracking-wider">ពណ៌ (Theme)</label>
                                <select name="color" x-model="currentSetting.color"
                                    class="w-full px-4 py-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all cursor-pointer appearance-none">
                                    <option value="blue">Blue</option>
                                    <option value="emerald">Emerald</option>
                                    <option value="red">Red</option>
                                    <option value="amber">Amber</option>
                                    <option value="purple">Purple</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="status" value="1"
                                    x-model="currentSetting.status"
                                    :checked="currentSetting.status"
                                    class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                <span class="ms-3 text-sm font-medium dark:text-gray-300">បើកដំណើរការ</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 dark:border-gray-800">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-gray-500 font-medium hover:text-gray-700 dark:hover:text-gray-300 transition-colors">បោះបង់</button>
                        <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20 font-bold">ធ្វើបច្ចុប្បន្នភាព</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showDetailModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-sm relative border dark:border-gray-800 overflow-hidden font-kantumruy">
            <div class="p-8 text-center">
                <div class="w-24 h-24 flex items-center justify-center rounded-3xl text-4xl mx-auto mb-6 shadow-inner border transition-all duration-500"
                    :class="{
                        'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:border-blue-800/50': currentSetting.color === 'blue',
                        'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:border-emerald-800/50': currentSetting.color === 'emerald',
                        'bg-red-100 text-red-600 dark:bg-red-900/30 dark:border-red-800/50': currentSetting.color === 'red',
                        'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:border-amber-800/50': currentSetting.color === 'amber'
                     }">
                    <i :class="currentSetting.icon || 'fas fa-info-circle'"></i>
                </div>

                <h4 class="text-2xl font-black dark:text-white mb-1" x-text="currentSetting.label"></h4>
                <p class="text-sm text-gray-400 uppercase tracking-widest font-bold mb-6" x-text="'Key: ' + currentSetting.key"></p>

                <div class="space-y-3 mb-8 text-left">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800">
                        <span class="text-[10px] text-gray-400 font-bold uppercase block mb-1">តម្លៃ / ព័ត៌មាន</span>
                        <p class="text-sm dark:text-gray-200 font-medium break-words" x-text="currentSetting.value"></p>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border dark:border-gray-800 flex justify-between items-center">
                        <span class="text-xs text-gray-400 font-bold uppercase">ស្ថានភាពបង្ហាញ</span>
                        <span :class="currentSetting.status ? 'text-emerald-500' : 'text-red-500'" class="font-bold flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" :class="currentSetting.status ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'"></span>
                            <span x-text="currentSetting.status ? 'បង្ហាញ' : 'មិនបង្ហាញ'"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800/50 flex gap-3">
                <button @click="showDetailModal = false"
                    class="flex-1 h-12 bg-white dark:bg-gray-800 border dark:border-gray-700 font-black text-[11px] uppercase tracking-widest rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all dark:text-white">
                    បិទវិញ
                </button>
                <button @click="showDetailModal = false; showEditModal = true"
                    class="flex-1 h-12 bg-blue-600 text-white font-black text-[11px] uppercase tracking-widest rounded-xl shadow-lg shadow-blue-500/20 hover:bg-blue-700 active:scale-95 transition-all">
                    កែសម្រួល
                </button>
            </div>
        </div>
    </div>
</div>