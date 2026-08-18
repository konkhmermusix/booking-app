{{-- 1. ADD ROOM BOOKING MODAL (WALK-IN) --}}
<div x-show="showAddModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">

        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បន្ថែមកក់បន្ទប់ថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Customer, Room & Date Details</p>
                </div>
                <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="saveBooking()">
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
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs appearance-none cursor-pointer shadow-xs">
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
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs shadow-xs">
                                <template x-if="errors.check_in"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.check_in[0]"></span></template>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃចេញ (Check-out) <span class="text-red-500">*</span></label>
                                <input type="date" x-model="newBooking.check_out" :min="getMinCheckOutDate(newBooking.check_in)" @change="handleCheckOutChange()"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs shadow-xs">
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
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">២. ជ្រើសរើសបន្ទប់ដែលទំនេរ<span class="text-red-500">*</span></h4>
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
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">៣. ព័ត៌មានអតិថិជន</h4>
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

                    {{-- 💳 STEP 4: PAYMENT SECTION --}}
                    <div class="p-5 rounded-2xl bg-blue-50/50 dark:bg-gray-800/60 border border-blue-100 dark:border-gray-700 space-y-4">
                        <div class="flex items-center gap-2 border-b border-blue-100 dark:border-gray-700 pb-2">
                            <i class="fas fa-wallet text-blue-600 dark:text-blue-400"></i>
                            <h4 class="text-xs font-black uppercase tracking-wider text-blue-900 dark:text-blue-200">៤. ព័ត៌មានទូទាត់ប្រាក់ </h4>
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

                        {{-- លេខប្រតិបត្តិការ (Transaction ID) --}}
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

