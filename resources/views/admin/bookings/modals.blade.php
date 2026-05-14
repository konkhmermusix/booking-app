<div x-show="showAddModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមកក់បន្ទប់ថ្មី</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីធ្វើការកក់</p>
                    </div>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <form @submit.prevent="saveBooking()">
                <div class="p-8 space-y-6 max-h-[60vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ជ្រើសរើសបន្ទប់ <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <select x-model="newBooking.room_id"
                                    class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                    <option value="">រើសបន្ទប់ដែលទំនេរ</option>
                                    <template x-for="room in rooms" :key="room.id">
                                        <option :value="room.id" x-text="`${room.room_number} (${room.room_type.name}) - $${room.room_type.base_price}`"></option>
                                    </template>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                            <template x-if="errors.room_id">
                                <span class="text-[10px] text-red-500 ml-2" x-text="errors.room_id[0]"></span>
                            </template>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចូល (Check-in)</label>
                            <div class="relative group">
                                <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="date"
                                    x-model="newBooking.check_in"
                                    :min="min_date"
                                    @change="if(newBooking.check_out < newBooking.check_in) newBooking.check_out = ''; calculateTotal();"
                                    class="w-full h-14 pl-10 pr-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                            </div>
                            <template x-if="errors.check_in">
                                <span class="text-[10px] text-red-500 ml-2" x-text="errors.check_in[0]"></span>
                            </template>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចេញ (Check-out)</label>
                            <div class="relative group">
                                <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs group-focus-within:text-blue-500 transition-colors"></i>
                                <input type="date"
                                    x-model="newBooking.check_out"
                                    :min="newBooking.check_in ? newBooking.check_in : min_date"
                                    @change="calculateTotal()"
                                    class="w-full h-14 pl-10 pr-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                            </div>

                            <template x-if="errors.check_out">
                                <span class="text-[10px] text-red-500 ml-2" x-text="errors.check_out[0]"></span>
                            </template>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ការបង់ប្រាក់</label>
                            <div class="relative group">
                                <select x-model="newBooking.payment_method"
                                    class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium relative z-0">
                                    <option value="cash">សាច់ប្រាក់</option>
                                    <option value="bank_transfer">ផ្ទេរតាមធនាគារ</option>
                                    <option value="khqr">ឃ្យូអរកូដ</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃសរុប (Grand Total)</label>
                            <div class="w-full flex items-center h-14 px-6 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 bg-emerald-50/50 dark:bg-emerald-900/10 text-emerald-600 dark:text-emerald-400 transition-all shadow-sm">

                                <div class="flex items-center border-r dark:border-gray-700 pr-4 mr-4">
                                    <div class="flex items-center justify-center bg-emerald-600 text-white w-7 h-7 rounded-lg shadow-md shadow-emerald-500/20 mr-2">
                                        <span class="text-xs font-bold">$</span>
                                    </div>
                                    <div class="flex items-baseline">
                                        <span class="text-2xl font-black tracking-tight" x-text="newBooking.total_price.toLocaleString()"></span>
                                        <span class="ml-1 text-[10px] font-bold opacity-60">ដុល្លារ</span>
                                    </div>
                                </div>

                                <div class="flex items-center">
                                    <div class="flex items-center justify-center bg-emerald-600 text-white w-7 h-7 rounded-lg shadow-md shadow-emerald-500/20 mr-2">
                                        <span class="text-xs font-bold">៛</span>
                                    </div>
                                    <div class="flex items-baseline">
                                        <span class="text-2xl font-black tracking-tight" x-text="(newBooking.total_price * 4000).toLocaleString()"></span>
                                        <span class="ml-1 text-[10px] font-bold opacity-60">រៀល</span>
                                    </div>
                                </div>

                                <template x-if="newBooking.total_price > 0">
                                    <div class="ml-auto">
                                        <span class="text-[10px] bg-emerald-100 dark:bg-emerald-800 px-2 py-1 rounded-md font-bold italic">
                                            (1$ = 4,000៛)
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សំណូមពរផ្សេងៗ</label>
                            <textarea x-model="newBooking.special_requests" rows="3"
                                class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"
                                placeholder="សុំបន្ទប់ស្អាត ឬសុំបន្ថែមភួយ..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false"
                        class="px-6 h-12 rounded-2xl font-bold text-sm text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        បោះបង់
                    </button>
                    <button type="submit"
                        :disabled="loading" class="px-10 h-12 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-500/30 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none flex items-center gap-2">
                        <template x-if="!loading">
                            <div class="flex items-center gap-2">
                                <span>បញ្ជាក់ការកក់</span>
                            </div>
                        </template>
                        <template x-if="loading">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>កំពុងរក្សាទុក...</span>
                            </div>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ប្តូរ x-show ទៅជា showEditModal -->
