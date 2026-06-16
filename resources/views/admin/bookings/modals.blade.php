<div x-show="showAddModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមកក់ថ្មី (Walk-in)</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីធ្វើការកក់ក្រៅប្រព័ន្ធ</p>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <form @submit.prevent="saveBooking()">
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ប្រភេទនៃការកក់ <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <select x-model="newBooking.booking_category" @change="resetFormCategory()"
                                class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold text-blue-600 dark:text-blue-400">
                                <option value="hotel">🏨 កក់បន្ទប់សណ្ឋាគារ (Hotel Room)</option>
                                <option value="meeting_room">🏢 កក់បន្ទប់ប្រជុំ (Meeting Room)</option>
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 bg-blue-50/40 dark:bg-blue-900/10 rounded-2xl border border-blue-100/50 dark:border-blue-900/20">
                        <div class="md:col-span-2">
                            <span class="text-xs font-black text-blue-600 uppercase tracking-wider">👤 ព័ត៌មានអតិថិជន Walk-In</span>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ឈ្មោះអតិថិជន <span class="text-red-500">*</span></label>
                            <input type="text" x-model="newBooking.customer_name" placeholder="ឧ. កក្កដា ទេព"
                                class="w-full h-14 px-5 rounded-2xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                            <template x-if="errors.customer_name"><span class="text-[10px] text-red-500 ml-2" x-text="errors.customer_name[0]"></span></template>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                            <input type="text" x-model="newBooking.customer_phone" placeholder="ឧ. 096 XXXXXXX"
                                class="w-full h-14 px-5 rounded-2xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                            <template x-if="errors.customer_phone"><span class="text-[10px] text-red-500 ml-2" x-text="errors.customer_phone[0]"></span></template>
                        </div>
                    </div>

                    <template x-if="newBooking.booking_category === 'hotel'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ជ្រើសរើសបន្ទប់សណ្ឋាគារ <span class="text-red-500">*</span></label>
                                <div class="relative group">
                                    <select x-model="newBooking.room_id" @change="calculateTotal()"
                                        class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                        <option value="">រើសបន្ទប់ដែលទំនេរ</option>
                                        <template x-for="room in rooms" :key="room.id">
                                            <option :value="room.id" x-text="`${room.room_number} (${room.room_type?.name}) - $${room.room_type?.base_price}/យប់`"></option>
                                        </template>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                                </div>
                                <template x-if="errors.room_id"><span class="text-[10px] text-red-500 ml-2" x-text="errors.room_id[0]"></span></template>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចូល (Check-in)</label>
                                <input type="date" x-model="newBooking.check_in" :min="min_date" @change="if(newBooking.check_out < newBooking.check_in) newBooking.check_out = ''; calculateTotal();"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                                <template x-if="errors.check_in"><span class="text-[10px] text-red-500 ml-2" x-text="errors.check_in[0]"></span></template>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចេញ (Check-out)</label>
                                <input type="date" x-model="newBooking.check_out" :min="newBooking.check_in ? newBooking.check_in : min_date" @change="calculateTotal()"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                                <template x-if="errors.check_out"><span class="text-[10px] text-red-500 ml-2" x-text="errors.check_out[0]"></span></template>
                            </div>
                        </div>
                    </template>

                    <template x-if="newBooking.booking_category === 'meeting_room'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ជ្រើសរើសបន្ទប់ប្រជុំ <span class="text-red-500">*</span></label>
                                <div class="relative group">
                                    <select x-model="newBooking.meeting_room_id"
                                        class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                        <option value="">រើសបន្ទប់ប្រជុំ</option>
                                        <template x-for="mroom in meetingRooms" :key="mroom.id">
                                            <option :value="mroom.id" x-text="`${mroom.room_name} - ${mroom.location}`"></option>
                                        </template>
                                    </select>
                                    <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                                </div>
                                <template x-if="errors.meeting_room_id"><span class="text-[10px] text-red-500 ml-2" x-text="errors.meeting_room_id[0]"></span></template>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចាប់ផ្តើម</label>
                                <input type="date" x-model="newBooking.start_date" :min="min_date"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃបញ្ចប់</label>
                                <input type="date" x-model="newBooking.end_date" :min="newBooking.start_date || min_date"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ម៉ោងចាប់ផ្តើម</label>
                                <input type="time" x-model="newBooking.start_time"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ម៉ោងបញ្ចប់</label>
                                <input type="time" x-model="newBooking.end_time"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សរុបម៉ោង</label>
                                <input type="number" step="0.1" x-model="newBooking.total_hours" placeholder="គិតជាម៉ោង"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ចំនួនអ្នកចូលរួម</label>
                                <input type="number" x-model="newBooking.attendees_count" placeholder="នាក់"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ទម្រង់រៀបចំបន្ទប់ (Setup Style)</label>
                                <input type="text" x-model="newBooking.setup_style" placeholder="ឧ. បែបថ្នាក់រៀន (Classroom Style), បែបអក្សរ U"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                            </div>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t dark:border-gray-800 pt-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">វិធីសាស្ត្របង់ប្រាក់</label>
                            <div class="relative group">
                                <select x-model="newBooking.payment_method"
                                    class="w-full h-14 pl-7 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-medium">
                                    <option value="cash">💵 សាច់ប្រាក់ (Cash)</option>
                                    <option value="bank_transfer">🏦 ផ្ទេរតាមធនាគារ</option>
                                    <option value="khqr">📱 ឃ្យូអរកូដ (KHQR)</option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃសរុប (Grand Total) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" x-model="newBooking.total_price" :readonly="newBooking.booking_category === 'hotel'"
                                class="w-full h-14 px-6 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 bg-emerald-50/50 dark:bg-emerald-900/10 text-emerald-600 dark:text-emerald-400 font-black text-2xl focus:ring-2 focus:ring-emerald-500 outline-none transition-all">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សំណូមពរផ្សេងៗ</label>
                        <textarea x-model="newBooking.special_requests" rows="3"
                            class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                            placeholder="បញ្ជាក់បន្ថែមផ្សេងៗ..."></textarea>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-6 h-12 rounded-2xl font-bold text-sm text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">បោះបង់</button>
                    <button type="submit" :disabled="loading" class="px-10 h-12 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-emerald-500/30 transition-all">
                        <span x-text="!loading ? 'បញ្ជាក់ការកក់' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showEditModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl text-blue-600 uppercase tracking-tight">កែសម្រួលការកក់បន្ទប់</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">កែប្រែព័ត៌មានការកក់ក្រៅប្រព័ន្ធ</p>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <form @submit.prevent="updateBooking()">
                <div class="p-8 space-y-6 max-h-[65vh] overflow-y-auto custom-scrollbar">

                    <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-xl">
                        <span class="text-xs font-bold text-gray-500">ប្រភេទការកក់៖ </span>
                        <span class="text-sm font-black dark:text-white uppercase" x-text="editingBooking.booking_category === 'meeting_room' ? '🏢 បន្ទប់ប្រជុំ' : '🏨 បន្ទប់សណ្ឋាគារ'"></span>
                    </div>

                    <template x-if="!editingBooking.booking_category || editingBooking.booking_category === 'hotel'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ជ្រើសរើសបន្ទប់</label>
                                <select x-model="editingBooking.room_id" @change="calculateTotalEdit()"
                                    class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                    <template x-for="room in rooms" :key="room.id">
                                        <option :value="room.id" x-text="`${room.room_number} (${room.room_type?.name})`" :selected="room.id == editingBooking.room_id"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចូល (Check-in)</label>
                                <input type="date" x-model="editingBooking.check_in" @change="calculateTotalEdit()" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចេញ (Check-out)</label>
                                <input type="date" x-model="editingBooking.check_out" @change="calculateTotalEdit()" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                            </div>
                        </div>
                    </template>

                    <template x-if="editingBooking.booking_category === 'meeting_room'">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 md:col-span-2">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ជ្រើសរើសបន្ទប់ប្រជុំ</label>
                                <select x-model="editingBooking.meeting_room_id" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                    <template x-for="mroom in meetingRooms" :key="mroom.id">
                                        <option :value="mroom.id" x-text="mroom.room_name" :selected="mroom.id == editingBooking.meeting_room_id"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="space-y-2"><label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចាប់ផ្តើម</label><input type="date" x-model="editingBooking.start_date" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none font-bold"></div>
                            <div class="space-y-2"><label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃបញ្ចប់</label><input type="date" x-model="editingBooking.end_date" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none font-bold"></div>
                            <div class="space-y-2"><label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ម៉ោងចាប់ផ្តើម</label><input type="time" x-model="editingBooking.start_time" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none font-bold"></div>
                            <div class="space-y-2"><label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ម៉ោងបញ្ចប់</label><input type="time" x-model="editingBooking.end_time" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none font-bold"></div>
                            <div class="space-y-2"><label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សរុបម៉ោង</label><input type="number" step="0.1" x-model="editingBooking.total_hours" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none font-medium"></div>
                            <div class="space-y-2"><label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ចំនួនអ្នកចូលរួម</label><input type="number" x-model="editingBooking.attendees_count" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none font-medium"></div>
                            <div class="space-y-2 md:col-span-2"><label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ទម្រង់រៀបចំបន្ទប់</label><input type="text" x-model="editingBooking.setup_style" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 outline-none font-medium"></div>
                        </div>
                    </template>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t dark:border-gray-800 pt-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ការបង់ប្រាក់</label>
                            <select x-model="editingBooking.payment_method" class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                <option value="cash">សាច់ប្រាក់</option>
                                <option value="bank_transfer">ផ្ទេរតាមធនាគារ</option>
                                <option value="khqr">ឃ្យូអរកូដ</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃសរុបថ្មី</label>
                            <input type="number" step="0.01" x-model="editingBooking.total_price" :readonly="editingBooking.booking_category === 'hotel'"
                                class="w-full h-14 px-6 rounded-2xl border border-blue-100 dark:border-blue-900 bg-blue-50/50 dark:bg-blue-900/10 text-blue-600 dark:text-blue-400 font-black text-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">សំណូមពរផ្សេងៗ</label>
                        <textarea x-model="editingBooking.special_requests" rows="3" class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all"></textarea>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-6 h-12 rounded-2xl font-bold text-sm text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">បោះបង់</button>
                    <button type="submit" :disabled="loading" class="px-10 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-500/30 transition-all">
                        <span x-text="!loading ? 'រក្សាទុកការកែប្រែ' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div x-show="showDetailModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="px-7 py-5 flex justify-between items-center border-b dark:border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg"><i class="fa-solid fa-circle-info text-blue-600"></i></div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិតនៃការកក់</h3>
                </div>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                    <span class="text-xs font-black uppercase text-gray-400">ប្រភេទកក់៖</span>
                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-md text-xs font-bold"
                        x-text="selectedBooking.meeting_room_id ? '🏢 បន្ទប់ប្រជុំ (Meeting Room)' : '🏨 បន្ទប់សណ្ឋាគារ (Hotel)'"></span>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <template x-if="!selectedBooking.meeting_room_id">
                        <div class="grid grid-cols-2 col-span-2 gap-6">
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">លេខបន្ទប់</p>
                                <p class="text-base font-bold dark:text-white" x-text="selectedBooking.room?.room_number || 'N/A'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ប្រភេទបន្ទប់</p>
                                <p class="text-base font-bold dark:text-white" x-text="selectedBooking.room?.room_type?.name || 'N/A'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Check-in</p>
                                <p class="text-sm font-bold text-blue-600" x-text="selectedBooking.check_in"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Check-out</p>
                                <p class="text-sm font-bold text-red-500" x-text="selectedBooking.check_out"></p>
                            </div>
                        </div>
                    </template>

                    <template x-if="selectedBooking.meeting_room_id">
                        <div class="grid grid-cols-2 col-span-2 gap-6">
                            <div class="col-span-2">
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">បន្ទប់ប្រជុំ</p>
                                <p class="text-base font-bold dark:text-white" x-text="selectedBooking.meeting_room?.room_name || 'N/A'"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ថ្ងៃចាប់ផ្តើម</p>
                                <p class="text-sm font-bold text-blue-600" x-text="selectedBooking.start_date"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ថ្ងៃបញ្ចប់</p>
                                <p class="text-sm font-bold text-red-500" x-text="selectedBooking.end_date"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ម៉ោង</p>
                                <p class="text-sm font-bold dark:text-white" x-text="`${selectedBooking.start_time} - ${selectedBooking.end_time} (${selectedBooking.total_hours} ម៉ោង)`"></p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ចំនួនអ្នកចូលរួម</p>
                                <p class="text-sm font-bold dark:text-white" x-text="(selectedBooking.attendees_count || '0') + ' នាក់'"></p>
                            </div>
                        </div>
                    </template>
                </div>

                <hr class="dark:border-gray-800">

                <div class="bg-emerald-50/40 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-950 rounded-2xl p-6 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">តម្លៃសរុបដែលត្រូវបង់</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-emerald-600" x-text="'$' + Number(selectedBooking.total_price).toLocaleString()"></span>
                            <span class="text-xs font-bold text-gray-400" x-text="'(~ ' + (selectedBooking.total_price * 4000).toLocaleString() + ' ៛)'"></span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest mb-1">វិធីសាស្ត្របង់ប្រាក់</p>
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full text-xs font-bold uppercase" x-text="selectedBooking.payment_method"></span>
                    </div>
                </div>

                <div class="space-y-2">
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">ព័ត៌មានលម្អិតបន្ថែម / សំណូមពរ</p>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl italic text-sm text-gray-600 dark:text-gray-300 border-l-4 border-blue-500 whitespace-pre-line"
                        x-text="selectedBooking.special_requests || 'គ្មានទិន្នន័យ'"></div>
                </div>
            </div>

            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end gap-3">
                <button @click="showDetailModal = false" class="px-8 h-12 rounded-xl font-bold text-sm text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">បិទ</button>
                <button @click="showDetailModal = false; showEditModal = true; editingBooking = {...selectedBooking}"
                    class="px-8 h-12 bg-[#002B5B] text-white rounded-xl font-bold text-sm shadow-lg transition-all active:scale-95 flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i><span>កែសម្រួល</span>
                </button>
            </div>
        </div>
    </div>
</div>