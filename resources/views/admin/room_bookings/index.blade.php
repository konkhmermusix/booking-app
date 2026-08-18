@extends('layouts.admin')
@section('title', 'ការគ្រប់គ្រងការកក់បន្ទប់ស្នាក់នៅ')
@section('content')

<div class="p-2 sm:p-2" x-data="bookingManager">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងការកក់បន្ទប់ស្នាក់នៅ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Real-time Room Booking System</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchBookings()" placeholder="ស្វែងរកលេខកូដកក់..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <div class="w-full sm:w-40">
                <div class="relative group">
                    <select x-model="status" @change="fetchBookings()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">ទាំងអស់</option>
                        <option value="pending">រង់ចាំ</option>
                        <option value="confirmed">បានបញ្ជាក់</option>
                        <option value="completed">បានបញ្ចប់</option>
                        <option value="cancelled">បោះបង់</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="setView('table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]">
                    <i class="fas fa-table-list"></i>
                </button>
                <button @click="setView('list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button @click="setView('grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]">
                    <i class="fas fa-th-large"></i>
                </button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមកក់ថ្មី
            </button>
        </div>
    </div>

    <div x-show="loading" x-cloak class="mb-4 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center">
        កំពុងដំណើរការ...
    </div>

    <div id="bookings-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.room_bookings.partials.booking_list')
    </div>

    @include('admin.room_bookings.modals')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingManager', () => ({
            viewMode: localStorage.getItem('bookingView') || 'table',
            showAddModal: false,
            showEditModal: false,
            showDetailModal: false,
            showSlipModal: false,
            selectedSlip: '',

            editingBooking: {},
            selectedBooking: {},

            loading: false,
            search: '{{ request("search", "") }}',
            status: '{{ request("status", "") }}',
            rooms: @js($rooms),
            hotels: @js($hotels),
            errors: {},
            notyf: null,
            min_date: '',
            busyRoomIds: [],
            isSearchingRooms: false,

            newBooking: {
                hotel_id: '',
                room_id: '',
                room_ids: [],
                meeting_room_id: '',
                duration: '1',
                check_in: '',
                check_out: '',
                price_per_night: 0,
                total_price: 0,
                total_hours: '',
                attendees_count: '',
                setup_style: '',
                payment_status: 'paid',
                payment_method: 'cash',
                transaction_id: '',
                special_requests: ''
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

            resetForm() {
                let today = this.formatDate(new Date());
                let tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);

                this.newBooking = {
                    hotel_id: '',
                    customer_name: '',
                    customer_phone: '',
                    customer_email: '',
                    room_id: '',
                    room_ids: [],
                    meeting_room_id: '',
                    duration: '1',
                    check_in: today,
                    check_out: this.formatDate(tomorrow),
                    total_price: 0,
                    price_per_night: 0,
                    payment_status: 'paid',
                    payment_method: 'cash',
                    transaction_id: '',
                    special_requests: ''
                };
                this.errors = {};
            },

            init() {
                this.min_date = this.formatDate(new Date());
                this.resetForm();

                this.notyf = new Notyf({
                    duration: 3000,
                    position: { x: 'right', y: 'top' },
                    dismissible: true,
                    types: [
                        { type: 'success', background: '#10b981', icon: { className: 'fas fa-check-circle', color: '#fff' } },
                        { type: 'error', background: '#ef4444', icon: { className: 'fas fa-exclamation-circle', color: '#fff' } }
                    ]
                });

                this.fetchBookings();
                this.checkAvailableRooms();

                this.$watch('showAddModal', (val) => {
                    if (val) {
                        if (!this.newBooking.check_in || !this.newBooking.check_out) {
                            this.resetForm();
                        }
                        this.checkAvailableRooms();
                    }
                });

                this.$watch('newBooking.room_id', () => this.calculateTotal());
                this.$watch('newBooking.check_in', () => { this.calculateTotal(); this.checkAvailableRooms(); });
                this.$watch('newBooking.check_out', () => { this.calculateTotal(); this.checkAvailableRooms(); });

                document.addEventListener('click', (e) => {
                    const link = e.target.closest('#bookings-container .pagination a, #bookings-container .pagination-container a, #bookings-container a.page-link');
                    if (link) {
                        e.preventDefault();
                        const url = link.getAttribute('href');
                        if (url && url !== '#') {
                            this.fetchBookings(url);
                        }
                    }
                });
            },

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

                if (roomObj && roomObj.status) {
                    const st = String(roomObj.status).toLowerCase();
                    if (st !== 'available' && st !== 'active') {
                        return true;
                    }
                }

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

            toggleRoomSelection(roomId) {
                if (!Array.isArray(this.newBooking.room_ids)) {
                    this.newBooking.room_ids = [];
                }
                let index = this.newBooking.room_ids.indexOf(roomId);
                if (index > -1) {
                    this.newBooking.room_ids.splice(index, 1);
                } else {
                    this.newBooking.room_ids.push(roomId);
                }
                if (this.newBooking.room_ids.length > 0) {
                    this.newBooking.room_id = this.newBooking.room_ids[0];
                } else {
                    this.newBooking.room_id = '';
                }
                this.calculateTotal();
            },

            toggleEditRoomSelection(roomId) {
                if (!Array.isArray(this.editingBooking.room_ids)) {
                    this.editingBooking.room_ids = [];
                }
                let index = this.editingBooking.room_ids.indexOf(roomId);
                if (index > -1) {
                    this.editingBooking.room_ids.splice(index, 1);
                } else {
                    this.editingBooking.room_ids.push(roomId);
                }
                if (this.editingBooking.room_ids.length > 0) {
                    this.editingBooking.room_id = this.editingBooking.room_ids[0];
                } else {
                    this.editingBooking.room_id = '';
                }
                this.calculateEditTotal();
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
                        if (!this.newBooking.hotel_id) {
                            this.newBooking.hotel_id = room.hotel_id;
                        }
                    }
                });

                this.newBooking.price_per_night = totalPricePerNight;

                if (this.newBooking.check_in && this.newBooking.check_out) {
                    const start = new Date(this.newBooking.check_in);
                    const end = new Date(this.newBooking.check_out);

                    if (end >= start) {
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
                        this.newBooking.total_price = (diffDays * totalPricePerNight).toFixed(2);
                    } else {
                        this.newBooking.total_price = 0;
                    }
                }
            },

            calculateEditTotal() {
                let totalPricePerNight = 0;
                let selectedIds = (Array.isArray(this.editingBooking.room_ids) && this.editingBooking.room_ids.length > 0) 
                    ? this.editingBooking.room_ids 
                    : (this.editingBooking.room_id ? [this.editingBooking.room_id] : []);

                selectedIds.forEach(id => {
                    const room = this.rooms.find(r => r.id == id);
                    if (room) {
                        const price = (room.room_type && room.room_type.base_price) ? parseFloat(room.room_type.base_price) : 0;
                        totalPricePerNight += price;
                    }
                });

                if (this.editingBooking.check_in && this.editingBooking.check_out) {
                    const start = new Date(this.editingBooking.check_in);
                    const end = new Date(this.editingBooking.check_out);

                    if (end >= start) {
                        const diffTime = Math.abs(end - start);
                        const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
                        this.editingBooking.total_price = (diffDays * totalPricePerNight).toFixed(2);
                    }
                }
            },

            handleEditDurationChange() {
                let days = parseInt(this.editingBooking.duration) || 1;

                if (!this.editingBooking.check_in) {
                    this.editingBooking.check_in = this.formatDate(new Date());
                }

                let start = new Date(this.editingBooking.check_in);
                start.setDate(start.getDate() + days);
                this.editingBooking.check_out = this.formatDate(start);
                this.calculateEditTotal();
                this.checkAvailableRooms(this.editingBooking.id);
            },

            handleEditDateOrDurationChange() {
                if (!this.editingBooking.check_in) return;

                let minOut = this.getMinCheckOutDate(this.editingBooking.check_in);

                if (this.editingBooking.duration) {
                    this.handleEditDurationChange();
                } else {
                    if (!this.editingBooking.check_out || this.editingBooking.check_out <= this.editingBooking.check_in) {
                        this.editingBooking.check_out = minOut;
                    }
                    this.calculateEditTotal();
                    this.checkAvailableRooms(this.editingBooking.id);
                }
            },

            editBooking(booking) {
                const uName = booking.user ? booking.user.name : '';
                const uPhone = booking.user ? booking.user.phone : '';
                const uEmail = booking.user ? booking.user.email : '';
                let rIds = [];
                if (booking.details && booking.details.length > 0) {
                    rIds = booking.details.map(d => d.room_id);
                } else if (booking.room_id) {
                    rIds = [booking.room_id];
                }

                let durationDays = '';
                if (booking.check_in && booking.check_out) {
                    let d1 = new Date(booking.check_in);
                    let d2 = new Date(booking.check_out);
                    let diff = Math.ceil(Math.abs(d2 - d1) / (1000 * 60 * 60 * 24));
                    if (diff > 0) durationDays = String(diff);
                }

                this.editingBooking = {
                    id: booking.id,
                    customer_name: booking.customer_name || uName,
                    customer_phone: booking.customer_phone || uPhone,
                    customer_email: booking.customer_email || uEmail,
                    room_id: booking.room_id,
                    room_ids: rIds,
                    duration: durationDays,
                    check_in: booking.check_in,
                    check_out: booking.check_out,
                    payment_status: (booking.payment ? booking.payment.status : 'paid'),
                    payment_method: booking.payment_method || (booking.payment ? booking.payment.method : 'cash'),
                    transaction_id: (booking.payment ? booking.payment.transaction_id : ''),
                    status: booking.status || 'pending',
                    total_price: booking.total_price,
                    special_requests: booking.special_requests || ''
                };
                this.checkAvailableRooms(booking.id);
                this.showEditModal = true;
            },

            viewDetail(booking) {
                this.selectedBooking = booking;
                this.showDetailModal = true;
            },

            getSlipUrl(slip) {
                if (!slip) return '';
                if (slip.startsWith('http://') || slip.startsWith('https://')) return slip;
                if (slip.startsWith('/')) return slip;
                if (slip.startsWith('storage/')) return '/' + slip;
                return '/storage/' + slip;
            },

            viewSlip(slip) {
                if (!slip) return;
                this.selectedSlip = this.getSlipUrl(slip);
                this.showSlipModal = true;
            },

            async updateBooking() {
                this.loading = true;
                this.errors = {};
                try {
                    const res = await axios.put('/admin/room-bookings/' + this.editingBooking.id, this.editingBooking, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    if (res.data && res.data.success) {
                        this.showEditModal = false;
                        this.fetchBookings();
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'success',
                                message: res.data.message || 'បានធ្វើបច្ចុប្បន្នភាពការកក់ដោយជោគជ័យ!'
                            }
                        }));
                    }
                } catch (err) {
                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;
                    }
                    const errMsg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'មានបញ្ហាក្នុងការធ្វើបច្ចុប្បន្នភាព!';
                    window.dispatchEvent(new CustomEvent('toast', {
                        detail: {
                            type: 'error',
                            message: errMsg
                        }
                    }));
                } finally {
                    this.loading = false;
                }
            },

            async deleteBooking(id) {
                Swal.fire({
                    title: 'តើអ្នកពិតជាចង់លុបការកក់នេះមែនទេ?',
                    text: 'ទិន្នន័យដែលបានលុបមិនអាចទាញត្រឡប់មកវិញបានទេ!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'លុបចេញ',
                    cancelButtonText: 'បោះបង់',
                    reverseButtons: true,
                    customClass: {
                        popup: 'bg-white dark:bg-gray-900 rounded-3xl border border-gray-100 dark:border-gray-800 shadow-2xl',
                        title: 'dark:text-white font-extrabold',
                        htmlContainer: 'text-gray-500 dark:text-gray-400'
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        this.loading = true;
                        try {
                            const res = await axios.delete('/admin/room-bookings/' + id, {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });
                            if (res.data && res.data.success) {
                                this.fetchBookings();
                                window.dispatchEvent(new CustomEvent('toast', {
                                    detail: {
                                        type: 'success',
                                        message: res.data.message || 'បានលុបការកក់ដោយជោគជ័យ!'
                                    }
                                }));
                            }
                        } catch (err) {
                            const errMsg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'មានបញ្ហាក្នុងការលុប!';
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'error',
                                    message: errMsg
                                }
                            }));
                        } finally {
                            this.loading = false;
                        }
                    }
                });
            },

            setView(mode) {
                this.viewMode = mode;
                localStorage.setItem('bookingView', mode);
            },

            async fetchBookings(url = null) {
                this.loading = true;
                let fetchUrl = url || '{{ route("room-bookings.index") }}';

                try {
                    const res = await axios.get(fetchUrl, {
                        params: {
                            search: this.search,
                            status: this.status,
                            view_mode: this.viewMode
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    document.getElementById('bookings-container').innerHTML = res.data;
                } catch (err) {
                    console.error("Error fetching bookings:", err);
                } finally {
                    this.loading = false;
                }
            },

            async saveBooking() {
                this.loading = true;
                this.errors = {};

                try {
                    const res = await axios.post('/admin/room-bookings', this.newBooking, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (res.data.success) {
                        this.showAddModal = false;
                        this.resetForm();
                        this.fetchBookings();

                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'success',
                                message: res.data.message || 'បង្កើតការកក់ផ្ទាល់ (Walk-In) បានជោគជ័យ!'
                            }
                        }));
                    }
                } catch (err) {
                    if (err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors;

                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                message: 'សូមពិនិត្យមើលទិន្នន័យដែលបានបញ្ចូលឡើងវិញ!'
                            }
                        }));
                    } else {
                        console.error(err);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                message: 'មានបញ្ហាបច្ចេកទេសក្នុងការរក្សាទុក!'
                            }
                        }));
                    }
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>

@endsection
