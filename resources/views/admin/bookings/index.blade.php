@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងការកក់')

@section('content')
<div id="bookings-page" class="p-2 sm:p-2"
    x-data="{
        viewMode: localStorage.getItem('bookingView') || 'table',
        showAddModal: false,
        showEditModal: false,
        showDetailModal: false,
        showSlipModal: false,
        slipUrl: '',
        submitting: false,
        loading: false,

        editType: 'room',
        editingBooking: {},
        currentBooking: null,
        busyRoomIds: [],
        rooms: @js($rooms),
        meetingRooms: @js($meetingRooms),
        min_date: '{{ date("Y-m-d") }}',
        errors: {},

        viewSlip(url) {
            this.slipUrl = url;
            this.showSlipModal = true;
        },

        search: '{{ request('search') }}',
        category: '{{ request('category', 'all') }}',
        status: '{{ request('status') }}',

        newBooking: {
            booking_category: 'hotel',
            customer_id: '',
            customer_name: '',
            customer_phone: '',
            room_id: '',
            meeting_room_id: '',
            check_in: '{{ date('Y-m-d') }}',
            check_out: '{{ date('Y-m-d', strtotime('+1 day')) }}',
            start_date: '{{ date('Y-m-d') }}',
            end_date: '{{ date('Y-m-d', strtotime('+1 day')) }}',
            start_time: '08:00',
            end_time: '17:00',
            total_hours: 9,
            attendees_count: 10,
            setup_style: '',
            total_price: 0,
            payment_method: 'cash',
            special_requests: ''
        },

        openDetailModal(booking) {
            this.currentBooking = JSON.parse(JSON.stringify(booking));
            this.showDetailModal = true;
        },

        formatDate(date) {
            let yyyy = date.getFullYear();
            let mm = String(date.getMonth() + 1).padStart(2, '0');
            let dd = String(date.getDate()).padStart(2, '0');
            return `${yyyy}-${mm}-${dd}`;
        },

        formatDateDisplay(d) {
            if(!d) return 'N/A';
            const clean = String(d).split('T')[0];
            const parts = clean.split('-');
            if(parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`;
            return clean;
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

        isRoomBusy(roomOrId) {
            if (!roomOrId) return false;
            let roomId = roomOrId;
            let roomObj = null;

            if (typeof roomOrId === 'object') {
                roomId = roomOrId.id;
                roomObj = roomOrId;
            } else {
                const pool = (this.editType === 'meeting') ? (this.meetingRooms || []) : (this.rooms || []);
                roomObj = pool.find(r => String(r.id) === String(roomOrId));
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
            if (this.editType === 'meeting' || (this.editingBooking && this.editingBooking.meeting_room_id)) {
                let sDate = this.editingBooking.start_date;
                let eDate = this.editingBooking.end_date;
                let sTime = this.editingBooking.start_time;
                let eTime = this.editingBooking.end_time;
                if (!sDate || !eDate) return;
                try {
                    let res = await axios.get('/admin/meeting-bookings/available-rooms', {
                        params: { start_date: sDate, end_date: eDate, start_time: sTime, end_time: eTime, exclude_booking_id: excludeId }
                    });
                    if (res.data && res.data.success) {
                        this.busyRoomIds = res.data.busy_room_ids || [];
                        if (res.data.rooms && res.data.rooms.length > 0) {
                            this.meetingRooms = res.data.rooms;
                        }
                    }
                } catch(e) { console.error(e); }
            } else {
                let cIn = this.editingBooking.check_in;
                let cOut = this.editingBooking.check_out;
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
                } catch(e) { console.error(e); }
            }
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

        calculateEditTotal() {
            if (!this.editingBooking) return;
            if (this.editType === 'meeting' || this.editingBooking.meeting_room_id) {
                const room = (this.meetingRooms || []).find(r => r.id == this.editingBooking.meeting_room_id);
                const basePrice = (room && room.room_type) ? parseFloat(room.room_type.base_price) : 0;
                let hours = 1;
                if (this.editingBooking.start_time && this.editingBooking.end_time) {
                    const start = parseInt(this.editingBooking.start_time.split(':')[0]);
                    const end = parseInt(this.editingBooking.end_time.split(':')[0]);
                    if (end > start) hours = end - start;
                }
                let days = 1;
                if (this.editingBooking.start_date && this.editingBooking.end_date) {
                    const d1 = new Date(this.editingBooking.start_date);
                    const d2 = new Date(this.editingBooking.end_date);
                    if (d2 >= d1) days = Math.max(1, Math.ceil(Math.abs(d2 - d1) / (1000 * 60 * 60 * 24)) + 1);
                }
                this.editingBooking.total_hours = hours;
                this.editingBooking.total_price = (basePrice * hours * days).toFixed(2);
            } else {
                let totalPricePerNight = 0;
                let selectedIds = (Array.isArray(this.editingBooking.room_ids) && this.editingBooking.room_ids.length > 0) 
                    ? this.editingBooking.room_ids 
                    : (this.editingBooking.room_id ? [this.editingBooking.room_id] : []);

                selectedIds.forEach(id => {
                    const room = (this.rooms || []).find(r => r.id == id);
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

        handleEditDateTimeChange() {
            this.calculateEditTotal();
            this.checkAvailableRooms(this.editingBooking.id);
        },

        openEditModal(booking) {
            this.currentBooking = JSON.parse(JSON.stringify(booking));
            const isMeeting = booking.meeting_room_id || (booking.room && (booking.room.room_type?.category === 'meeting' || booking.room.roomType?.category === 'meeting'));
            
            if (isMeeting) {
                this.editType = 'meeting';
                let sDate = booking.start_date ? booking.start_date.split('T')[0] : (booking.check_in ? booking.check_in.split('T')[0] : '');
                let eDate = booking.end_date ? booking.end_date.split('T')[0] : (booking.check_out ? booking.check_out.split('T')[0] : '');
                
                this.editingBooking = {
                    id: booking.id,
                    booking_code: booking.booking_code,
                    booking_category: 'meeting_room',
                    customer_name: booking.customer_name || (booking.user ? booking.user.name : ''),
                    customer_phone: booking.customer_phone || (booking.user ? booking.user.phone : ''),
                    customer_email: booking.customer_email || (booking.user ? booking.user.email : ''),
                    meeting_room_id: booking.meeting_room_id || (booking.room_id ? booking.room_id : ''),
                    start_date: sDate,
                    end_date: eDate,
                    start_time: booking.start_time || '08:00',
                    end_time: booking.end_time || '17:00',
                    total_hours: booking.total_hours || 9,
                    attendees_count: booking.attendees_count || 10,
                    setup_style: booking.setup_style || '',
                    total_price: booking.total_price || 0,
                    payment_status: booking.payment ? booking.payment.status : 'paid',
                    payment_method: booking.payment_method || (booking.payment ? booking.payment.method : 'cash'),
                    transaction_id: booking.payment ? booking.payment.transaction_id : '',
                    status: booking.status || 'confirmed',
                    special_requests: booking.special_requests || ''
                };
            } else {
                this.editType = 'room';
                let rIds = [];
                if (booking.details && booking.details.length > 0) {
                    rIds = booking.details.map(d => d.room_id);
                } else if (booking.room_id) {
                    rIds = [booking.room_id];
                }
                let cIn = booking.check_in ? booking.check_in.split('T')[0] : '';
                let cOut = booking.check_out ? booking.check_out.split('T')[0] : '';
                let durationDays = '1';
                if (cIn && cOut) {
                    let start = new Date(cIn);
                    let end = new Date(cOut);
                    let diffTime = end - start;
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if (diffDays > 0) durationDays = String(diffDays);
                }

                this.editingBooking = {
                    id: booking.id,
                    booking_code: booking.booking_code,
                    booking_category: 'hotel',
                    customer_name: booking.customer_name || (booking.user ? booking.user.name : ''),
                    customer_phone: booking.customer_phone || (booking.user ? booking.user.phone : ''),
                    customer_email: booking.customer_email || (booking.user ? booking.user.email : ''),
                    room_id: booking.room_id || (rIds.length > 0 ? rIds[0] : ''),
                    room_ids: rIds,
                    check_in: cIn,
                    check_out: cOut,
                    duration: durationDays,
                    total_price: booking.total_price || 0,
                    payment_status: booking.payment ? booking.payment.status : 'paid',
                    payment_method: booking.payment_method || (booking.payment ? booking.payment.method : 'cash'),
                    transaction_id: booking.payment ? booking.payment.transaction_id : '',
                    status: booking.status || 'confirmed',
                    special_requests: booking.special_requests || ''
                };
            }

            this.checkAvailableRooms(booking.id);
            this.showEditModal = true;
        },

        calculateTotalAdd() {
            if (this.newBooking.booking_category === 'hotel') {
                const roomSelect = document.querySelector('[x-model=\'newBooking.room_id\']');
                if(!roomSelect) return;
                const selectedOption = roomSelect.options[roomSelect.selectedIndex];
                const basePrice = selectedOption ? parseFloat(selectedOption.getAttribute('data-price') || 0) : 0;
                
                if(this.newBooking.check_in && this.newBooking.check_out) {
                    const checkIn = new Date(this.newBooking.check_in);
                    const checkOut = new Date(this.newBooking.check_out);
                    const diffTime = checkOut - checkIn;
                    const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)));
                    this.newBooking.total_price = (basePrice * diffDays).toFixed(2);
                }
            }
        },

        async fetchBookings(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('bookings.index') }}';
            if (window.location.protocol === 'https:' && fetchUrl.startsWith('http://')) {
                fetchUrl = fetchUrl.replace('http://', 'https://');
            }

            try {
                const response = await axios.get(fetchUrl, {
                    params: {
                        search: this.search,
                        category: this.category,
                        status: this.status,
                    },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('bookings-container').innerHTML = response.data;
            } catch (error) {
                console.error(error);
            } finally {
                this.loading = false;
            }
        },

        async saveBooking() {
            this.submitting = true;
            try {
                const response = await axios.post('{{ route('bookings.store') }}', this.newBooking, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if(response.data.success) {
                    Swal.fire('ជោគជ័យ', response.data.message, 'success');
                    this.showAddModal = false;
                    this.fetchBookings();
                }
            } catch(error) {
                const msg = error.response?.data?.message || 'មានបញ្ហាក្នុងការរក្សាទុក!';
                Swal.fire('បរាជ័យ', msg, 'error');
            } finally {
                this.submitting = false;
            }
        },

        async updateBooking() {
            if (!this.editingBooking) return;
            this.submitting = true;
            
            let updateUrl = `/admin/bookings/${this.editingBooking.id}`;
            if (this.editType === 'meeting' || this.editingBooking.meeting_room_id) {
                updateUrl = `/admin/meeting-bookings/${this.editingBooking.id}`;
            } else {
                updateUrl = `/admin/room-bookings/${this.editingBooking.id}`;
            }

            try {
                const response = await axios.put(updateUrl, this.editingBooking, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ជោគជ័យ',
                        text: response.data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    this.showEditModal = false;
                    this.fetchBookings();
                }
            } catch (error) {
                const msg = error.response?.data?.message || 'មានបញ្ហាក្នុងការធ្វើបច្ចុប្បន្នភាព!';
                Swal.fire('បរាជ័យ', msg, 'error');
            } finally {
                this.submitting = false;
            }
        },

        async quickUpdateStatus(bookingId, newStatus) {
            try {
                const isMeeting = this.editingBooking?.meeting_room_id || (this.currentBooking && (this.currentBooking.meeting_room_id || this.category === 'meeting_room'));
                const response = await axios.patch(`/admin/bookings/${bookingId}/status`, {
                    status: newStatus,
                    category: isMeeting ? 'meeting_room' : 'hotel'
                }, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if(response.data.success) {
                    if(this.currentBooking && this.currentBooking.id === bookingId) {
                        this.currentBooking.status = newStatus;
                    }
                    if(this.editingBooking && this.editingBooking.id === bookingId) {
                        this.editingBooking.status = newStatus;
                    }
                    Swal.fire('ជោគជ័យ', response.data.message, 'success');
                    this.fetchBookings();
                }
            } catch(error) {
                Swal.fire('បរាជ័យ', 'មិនអាចប្តូរស្ថានភាពបានឡើយ!', 'error');
            }
        },

        async deleteBooking(bookingId) {
            const result = await Swal.fire({
                title: 'តើអ្នកប្រាកដជាចង់លុបការកក់នេះមែនទេ?',
                text: 'ទិន្នន័យដែលបានលុបមិនអាចសង្គ្រោះវិញបានឡើយ!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'យល់ព្រមលុប',
                cancelButtonText: 'បោះបង់'
            });

            if(result.isConfirmed) {
                try {
                    const response = await axios.delete(`/admin/bookings/${bookingId}`, {
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    if(response.data.success) {
                        Swal.fire('ជោគជ័យ', response.data.message, 'success');
                        this.fetchBookings();
                    }
                } catch(error) {
                    Swal.fire('បរាជ័យ', 'មិនអាចលុបទិន្នន័យបានឡើយ!', 'error');
                }
            }
        }
    }">

    {{-- Top Bar & View Controls --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div>
                <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងការកក់</h2>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Real-time Booking System</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('room-bookings.index') }}" class="h-9 px-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm shadow-blue-500/20 active:scale-95 transition-all cursor-pointer">
                    <i class="fa-solid fa-plus text-[10px]"></i> កក់បន្ទប់ស្នាក់នៅ
                </a>
                <a href="{{ route('meeting-bookings.index') }}" class="h-9 px-3.5 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm shadow-purple-500/20 active:scale-95 transition-all cursor-pointer">
                    <i class="fa-solid fa-plus text-[10px]"></i> កក់សាលប្រជុំ
                </a>


            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            {{-- Search Input --}}
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchBookings()" placeholder="ស្វែងរកកូដ ឬ ឈ្មោះ..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all text-xs font-semibold">
            </div>

            {{-- Category Filter --}}
            <div class="w-full sm:w-44">
                <div class="relative group">
                    <select x-model="category" @change="fetchBookings()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="all">ប្រភេទទាំងអស់</option>
                        <option value="hotel">បន្ទប់ស្នាក់នៅ</option>
                        <option value="meeting_room">សាលប្រជុំ</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="w-full sm:w-36">
                <div class="relative group">
                    <select x-model="status" @change="fetchBookings()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-xs font-semibold relative z-0 cursor-pointer">
                        <option value="">ស្ថានភាពទាំងអស់</option>
                        <option value="pending">រង់ចាំពិនិត្យ</option>
                        <option value="confirmed">បានបញ្ជាក់</option>
                        <option value="completed">បានបញ្ចប់</option>
                        <option value="cancelled">បានបោះបង់</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- View Mode Switcher --}}
            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('bookingView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="Table View"><i class="fas fa-table-list"></i></button>
                <button @click="viewMode = 'list'; localStorage.setItem('bookingView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="List View"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('bookingView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="Grid View"><i class="fas fa-th-large"></i></button>
            </div>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div x-show="loading" x-cloak class="mb-6 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center font-bold">
        កំពុងដំណើរការ...
    </div>

    {{-- Bookings Partial Container --}}
    <div id="bookings-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.bookings.partials.booking_list')
    </div>

    {{-- Modals Inclusion --}}
    @include('admin.bookings.modals')

    {{-- PAYMENT SLIP PREVIEW MODAL --}}
    <div x-show="showSlipModal" class="fixed inset-0 z-[110] overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 py-10">
            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-md transition-opacity" @click="showSlipModal = false"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>

            <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl max-w-lg w-full relative border border-gray-100 dark:border-gray-800 p-6 space-y-4 z-10 transition-all"
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
                <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-3">
                    <h3 class="text-base font-black text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-file-image text-emerald-500"></i> មើលបង្កាន់ដៃបង់ប្រាក់លម្អិត
                    </h3>
                    <button type="button" @click="showSlipModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-3xl transition-transform hover:rotate-90 cursor-pointer">&times;</button>
                </div>
                
                <div class="flex justify-center bg-gray-50 dark:bg-gray-800/50 p-3 rounded-2xl overflow-hidden min-h-[250px] items-center border border-gray-100 dark:border-gray-800">
                    <template x-if="slipUrl">
                        <img :src="slipUrl" class="max-h-[480px] w-auto object-contain rounded-xl shadow-md transition-all hover:scale-105" alt="Payment Slip Image">
                    </template>
                    <template x-if="!slipUrl">
                        <p class="text-xs text-gray-400 font-bold">គ្មានរូបភាពបង្កាន់ដៃបង់ប្រាក់នៅឡើយ</p>
                    </template>
                </div>

                <div class="flex justify-end items-center gap-3 pt-2">
                    <a :href="slipUrl" download target="_blank" class="px-5 h-10 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-extrabold text-xs rounded-xl flex items-center gap-2 transition shadow-md shadow-emerald-500/20 cursor-pointer">
                        <i class="fas fa-download"></i> ទាញយករូបភាព
                    </a>
                    <button type="button" @click="showSlipModal = false" class="px-5 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-extrabold text-xs rounded-xl transition cursor-pointer">
                        បិទ
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pagination Click Interceptor --}}
<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('#bookings-container a, .pagination a, nav a');
        if (link && link.href && (link.href.includes('page=') || link.href.includes('bookings'))) {
            e.preventDefault();
            const pageContainer = document.getElementById('bookings-page') || document.querySelector('[x-data*="fetchBookings"]');
            if (pageContainer && typeof Alpine !== 'undefined' && Alpine.$data) {
                const component = Alpine.$data(pageContainer);
                if (component && typeof component.fetchBookings === 'function') {
                    component.fetchBookings(link.href);
                }
            }
        }
    });
</script>
@endsection