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

            <div class="px-7 py-4 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                <div>
                    <h3 class="font-black text-xl text-blue-600 uppercase tracking-tight">កែសម្រួលការកក់បន្ទប់ #<span x-text="currentBooking?.booking_code"></span></h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Update Booking Details</p>
                </div>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
            </div>

            <form @submit.prevent="updateBooking()">
                <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar" x-if="currentBooking">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Customer Select --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">អតិថិជន <span class="text-red-500">*</span></label>
                            <select x-model="currentBooking.customer_id" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email ?? $customer->phone ?? 'Customer' }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Room Select --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">បន្ទប់ <span class="text-red-500">*</span></label>
                            <select x-model="currentBooking.room_id" @change="calculateTotalEdit()" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-price="{{ $room->roomType->base_price ?? 0 }}">
                                    បន្ទប់លេខ {{ $room->room_number }} - {{ $room->roomType->name ?? 'Standard' }} (${{ number_format($room->roomType->base_price ?? 0, 2) }}/យប់)
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Check-in Date --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចូលស្នាក់នៅ <span class="text-red-500">*</span></label>
                            <input type="date" x-model="currentBooking.check_in_date" @change="calculateTotalEdit()" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                        </div>

                        {{-- Check-out Date --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ថ្ងៃចាកចេញ <span class="text-red-500">*</span></label>
                            <input type="date" x-model="currentBooking.check_out_date" @change="calculateTotalEdit()" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                        </div>

                        {{-- Number of Guests --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ចំនួនភ្ញៀវ <span class="text-red-500">*</span></label>
                            <input type="number" min="1" x-model="currentBooking.number_of_guests" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-bold">
                        </div>

                        {{-- Total Price --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">តម្លៃសរុប ($) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" x-model="currentBooking.total_price" required class="w-full h-14 px-6 rounded-2xl border border-blue-100 dark:border-blue-900 bg-blue-50/50 dark:bg-blue-950/20 text-blue-600 dark:text-blue-400 font-black text-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        </div>

                        {{-- Payment Status --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាពបង់ប្រាក់ <span class="text-red-500">*</span></label>
                            <select x-model="currentBooking.payment_status" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
                                <option value="pending">Pending (រង់ចាំ)</option>
                                <option value="paid">Paid (បានបង់)</option>
                                <option value="failed">Failed (បរាជ័យ)</option>
                            </select>
                        </div>

                        {{-- Booking Status --}}
                        <div class="space-y-2">
                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">ស្ថានភាពការកក់ <span class="text-red-500">*</span></label>
                            <select x-model="currentBooking.booking_status" required class="w-full h-14 px-5 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-medium">
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
                        <textarea x-model="currentBooking.notes" rows="3" class="w-full p-4 rounded-2xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>
                </div>

                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-800/50 flex justify-end items-center gap-4 border-t dark:border-gray-800">
                    <button type="button" @click="showEditModal = false" class="px-6 h-11 rounded-2xl font-bold text-sm text-gray-400 hover:text-rose-500 transition-all">បោះបង់</button>
                    <button type="submit" :disabled="submitting" class="px-8 h-11 bg-orange-500 hover:bg-orange-600 text-white rounded-2xl font-bold text-sm shadow-lg shadow-orange-500/20 active:scale-95 transition-all">
                        <span x-text="!submitting ? 'ធ្វើបច្ចុប្បន្នភាព' : 'កំពុងដំណើរការ...'"></span>
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