{{-- Add Booking Modal --}}
<div x-show="showAddModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showAddModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">បង្កើតការកក់បន្ទប់ថ្មី</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Create New Room Reservation</p>
                </div>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <form @submit.prevent="saveBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Walk-In Customer Info --}}
                        <div class="space-y-2 md:col-span-2 p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/20">
                            <span class="text-xs font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider block mb-2">ព័ត៌មានអតិថិជន Walk-in (អតិថិជនមកផ្ទាល់)</span>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">ឈ្មោះអតិថិជន <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="newBooking.customer_name" required placeholder="ឧ. កក្កដា ទេព"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase text-gray-400 mb-1">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="newBooking.customer_phone" required placeholder="ឧ. 096 XXXXXXX"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none text-sm font-medium">
                                </div>
                            </div>
                        </div>

                        {{-- Room Select --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">បន្ទប់ <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select x-model="newBooking.room_id" @change="calculateTotalAdd()" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-medium">
                                    <option value="" disabled selected>ជ្រើសរើសបន្ទប់</option>
                                    @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" data-price="{{ $room->roomType->base_price ?? 0 }}">
                                        បន្ទប់លេខ {{ $room->room_number }} - {{ $room->roomType->name ?? 'Standard' }} (${{ number_format($room->roomType->base_price ?? 0, 2) }}/យប់)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Check-in Date --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចូលស្នាក់នៅ <span class="text-red-500">*</span></label>
                            <input type="date" x-model="newBooking.check_in_date" min="{{ date('Y-m-d') }}" @change="if(newBooking.check_out_date < newBooking.check_in_date) newBooking.check_out_date = newBooking.check_in_date; calculateTotalAdd();" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                        </div>

                        {{-- Check-out Date --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចាកចេញ <span class="text-red-500">*</span></label>
                            <input type="date" x-model="newBooking.check_out_date" :min="newBooking.check_in_date ? newBooking.check_in_date : '{{ date('Y-m-d') }}'" @change="calculateTotalAdd()" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                        </div>

                        {{-- Number of Guests --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ចំនួនភ្ញៀវ <span class="text-red-500">*</span></label>
                            <input type="number" min="1" x-model="newBooking.number_of_guests" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                        </div>

                        {{-- Total Price --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃសរុប ($) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" x-model="newBooking.total_price" required class="w-full h-14 px-6 rounded-2xl border border-emerald-100 dark:border-emerald-900 bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 font-black text-xl focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>

                        {{-- Payment Status --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាពបង់ប្រាក់ <span class="text-red-500">*</span></label>
                            <select x-model="newBooking.payment_status" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                <option value="pending">Pending (រង់ចាំ)</option>
                                <option value="paid">Paid (បានបង់)</option>
                                <option value="failed">Failed (បរាជ័យ)</option>
                            </select>
                        </div>

                        {{-- Booking Status --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាពការកក់ <span class="text-red-500">*</span></label>
                            <select x-model="newBooking.booking_status" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                <option value="pending">Pending (រង់ចាំ)</option>
                                <option value="confirmed">Confirmed (បានបញ្ជាក់)</option>
                                <option value="completed">Completed (រួចរាល់)</option>
                                <option value="cancelled">Cancelled (បានបោះបង់)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div class="space-y-2">
                        <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">មតិផ្សេងៗ</label>
                        <textarea x-model="newBooking.notes" rows="3" class="w-full p-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="ព័ត៌មានបន្ថែម..."></textarea>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showAddModal = false" class="px-6 h-11 rounded-2xl font-bold text-sm text-gray-400 hover:text-rose-500 transition-all">បោះបង់</button>
                    <button type="submit" :disabled="submitting" class="px-8 h-11 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-500/20 active:scale-95 transition-all">
                        <span x-text="!submitting ? 'រក្សាទុក' : 'កំពុងដំណើរការ...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Booking Modal --}}
<div x-show="showEditModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            {{-- HEADER --}}
            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">
                        <span x-text="editType === 'meeting' ? 'កែសម្រួលព័ត៌មានកក់សាលប្រជុំ' : 'កែសម្រួលព័ត៌មានការកក់បន្ទប់'"></span> #<span x-text="editingBooking?.booking_code || ''"></span>
                    </h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest" x-text="editType === 'meeting' ? 'Update Meeting Booking Information' : 'Update Room Booking Information'"></p>
                </div>
                <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
            </div>

            <form @submit.prevent="updateBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar">

                    {{-- 🏨 A. ROOM BOOKING EDIT FORM --}}
                    <template x-if="editType === 'room'">
                        <div class="space-y-6">
                            {{-- CUSTOMER INFO --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">ឈ្មោះអតិថិជន <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="editingBooking.customer_name" required placeholder="ឈ្មោះពេញ"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs">
                                </div>

                                <div class="space-y-2">
                                    <label class="block text-[11px] font-black uppercase text-gray-400 ml-2 tracking-widest">លេខទូរស័ព្ទ <span class="text-red-500">*</span></label>
                                    <input type="text" x-model="editingBooking.customer_phone" required placeholder="096 XXXXXXX"
                                        class="w-full h-12 px-4 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all font-bold text-xs">
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

                            {{-- ROOM SELECTION / SWITCH ROOM --}}
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
                                        </div>
                                    </div>
                                </div>
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
                    </template>

                    {{-- 🏛️ B. MEETING BOOKING EDIT FORM --}}
                    <template x-if="editType === 'meeting'">
                        <div class="space-y-6">
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
                    </template>

                </div>

                {{-- FOOTER --}}
                <div class="px-7 py-3 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-8 h-10 font-black text-sm uppercase tracking-[0.2em] text-gray-400 hover:text-red-500 transition-all italic cursor-pointer">បោះបង់</button>
                    <button type="submit" :disabled="submitting" :class="submitting ? 'opacity-50 cursor-not-allowed' : ''" 
                        class="px-8 h-10 text-white font-black text-sm uppercase tracking-[0.2em] rounded-2xl shadow-xl transition-all cursor-pointer"
                        :class="editType === 'meeting' ? 'bg-purple-600 hover:bg-purple-700 shadow-purple-500/20' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/20'">
                        <span x-text="!submitting ? 'រក្សាទុកការកែប្រែ' : 'កំពុងរក្សាទុក...'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Booking Detail Modal --}}
<div x-show="showDetailModal" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 py-10">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="showDetailModal = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-2xl relative border-none overflow-hidden transition-all"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ព័ត៌មានលម្អិតនៃការកក់ #<span x-text="currentBooking?.booking_code"></span></h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Reservation Full Specification</p>
                </div>
                <button @click="showDetailModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar" x-if="currentBooking">
                
                {{-- Customer & Room Card --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider mb-2">ព័ត៌មានអតិថិជន</p>
                        <h4 class="font-bold text-base dark:text-white" x-text="currentBooking?.customer?.name || 'N/A'"></h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="currentBooking?.customer?.email || currentBooking?.customer?.phone || 'គ្មានអ៊ីមែល/លេខទូរស័ព្ទ'"></p>
                    </div>

                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                        <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider mb-2">ព័ត៌មានបន្ទប់</p>
                        <h4 class="font-bold text-base text-blue-600 dark:text-blue-400">
                            បន្ទប់លេខ <span x-text="currentBooking?.room?.room_number || 'N/A'"></span>
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" x-text="(currentBooking?.room?.room_type?.name || '') + (currentBooking?.room?.room_type?.hotel?.name ? ' - ' + currentBooking?.room?.room_type?.hotel?.name : '')"></p>
                    </div>
                </div>

                {{-- Dates & Guests Card --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 bg-blue-50/50 dark:bg-blue-900/10 rounded-2xl border border-blue-100 dark:border-blue-900/20">
                        <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-wider mb-1">ថ្ងៃចូលស្នាក់នៅ</p>
                        <p class="text-sm font-black dark:text-white" x-text="formatDisplayDate(currentBooking?.check_in_date)"></p>
                    </div>

                    <div class="p-4 bg-rose-50/50 dark:bg-rose-900/10 rounded-2xl border border-rose-100 dark:border-rose-900/20">
                        <p class="text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-wider mb-1">ថ្ងៃចាកចេញ</p>
                        <p class="text-sm font-black dark:text-white" x-text="formatDisplayDate(currentBooking?.check_out_date)"></p>
                    </div>

                    <div class="p-4 bg-purple-50/50 dark:bg-purple-900/10 rounded-2xl border border-purple-100 dark:border-purple-900/20">
                        <p class="text-[10px] font-black text-purple-600 dark:text-purple-400 uppercase tracking-wider mb-1">ចំនួនភ្ញៀវ</p>
                        <p class="text-sm font-black dark:text-white" x-text="(currentBooking?.number_of_guests || 1) + ' នាក់'"></p>
                    </div>
                </div>

                {{-- Payment & Total Price --}}
                <div class="p-5 bg-emerald-50/60 dark:bg-emerald-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">តម្លៃសរុបដែលត្រូវបង់</p>
                        <div class="flex items-baseline gap-2">
                            <span class="text-3xl font-black text-emerald-600 dark:text-emerald-400" x-text="'$' + parseFloat(currentBooking?.total_price || 0).toFixed(2)"></span>
                            <span class="text-xs font-bold text-gray-400" x-text="'(~ ' + (parseFloat(currentBooking?.total_price || 0) * {{ $khrRate }}).toLocaleString() + ' ៛)'"></span>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">ស្ថានភាពបង់ប្រាក់</p>
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase"
                            :class="{
                                'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400': currentBooking?.payment_status === 'paid',
                                'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400': currentBooking?.payment_status === 'failed',
                                'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400': currentBooking?.payment_status === 'pending'
                            }"
                            x-text="currentBooking?.payment_status === 'paid' ? 'បានបង់ប្រាក់' : (currentBooking?.payment_status === 'failed' ? 'បរាជ័យ' : 'រង់ចាំបង់')">
                        </span>
                    </div>
                </div>

                {{-- Status Change Action Buttons --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">ប្តូរស្ថានភាពការកក់រហ័ស (Quick Status Update)</p>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="quickUpdateStatus(currentBooking.id, 'confirmed')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                            :class="currentBooking?.booking_status === 'confirmed' ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400'">
                            <i class="fas fa-check-circle text-xs"></i> Confirm (បញ្ជាក់)
                        </button>

                        <button type="button" @click="quickUpdateStatus(currentBooking.id, 'completed')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                            :class="currentBooking?.booking_status === 'completed' ? 'bg-emerald-600 text-white shadow-md' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400'">
                            <i class="fas fa-flag-checkered text-xs"></i> Complete (រួចរាល់)
                        </button>

                        <button type="button" @click="quickUpdateStatus(currentBooking.id, 'cancelled')"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5"
                            :class="currentBooking?.booking_status === 'cancelled' ? 'bg-rose-600 text-white shadow-md' : 'bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-900/20 dark:text-rose-400'">
                            <i class="fas fa-times-circle text-xs"></i> Cancel (បោះបង់)
                        </button>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="space-y-2">
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-wider italic">មតិផ្សេងៗ</p>
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl text-sm text-gray-600 dark:text-gray-300 italic border-l-4 border-blue-500"
                        x-text="currentBooking?.notes || 'មិនមានចំណាំឡើយ'"></div>
                </div>

            </div>

            <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-between items-center border-t dark:border-gray-800">
                <template x-if="currentBooking?.id">
                    <a :href="`/admin/bookings/invoice/${currentBooking.id}`" target="_blank"
                        class="px-5 h-10 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs flex items-center gap-2 shadow-md transition-all">
                        <i class="fas fa-file-invoice"></i> ទាញយកវិក្កយបត្រ PDF
                    </a>
                </template>
                <div class="flex items-center gap-2">
                    <button type="button" @click="showDetailModal = false" class="px-6 h-10 rounded-xl font-bold text-xs text-gray-400 hover:text-gray-600">បិទ</button>
                    <button type="button" @click="showDetailModal = false; openEditModal(currentBooking)" class="px-6 h-10 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-xs shadow-md transition-all flex items-center gap-1.5">
                        <i class="fas fa-edit text-xs"></i> កែសម្រួល
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>