{{-- 1. ADD ROOM BOOKING MODAL (WALK-IN FROM CALENDAR) --}}
<div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមកក់បន្ទប់ថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Customer, Room & Date Details</p>
                </div>
                <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="saveCalendarBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    {{-- 📅 STEP 1: DURATION, CHECK-IN & CHECK-OUT (SELECT DATES FIRST LIKE WEBSITE) --}}
                    <div class="p-5 rounded-2xl bg-blue-50/40 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 space-y-4">
                        <div class="flex items-center justify-between border-b border-blue-100 dark:border-blue-900/30 pb-2 flex-wrap gap-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-400"></i>
                                <h4 class="text-xs font-black uppercase tracking-wider text-blue-900 dark:text-blue-200">១. ជ្រើសរើសថ្ងៃចូល-ថ្ងៃចេញ</h4>
                            </div>
                            <button type="button" @click="handleSearchAvailableRooms(true)" 
                                :disabled="isSearchingRooms"
                                class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
                                <i class="fas" :class="isSearchingRooms ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                                <span>ស្វែងរកបន្ទប់</span>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ចំនួនថ្ងៃស្នាក់នៅ</label>
                                <div class="relative">
                                    <select x-model="newBooking.duration" @change="handleDurationChange()"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs appearance-none cursor-pointer">
                                        <option value="">-- រើសចំនួនថ្ងៃ --</option>
                                        <option value="1">1 ថ្ងៃ (1 យប់)</option>
                                        <option value="2">2 ថ្ងៃ (2 យប់)</option>
                                        <option value="3">3 ថ្ងៃ (3 យប់)</option>
                                        <option value="4">4 ថ្ងៃ (4 យប់)</option>
                                        <option value="5">5 ថ្ងៃ (5 យប់)</option>
                                        <option value="6">6 ថ្ងៃ (6 យប់)</option>
                                        <option value="7">7 ថ្ងៃ (1 សប្តាហ៍)</option>
                                        <option value="8">8 ថ្ងៃ</option>
                                        <option value="9">9 ថ្ងៃ</option>
                                        <option value="10">10 ថ្ងៃ</option>
                                        <option value="14">14 ថ្ងៃ (2 សប្តាហ៍)</option>
                                        <option value="21">21 ថ្ងៃ (3 សប្តាហ៍)</option>
                                        <option value="30">30 ថ្ងៃ (1 ខែ)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃចូល (Check-in) <span class="text-red-500">*</span></label>
                                <input type="date" x-model="newBooking.check_in" :min="min_date" @change="handleDateOrDurationChange()"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs">
                                <template x-if="errors.check_in"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.check_in[0]"></span></template>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃចេញ (Check-out) <span class="text-red-500">*</span></label>
                                <input type="date" x-model="newBooking.check_out" :min="getMinCheckOutDate(newBooking.check_in)" @change="handleCheckOutChange()"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs">
                                <template x-if="errors.check_out"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.check_out[0]"></span></template>
                            </div>
                        </div>

                        <div class="pt-1">
                            <button type="button" @click="handleSearchAvailableRooms(true)" 
                                :disabled="isSearchingRooms"
                                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-bold text-xs rounded-xl shadow-xs hover:shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                                <i class="fas" :class="isSearchingRooms ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                                <span>ស្វែងរកបន្ទប់ទំនេរតាមថ្ងៃដែលបានជ្រើស</span>
                            </button>
                        </div>
                    </div>

                    {{-- 🏨 STEP 2: ROOM SELECT (SHOWS AVAILABLE ROOMS BASED ON DATES ABOVE) --}}
                    <div class="p-5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 space-y-3" 
                        x-data="{ openRoomSearch: false, searchRoomQuery: '' }" 
                        @open-room-dropdown.window="openRoomSearch = true">
                        <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-bed text-blue-600 dark:text-blue-400"></i>
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">២. ជ្រើសរើសបន្ទប់ដែលទំនេរ <span class="text-red-500">*</span></h4>
                            </div>
                            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider" x-text="`បានជ្រើស: ${newBooking.room_ids ? newBooking.room_ids.length : 0} បន្ទប់`"></span>
                        </div>
                        
                        <div class="relative">
                            {{-- Trigger Button / Selected Badges --}}
                            <div @click="openRoomSearch = !openRoomSearch"
                                class="w-full min-h-[3.5rem] p-3 px-5 rounded-2xl bg-white dark:bg-gray-900 dark:text-white border-none focus-within:ring-2 focus-within:ring-blue-500 flex items-center justify-between cursor-pointer transition-all gap-2 flex-wrap shadow-xs">
                                
                                <div class="flex flex-wrap items-center gap-1.5 flex-1">
                                    <template x-if="!newBooking.room_ids || newBooking.room_ids.length === 0">
                                        <span class="font-bold text-sm text-gray-400">ជ្រើសរើសបន្ទប់ដែលទំនេរក្នុងកំឡុងថ្ងៃនេះ (ចុចដើម្បីជ្រើស)...</span>
                                    </template>

                                    <template x-for="rId in newBooking.room_ids" :key="rId">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-sm">
                                            <i class="fa-solid fa-bed text-[10px]"></i>
                                            <span x-text="rooms.find(r => r.id == rId) ? `បន្ទប់ ${rooms.find(r => r.id == rId).room_number} ($${rooms.find(r => r.id == rId).room_type?.base_price})` : ''"></span>
                                            <button type="button" @click.stop="toggleRoomSelection(rId)" class="ml-1 text-white/80 hover:text-white">&times;</button>
                                        </span>
                                    </template>
                                </div>

                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="openRoomSearch ? 'rotate-180 text-blue-500' : ''"></i>
                            </div>

                            {{-- Dropdown --}}
                            <div x-show="openRoomSearch" @click.outside="openRoomSearch = false" x-cloak
                                x-transition:enter="ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 top-full mt-2 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 z-50 overflow-hidden">
                                
                                <div class="p-3 border-b border-gray-100 dark:border-gray-800 relative bg-gray-50/50 dark:bg-gray-800/50">
                                    <input type="text" x-model="searchRoomQuery" placeholder="ស្វែងរកលេខបន្ទប់ ឬប្រភេទបន្ទប់..."
                                        class="w-full h-10 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 text-xs dark:text-white outline-none focus:ring-2 focus:ring-blue-500 font-bold">
                                </div>

                                <div class="max-h-60 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="room in rooms.filter(r => !isRoomBusy(r.id) && (!searchRoomQuery || `${r.room_number} ${r.room_type?.name}`.toLowerCase().includes(searchRoomQuery.toLowerCase())))" :key="room.id">
                                        <div @click="toggleRoomSelection(room.id)"
                                            :class="{
                                                'bg-blue-600 text-white font-bold': newBooking.room_ids && newBooking.room_ids.some(id => String(id) === String(room.id)),
                                                'hover:bg-blue-50 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200': !(newBooking.room_ids && newBooking.room_ids.some(id => String(id) === String(room.id)))
                                            }"
                                            class="px-4 py-3 rounded-xl text-xs flex items-center justify-between cursor-pointer transition">
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" :checked="newBooking.room_ids && newBooking.room_ids.some(id => String(id) === String(room.id))" class="rounded text-blue-600 focus:ring-0 mr-1 pointer-events-none">
                                                <i class="fa-solid fa-bed text-sm" :class="(newBooking.room_ids && newBooking.room_ids.some(id => String(id) === String(room.id))) ? 'text-white' : 'text-blue-500'"></i>
                                                <span class="font-bold" x-text="`បន្ទប់ ${room.room_number}`"></span>
                                                <span class="opacity-80" x-text="`(${room.room_type?.name})`"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">ទំនេរ</span>
                                                <span class="font-extrabold" x-text="`$${room.room_type?.base_price}/យប់`"></span>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="rooms.filter(r => !isRoomBusy(r.id) && (!searchRoomQuery || `${r.room_number} ${r.room_type?.name}`.toLowerCase().includes(searchRoomQuery.toLowerCase()))).length === 0" 
                                        class="p-4 text-center text-xs text-gray-400 font-bold italic">
                                        <i class="fa-solid fa-circle-exclamation mr-1 text-amber-500"></i> ពុំមានបន្ទប់ទំនេរតាមកាលបរិច្ឆេទនេះឡើយ (សូមជ្រើសរើសថ្ងៃផ្សេង)
                                    </div>
                                </div>
                            </div>
                        </div>
                        <template x-if="errors.room_id"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.room_id[0]"></span></template>
                    </div>

                    {{-- 👤 STEP 3: CUSTOMER INFO --}}
                    <div class="p-5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                            <i class="fas fa-user-tag text-blue-600 dark:text-blue-400"></i>
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">៣. ព័ត៌មានអតិថិជនកក់ផ្ទាល់</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ឈ្មោះអតិថិជន <span class="text-red-500">*</span></label>
                                <input type="text" x-model="newBooking.customer_name" required placeholder="ឈ្មោះពេញ"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal text-xs">
                                <template x-if="errors.customer_name"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.customer_name[0]"></span></template>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                                <input type="text" x-model="newBooking.customer_phone" required placeholder="096 XXXXXXX"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold placeholder:font-normal text-xs">
                                <template x-if="errors.customer_phone"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.customer_phone[0]"></span></template>
                            </div>
                        </div>
                    </div>

                    {{-- 💳 STEP 4: PAYMENT SECTION (FORM PAYMENT) --}}
                    <div class="p-5 rounded-2xl bg-blue-50/50 dark:bg-gray-800/60 border border-blue-100 dark:border-gray-700 space-y-4">
                        <div class="flex items-center gap-2 border-b border-blue-100 dark:border-gray-700 pb-2">
                            <i class="fas fa-wallet text-blue-600 dark:text-blue-400"></i>
                            <h4 class="text-xs font-black uppercase tracking-wider text-blue-900 dark:text-blue-200">៤. ព័ត៌មានទូទាត់ប្រាក់</h4>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- ស្ថានភាពទូទាត់ (Payment Status) --}}
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ស្ថានភាពទូទាត់ប្រាក់ <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select x-model="newBooking.payment_status"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold text-xs">
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
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none font-bold text-xs">
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
                            <input type="text" x-model="newBooking.transaction_id" placeholder="ឧ. TXN-987654321"
                                class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-xs">
                        </div>

                        {{-- តម្លៃសរុប (Grand Total) --}}
                        <div class="pt-2 flex justify-between items-center border-t border-blue-100 dark:border-gray-700">
                            <span class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">តម្លៃសរុបត្រូវទូទាត់:</span>
                            <span class="text-2xl font-black text-blue-600 dark:text-blue-400" x-text="`$${newBooking.total_price || 0}`"></span>
                        </div>
                    </div>

                    {{-- 📝 REQUESTS --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">មតិផ្សេងៗ</label>
                        <textarea x-model="newBooking.special_requests" rows="3"
                            class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium"
                            placeholder="បញ្ជាក់បន្ថែមផ្សេងៗ ប្រសិនបើមាន..."></textarea>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បោះបង់</button>
                    <button type="submit" :disabled="loading" :class="loading ? 'opacity-50 cursor-not-allowed' : ''" class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all cursor-pointer">
                        <span x-text="!loading ? 'រក្សាទុកព័ត៌មាន' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 2. Quick Add MEETING Booking Modal from Calendar --}}
<div x-show="showMeetingAddModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showMeetingAddModal = false"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កក់សាលប្រជុំថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Customer, Meeting Room & Event Details</p>
                </div>
                <button type="button" @click="showMeetingAddModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="saveMeetingBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    {{-- 👤 CUSTOMER INFO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឈ្មោះអតិថិជន / អង្គភាព <span class="text-red-500">*</span></label>
                            <input type="text" x-model="newMeeting.customer_name" required placeholder="ឈ្មោះពេញ"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold placeholder:font-normal text-sm">
                            <template x-if="errors.customer_name"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.customer_name[0]"></span></template>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                            <input type="text" x-model="newMeeting.customer_phone" required placeholder="012 XXXXXX"
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
                                <input type="date" x-model="selectedMeetingDate" min="{{ date('Y-m-d') }}" @change="if(newMeeting.end_date < selectedMeetingDate) newMeeting.end_date = selectedMeetingDate; calculateMeetingTotal();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃបញ្ចប់ <span class="text-red-500">*</span></label>
                                <input type="date" x-model="newMeeting.end_date" :min="selectedMeetingDate ? selectedMeetingDate : '{{ date('Y-m-d') }}'" @change="calculateMeetingTotal();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-1 tracking-widest">ម៉ោងចាប់ផ្តើម <span class="text-red-500">*</span></label>
                                <input type="time" x-model="newMeeting.start_time" @change="calculateMeetingTotal();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-[11px] font-black uppercase text-gray-400 ml-1 tracking-widest">ម៉ោងបញ្ចប់ <span class="text-red-500">*</span></label>
                                <input type="time" x-model="newMeeting.end_time" @change="calculateMeetingTotal();" required
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                            </div>
                        </div>
                    </div>

                    {{-- 🏢 MEETING ROOM SELECT (SINGLE SELECT) --}}
                    <div class="space-y-2">
                        <div class="flex justify-between items-center ml-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 tracking-widest">២. ជ្រើសរើសសាលប្រជុំទំនេរ <span class="text-red-500">*</span></label>
                            <span class="text-[10px] font-bold text-purple-600 dark:text-purple-400 uppercase">
                                តាមកាលបរិច្ឆេទ៖ <span x-text="`${formatDateDisplay(selectedMeetingDate)} ដល់ ${formatDateDisplay(newMeeting.end_date)}`"></span>
                            </span>
                        </div>
                        
                        <div class="relative group">
                            <select x-model="selectedMeetingRoom" @change="calculateMeetingTotal()" required
                                class="w-full h-14 pl-6 pr-10 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 font-bold text-sm appearance-none cursor-pointer outline-none">
                                <option value="">ជ្រើសរើសសាលប្រជុំដែលទំនេរ...</option>
                                @foreach($meetingRooms as $mRoom)
                                <option value="{{ $mRoom->id }}" data-price="{{ $mRoom->roomType->base_price ?? 0 }}">
                                    សាលប្រជុំ {{ $mRoom->room_number }} ({{ $mRoom->roomType->name ?? 'Meeting Hall' }}) - ${{ number_format($mRoom->roomType->base_price ?? 0, 2) }}/ម៉ោង
                                </option>
                                @endforeach
                            </select>
                            <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                        </div>
                        <template x-if="errors.meeting_room_id"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.meeting_room_id[0]"></span></template>
                    </div>

                    {{-- EVENT SETUP & ATTENDEES --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ចំនួនអ្នកចូលរួម (នាក់)</label>
                            <input type="number" min="1" x-model="newMeeting.attendees_count" placeholder="10"
                                class="w-full h-14 px-6 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all font-bold text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ទម្រង់រៀបចំសាល</label>
                            <div class="relative">
                                <select x-model="newMeeting.setup_style"
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
                                    <select x-model="newMeeting.payment_status"
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
                                    <select x-model="newMeeting.payment_method"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all appearance-none font-bold text-xs">
                                        <option value="cash">ប្រាក់សុទ្ធ</option>
                                        <option value="khqr">ឃ្យូអរកូដ</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- លេខប្រតិបត្តិការ (Transaction ID for QR) --}}
                        <div x-show="newMeeting.payment_method === 'khqr' || newMeeting.payment_method === 'qr'" class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">លេខប្រតិបត្តិការ / Transaction ID (ប្រសិនបើមាន)</label>
                            <input type="text" x-model="newMeeting.transaction_id" placeholder="TXN-987654321"
                                class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none font-bold text-xs">
                        </div>

                        {{-- តម្លៃសរុប (Grand Total) --}}
                        <div class="pt-2 flex justify-between items-center border-t border-purple-100 dark:border-gray-700">
                            <span class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">តម្លៃសរុបត្រូវទូទាត់:</span>
                            <span class="text-2xl font-black text-purple-600 dark:text-purple-400" x-text="`$${newMeeting.total_price || 0}`"></span>
                        </div>
                    </div>

                    {{-- SPECIAL REQUESTS --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">មតិផ្សេងៗ</label>
                        <textarea x-model="newMeeting.special_requests" rows="3"
                            class="w-full p-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all text-sm font-medium"
                            placeholder="មតិផ្សេងៗ ឬការរៀបចំបន្ថែម..."></textarea>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showMeetingAddModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បោះបង់</button>
                    <button type="submit" :disabled="meetingLoading" :class="meetingLoading ? 'opacity-50 cursor-not-allowed' : ''" class="px-8 h-10 bg-purple-600 hover:bg-purple-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-purple-500/20 active:scale-95 transition-all cursor-pointer">
                        <span x-text="!meetingLoading ? 'រក្សាទុកព័ត៌មាន' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 3. Booking Detail Modal (Stay + Meeting unified) --}}
<div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានការកក់</h3>
                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 font-mono font-black text-xs rounded-lg"
                            x-text="'#' + (selectedBooking?.booking_code || '')"></span>
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5"
                        x-text="selectedBooking?.type === 'meeting' ? ' Meeting Hall Reservation Details' : ' Hotel Room Reservation Details'"></p>
                </div>
                <button type="button" @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto custom-scrollbar">

                {{-- 👤 GUEST & BOOKING SOURCE INFO CARD --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800/80 rounded-2xl flex items-center justify-between gap-4 border border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3.5">
                        <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-lg shadow-md shadow-blue-500/20">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider block">ឈ្មោះអតិថិជន</span>
                            <h4 class="font-black text-base text-gray-900 dark:text-white leading-tight"
                                x-text="selectedBooking?.customer_name || (selectedBooking?.user?.name) || 'ភ្ញៀវកក់ផ្ទាល់'"></h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5 flex items-center gap-1.5">
                                <i class="fas fa-phone text-[10px] text-blue-500"></i>
                                <span x-text="selectedBooking?.customer_phone || (selectedBooking?.user?.phone) || 'គ្មានលេខទូរស័ព្ទ'"></span>
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider block">ប្រភេទការកក់</span>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800 mt-1"
                            x-text="selectedBooking?.type === 'meeting' ? 'សាលប្រជុំ' : 'បន្ទប់ស្នាក់នៅ'"></span>
                    </div>
                </div>

                {{-- 🏨 ROOM & DATES INFO GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Room / Hall --}}
                    <div class="p-4 bg-blue-50/70 dark:bg-blue-950/20 rounded-2xl border border-blue-100 dark:border-blue-900/30 flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold shrink-0 mt-0.5">
                            <i class="fas" :class="selectedBooking?.type === 'meeting' ? 'fa-building' : 'fa-door-open'"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider block"
                                x-text="selectedBooking?.type === 'meeting' ? 'សាលប្រជុំ' : 'បន្ទប់ស្នាក់នៅ'"></span>
                            <p class="font-black text-sm text-gray-800 dark:text-gray-100 mt-0.5"
                                x-text="selectedBooking?.room?.room_number ? (selectedBooking?.type === 'meeting' ? 'សាល ' : 'បន្ទប់ ') + selectedBooking.room.room_number : 'N/A'"></p>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium"
                                x-text="selectedBooking?.room?.room_type?.name || selectedBooking?.room_type || ''"></p>
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="p-4 bg-purple-50/70 dark:bg-purple-950/20 rounded-2xl border border-purple-100 dark:border-purple-900/30 flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold shrink-0 mt-0.5">
                            <i class="fas fa-calendar-alt text-base"></i>
                        </div>
                        <div>
                            <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider block">កាលបរិច្ឆេទ & ម៉ោង</span>
                            <p class="font-black text-xs text-emerald-600 dark:text-emerald-400 mt-0.5"
                                x-text="`ចូល: ${formatDateDMY(selectedBooking?.check_in || selectedBooking?.start_date)}`"></p>
                            <p class="font-black text-xs text-rose-500 mt-0.5"
                                x-text="`ចេញ: ${formatDateDMY(selectedBooking?.check_out || selectedBooking?.end_date)}`"></p>
                            <template x-if="selectedBooking?.type === 'meeting'">
                                <p class="text-[11px] text-purple-600 dark:text-purple-400 font-bold mt-1"
                                    x-text="`ម៉ោង: ${selectedBooking?.start_time || 'N/A'} - ${selectedBooking?.end_time || 'N/A'}`"></p>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- 👥 EVENT DETAILS FOR MEETINGS (ATTENDEES & SETUP STYLE) --}}
                <template x-if="selectedBooking?.type === 'meeting'">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="p-3.5 bg-purple-50/50 dark:bg-purple-950/20 rounded-2xl border border-purple-100 dark:border-purple-900/30 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider block">ចំនួនអ្នកចូលរួម</span>
                                <span class="font-bold text-xs text-purple-700 dark:text-purple-300" x-text="`${selectedBooking?.attendees_count || 0} នាក់`"></span>
                            </div>
                        </div>
                        <div class="p-3.5 bg-indigo-50/50 dark:bg-indigo-950/20 rounded-2xl border border-indigo-100 dark:border-indigo-900/30 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fas fa-th-large"></i>
                            </div>
                            <div>
                                <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider block">ទម្រង់រៀបចំសាល</span>
                                <span class="font-bold text-xs text-indigo-700 dark:text-indigo-300" x-text="selectedBooking?.setup_style || 'Standard'"></span>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- 💳 PAYMENT & GRAND TOTAL CARD --}}
                <div class="p-5 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-950/30 dark:to-teal-950/20 rounded-2xl border border-emerald-200/60 dark:border-emerald-900/30 flex justify-between items-center">
                    <div>
                        <span class="text-[10px] font-black uppercase text-emerald-700 dark:text-emerald-400 tracking-widest block">តម្លៃសរុប</span>
                        <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5 block"
                            x-text="'$' + parseFloat(selectedBooking?.total_price || 0).toFixed(2)"></span>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase"
                            x-text="`ទូទាត់តាម: ${selectedBooking?.payment_method === 'qr' || selectedBooking?.payment_method === 'khqr' ? 'ឃ្យូអរកូដ' : (selectedBooking?.payment_method === 'bank_transfer' ? 'ផ្ទេរតាមធនាគារ' : 'ប្រាក់សុទ្ធ')}`"></span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-black uppercase text-gray-400 tracking-wider block mb-1">ស្ថានភាព</span>
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase inline-block shadow-xs"
                            :class="{
                                'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-300': selectedBooking?.status === 'pending',
                                'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-300': selectedBooking?.status === 'confirmed',
                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-300': selectedBooking?.status === 'completed',
                                'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300 border border-red-300': selectedBooking?.status === 'cancelled',
                            }"
                            x-text="{
                                'pending': 'រង់ចាំពិនិត្យ',
                                'confirmed': 'បានបញ្ជាក់',
                                'completed': 'បានបញ្ចប់',
                                'cancelled': 'បោះបង់'
                            }[selectedBooking?.status] || selectedBooking?.status || 'N/A'">
                        </span>
                    </div>
                </div>

                {{-- ⚙️ STATUS UPDATE FORM (STANDARD DROPDOWN & QUICK BUTTONS) --}}
                <div class="p-5 bg-gray-50 dark:bg-gray-800/80 rounded-2xl border border-gray-100 dark:border-gray-800 space-y-3" x-show="selectedBooking?.id">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 tracking-widest flex items-center gap-1.5">
                            <i class="fas fa-sliders-h text-blue-500"></i> កែប្រែស្ថានភាពការកក់
                        </label>
                    </div>

                    {{-- Dynamic Select Form Dropdown --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="relative flex-1">
                            <select x-model="selectedBooking.status"
                                class="w-full h-12 pl-4 pr-10 rounded-xl border-none bg-white dark:bg-gray-900 dark:text-white font-bold text-xs focus:ring-2 focus:ring-blue-500 shadow-sm appearance-none outline-none cursor-pointer">
                                <option value="pending">រង់ចាំពិនិត្យ</option>
                                <option value="confirmed">បានបញ្ជាក់</option>
                                <option value="completed">បានបញ្ចប់</option>
                                <option value="cancelled">បោះបង់</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>

                        <button type="button" @click="quickUpdateStatus(selectedBooking.status)" :disabled="actionLoading"
                            class="px-6 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md shadow-blue-500/20 active:scale-95 transition-all flex items-center justify-center gap-2 cursor-pointer shrink-0">
                            <i class="fas fa-save" x-show="!actionLoading"></i>
                            <i class="fas fa-spinner fa-spin" x-show="actionLoading"></i>
                            <span x-text="!actionLoading ? 'រក្សាទុក' : 'កំពុងរក្សាទុក...'"></span>
                        </button>
                    </div>

                    {{-- Fast 1-Click Action Buttons Shortcut --}}
                    <div class="pt-2 border-t border-gray-200/60 dark:border-gray-700/60">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-2">ចុចផ្លាស់ប្តូរលឿន:</span>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" @click="quickUpdateStatus('pending')" :disabled="actionLoading"
                                :class="selectedBooking?.status === 'pending' ? 'ring-2 ring-amber-500 font-black' : ''"
                                class="px-3.5 py-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-300 rounded-xl font-bold text-xs flex items-center gap-1.5 transition cursor-pointer">
                                <i class="fas fa-clock"></i> រង់ចាំពិនិត្យ
                            </button>
                            <button type="button" @click="quickUpdateStatus('confirmed')" :disabled="actionLoading"
                                :class="selectedBooking?.status === 'confirmed' ? 'ring-2 ring-blue-500 font-black' : ''"
                                class="px-3.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 rounded-xl font-bold text-xs flex items-center gap-1.5 transition cursor-pointer">
                                <i class="fas fa-check"></i> បានបញ្ជាក់
                            </button>
                            <button type="button" @click="quickUpdateStatus('completed')" :disabled="actionLoading"
                                :class="selectedBooking?.status === 'completed' ? 'ring-2 ring-emerald-500 font-black' : ''"
                                class="px-3.5 py-1.5 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-300 rounded-xl font-bold text-xs flex items-center gap-1.5 transition cursor-pointer">
                                <i class="fas fa-sign-out-alt"></i> បានបញ្ចប់
                            </button>
                            <button type="button" @click="if(confirm('តើអ្នកពិតជាចង់បោះបង់ការកក់នេះមែនទេ?')) quickUpdateStatus('cancelled')" :disabled="actionLoading"
                                :class="selectedBooking?.status === 'cancelled' ? 'ring-2 ring-red-500 font-black' : ''"
                                class="px-3.5 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-900/30 dark:text-red-300 rounded-xl font-bold text-xs flex items-center gap-1.5 transition cursor-pointer">
                                <i class="fas fa-times"></i> បោះបង់
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center border-t dark:border-gray-800">
                <template x-if="selectedBooking?.id">
                    <a :href="selectedBooking?.type === 'meeting'
                            ? `/admin/meeting-bookings?search=${selectedBooking.booking_code || ''}`
                            : `/admin/room-bookings?search=${selectedBooking.booking_code || ''}`"
                        class="px-4 h-9 bg-gray-800 hover:bg-gray-900 text-white rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-sm transition-all">
                        <i class="fas fa-external-link-alt text-[10px]"></i> មើលលម្អិតពេញលេញ
                    </a>
                </template>
                <button type="button" @click="showDetailModal = false"
                    class="px-6 h-9 rounded-xl font-bold text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-200/60 dark:text-gray-400 dark:hover:bg-gray-700 transition-all ml-auto cursor-pointer">
                    បិទ
                </button>
            </div>
        </div>
    </div>
</div>

