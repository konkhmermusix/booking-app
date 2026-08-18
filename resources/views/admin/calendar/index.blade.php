@extends('layouts.admin')
@section('title', 'កាលវិភាគគ្រប់គ្រងការកក់សណ្ឋាគារ')
@section('content')

<div class="p-3 sm:p-4 space-y-4" x-data="calendarManager()" x-init="init()">
    {{-- Top Control Bar & KPI Stats --}}
    <div class="bg-white dark:bg-gray-900 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 space-y-4">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">កាលវិភាគគ្រប់គ្រងការកក់</h2>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">គ្រប់គ្រងបន្ទប់ស្នាក់នៅ និងសាលប្រជុំតាមកាលវិភាគប្រចាំថ្ងៃបានយ៉ាងងាយស្រួល</p>
            </div>

            {{-- Date & Month Navigation --}}
            <div class="flex flex-wrap items-center gap-2">
                <button onclick="goToToday()" class="px-4 h-10 text-xs font-bold bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300 rounded-2xl hover:bg-blue-100 transition shadow-xs flex items-center gap-1.5">
                    ថ្ងៃនេះ
                </button>

                <div class="flex items-center bg-gray-100 dark:bg-gray-800 p-1 rounded-2xl h-10 dark:border-gray-700">
                    <button onclick="changeMonth(-1)" class="w-10 h-full text-xs text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 rounded-xl transition flex items-center justify-center" title="ខែមុន">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span id="current-month-label" class="text-xs font-black px-4 text-gray-800 dark:text-gray-200 min-w-[130px] text-center">
                        {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                    </span>
                    <button onclick="changeMonth(1)" class="w-10 h-full text-xs text-gray-600 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-700 rounded-xl transition flex items-center justify-center" title="ខែបន្ទាប់">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>

                {{-- Room Category Tabs --}}
                <div class="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-2xl h-10 items-center">
                    <button @click="currentTab = 'stay'"
                        :class="currentTab === 'stay' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900'"
                        class="px-4 h-full rounded-xl transition-all flex items-center justify-center text-xs font-bold gap-2">
                        បន្ទប់ស្នាក់នៅ
                    </button>
                    <button @click="currentTab = 'meeting'"
                        :class="currentTab === 'meeting' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900'"
                        class="px-4 h-full rounded-xl transition-all flex items-center justify-center text-xs font-bold gap-2">
                        សាលប្រជុំ
                    </button>
                </div>

                {{-- Quick Add Booking Button --}}
                <button @click="currentTab === 'stay' ? openAddModalForRoom() : openAddModalForMeeting()" 
                    :class="currentTab === 'stay' ? 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/20' : 'bg-purple-600 hover:bg-purple-700 shadow-purple-500/20'"
                    class="h-10 px-4 text-white rounded-2xl shadow-md text-xs font-bold flex items-center gap-2 transition-all active:scale-95 cursor-pointer shrink-0">
                    <i class="fas fa-plus-circle"></i>
                    <span x-text="currentTab === 'stay' ? 'បន្ថែមកក់បន្ទប់' : 'បន្ថែមកក់សាល'"></span>
                </button>
            </div>
        </div>

        {{-- KPI Overview Badges --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-3 border-t border-gray-100 dark:border-gray-800">
            <div class="p-3 bg-blue-50/70 dark:bg-blue-900/20 rounded-2xl border border-blue-100 dark:border-blue-900/30 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold text-base shadow-sm">
                    <i class="fas fa-door-closed"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-400">បន្ទប់សរុប</p>
                    <p class="text-base font-black text-blue-700 dark:text-blue-300">{{ $stats['total_rooms'] ?? 0 }} បន្ទប់</p>
                </div>
            </div>

            <div class="p-3 bg-emerald-50/70 dark:bg-emerald-900/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-base shadow-sm">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-400">ទំនេរថ្ងៃនេះ</p>
                    <p class="text-base font-black text-emerald-700 dark:text-emerald-300">{{ $stats['available_today'] ?? 0 }} បន្ទប់</p>
                </div>
            </div>

            <div class="p-3 bg-amber-50/70 dark:bg-amber-900/20 rounded-2xl border border-amber-100 dark:border-amber-900/30 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-base shadow-sm">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-400">កំពុងស្នាក់នៅ</p>
                    <p class="text-base font-black text-amber-700 dark:text-amber-300">{{ $stats['occupied_today'] ?? 0 }} បន្ទប់</p>
                </div>
            </div>

            <div class="p-3 bg-purple-50/70 dark:bg-purple-900/20 rounded-2xl border border-purple-100 dark:border-purple-900/30 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold text-base shadow-sm">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase text-gray-400 dark:text-gray-400">រង់ចាំការបញ្ជាក់</p>
                    <p class="text-base font-black text-purple-700 dark:text-purple-300">{{ $stats['pending_count'] ?? 0 }} កំណត់</p>
                </div>
            </div>
        </div>

        {{-- Quick Scroll Shortcuts & Status Legend --}}
        <div class="flex flex-wrap items-center justify-between gap-3 text-xs font-semibold pt-2 border-t border-gray-100 dark:border-gray-800 text-gray-500 dark:text-gray-400">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-gray-400 font-bold">រមូរតាមថ្ងៃ:</span>
                <button type="button" onclick="scrollCalendarTo('start')" class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition shadow-xs">
                   ដើមខែ
                </button>
                <button type="button" onclick="scrollCalendarTo('today')" class="px-3 py-1 bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-300 rounded-xl font-bold hover:bg-blue-100 transition shadow-xs">
                   ថ្ងៃនេះ
                </button>
                <button type="button" onclick="scrollCalendarTo('end')" class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition shadow-xs">
                   ចុងខែ
                </button>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> ទំនេរ</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-amber-500"></span> រង់ចាំបញ្ជាក់</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-600"></span> បានបញ្ជាក់/ចូលស្នាក់នៅ</span>
            </div>
        </div>
    </div>

    {{-- Main Calendar Table Container --}}
    <div id="calendar-table-container" class="transition-opacity duration-300">
        @include('admin.calendar.partials.calendar_table')
    </div>

    @include('admin.calendar.modals')
</div>

<style>
    /* 🌟 Guaranteed Smooth & Thick Scrollbars */
    .custom-calendar-scrollbar {
        overflow-x: auto !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: auto;
        scrollbar-color: #94a3b8 #f1f5f9;
    }
    .custom-calendar-scrollbar::-webkit-scrollbar {
        height: 12px !important;
        width: 12px !important;
    }
    .custom-calendar-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9 !important;
        border-radius: 8px;
    }
    .custom-calendar-scrollbar::-webkit-scrollbar-thumb {
        background: #94a3b8 !important;
        border-radius: 8px;
        border: 2px solid #f1f5f9;
    }
    .custom-calendar-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b !important;
    }
    .ajax-loading {
        opacity: 0.5;
        pointer-events: none;
        transition: opacity 0.2s ease;
    }
</style>

{{-- 🌟 ALPINE & AJAX SCRIPTS --}}
<script>
    let currentMonth = parseInt('{{ $month }}');
    let currentYear = parseInt('{{ $year }}');

    function calendarManager() {
        return {
            currentTab: 'stay',
            showAddModal: false,
            showMeetingAddModal: false,
            showDetailModal: false,
            loading: false,
            meetingLoading: false,
            actionLoading: false,
            selectedRoom: '',
            selectedMeetingRoom: '',
            selectedDate: '',
            selectedMeetingDate: '',
            selectedBooking: {},
            stayRooms: @js($stayRooms),
            rooms: @js($stayRooms),
            meetingRooms: @js($meetingRooms),
            busyRoomIds: [],
            errors: {},
            min_date: '{{ date("Y-m-d") }}',
            newBooking: {
                customer_name: '',
                customer_phone: '',
                customer_email: '',
                room_id: '',
                room_ids: [],
                duration: '1',
                check_in: '{{ date("Y-m-d") }}',
                check_out: '{{ date("Y-m-d", strtotime("+1 day")) }}',
                payment_status: 'paid',
                payment_method: 'cash',
                transaction_id: '',
                total_price: 0,
                special_requests: ''
            },
            newMeeting: {
                customer_name: '',
                customer_phone: '',
                end_date: '',
                start_time: '08:00',
                end_time: '12:00',
                attendees_count: 10,
                setup_style: 'theater',
                payment_method: 'cash',
                total_price: 0,
                total_hours: 4,
                special_requests: ''
            },

            init() {
                this.$watch('newBooking.room_id', () => this.calculateTotal());
                this.$watch('newBooking.check_in', () => { this.calculateTotal(); this.checkAvailableRooms(); });
                this.$watch('newBooking.check_out', () => { this.calculateTotal(); this.checkAvailableRooms(); });
                this.$watch('selectedMeetingDate', (val) => {
                    if (val && !this.newMeeting.end_date) {
                        this.newMeeting.end_date = val;
                    }
                    this.calculateMeetingTotal();
                });
                
                this.checkAvailableRooms();

                // Auto scroll to today on init
                setTimeout(() => scrollCalendarTo('today'), 300);
            },

            openAddModalForRoom(roomId, dateStr) {
                let checkIn = dateStr || this.formatDate(new Date());
                let d = new Date(checkIn);
                d.setDate(d.getDate() + 1);
                let checkOut = this.formatDate(d);
                
                this.newBooking = {
                    customer_name: '',
                    customer_phone: '',
                    customer_email: '',
                    room_id: roomId || '',
                    room_ids: roomId ? [parseInt(roomId)] : [],
                    duration: '1',
                    check_in: checkIn,
                    check_out: checkOut,
                    payment_status: 'paid',
                    payment_method: 'cash',
                    transaction_id: '',
                    total_price: 0,
                    special_requests: ''
                };
                this.errors = {};
                this.showAddModal = true;
                this.checkAvailableRooms();
                this.calculateTotal();
            },

            openAddModalForMeeting(roomId, dateStr) {
                let startDate = dateStr || this.formatDate(new Date());
                this.selectedMeetingRoom = roomId || '';
                this.selectedMeetingDate = startDate;
                this.newMeeting = {
                    customer_name: '',
                    customer_phone: '',
                    customer_email: '',
                    end_date: startDate,
                    start_time: '08:00',
                    end_time: '12:00',
                    attendees_count: 10,
                    setup_style: 'theater',
                    payment_method: 'cash',
                    total_price: 0,
                    total_hours: 4,
                    special_requests: ''
                };
                this.errors = {};
                this.calculateMeetingTotal();
                this.showMeetingAddModal = true;
            },

            isSearchingRooms: false,

            isRoomBusy(roomOrId) {
                if (!roomOrId) return false;
                let roomId = roomOrId;
                let roomObj = null;

                if (typeof roomOrId === 'object') {
                    roomId = roomOrId.id;
                    roomObj = roomOrId;
                } else {
                    roomObj = (this.rooms || []).find(r => String(r.id) === String(roomOrId));
                }

                // Check static status column in rooms table
                if (roomObj && roomObj.status) {
                    const st = String(roomObj.status).toLowerCase();
                    if (st !== 'available' && st !== 'active') {
                        return true;
                    }
                }

                // Check dynamic date range overlap
                if (!this.busyRoomIds || !Array.isArray(this.busyRoomIds)) return false;
                return this.busyRoomIds.some(id => String(id) === String(roomId));
            },

            async checkAvailableRooms(excludeId = null) {
                let cIn = this.newBooking.check_in;
                let cOut = this.newBooking.check_out;
                if (!cIn || !cOut) return;
                try {
                    let res = await axios.get('/admin/room-bookings/available-rooms', {
                        params: { check_in: cIn, check_out: cOut, exclude_booking_id: excludeId }
                    });
                    if (res.data && res.data.success) {
                        this.busyRoomIds = res.data.busy_room_ids || [];
                        if (res.data.rooms && res.data.rooms.length > 0) {
                            this.rooms = res.data.rooms;
                        }
                    }
                } catch(e) {
                    console.error(e);
                }
            },

            async handleSearchAvailableRooms(showToast = false) {
                if (!this.newBooking.check_in) {
                    this.newBooking.check_in = this.formatDate(new Date());
                }
                if (!this.newBooking.check_out || this.newBooking.check_out <= this.newBooking.check_in) {
                    let days = parseInt(this.newBooking.duration) || 1;
                    let start = new Date(this.newBooking.check_in);
                    start.setDate(start.getDate() + days);
                    this.newBooking.check_out = this.formatDate(start);
                }

                this.isSearchingRooms = true;
                try {
                    await this.checkAvailableRooms();
                    let availableCount = (this.rooms || []).filter(r => !this.isRoomBusy(r.id)).length;
                    
                    if (showToast) {
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: availableCount > 0 ? 'success' : 'error',
                                message: availableCount > 0 
                                    ? `រកឃើញ ${availableCount} បន្ទប់ទំនេរ ចាប់ពី ${this.formatDateDisplay(this.newBooking.check_in)} ដល់ ${this.formatDateDisplay(this.newBooking.check_out)}`
                                    : `ពុំមានបន្ទប់ទំនេរសម្រាប់កាលបរិច្ឆេទនេះឡើយ (សូមជ្រើសរើសថ្ងៃផ្សេង)`
                            }
                        }));
                    }
                    window.dispatchEvent(new CustomEvent('open-room-dropdown'));
                } catch(e) {
                    console.error(e);
                } finally {
                    this.isSearchingRooms = false;
                }
            },

            toggleRoomSelection(roomId) {
                if (!Array.isArray(this.newBooking.room_ids)) {
                    this.newBooking.room_ids = [];
                }
                const rId = parseInt(roomId);
                const index = this.newBooking.room_ids.indexOf(rId);
                if (index > -1) {
                    this.newBooking.room_ids.splice(index, 1);
                } else {
                    this.newBooking.room_ids.push(rId);
                }
                this.newBooking.room_id = this.newBooking.room_ids.length > 0 ? this.newBooking.room_ids[0] : '';
                this.calculateTotal();
            },

            formatDate(date) {
                let yyyy = date.getFullYear();
                let mm = String(date.getMonth() + 1).padStart(2, '0');
                let dd = String(date.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            },

            formatDateDisplay(d) {
                if (!d) return 'N/A';
                let p = String(d).split('T')[0].split('-');
                return p.length === 3 ? `${p[2]}/${p[1]}/${p[0]}` : d;
            },

            getMinCheckOutDate(checkInStr) {
                if (!checkInStr) {
                    let tomorrow = new Date();
                    tomorrow.setDate(tomorrow.getDate() + 1);
                    return this.formatDate(tomorrow);
                }
                let d = new Date(checkInStr);
                d.setDate(d.getDate() + 1);
                return this.formatDate(d);
            },

            handleDurationChange() {
                let days = parseInt(this.newBooking.duration) || 1;
                if (!this.newBooking.check_in) {
                    this.newBooking.check_in = this.formatDate(new Date());
                }
                let start = new Date(this.newBooking.check_in);
                start.setDate(start.getDate() + days);
                this.newBooking.check_out = this.formatDate(start);
                this.calculateTotal();
                this.checkAvailableRooms();
            },

            handleCheckOutChange() {
                if (this.newBooking.check_in && this.newBooking.check_out) {
                    let start = new Date(this.newBooking.check_in);
                    let end = new Date(this.newBooking.check_out);
                    let diffTime = end - start;
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays > 0) {
                        this.newBooking.duration = String(diffDays);
                    }
                }
                this.calculateTotal();
                this.checkAvailableRooms();
            },

            handleDateOrDurationChange() {
                if (!this.newBooking.check_in) return;
                let minOut = this.getMinCheckOutDate(this.newBooking.check_in);
                if (this.newBooking.duration) {
                    this.handleDurationChange();
                } else {
                    if (!this.newBooking.check_out || this.newBooking.check_out <= this.newBooking.check_in) {
                        this.newBooking.check_out = minOut;
                    }
                    this.calculateTotal();
                    this.checkAvailableRooms();
                }
            },

            calculateTotal() {
                let totalPricePerNight = 0;
                let selectedIds = (Array.isArray(this.newBooking.room_ids) && this.newBooking.room_ids.length > 0) 
                    ? this.newBooking.room_ids 
                    : (this.newBooking.room_id ? [this.newBooking.room_id] : []);

                selectedIds.forEach(id => {
                    const room = this.rooms.find(r => r.id == id);
                    if (room) {
                        const price = (room.room_type && room.room_type.base_price) ? parseFloat(room.room_type.base_price) : 0;
                        totalPricePerNight += price;
                    }
                });

                let days = 1;
                if (this.newBooking.check_in && this.newBooking.check_out) {
                    const d1 = new Date(this.newBooking.check_in);
                    const d2 = new Date(this.newBooking.check_out);
                    if (d2 > d1) {
                        days = Math.max(1, Math.ceil(Math.abs(d2 - d1) / (1000 * 60 * 60 * 24)));
                    }
                }

                this.newBooking.total_price = (totalPricePerNight * days).toFixed(2);
            },

            formatDateDMY(dateStr) {
                if (!dateStr) return 'N/A';
                let clean = dateStr.toString().split('T')[0].trim();
                let parts = clean.split('-');
                if (parts.length === 3) {
                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                }
                return dateStr;
            },

            calculateMeetingTotal() {
                const roomList = Array.from(this.meetingRooms);
                const room = roomList.find(r => r.id == this.selectedMeetingRoom);
                const basePrice = room?.room_type?.base_price || 0;

                if (this.newMeeting.start_time && this.newMeeting.end_time) {
                    const [h1, m1] = this.newMeeting.start_time.split(':').map(Number);
                    const [h2, m2] = this.newMeeting.end_time.split(':').map(Number);
                    let hours = (h2 + m2 / 60) - (h1 + m1 / 60);
                    if (hours < 1) hours = 1;
                    this.newMeeting.total_hours = Math.round(hours);
                    this.newMeeting.total_price = (this.newMeeting.total_hours * basePrice).toFixed(2);
                }
            },

            async saveCalendarBooking() {
                this.loading = true;
                this.errors = {};
                try {
                    const res = await axios.post('/admin/room-bookings', this.newBooking, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (res.data && res.data.success) {
                        this.showAddModal = false;
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { type: 'success', message: res.data.message || 'ការកក់បន្ទប់ជោគជ័យ!' }
                        }));
                        if (typeof changeMonth === 'function') {
                            changeMonth(0);
                        } else {
                            window.location.reload();
                        }
                    }
                } catch (err) {
                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                    const msg = err.response?.data?.message || 'មានបញ្ហាក្នុងការកក់បន្ទប់!';
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: msg } }));
                } finally {
                    this.loading = false;
                }
            },

            async saveMeetingBooking() {
                this.meetingLoading = true;
                this.errors = {};
                try {
                    const payload = {
                        meeting_room_id: this.selectedMeetingRoom,
                        customer_name: this.newMeeting.customer_name,
                        customer_phone: this.newMeeting.customer_phone,
                        start_date: this.selectedMeetingDate,
                        end_date: this.newMeeting.end_date || this.selectedMeetingDate,
                        start_time: this.newMeeting.start_time,
                        end_time: this.newMeeting.end_time,
                        total_hours: this.newMeeting.total_hours || 4,
                        total_price: this.newMeeting.total_price || 0,
                        payment_method: this.newMeeting.payment_method || 'cash',
                        payment_status: this.newMeeting.payment_status || 'paid',
                        transaction_id: this.newMeeting.transaction_id || '',
                        setup_style: this.newMeeting.setup_style || 'theater',
                        attendees_count: this.newMeeting.attendees_count || 10,
                        special_requests: this.newMeeting.special_requests || ''
                    };

                    const res = await axios.post('/admin/meeting-bookings', payload, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (res.data && res.data.success) {
                        this.showMeetingAddModal = false;
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { type: 'success', message: res.data.message || 'កក់សាលប្រជុំជោគជ័យ!' }
                        }));
                        if (typeof changeMonth === 'function') {
                            changeMonth(0);
                        } else {
                            window.location.reload();
                        }
                    }
                } catch (err) {
                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                    const msg = err.response?.data?.message || 'មានបញ្ហាក្នុងការកក់សាលប្រជុំ!';
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: msg } }));
                } finally {
                    this.meetingLoading = false;
                }
            },

            async quickUpdateStatus(newStatus) {
                if (!this.selectedBooking?.id) return;
                this.actionLoading = true;
                try {
                    const type = this.selectedBooking.type || 'stay';
                    const res = await axios.post(`/admin/calendar/update-status/${this.selectedBooking.id}`, {
                        type: type,
                        status: newStatus
                    }, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (res.data.success) {
                        this.selectedBooking.status = newStatus;
                        this.showDetailModal = false;
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: { type: 'success', message: res.data.message || 'ធ្វើបច្ចុប្បន្នភាពជោគជ័យ!' }
                        }));
                        changeMonth(0);
                    }
                } catch (err) {
                    const msg = err.response?.data?.message || 'មានបញ្ហាក្នុងការធ្វើបច្ចុប្បន្នភាព!';
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: msg } }));
                } finally {
                    this.actionLoading = false;
                }
            }
        }
    }

    function scrollCalendarTo(position) {
        const containers = document.querySelectorAll('.custom-calendar-scrollbar');
        containers.forEach(container => {
            if (position === 'start') {
                container.scrollTo({ left: 0, behavior: 'smooth' });
            } else if (position === 'end') {
                container.scrollTo({ left: container.scrollWidth, behavior: 'smooth' });
            } else if (position === 'today') {
                const todayHeader = container.querySelector('.bg-blue-600, .bg-purple-600');
                if (todayHeader) {
                    const offsetLeft = todayHeader.offsetLeft - 300;
                    container.scrollTo({ left: Math.max(0, offsetLeft), behavior: 'smooth' });
                }
            }
        });
    }

    function goToToday() {
        const now = new Date();
        currentMonth = now.getMonth() + 1;
        currentYear = now.getFullYear();
        changeMonth(0);
    }

    function changeMonth(direction) {
        currentMonth += direction;

        if (currentMonth > 12) {
            currentMonth = 1;
            currentYear++;
        } else if (currentMonth < 1) {
            currentMonth = 12;
            currentYear--;
        }

        const container = document.getElementById('calendar-table-container');
        container.classList.add('ajax-loading');

        const url = `{{ route('calendar.index') }}?month=${currentMonth}&year=${currentYear}`;

        fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.text();
            })
            .then(html => {
                container.innerHTML = html;
                container.classList.remove('ajax-loading');
                const monthNames = ["មករា", "កុម្ភៈ", "មីនា", "មេសា", "ឧសភា", "មិថុនា", "កក្កដា", "សីហា", "កញ្ញា", "តុលា", "វិច្ឆិកា", "ធ្នូ"];
                document.getElementById('current-month-label').innerText = `${monthNames[currentMonth - 1]} ${currentYear}`;
                setTimeout(() => scrollCalendarTo('today'), 200);
            })
            .catch(error => {
                console.error('Error loading calendar:', error);
                container.classList.remove('ajax-loading');
            });
    }
</script>
@endsection