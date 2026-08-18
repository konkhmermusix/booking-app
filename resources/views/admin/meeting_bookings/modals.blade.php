{{-- 1. ADD MEETING BOOKING MODAL (WALK-IN) --}}
<div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កក់សាលប្រជុំថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Customer, Meeting Room & Event Details</p>
                </div>
                <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="saveBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    {{-- 👤 CUSTOMER INFO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឈ្មោះអតិថិជន / អង្គភាព <span class="text-red-500">*</span></label>
                            <input type="text" x-model="newBooking.customer_name" required placeholder="ឈ្មោះពេញ"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold placeholder:font-normal text-sm">
                            <template x-if="errors.customer_name"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.customer_name[0]"></span></template>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                            <input type="text" x-model="newBooking.customer_phone" required placeholder="012 XXXXXX"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold placeholder:font-normal text-sm">
                            <template x-if="errors.customer_phone"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.customer_phone[0]"></span></template>
                        </div>
                    </div>

                    {{-- 📅 DATES & TIMES --}}
                    <div class="p-5 rounded-2xl bg-purple-50/40 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/30 space-y-3">
                        <div class="flex items-center gap-2 border-b border-purple-100 dark:border-purple-900/40 pb-2">
                            <i class="far fa-calendar-alt text-purple-600 dark:text-purple-400"></i>
                            <h4 class="text-xs font-black uppercase tracking-wider text-purple-900 dark:text-purple-200">១. បញ្ចូលថ្ងៃនិងម៉ោងប្រជុំដើម្បីស្វែងរកសាលប្រជុំទំនេរ <span class="text-red-500">*</span></h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃចាប់ផ្តើម <span class="text-red-500">*</span></label>
                                <input type="date" x-model="newBooking.start_date" :min="min_date" @change="if(newBooking.end_date < newBooking.start_date) newBooking.end_date = newBooking.start_date; calculateTotal(); checkAvailableRooms();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃបញ្ចប់ <span class="text-red-500">*</span></label>
                                <input type="date" x-model="newBooking.end_date" :min="newBooking.start_date ? newBooking.start_date : min_date" @change="calculateTotal(); checkAvailableRooms();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-1 tracking-widest">ម៉ោងចាប់ផ្តើម <span class="text-red-500">*</span></label>
                                <input type="time" x-model="newBooking.start_time" @change="calculateTotal(); checkAvailableRooms();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-1 tracking-widest">ម៉ោងបញ្ចប់ <span class="text-red-500">*</span></label>
                                <input type="time" x-model="newBooking.end_time" @change="calculateTotal(); checkAvailableRooms();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>
                        </div>
                    </div>

                    {{-- 🏢 MEETING ROOM SELECT (SINGLE SELECT) --}}
                    <div class="space-y-2" x-data="{ openMeetingSearch: false, searchMeetingQuery: '' }">
                        <div class="flex justify-between items-center ml-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 tracking-widest">២. ជ្រើសរើសសាលប្រជុំទំនេរ <span class="text-red-500">*</span></label>
                            <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase">
                                តាមកាលបរិច្ឆេទ៖ <span x-text="`${formatDateDisplay(newBooking.start_date)} ដល់ ${formatDateDisplay(newBooking.end_date)}`"></span>
                            </span>
                        </div>
                        
                        <div class="relative">
                            <div @click="openMeetingSearch = !openMeetingSearch"
                                class="w-full h-14 px-6 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white border-none focus-within:ring-2 focus-within:ring-purple-500 flex items-center justify-between cursor-pointer transition-all">
                                <span class="font-bold text-sm truncate" 
                                    x-text="newBooking.meeting_room_id ? (meetingRooms.find(r => r.id == newBooking.meeting_room_id) ? `សាលប្រជុំ ${meetingRooms.find(r => r.id == newBooking.meeting_room_id).room_number} (${meetingRooms.find(r => r.id == newBooking.meeting_room_id).room_type?.name || 'Meeting'}) - $${meetingRooms.find(r => r.id == newBooking.meeting_room_id).room_type?.base_price || 0}/ម៉ោង` : 'ជ្រើសរើសសាលប្រជុំដែលទំនេរ...') : 'ជ្រើសរើសសាលប្រជុំដែលទំនេរ...'">
                                </span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="openMeetingSearch ? 'rotate-180 text-purple-500' : ''"></i>
                            </div>

                            <div x-show="openMeetingSearch" @click.outside="openMeetingSearch = false" x-cloak
                                x-transition:enter="ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 top-full mt-2 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 z-50 overflow-hidden">
                                
                                <div class="p-3 border-b border-gray-100 dark:border-gray-800 relative bg-gray-50/50 dark:bg-gray-800/50">
                                    <input type="text" x-model="searchMeetingQuery" placeholder="ស្វែងរកសាលប្រជុំ ឬប្រភេទ..."
                                        class="w-full h-10 px-4 rounded-xl bg-white dark:bg-gray-800 text-xs dark:text-white border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-purple-500">
                                </div>

                                <div class="max-h-60 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="room in meetingRooms.filter(r => (!searchMeetingQuery || `${r.room_number} ${r.room_type?.name}`.toLowerCase().includes(searchMeetingQuery.toLowerCase())))" :key="room.id">
                                        <div @click="if(!isRoomBusy(room.id) || newBooking.meeting_room_id == room.id) { newBooking.meeting_room_id = room.id; openMeetingSearch = false; searchMeetingQuery = ''; calculateTotal(); }"
                                            :class="{
                                                'bg-purple-600 text-white font-bold cursor-pointer': newBooking.meeting_room_id == room.id,
                                                'hover:bg-purple-50 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200 cursor-pointer': !isRoomBusy(room.id) && newBooking.meeting_room_id != room.id,
                                                'opacity-50 bg-gray-100 dark:bg-gray-800/40 text-gray-400 cursor-not-allowed': isRoomBusy(room.id) && newBooking.meeting_room_id != room.id
                                            }"
                                            class="px-4 py-3 rounded-xl text-xs flex items-center justify-between transition">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-users" :class="newBooking.meeting_room_id == room.id ? 'text-white' : (isRoomBusy(room.id) ? 'text-gray-400' : 'text-purple-500')"></i>
                                                <span class="font-bold" x-text="`សាលប្រជុំ ${room.room_number}`"></span>
                                                <span class="opacity-80" x-text="`(${room.room_type?.name || 'Meeting'})`"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <template x-if="newBooking.meeting_room_id == room.id">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-500 text-white">កំពុងជ្រើស</span>
                                                </template>
                                                <template x-if="newBooking.meeting_room_id != room.id && !isRoomBusy(room.id)">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">ទំនេរ</span>
                                                </template>
                                                <template x-if="newBooking.meeting_room_id != room.id && isRoomBusy(room.id)">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300">ពុំទំនេរ (ម៉ោងស្ទួន)</span>
                                                </template>
                                                <span class="font-extrabold" x-text="`$${room.room_type?.base_price || 0}/ម៉ោង`"></span>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="meetingRooms.filter(r => !isRoomBusy(r.id) && (!searchMeetingQuery || `${r.room_number} ${r.room_type?.name}`.toLowerCase().includes(searchMeetingQuery.toLowerCase()))).length === 0" 
                                        class="p-4 text-center text-xs text-amber-600 dark:text-amber-400 font-bold bg-amber-50 dark:bg-amber-950/20 rounded-xl">
                                        <i class="fa-solid fa-circle-exclamation mr-1"></i> ពុំមានសាលប្រជុំទំនេរតាមកាលបរិច្ឆេទនិងម៉ោងនេះឡើយ (សូមជ្រើសរើសថ្ងៃ/ម៉ោងផ្សេង)
                                    </div>
                                </div>
                            </div>
                        </div>
                        <template x-if="errors.meeting_room_id"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.meeting_room_id[0]"></span></template>
                    </div>

                    {{-- EVENT SETUP & ATTENDEES --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំនួនអ្នកចូលរួម (នាក់)</label>
                            <input type="number" x-model="newBooking.attendees_count" placeholder="ឧ. 20"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ទម្រង់រៀបចំសាល</label>
                            <div class="relative">
                                <select x-model="newBooking.setup_style"
                                    class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold text-sm appearance-none cursor-pointer">
                                    <option value="">ជ្រើសរើសទម្រង់រៀបចំ</option>
                                    <option value="Classroom">ថ្នាក់រៀន</option>
                                    <option value="Theater">មហោស្រព / សាលប្រជុំ </option>
                                    <option value="U-Shape">អក្សរ យូ </option>
                                    <option value="Boardroom">ប្រជុំក្រុមប្រឹក្សា</option>
                                    <option value="Banquet">តុមូលពិធីលៀងសាយភោជន៍</option>
                                    <option value="Cocktail">ជប់លៀងឈរ</option>
                                    <option value="Hollow Square">ការ៉េចតុកោណ </option>
                                    <option value="Cabaret">តុមូលកន្លះវង់</option>
                                    <option value="Custom">រៀបចំពិសេសតាមការស្នើសុំ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 💳 PAYMENT SECTION (FORM PAYMENT) --}}
                    <div class="p-5 rounded-2xl bg-purple-50/50 dark:bg-gray-800/60 border border-purple-100 dark:border-gray-700 space-y-4">
                        <div class="flex items-center gap-2 border-b border-purple-100 dark:border-gray-700 pb-2">
                            <i class="fas fa-wallet text-purple-600 dark:text-purple-400"></i>
                            <h4 class="text-xs font-black uppercase tracking-wider text-purple-900 dark:text-purple-200">ព័ត៌មានទូទាត់ប្រាក់</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- ស្ថានភាពទូទាត់ (Payment Status) --}}
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ស្ថានភាពទូទាត់ប្រាក់ <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select x-model="newBooking.payment_status"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all appearance-none font-bold text-xs">
                                        <option value="paid">បានបង់រួច</option>
                                        <option value="pending">មិនទាន់បង់ / រង់ចាំពិនិត្យ</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- វិធីសាស្ត្របង់ប្រាក់ (Payment Method) --}}
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">វិធីសាស្ត្របង់ប្រាក់ <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select x-model="newBooking.payment_method"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all appearance-none font-bold text-xs">
                                        <option value="cash">ប្រាក់សុទ្ធ</option>
                                        <option value="qr">ឃ្យូអរកូដ</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- លេខប្រតិបត្តិការ (Transaction ID for QR) --}}
                        <div x-show="newBooking.payment_method === 'qr' || newBooking.payment_method === 'bank_transfer' || newBooking.payment_method === 'khqr'" class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">លេខប្រតិបត្តិការ / Transaction ID (ប្រសិនបើមាន)</label>
                            <input type="text" x-model="newBooking.transaction_id" placeholder="TXN-987654321"
                                class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                        </div>

                {{-- តម្លៃសរុប (Grand Total) --}}
                        <div class="pt-2 flex justify-between items-center border-t border-purple-100 dark:border-gray-700">
                            <span class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">តម្លៃសរុបត្រូវទូទាត់:</span>
                            <span class="text-2xl font-black text-purple-600 dark:text-purple-400" x-text="`$${newBooking.total_price || 0}`"></span>
                        </div>
                    </div>

                    {{-- SPECIAL REQUESTS --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">មតិផ្សេងៗ</label>
                        <textarea x-model="newBooking.special_requests" rows="3"
                            class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all text-sm font-medium"
                            placeholder="មតិផ្សេងៗ ឬការរៀបចំបន្ថែម..."></textarea>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បោះបង់</button>
                    <button type="submit" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''" class="px-8 h-10 bg-purple-600 hover:bg-purple-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-purple-500/20 active:scale-95 transition-all cursor-pointer">
                        <span x-text="!loading ? 'រក្សាទុកព័ត៌មាន' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. MEETING BOOKING DETAIL MODAL --}}
<div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-3xl relative border border-gray-100 dark:border-gray-800 overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-8 py-5 flex justify-between items-center bg-gradient-to-r from-purple-900 via-indigo-900 to-purple-800 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20">
                        <i class="fas fa-handshake-angle text-xl text-amber-400"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-lg tracking-tight uppercase">ព័ត៌មានលម្អិតការកក់សាលប្រជុំ</h3>
                            <span class="px-2.5 py-0.5 rounded-lg bg-amber-400 text-purple-950 font-black text-xs font-mono" x-text="`#${selectedBooking.booking_code || ''}`"></span>
                        </div>
                        <p class="text-[11px] text-purple-200 font-bold uppercase tracking-widest mt-0.5">Meeting Booking Details</p>
                    </div>
                </div>
                <button type="button" @click="showDetailModal = false" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-2xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <div class="p-8 space-y-6 max-h-[72vh] overflow-y-auto custom-scrollbar text-sm">
                {{-- STATUS & SOURCE --}}
                <div class="flex justify-between items-center p-5 bg-purple-50/50 dark:bg-gray-800/60 rounded-2xl border border-purple-100 dark:border-gray-700">
                    <div>
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">ប្រភពការកក់</span>
                        <span class="font-black text-sm text-purple-600 dark:text-purple-400 mt-1 block flex items-center gap-1.5"
                            x-text="selectedBooking.booking_type === 'online' || (selectedBooking.user_id && !selectedBooking.customer_name) ? '🌐 កក់តាមអនឡាញ (Website)' : '🏬 កក់ផ្ទាល់ (Walk-In)'"></span>
                    </div>
                    <div>
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block text-right">ស្ថានភាពការកក់</span>
                        <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase mt-1 inline-block shadow-xs"
                            :class="{
                                'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300': selectedBooking.status === 'pending',
                                'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300': selectedBooking.status === 'confirmed',
                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300': selectedBooking.status === 'completed',
                                'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300': selectedBooking.status === 'cancelled'
                            }"
                            x-text="{
                                'pending': 'រង់ចាំពិនិត្យ',
                                'confirmed': 'បានបញ្ជាក់',
                                'completed': 'បានបញ្ចប់',
                                'cancelled': 'បោះបង់'
                            }[selectedBooking.status] || selectedBooking.status"></span>
                    </div>
                </div>

                {{-- CUSTOMER INFO --}}
                <div class="space-y-2">
                    <h4 class="text-[11px] font-black uppercase tracking-widest text-purple-800 dark:text-purple-400 flex items-center gap-1.5">
                        <i class="fas fa-user-tag"></i> ព័ត៌មានអតិថិជន / អង្គភាព
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 rounded-2xl bg-gray-50 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700">
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">ឈ្មោះអតិថិជន / អង្គភាព:</span>
                            <span class="font-black dark:text-white text-base mt-0.5 block" x-text="selectedBooking.customer_name || (selectedBooking.user?.name ?? 'N/A')"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">លេខទូរស័ព្ទ:</span>
                            <span class="font-bold dark:text-white text-sm mt-0.5 block" x-text="selectedBooking.customer_phone || (selectedBooking.user?.phone ?? 'N/A')"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">អ៊ីមែល:</span>
                            <span class="font-medium text-gray-700 dark:text-gray-300 text-xs mt-0.5 block truncate" x-text="selectedBooking.customer_email || (selectedBooking.user?.email ?? 'N/A')"></span>
                        </div>
                    </div>
                </div>

                {{-- ROOM & DATES INFO --}}
                <div class="space-y-2">
                    <h4 class="text-[11px] font-black uppercase tracking-widest text-purple-800 dark:text-purple-400 flex items-center gap-1.5">
                        <i class="fas fa-door-open"></i> ព័ត៌មានសាលប្រជុំ និងកាលបរិច្ឆេទ
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 rounded-2xl bg-gray-50 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700">
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">សាលប្រជុំ:</span>
                            <span class="font-black text-purple-600 dark:text-purple-400 text-base mt-0.5 block" x-text="`សាលប្រជុំ ${selectedBooking.room?.room_number ?? 'N/A'}`"></span>
                            <span class="text-xs text-gray-500 font-medium block mt-0.5" x-text="`${selectedBooking.room?.room_type?.name ?? ''} ($${selectedBooking.room?.room_type?.base_price || 0}/ម៉ោង)`"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">កាលបរិច្ឆេទប្រជុំ (ចំនួនថ្ងៃ):</span>
                            <span class="font-black text-emerald-600 dark:text-emerald-400 text-sm mt-0.5 block" x-text="`${formatDateDisplay(selectedBooking.start_date)} ដល់ ${formatDateDisplay(selectedBooking.end_date)} (${calculateDays(selectedBooking.start_date, selectedBooking.end_date)} ថ្ងៃ)`"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">ម៉ោងប្រជុំ (រយៈពេល):</span>
                            <span class="font-black text-amber-600 dark:text-amber-400 text-sm mt-0.5 block" x-text="`${formatTimeKhmer(selectedBooking.start_time)} - ${formatTimeKhmer(selectedBooking.end_time)} (${selectedBooking.total_hours || 0} ម៉ោង)`"></span>
                        </div>
                    </div>
                </div>

                {{-- ATTENDEES & SETUP --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-5 rounded-2xl bg-gray-50 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-700">
                    <div>
                        <span class="text-xs text-gray-400 font-bold block">ចំនួនអ្នកចូលរួម:</span>
                        <span class="font-black text-purple-600 dark:text-purple-400 text-base mt-0.5 block flex items-center gap-1.5">
                            <i class="fas fa-users text-sm"></i>
                            <span x-text="`${selectedBooking.attendees_count || 10} នាក់`"></span>
                        </span>
                    </div>
                    <div>
                        <span class="text-xs text-gray-400 font-bold block">ទម្រង់រៀបចំសាល:</span>
                        <span class="font-black dark:text-white text-base mt-0.5 block flex items-center gap-1.5">
                            <i class="fas fa-chair text-purple-500 text-sm"></i>
                            <span x-text="formatSetupStyle(selectedBooking.setup_style)"></span>
                        </span>
                    </div>
                </div>

                {{-- PAYMENT & TOTAL --}}
                <div class="p-6 rounded-2xl bg-purple-50/60 dark:bg-purple-950/20 border border-purple-200/60 dark:border-purple-800/60 space-y-4">
                    <div class="flex items-center justify-between border-b border-purple-200/50 dark:border-purple-800/50 pb-3 flex-wrap gap-2">
                        <span class="text-xs font-black uppercase tracking-wider text-purple-800 dark:text-purple-300 flex items-center gap-2">
                            <i class="fas fa-wallet text-purple-600 dark:text-purple-400"></i> ព័ត៌មាននៃការទូទាត់ប្រាក់
                        </span>
                        <div class="flex items-center gap-2 flex-wrap">
                            <template x-if="selectedBooking.payment && selectedBooking.payment.payment_slip">
                                <button type="button" @click="viewSlip(getSlipUrl(selectedBooking.payment.payment_slip))"
                                    class="px-3.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-full text-xs font-black flex items-center gap-1.5 transition active:scale-95 cursor-pointer shadow-xs">
                                    <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                                </button>
                            </template>
                            <template x-if="selectedBooking.payment?.status === 'paid' || !selectedBooking.payment">
                                <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300 flex items-center gap-1">
                                    <i class="fas fa-check-circle"></i> បានបង់រួច
                                </span>
                            </template>
                            <template x-if="selectedBooking.payment?.status === 'pending'">
                                <span class="px-3 py-1 rounded-full text-xs font-black bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300 flex items-center gap-1">
                                    <i class="fas fa-clock"></i> មិនទាន់បង់ / រង់ចាំពិនិត្យ
                                </span>
                            </template>
                            <template x-if="selectedBooking.payment?.status === 'refunded'">
                                <span class="px-3 py-1 rounded-full text-xs font-black bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300 flex items-center gap-1">
                                    <i class="fas fa-undo"></i> បានសងប្រាក់វិញ
                                </span>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                        <div>
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">វិធីសាស្ត្របង់ប្រាក់</span>
                            <span class="font-black text-gray-800 dark:text-gray-100 uppercase text-sm mt-1 flex items-center gap-1.5"
                                x-text="['qr', 'bank_transfer', 'khqr'].includes(selectedBooking.payment_method || (selectedBooking.payment ? selectedBooking.payment.method : '')) ? 'ឃ្យូអរកូដ (Bank QR)' : 'សាច់ប្រាក់ (Cash)'"></span>
                            <template x-if="selectedBooking.payment && selectedBooking.payment.payment_slip">
                                <button type="button" @click="viewSlip(getSlipUrl(selectedBooking.payment.payment_slip))"
                                    class="mt-1.5 text-xs font-black text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 flex items-center gap-1 cursor-pointer transition">
                                    <i class="fas fa-receipt"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                                </button>
                            </template>
                        </div>
                        <div>
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">លេខប្រតិបត្តិការ (TXN ID)</span>
                            <span class="font-mono font-bold text-gray-700 dark:text-gray-300 text-xs mt-1 block"
                                x-text="selectedBooking.payment?.transaction_id || 'N/A'"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">តម្លៃសរុប (Grand Total)</span>
                            <span class="font-black text-3xl text-purple-600 dark:text-purple-400 mt-1 block" x-text="`$${parseFloat(selectedBooking.total_price || 0).toFixed(2)}`"></span>
                        </div>
                    </div>

                    {{-- PAYMENT SLIP ATTACHMENT --}}
                    <template x-if="selectedBooking.payment && selectedBooking.payment.payment_slip">
                        <div class="pt-4 border-t border-purple-200/60 dark:border-purple-800/60 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-purple-800 dark:text-purple-300 flex items-center gap-1.5">
                                    <i class="fas fa-receipt text-purple-600 dark:text-purple-400"></i> រូបភាពបង្កាន់ដៃបង់ប្រាក់:
                                </span>
                                <button type="button" @click="viewSlip(getSlipUrl(selectedBooking.payment.payment_slip))"
                                    class="px-3 py-1 bg-purple-600 hover:bg-purple-700 active:scale-95 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition shadow-xs cursor-pointer">
                                    <i class="fas fa-expand text-[10px]"></i> ពង្រីករូបភាពពេញអេក្រង់
                                </button>
                            </div>

                            <div class="relative group/slip cursor-pointer overflow-hidden rounded-2xl border border-purple-200 dark:border-purple-800 bg-white dark:bg-gray-900 p-2 flex justify-center max-h-64"
                                @click="viewSlip(getSlipUrl(selectedBooking.payment.payment_slip))">
                                <img :src="getSlipUrl(selectedBooking.payment.payment_slip)" class="max-h-60 w-auto object-contain rounded-xl shadow-xs group-hover/slip:scale-105 transition-transform duration-300" alt="Payment Slip Image">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/slip:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-2xl">
                                    <span class="px-4 py-2 bg-white/90 dark:bg-gray-900/90 text-purple-700 dark:text-purple-400 font-extrabold text-xs rounded-xl shadow-md flex items-center gap-1.5">
                                        <i class="fas fa-search-plus"></i> ចុចដើម្បីមើលរូបភាពពេញ
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- SPECIAL REQUESTS --}}
                <template x-if="selectedBooking.special_requests">
                    <div class="space-y-2">
                        <h4 class="text-[11px] font-black uppercase tracking-widest text-gray-400">មតិផ្សេងៗ</h4>
                        <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-800 italic text-gray-600 dark:text-gray-300 text-xs font-medium" x-text="selectedBooking.special_requests"></div>
                    </div>
                </template>
            </div>

            {{-- FOOTER --}}
            <div class="px-8 py-4 bg-gray-50 dark:bg-gray-800/50 flex flex-wrap justify-between items-center gap-3 border-t dark:border-gray-800">
                <div class="flex items-center gap-2 flex-wrap">
                    <template x-if="selectedBooking.payment && selectedBooking.payment.payment_slip">
                        <button type="button" @click="viewSlip(getSlipUrl(selectedBooking.payment.payment_slip))"
                            class="px-5 h-10 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-md shadow-emerald-500/20 flex items-center gap-2 transition-all cursor-pointer">
                            <i class="fas fa-file-image"></i> មើលបង្កាន់ដៃបង់ប្រាក់
                        </button>
                    </template>
                    <a :href="`/admin/meeting-bookings/${selectedBooking.id}/print-invoice`" target="_blank" class="px-5 h-10 bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-md shadow-purple-500/20 flex items-center gap-2 transition-all cursor-pointer">
                        <i class="fas fa-print"></i> ព្រីនវិក្កយបត្រ
                    </a>
                    <a :href="`/admin/bookings/invoice/${selectedBooking.id}`" target="_blank" class="px-5 h-10 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-md shadow-emerald-500/20 flex items-center gap-2 transition-all cursor-pointer">
                        <i class="fas fa-file-pdf"></i> ទាញយក PDF
                    </a>
                </div>
                <button type="button" @click="showDetailModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បិទ</button>
            </div>
        </div>
    </div>