<div x-show="showEditModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <!-- ចុចលើផ្ទៃខ្មៅដើម្បីបិទ -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <!-- Header: ប្តូរចំណងជើង -->
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div class="flex items-center gap-4">
                    <div>
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight text-blue-600">កែសម្រួលការកក់បន្ទប់</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">កែប្រែព័ត៌មានដែលបានកក់រួច</p>
                    </div>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <!-- Form: ប្តូរទៅកាន់ updateBooking() -->
            <form @submit.prevent="updateBooking()">
                <div class="p-8 space-y-6 max-h-[60vh] overflow-y-auto custom-scrollbar">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- ជ្រើសរើសបន្ទប់ -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ជ្រើសរើសបន្ទប់ <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <!-- x-model ភ្ជាប់ទៅ editingBooking -->
                                <select x-model="editingBooking.room_id"
                                    class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                    <template x-for="room in rooms" :key="room.id">
                                        <option :value="room.id" x-text="`${room.room_number} (${room.room_type.name})`" :selected="room.id == editingBooking.room_id"></option>
                                    </template>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <!-- Check-in -->
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចូល (Check-in)</label>
                            <div class="relative group">
                                <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="date" x-model="editingBooking.check_in" @change="calculateTotalEdit()"
                                    class="w-full h-14 pl-10 pr-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                            </div>
                        </div>

                        <!-- Check-out -->
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចេញ (Check-out)</label>
                            <div class="relative group">
                                <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="date" x-model="editingBooking.check_out" @change="calculateTotalEdit()"
                                    class="w-full h-14 pl-10 pr-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                            </div>
                        </div>

                        <!-- ការបង់ប្រាក់ -->
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ការបង់ប្រាក់</label>
                            <div class="relative group">
                                <select x-model="editingBooking.payment_method"
                                    class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                    <option value="cash">សាច់ប្រាក់</option>
                                    <option value="bank_transfer">ផ្ទេរតាមធនាគារ</option>
                                    <option value="khqr">ឃ្យូអរកូដ</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- បង្ហាញតម្លៃសរុប (Grand Total) -->
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃសរុបថ្មី</label>
                            <div class="w-full flex items-center h-14 px-6 rounded-2xl border border-blue-100 dark:border-blue-900 bg-blue-50/50 dark:bg-blue-900/10 text-blue-600 dark:text-blue-400 transition-all shadow-sm font-black text-2xl">
                                <span>$</span>
                                <span x-text="editingBooking.total_price.toLocaleString()"></span>
                            </div>
                        </div>

                        <!-- សំណូមពរ -->
                        <div class="space-y-2 md:col-span-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សំណូមពរផ្សេងៗ</label>
                            <textarea x-model="editingBooking.special_requests" rows="3"
                                class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-6 h-12 rounded-2xl font-bold text-sm text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        បោះបង់
                    </button>
                    <button type="submit" :disabled="loading"
                        class="px-10 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all active:scale-95 disabled:opacity-50">
                        <template x-if="!loading">
                            <span>រក្សាទុកការកែប្រែ</span>
                        </template>
                        <template x-if="loading">
                            <div class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>កំពុងរក្សាទុក...</span>
                            </div>
                        </template>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- ប្តូរ x-show ទៅជា showViewModal -->
<div x-show="showDetailModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = true"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100">

            <!-- Header -->
            <div class="px-7 py-5 flex justify-between items-center border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <i class="fa-solid fa-circle-info text-blue-600"></i>
                    </div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិតនៃការកក់</h3>
                </div>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <!-- Body -->
            <div class="p-8 space-y-8">
                <!-- ផ្នែកព័ត៌មានបន្ទប់ និងតម្លៃ -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">លេខបន្ទប់</p>
                        <p class="text-lg font-bold dark:text-white" x-text="selectedBooking.room?.room_number || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ប្រភេទបន្ទប់</p>
                        <p class="text-lg font-bold dark:text-white" x-text="selectedBooking.room?.room_type?.name || 'N/A'"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ថ្ងៃចូល (Check-in)</p>
                        <p class="text-sm font-bold text-blue-600" x-text="selectedBooking.check_in"></p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ថ្ងៃចេញ (Check-out)</p>
                        <p class="text-sm font-bold text-red-500" x-text="selectedBooking.check_out"></p>
                    </div>
                </div>

                <hr class="dark:border-gray-800">

                <!-- ផ្នែកតម្លៃសរុប -->
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">តម្លៃសរុបដែលត្រូវបង់</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-emerald-600" x-text="'$' + selectedBooking.total_price"></span>
                            <span class="text-sm font-bold text-gray-400" x-text="'(~ ' + (selectedBooking.total_price * 4000).toLocaleString() + ' ៛)'"></span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">វិធីសាស្ត្របង់ប្រាក់</p>
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold uppercase"
                            x-text="selectedBooking.payment_method"></span>
                    </div>
                </div>

                <!-- ផ្នែកសំណូមពរ -->
                <div class="space-y-2">
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">សំណូមពរផ្សេងៗ</p>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl italic text-sm text-gray-600 dark:text-gray-300 border-l-4 border-gray-200 dark:border-gray-700"
                        x-text="selectedBooking.special_requests || 'គ្មានសំណូមពរ'"></div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3">
                <button @click="showDetailModal = false"
                    class="px-8 h-12 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                    បិទ
                </button>
                <button @click="showDetailModal = false; showEditModal = true; editingBooking = {...selectedBooking}"
                    class="px-8 h-12 bg-[#002B5B] text-white rounded-xl font-bold text-sm shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span>កែសម្រួល</span>
                </button>
            </div>
        </div>
    </div>
</div>