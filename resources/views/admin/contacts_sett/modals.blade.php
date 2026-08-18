<!-- Add Modal -->
<div x-show="showAddModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all z-10">
            <div class="px-6 py-4 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <div>
                    <h3 class="font-black text-lg dark:text-white uppercase tracking-tight flex items-center gap-2"> 
                        បន្ថែមព័ត៌មាន / ការកំណត់ថ្មី
                    </h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">New System Setting Item</p>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form action="{{ route('contacts_sett.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <!-- Quick Presets -->
                    <div class="p-3 bg-blue-50/70 dark:bg-blue-950/40 rounded-xl border border-blue-200 dark:border-blue-800 space-y-2">
                        <span class="text-xs font-bold text-blue-800 dark:text-blue-300 flex items-center gap-1.5">
                            <i class="fa-solid fa-wand-magic-sparkles text-amber-500"></i>
                            កំណត់រហ័ស៖
                        </span>
                        <div class="flex flex-wrap gap-1.5">
                            <button type="button" @click="currentSetting.key = 'khr_rate'; currentSetting.label = 'អត្រាប្តូរប្រាក់ (USD ➔ KHR)'; currentSetting.value = '4100'; currentSetting.icon = 'fa-solid fa-coins'; currentSetting.color = 'emerald';"
                                class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-[10px] uppercase shadow-xs transition-all">
                                + KHR Rate
                            </button>
                            <button type="button" @click="currentSetting.key = 'logo'; currentSetting.label = 'រូបសញ្ញាប្រព័ន្ធ (Logo)'; currentSetting.value = 'images/logo/P&t Palace Hotel.png'; currentSetting.icon = 'fa-solid fa-image'; currentSetting.color = 'amber';"
                                class="px-2.5 py-1 bg-amber-500 hover:bg-amber-600 text-white rounded-lg font-bold text-[10px] uppercase shadow-xs transition-all">
                                + Logo
                            </button>
                            <button type="button" @click="currentSetting.key = 'site_name'; currentSetting.label = 'ឈ្មោះសណ្ឋាគារ/ប្រព័ន្ធ'; currentSetting.value = 'ភីអេនធី ផាលេស'; currentSetting.icon = 'fa-solid fa-hotel'; currentSetting.color = 'blue';"
                                class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold text-[10px] uppercase shadow-xs transition-all">
                                + Site Name
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Key (សម្គាល់) <span class="text-red-500">*</span></label>
                            <input type="text" name="key" x-model="currentSetting.key" placeholder="ឧ. khr_rate, phone" required
                                class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Label (បង្ហាញ) <span class="text-red-500">*</span></label>
                            <input type="text" name="label" x-model="currentSetting.label" placeholder="ឧ. អត្រាប្តូរប្រាក់" required
                                class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">តម្លៃ (Value) <span class="text-red-500">*</span></label>
                        <textarea name="value" x-model="currentSetting.value" placeholder="ឧ. 4100 (សម្រាប់ KHR exchange rate) ឬលេខទូរស័ព្ទ/Link..." rows="2"
                            class="w-full p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-medium resize-none"></textarea>
                    </div>

                    <div class="p-3.5 bg-blue-50/50 dark:bg-blue-950/30 rounded-xl border border-blue-100 dark:border-blue-900/40 space-y-2">
                        <label class="block text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                            ផ្ទុកឡើងរូបភាព / QR Code (បើមាន)
                        </label>
                        <input type="file" name="image_file" accept="image/*"
                            class="w-full text-xs text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl p-1.5 bg-white dark:bg-gray-900">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Icon (FontAwesome)</label>
                            <input type="text" name="icon" x-model="currentSetting.icon" placeholder="fas fa-coins"
                                class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">ពណ៌ (Theme Color)</label>
                            <select name="color" x-model="currentSetting.color" class="w-full h-11 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-bold">
                                <option value="emerald">បៃតង</option>
                                <option value="blue">ខៀវ</option>
                                <option value="red">ក្រហម</option>
                                <option value="amber">លឿង</option>
                                <option value="purple">ស្វាយ</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1" checked class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-xs font-bold text-gray-700 dark:text-gray-300">បើកបង្ហាញ</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 border-t dark:border-gray-800 rounded-b-2xl">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2 text-xs font-bold text-gray-500 hover:text-red-600 transition-colors">បោះបង់</button>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all shadow-md font-bold text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-floppy-disk"></i> រក្សាទុក
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div x-show="showEditModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-lg relative border-none overflow-hidden transition-all z-10">
            <div class="px-6 py-4 border-b dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                <div>
                    <h3 class="font-black text-lg dark:text-white uppercase tracking-tight flex items-center gap-2">
                        កែប្រែ៖ <span x-text="currentSetting.label" class="text-blue-600 dark:text-blue-400"></span>
                    </h3>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Update Setting Details</p>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 text-2xl transition-transform hover:rotate-90">&times;</button>
            </div>

            <form :action="`{{ url('admin/contacts_sett') }}/${currentSetting.id}`" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    <template x-if="['khr_rate', 'exchange_rate', 'usd_khr_rate', 'riel_rate'].includes((currentSetting.key || '').toLowerCase())">
                        <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/50 rounded-xl border border-emerald-200 dark:border-emerald-800">
                            <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300 flex items-center gap-1.5">
                                <i class="fa-solid fa-coins text-amber-500"></i>
                                អត្រាប្តូរប្រាក់រៀល
                            </p>
                            <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1">
                                បញ្ចូលចំនួនលុយខ្មែរសម្រាប់ 1 $ ដុល្លារ (<span class="font-bold">4100</span> ឬ <span class="font-bold">4000</span>)
                            </p>
                        </div>
                    </template>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Key (សម្គាល់)</label>
                            <input type="text" name="key" x-model="currentSetting.key"
                                class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Label (ចំណងជើង) <span class="text-red-500">*</span></label>
                            <input type="text" name="label" x-model="currentSetting.label" required
                                class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">តម្លៃ (Value / Rate) <span class="text-red-500">*</span></label>
                        <textarea name="value" x-model="currentSetting.value" rows="2" required
                            class="w-full p-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-bold resize-none"></textarea>
                    </div>

                    <div class="p-3.5 bg-blue-50/50 dark:bg-blue-950/30 rounded-xl border border-blue-100 dark:border-blue-900/40 space-y-2">
                        <label class="block text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">
                            ផ្ទុកឡើងរូបភាពឃ្យូអរកូដ/ រូបភាពថ្មី (បើមាន)
                        </label>
                        <input type="file" name="image_file" accept="image/*"
                            class="w-full text-xs text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700 rounded-xl p-1.5 bg-white dark:bg-gray-900">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Icon (FontAwesome)</label>
                            <input type="text" name="icon" x-model="currentSetting.icon"
                                class="w-full h-11 px-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-mono">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">ពណ៌ (Theme Color)</label>
                            <select name="color" x-model="currentSetting.color"
                                class="w-full h-11 px-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all text-xs font-bold">
                                <option value="emerald">បៃតង</option>
                                <option value="blue">ខៀវ</option>
                                <option value="red">ក្រហម</option>
                                <option value="amber">លឿង</option>
                                <option value="purple">ស្វាយ</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="status" value="1"
                                x-model="currentSetting.status"
                                :checked="currentSetting.status"
                                class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ms-3 text-xs font-bold text-gray-700 dark:text-gray-300">បើកដំណើរការ</span>
                        </label>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-2 border-t dark:border-gray-800 rounded-b-2xl">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2 text-xs font-bold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">បោះបង់</button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-all shadow-md font-bold text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-check"></i> ធ្វើបច្ចុប្បន្នភាព
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Detail Modal -->
<div x-show="showDetailModal" class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-sm relative border-none overflow-hidden transition-all z-10">
            <div class="p-8 text-center">
                <div class="w-20 h-20 flex items-center justify-center rounded-2xl text-3xl mx-auto mb-4 shadow-inner border transition-all duration-300"
                    :class="{
                        'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:border-emerald-800/50': currentSetting.color === 'emerald',
                        'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:border-blue-800/50': currentSetting.color === 'blue',
                        'bg-red-100 text-red-600 dark:bg-red-900/30 dark:border-red-800/50': currentSetting.color === 'red',
                        'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:border-amber-800/50': currentSetting.color === 'amber',
                        'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:border-purple-800/50': currentSetting.color === 'purple'
                     }">
                    <i :class="currentSetting.icon || 'fas fa-info-circle'"></i>
                </div>

                <h4 class="text-xl font-black dark:text-white mb-1" x-text="currentSetting.label"></h4>
                <p class="text-xs text-gray-400 uppercase font-mono tracking-wider mb-6" x-text="'Key: ' + currentSetting.key"></p>

                <div class="space-y-3 mb-6 text-left">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border dark:border-gray-800">
                        <span class="text-[10px] text-gray-400 font-bold uppercase block mb-1">តម្លៃ (Value)</span>
                        <p class="text-sm dark:text-gray-200 font-bold break-words" x-text="currentSetting.value"></p>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border dark:border-gray-800 flex justify-between items-center">
                        <span class="text-xs text-gray-400 font-bold uppercase">ស្ថានភាពបង្ហាញ</span>
                        <span :class="currentSetting.status ? 'text-emerald-500' : 'text-red-500'" class="font-bold text-xs flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full" :class="currentSetting.status ? 'bg-emerald-500 animate-pulse' : 'bg-red-500'"></span>
                            <span x-text="currentSetting.status ? 'បង្ហាញ' : 'មិនបង្ហាញ'"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 flex gap-3 border-t dark:border-gray-800">
                <button @click="showDetailModal = false"
                    class="flex-1 py-2.5 bg-white dark:bg-gray-800 border dark:border-gray-700 font-bold text-xs rounded-xl hover:bg-gray-100 transition-all dark:text-white">
                    បិទ
                </button>
                <button @click="showDetailModal = false; showEditModal = true"
                    class="flex-1 py-2.5 bg-blue-600 text-white font-bold text-xs rounded-xl shadow-md hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-pen-to-square"></i> កែសម្រួល
                </button>
            </div>
        </div>
    </div>
</div>