</div>

{{-- 3. EDIT MEETING BOOKING MODAL --}}
<div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលព័ត៌មានកក់សាលប្រជុំ</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Meeting Booking Information</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="updateBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    {{-- CUSTOMER INFO --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឈ្មោះអតិថិជន / អង្គភាព <span class="text-red-500">*</span></label>
                            <input type="text" x-model="editingBooking.customer_name" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                            <input type="text" x-model="editingBooking.customer_phone" required
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold text-sm">
                        </div>
                    </div>

                    {{-- 📅 DATES & TIMES (EDIT) --}}
                    <div class="p-5 rounded-2xl bg-purple-50/40 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/30 space-y-3">
                        <div class="flex items-center gap-2 border-b border-purple-100 dark:border-purple-900/40 pb-2">
                            <i class="far fa-calendar-alt text-purple-600 dark:text-purple-400"></i>
                            <h4 class="text-xs font-black uppercase tracking-wider text-purple-900 dark:text-purple-200">១. ថ្ងៃនិងម៉ោងប្រជុំ <span class="text-red-500">*</span></h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃចាប់ផ្តើម <span class="text-red-500">*</span></label>
                                <input type="date" x-model="editingBooking.start_date" :min="min_date" @change="if(editingBooking.end_date < editingBooking.start_date) editingBooking.end_date = editingBooking.start_date; handleEditDateTimeChange();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃបញ្ចប់ <span class="text-red-500">*</span></label>
                                <input type="date" x-model="editingBooking.end_date" :min="editingBooking.start_date ? editingBooking.start_date : min_date" @change="handleEditDateTimeChange()" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-1 tracking-widest">ម៉ោងចាប់ផ្តើម <span class="text-red-500">*</span></label>
                                <input type="time" x-model="editingBooking.start_time" @change="handleEditDateTimeChange()" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-1 tracking-widest">ម៉ោងបញ្ចប់ <span class="text-red-500">*</span></label>
                                <input type="time" x-model="editingBooking.end_time" @change="handleEditDateTimeChange()" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>
                        </div>
                    </div>

                    {{-- 🏢 SINGLE MEETING ROOM SELECT (EDIT) --}}
                    <div class="space-y-2" x-data="{ openEditMeetingSearch: false, searchEditMeetingQuery: '' }">
                        <div class="flex justify-between items-center ml-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 tracking-widest">២. ជ្រើសរើសសាលប្រជុំទំនេរ <span class="text-red-500">*</span></label>
                            <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase">
                                តាមកាលបរិច្ឆេទ៖ <span x-text="`${formatDateDisplay(editingBooking.start_date)} ដល់ ${formatDateDisplay(editingBooking.end_date)}`"></span>
                            </span>
                        </div>
                        
                        <div class="relative">
                            <div @click="openEditMeetingSearch = !openEditMeetingSearch"
                                class="w-full h-14 px-6 rounded-2xl bg-gray-50 dark:bg-gray-800 dark:text-white border-none focus-within:ring-2 focus-within:ring-purple-500 flex items-center justify-between cursor-pointer transition-all">
                                <span class="font-bold text-sm truncate" 
                                    x-text="editingBooking.meeting_room_id ? (meetingRooms.find(r => r.id == editingBooking.meeting_room_id) ? `សាលប្រជុំ ${meetingRooms.find(r => r.id == editingBooking.meeting_room_id).room_number} (${meetingRooms.find(r => r.id == editingBooking.meeting_room_id).room_type?.name || 'Meeting'}) - $${meetingRooms.find(r => r.id == editingBooking.meeting_room_id).room_type?.base_price || 0}/ម៉ោង` : 'ជ្រើសរើសសាលប្រជុំ...') : 'ជ្រើសរើសសាលប្រជុំ...'">
                                </span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="openEditMeetingSearch ? 'rotate-180 text-purple-500' : ''"></i>
                            </div>

                            <div x-show="openEditMeetingSearch" @click.outside="openEditMeetingSearch = false" x-cloak
                                x-transition:enter="ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 top-full mt-2 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 z-50 overflow-hidden">
                                
                                <div class="p-3 border-b border-gray-100 dark:border-gray-800 relative bg-gray-50/50 dark:bg-gray-800/50">
                                    <input type="text" x-model="searchEditMeetingQuery" placeholder="ស្វែងរកសាលប្រជុំ ឬប្រភេទ..."
                                        class="w-full h-10 px-4 rounded-xl bg-white dark:bg-gray-800 text-xs dark:text-white border border-gray-200 dark:border-gray-700 outline-none focus:ring-2 focus:ring-purple-500">
                                </div>

                                <div class="max-h-60 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="room in meetingRooms.filter(r => (!searchEditMeetingQuery || `${r.room_number} ${r.room_type?.name}`.toLowerCase().includes(searchEditMeetingQuery.toLowerCase())))" :key="room.id">
                                        <div @click="if(!isRoomBusy(room.id) || editingBooking.meeting_room_id == room.id) { editingBooking.meeting_room_id = room.id; openEditMeetingSearch = false; searchEditMeetingQuery = ''; calculateEditTotal(); }"
                                            :class="{
                                                'bg-purple-600 text-white font-bold cursor-pointer': editingBooking.meeting_room_id == room.id,
                                                'hover:bg-purple-50 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200 cursor-pointer': !isRoomBusy(room.id) && editingBooking.meeting_room_id != room.id,
                                                'opacity-50 bg-gray-100 dark:bg-gray-800/40 text-gray-400 cursor-not-allowed': isRoomBusy(room.id) && editingBooking.meeting_room_id != room.id
                                            }"
                                            class="px-4 py-3 rounded-xl text-xs flex items-center justify-between transition">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-users" :class="editingBooking.meeting_room_id == room.id ? 'text-white' : (isRoomBusy(room.id) ? 'text-gray-400' : 'text-purple-500')"></i>
                                                <span class="font-bold" x-text="`សាលប្រជុំ ${room.room_number}`"></span>
                                                <span class="opacity-80" x-text="`(${room.room_type?.name || 'Meeting'})`"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <template x-if="editingBooking.meeting_room_id == room.id">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-purple-500 text-white">កំពុងជ្រើស</span>
                                                </template>
                                                <template x-if="editingBooking.meeting_room_id != room.id && !isRoomBusy(room.id)">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">ទំនេរ</span>
                                                </template>
                                                <template x-if="editingBooking.meeting_room_id != room.id && isRoomBusy(room.id)">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-rose-100 text-rose-700 dark:bg-rose-900/60 dark:text-rose-300">ពុំទំនេរ (ម៉ោងស្ទួន)</span>
                                                </template>
                                                <span class="font-extrabold" x-text="`$${room.room_type?.base_price || 0}/ម៉ោង`"></span>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STATUS & PAYMENT SECTION FOR EDIT --}}
                    <div class="p-5 rounded-2xl bg-amber-50/50 dark:bg-gray-800/60 border border-amber-100 dark:border-gray-700 space-y-4">
                        <div class="flex items-center gap-2 border-b border-amber-100 dark:border-gray-700 pb-2">
                            <i class="fas fa-wallet text-amber-600 dark:text-amber-400"></i>
                            <h4 class="text-xs font-black uppercase tracking-wider text-amber-900 dark:text-amber-200">ស្ថានភាព និងព័ត៌មានទូទាត់ប្រាក់</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            {{-- ស្ថានភាពការកក់ (Booking Status) --}}
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ស្ថានភាពការកក់</label>
                                <div class="relative">
                                    <select x-model="editingBooking.status"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all appearance-none font-bold text-xs">
                                        <option value="pending">រង់ចាំពិនិត្យ</option>
                                        <option value="confirmed">បានបញ្ជាក់</option>
                                        <option value="completed">បានបញ្ចប់</option>
                                        <option value="cancelled">បានបោះបង់</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- ស្ថានភាពទូទាត់ប្រាក់ (Payment Status) --}}
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ស្ថានភាពទូទាត់ប្រាក់</label>
                                <div class="relative">
                                    <select x-model="editingBooking.payment_status"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all appearance-none font-bold text-xs">
                                        <option value="paid">បានបង់រួច</option>
                                        <option value="pending">មិនទាន់បង់ / រង់ចាំពិនិត្យ</option>
                                        <option value="refunded">បានសងប្រាក់វិញ</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- វិធីសាស្ត្របង់ប្រាក់ (Payment Method) --}}
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">វិធីសាស្ត្របង់ប្រាក់</label>
                                <div class="relative">
                                    <select x-model="editingBooking.payment_method"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all appearance-none font-bold text-xs">
                                        <option value="cash">ប្រាក់សុទ្ធ</option>
                                        <option value="qr">ឃ្យូអរកូដ</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- លេខប្រតិបត្តិការ (Transaction ID) --}}
                        <div x-show="editingBooking.payment_method === 'qr' || editingBooking.payment_method === 'bank_transfer' || editingBooking.payment_method === 'khqr'" class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">លេខប្រតិបត្តិការ / Transaction ID</label>
                            <input type="text" x-model="editingBooking.transaction_id" placeholder="TXN-987654321"
                                class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                        </div>
                    </div>

                    {{-- ATTENDEES & SETUP --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំនួនអ្នកចូលរួម (នាក់)</label>
                            <input type="number" x-model="editingBooking.attendees_count"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ទម្រង់រៀបចំសាល (Setup Style)</label>
                            <div class="relative">
                                <select x-model="editingBooking.setup_style"
                                    class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold text-sm appearance-none cursor-pointer">
                                    <option value="">ជ្រើសរើសទម្រង់រៀបចំ</option>
                                    <option value="Classroom">ថ្នាក់រៀន</option>
                                    <option value="Theater">មហោស្រព / សាលប្រជុំ</option>
                                    <option value="U-Shape">អក្សរ យូ</option>
                                    <option value="Boardroom">ប្រជុំក្រុមប្រឹក្សា</option>
                                    <option value="Banquet">តុមូលពិធីលៀងសាយភោជន៍</option>
                                    <option value="Cocktail">ជប់លៀងឈរ</option>
                                    <option value="Hollow Square">ការ៉េចតុកោណ</option>
                                    <option value="Cabaret">តុមូលកន្លះវង់</option>
                                    <option value="Custom">រៀបចំពិសេសតាមការស្នើសុំ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TOTAL PRICE --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">តម្លៃសរុប <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" x-model="editingBooking.total_price" required
                            class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 text-purple-600 dark:text-purple-400 font-black text-2xl outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    {{-- SPECIAL REQUESTS --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">មតិផ្សេងៗ</label>
                        <textarea x-model="editingBooking.special_requests" rows="3"
                            class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all text-sm font-medium"
                            placeholder="មតិផ្សេងៗ..."></textarea>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បោះបង់</button>
                    <button type="submit" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''" class="px-8 h-10 bg-amber-500 hover:bg-amber-600 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-amber-500/20 active:scale-95 transition-all cursor-pointer">
                        <span x-text="!loading ? 'រក្សាទុកការកែប្រែ' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. PAYMENT SLIP PREVIEW MODAL --}}
<div x-show="showSlipModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-md transition-opacity" @click="showSlipModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl w-full max-w-2xl relative overflow-hidden p-6 z-10 border border-gray-100 dark:border-gray-800 space-y-4"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-3">
                <h3 class="text-base font-black text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-file-image text-emerald-500"></i> សន្លឹកចុងបង់ប្រាក់
                </h3>
                <button type="button" @click="showSlipModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <div class="flex justify-center p-2 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                <template x-if="selectedSlip">
                    <img :src="selectedSlip" class="max-h-[480px] w-auto object-contain rounded-xl shadow-md transition-all hover:scale-105" alt="Payment Slip Image">
                </template>
                <template x-if="!selectedSlip">
                    <p class="text-xs text-gray-400 font-bold">គ្មានរូបភាពបង្កាន់ដៃបង់ប្រាក់នៅឡើយ</p>
                </template>
            </div>

            <div class="flex justify-between items-center pt-2">
                <a :href="selectedSlip" target="_blank" class="px-4 py-2 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-2">
                    <i class="fas fa-download"></i> បើកមើលទំហំដើម
                </a>
                <button type="button" @click="showSlipModal = false" class="px-6 py-2 bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-300 rounded-xl text-xs font-bold transition">
                    បិទ
                </button>
            </div>
        </div>
    </div>
</div>