{{-- 2. DETAIL ROOM BOOKING MODAL --}}
<div x-show="showDetailModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិតការកក់បន្ទប់</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest" x-text="`Code: #${selectedBooking.booking_code || ''}`"></p>
                </div>
                <button type="button" @click="showDetailModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar text-sm">
                {{-- STATUS & SOURCE --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 bg-gray-50 dark:bg-gray-800 rounded-2xl border-none">
                    <div>
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">ប្រភពការកក់</span>
                        <span class="font-black text-sm text-blue-600 dark:text-blue-400 mt-1 block"
                            x-text="selectedBooking.booking_type === 'online' || (selectedBooking.user_id && !selectedBooking.customer_name) ? 'កក់តាមអនឡាញ' : 'កក់ផ្ទាល់'"></span>
                    </div>
                    <div>
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">សណ្ឋាគារ</span>
                        <span class="font-black text-sm text-gray-800 dark:text-gray-200 mt-1 block"
                            x-text="selectedBooking.hotel?.name || 'សណ្ឋាគារ ភីអេនធី ផាលេស'"></span>
                    </div>
                    <div class="text-right sm:text-right">
                        <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">ស្ថានភាពកក់</span>
                        <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase mt-1 inline-block"
                            :class="{
                                'bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400': selectedBooking.status === 'pending',
                                'bg-blue-100 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400': selectedBooking.status === 'confirmed',
                                'bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400': selectedBooking.status === 'completed',
                                'bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400': selectedBooking.status === 'cancelled'
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
                    <h4 class="text-[11px] font-black uppercase tracking-widest text-gray-400">ព័ត៌មានអតិថិជន</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-5 rounded-2xl bg-gray-50 dark:bg-gray-800">
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">ឈ្មោះអតិថិជន:</span>
                            <span class="font-black dark:text-white text-base mt-0.5 block" x-text="selectedBooking.customer_name || (selectedBooking.user?.name ?? 'N/A')"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">លេខទូរស័ព្ទ:</span>
                            <span class="font-black dark:text-white text-base mt-0.5 block" x-text="selectedBooking.customer_phone || (selectedBooking.user?.phone ?? 'N/A')"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">អ៊ីមែល:</span>
                            <span class="font-bold dark:text-gray-200 text-xs mt-0.5 block truncate" x-text="selectedBooking.customer_email || (selectedBooking.user?.email ?? 'N/A')"></span>
                        </div>
                    </div>
                </div>

                {{-- ROOM & DATES INFO --}}
                <div class="space-y-2">
                    <h4 class="text-[11px] font-black uppercase tracking-widest text-gray-400">ព័ត៌មានបន្ទប់ និងកាលបរិច្ឆេទ</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 p-5 rounded-2xl bg-gray-50 dark:bg-gray-800">
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">លេខបន្ទប់ដែលបានកក់:</span>
                            <template x-if="selectedBooking.details && selectedBooking.details.length > 0">
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    <template x-for="d in selectedBooking.details" :key="d.id">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded-xl text-xs font-bold border border-blue-200/50">
                                            <i class="fa-solid fa-bed text-[10px]"></i>
                                            <span x-text="`បន្ទប់ ${d.room ? d.room.room_number : 'N/A'}`"></span>
                                            <span class="text-[10px] text-gray-500 font-normal" x-text="d.room_type ? `(${d.room_type.name})` : (d.room && d.room.room_type ? `(${d.room.room_type.name})` : '')"></span>
                                        </span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!selectedBooking.details || selectedBooking.details.length === 0">
                                <div>
                                    <span class="font-black text-blue-600 dark:text-blue-400 text-base mt-0.5 block" x-text="`បន្ទប់ ${selectedBooking.room ? selectedBooking.room.room_number : 'N/A'}`"></span>
                                    <span class="text-xs text-gray-400 block mt-0.5" x-text="selectedBooking.room && selectedBooking.room.room_type ? selectedBooking.room.room_type.name : ''"></span>
                                </div>
                            </template>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">ថ្ងៃចូលស្នាក់នៅ:</span>
                            <span class="font-black text-emerald-600 dark:text-emerald-400 text-sm mt-0.5 block" x-text="formatDateDisplay(selectedBooking.check_in)"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">ថ្ងៃចាកចេញ:</span>
                            <span class="font-black text-rose-600 dark:text-rose-400 text-sm mt-0.5 block" x-text="formatDateDisplay(selectedBooking.check_out)"></span>
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 font-bold block">រយៈពេលស្នាក់នៅ:</span>
                            <span class="font-black text-blue-600 dark:text-blue-400 text-sm mt-0.5 block" x-text="`${selectedBooking.check_in && selectedBooking.check_out ? Math.max(1, Math.ceil(Math.abs(new Date(selectedBooking.check_out) - new Date(selectedBooking.check_in)) / (1000 * 60 * 60 * 24))) : 1} យប់`"></span>
                        </div>
                    </div>
                </div>

                {{-- PAYMENT & TOTAL --}}
                <div class="p-6 rounded-2xl bg-emerald-50/60 dark:bg-emerald-950/20 border border-emerald-500/20 space-y-4">
                    <div class="flex items-center justify-between border-b border-emerald-200/50 dark:border-emerald-800/50 pb-3">
                        <span class="text-xs font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300 flex items-center gap-2">
                            <i class="fas fa-wallet text-emerald-600 dark:text-emerald-400"></i> ព័ត៌មាននៃការទូទាត់ប្រាក់
                        </span>
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

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                        <div>
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">វិធីសាស្ត្របង់ប្រាក់</span>
                            <span class="font-black text-gray-800 dark:text-gray-100 uppercase text-sm mt-1 flex items-center gap-1.5"
                                x-text="['qr', 'bank_transfer', 'khqr'].includes(selectedBooking.payment_method || (selectedBooking.payment ? selectedBooking.payment.method : '')) ? 'ឃ្យូអរកូដ' : 'សាច់ប្រាក់'"></span>
                        </div>
                        <div>
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">លេខប្រតិបត្តិការ (TXN ID)</span>
                            <span class="font-mono font-bold text-gray-700 dark:text-gray-300 text-xs mt-1 block"
                                x-text="selectedBooking.payment?.transaction_id || 'N/A'"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest block">តម្លៃសរុប</span>
                            <span class="font-black text-3xl text-emerald-600 dark:text-emerald-400 mt-1 block" x-text="`$${parseFloat(selectedBooking.total_price || 0).toFixed(2)}`"></span>
                        </div>
                    </div>

                    {{-- EMBEDDED SLIP IMAGE PREVIEW & LIGHTBOX TRIGGER --}}
                    <template x-if="selectedBooking.payment && selectedBooking.payment.payment_slip">
                        <div class="pt-4 border-t border-emerald-200/60 dark:border-emerald-800/60 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-wider text-emerald-800 dark:text-emerald-300 flex items-center gap-1.5">
                                    <i class="fas fa-receipt text-emerald-600 dark:text-emerald-400"></i> រូបភាពបង្កាន់ដៃបង់ប្រាក់:
                                </span>
                                <button type="button" @click="viewSlip(selectedBooking.payment.payment_slip)"
                                    class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition shadow-xs cursor-pointer">
                                    <i class="fas fa-expand text-[10px]"></i> ពង្រីករូបភាពពេញអេក្រង់
                                </button>
                            </div>

                            <div class="relative group/slip cursor-pointer overflow-hidden rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-white dark:bg-gray-900 p-2 flex justify-center max-h-64"
                                @click="viewSlip(selectedBooking.payment.payment_slip)">
                                <img :src="getSlipUrl(selectedBooking.payment.payment_slip)" class="max-h-60 w-auto object-contain rounded-xl shadow-xs group-hover/slip:scale-105 transition-transform duration-300" alt="Payment Slip Image">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/slip:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-2xl">
                                    <span class="px-4 py-2 bg-white/90 dark:bg-gray-900/90 text-emerald-700 dark:text-emerald-400 font-extrabold text-xs rounded-xl shadow-md flex items-center gap-1.5">
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
            <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center border-t dark:border-gray-800">
                <a :href="`/admin/bookings/invoice/${selectedBooking.id}`" target="_blank" class="px-6 h-10 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider rounded-2xl shadow-lg shadow-emerald-500/20 flex items-center gap-2 transition-all">
                    <i class="fas fa-file-invoice"></i> ទាញយកវិក្កយបត្រ PDF
                </a>
                <button type="button" @click="showDetailModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បិទ</button>
            </div>
        </div>
    </div>
</div>

{{-- 3. EDIT ROOM BOOKING MODAL --}}
<div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">កែសម្រួលព័ត៌មានការកក់</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Customer, Status & Date Details</p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="updateBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    {{-- CUSTOMER INFO --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឈ្មោះអតិថិជន <span class="text-red-500">*</span></label>
                            <input type="text" x-model="editingBooking.customer_name" required placeholder="ឈ្មោះពេញ"
                                class="w-full h-12 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs">
                            <template x-if="errors.customer_name"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.customer_name[0]"></span></template>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                            <input type="text" x-model="editingBooking.customer_phone" required placeholder="096 XXXXXXX"
                                class="w-full h-12 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs">
                            <template x-if="errors.customer_phone"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.customer_phone[0]"></span></template>
                        </div>
                    </div>

                    {{-- DATES & DURATION --}}
                    <div class="p-5 rounded-2xl bg-blue-50/40 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 space-y-4">
                        <h4 class="text-xs font-black uppercase tracking-wider text-blue-900 dark:text-blue-200">កាលបរិច្ឆេទស្នាក់នៅ</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ចំនួនថ្ងៃស្នាក់នៅ</label>
                                <div class="relative">
                                    <select x-model="editingBooking.duration" @change="handleEditDurationChange()"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs appearance-none cursor-pointer">
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
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃចូល (Check-in)</label>
                                <input type="date" x-model="editingBooking.check_in" @change="handleEditDateOrDurationChange()"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-xs">
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[11px] font-black uppercase text-gray-500 dark:text-gray-400 ml-1 tracking-widest">ថ្ងៃចេញ (Check-out)</label>
                                <input type="date" x-model="editingBooking.check_out" @change="handleEditDateOrDurationChange()"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-xs">
                            </div>
                        </div>
                    </div>

                    {{-- 🏨 ROOM SELECTION / SWITCH ROOM --}}
                    <div class="p-5 rounded-2xl bg-gray-50/80 dark:bg-gray-800/40 border border-gray-100 dark:border-gray-800 space-y-3" 
                        x-data="{ openEditRoomSearch: false, searchEditRoomQuery: '' }">
                        <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-bed text-blue-600 dark:text-blue-400"></i>
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-900 dark:text-white">ជ្រើសរើស/ប្តូរបន្ទប់ <span class="text-red-500">*</span></h4>
                            </div>
                            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider" x-text="`បានជ្រើស: ${editingBooking.room_ids ? editingBooking.room_ids.length : 0} បន្ទប់`"></span>
                        </div>
                        
                        <div class="relative">
                            {{-- Trigger Button / Selected Badges --}}
                            <div @click="openEditRoomSearch = !openEditRoomSearch"
                                class="w-full min-h-[3.5rem] p-3 px-5 rounded-2xl bg-white dark:bg-gray-900 dark:text-white border-none focus-within:ring-2 focus-within:ring-blue-500 flex items-center justify-between cursor-pointer transition-all gap-2 flex-wrap shadow-xs">
                                
                                <div class="flex flex-wrap items-center gap-1.5 flex-1">
                                    <template x-if="!editingBooking.room_ids || editingBooking.room_ids.length === 0">
                                        <span class="font-bold text-sm text-gray-400">ជ្រើសរើសបន្ទប់...</span>
                                    </template>

                                    <template x-for="rId in editingBooking.room_ids" :key="rId">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-sm">
                                            <i class="fa-solid fa-bed text-[10px]"></i>
                                            <span x-text="rooms.find(r => r.id == rId) ? `បន្ទប់ ${rooms.find(r => r.id == rId).room_number} ($${rooms.find(r => r.id == rId).room_type?.base_price})` : ''"></span>
                                            <button type="button" @click.stop="toggleEditRoomSelection(rId)" class="ml-1 text-white/80 hover:text-white">&times;</button>
                                        </span>
                                    </template>
                                </div>

                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="openEditRoomSearch ? 'rotate-180 text-blue-500' : ''"></i>
                            </div>

                            {{-- Dropdown --}}
                            <div x-show="openEditRoomSearch" @click.outside="openEditRoomSearch = false" x-cloak
                                x-transition:enter="ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 top-full mt-2 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 z-50 overflow-hidden">
                                
                                <div class="p-3 border-b border-gray-100 dark:border-gray-800 relative bg-gray-50/50 dark:bg-gray-800/50">
                                    <input type="text" x-model="searchEditRoomQuery" placeholder="ស្វែងរកលេខបន្ទប់ ឬប្រភេទបន្ទប់..."
                                        class="w-full h-10 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 text-xs dark:text-white outline-none focus:ring-2 focus:ring-blue-500 font-bold">
                                </div>

                                <div class="max-h-60 overflow-y-auto p-2 space-y-1 custom-scrollbar">
                                    <template x-for="room in rooms.filter(r => (!isRoomBusy(r.id) || (editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(r.id)))) && (!searchEditRoomQuery || `${r.room_number} ${r.room_type?.name}`.toLowerCase().includes(searchEditRoomQuery.toLowerCase())))" :key="room.id">
                                        <div @click="toggleEditRoomSelection(room.id)"
                                            :class="{
                                                'bg-blue-600 text-white font-bold': editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(room.id)),
                                                'hover:bg-blue-50 dark:hover:bg-gray-800 text-gray-800 dark:text-gray-200': !(editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(room.id)))
                                            }"
                                            class="px-4 py-3 rounded-xl text-xs flex items-center justify-between cursor-pointer transition">
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" :checked="editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(room.id))" class="rounded text-blue-600 focus:ring-0 mr-1 pointer-events-none">
                                                <i class="fa-solid fa-bed text-sm" :class="(editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(room.id))) ? 'text-white' : 'text-blue-500'"></i>
                                                <span class="font-bold" x-text="`បន្ទប់ ${room.room_number}`"></span>
                                                <span class="opacity-80" x-text="`(${room.room_type?.name})`"></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <template x-if="editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(room.id))">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-blue-500 text-white">កំពុងជ្រើស</span>
                                                </template>
                                                <template x-if="!(editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(room.id)))">
                                                    <span class="px-2 py-0.5 rounded text-[10px] font-black bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300">ទំនេរ</span>
                                                </template>
                                                <span class="font-extrabold" x-text="`$${room.room_type?.base_price}/យប់`"></span>
                                            </div>
                                        </div>
                                    </template>

                                    <div x-show="rooms.filter(r => (!isRoomBusy(r.id) || (editingBooking.room_ids && editingBooking.room_ids.some(id => String(id) === String(r.id)))) && (!searchEditRoomQuery || `${r.room_number} ${r.room_type?.name}`.toLowerCase().includes(searchEditRoomQuery.toLowerCase()))).length === 0" 
                                        class="p-4 text-center text-xs text-gray-400 font-bold italic">
                                        <i class="fa-solid fa-circle-exclamation mr-1 text-amber-500"></i> ពុំមានបន្ទប់ទំនេរផ្សេងទៀតឡើយ
                                    </div>
                                </div>
                            </div>
                        </div>
                        <template x-if="errors.room_id"><span class="text-[10px] text-red-500 ml-2 block" x-text="errors.room_id[0]"></span></template>
                    </div>

                    {{-- STATUS & PAYMENT --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ស្ថានភាពការកក់</label>
                            <div class="relative">
                                <select x-model="editingBooking.status"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-xs appearance-none">
                                    <option value="pending">រង់ចាំពិនិត្យ</option>
                                    <option value="confirmed">បានបញ្ជាក់</option>
                                    <option value="completed">បានបញ្ចប់</option>
                                    <option value="cancelled">បោះបង់</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ស្ថានភាពទូទាត់</label>
                            <div class="relative">
                                <select x-model="editingBooking.payment_status"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-xs appearance-none">
                                    <option value="paid">បានបង់រួច</option>
                                    <option value="pending">មិនទាន់បង់</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">វិធីសាស្ត្របង់ប្រាក់</label>
                            <div class="relative">
                                <select x-model="editingBooking.payment_method"
                                    class="w-full h-12 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold text-xs appearance-none">
                                    <option value="cash">សាច់ប្រាក់</option>
                                    <option value="qr">ឃ្យូអរកូដ</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TOTAL PRICE --}}
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 flex justify-between items-center">
                        <span class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400">តម្លៃសរុប:</span>
                        <input type="number" step="0.01" x-model="editingBooking.total_price" class="w-36 h-10 px-3 text-right rounded-xl font-black text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-900 border-none text-lg">
                    </div>

                    {{-- SPECIAL REQUESTS --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest italic">មតិផ្សេងៗ</label>
                        <textarea x-model="editingBooking.special_requests" rows="3"
                            class="w-full p-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-xs font-medium"></textarea>
                    </div>
                </div>

                {{-- FOOTER --}}
                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បោះបង់</button>
                    <button type="submit" :disabled="loading" class="px-8 h-10 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl shadow-blue-500/20 active:scale-95 transition-all cursor-pointer">
                        <span x-text="!loading ? 'រក្សាទុកកែប្រែ' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- 4. PAYMENT SLIP LIGHTBOX MODAL --}}
<div x-show="showSlipModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-md transition-opacity" @click="showSlipModal = false"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-lg w-full relative border border-gray-100 dark:border-gray-800 p-6 space-y-4 z-10 transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-3">
                <h3 class="text-base font-black text-gray-800 dark:text-white flex items-center gap-2">
                    <i class="fas fa-file-image text-emerald-500"></i> មើលបង្កាន់ដៃបង់ប្រាក់លម្អិត                </h3>
                <button type="button" @click="showSlipModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>
            
            <div class="flex justify-center bg-gray-100 dark:bg-gray-800/50 p-3 rounded-2xl overflow-hidden min-h-[250px] items-center">
                <template x-if="selectedSlip">
                    <img :src="selectedSlip" class="max-h-[480px] w-auto object-contain rounded-xl shadow-md transition-all hover:scale-105" alt="Payment Slip Image">
                </template>
                <template x-if="!selectedSlip">
                    <p class="text-xs text-gray-400 font-bold">គ្មានរូបភាពបង្កាន់ដៃបង់ប្រាក់នៅឡើយ</p>
                </template>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a :href="selectedSlip" target="_blank" download class="px-5 h-10 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold text-xs rounded-xl flex items-center gap-2 transition shadow-md shadow-emerald-500/20">
                    <i class="fas fa-download"></i> ទាញយករូបភាព
                </a>
                <button type="button" @click="showSlipModal = false" class="px-5 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-extrabold text-xs rounded-xl transition">
                    បិទ
                </button>
            </div>
        </div>
    </div>
</div>
