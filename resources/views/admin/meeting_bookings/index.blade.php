@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងការកក់សាលប្រជុំ')

@section('content')

<div class="p-2 sm:p-2" x-data="meetingBookingManager">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងការកក់សាលប្រជុំ និងពិធីការ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Meeting & Event Booking Management</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            {{-- Search Input --}}
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchBookings()" placeholder="ស្វែងរកលេខកូដកក់..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all text-sm font-medium">
            </div>

            {{-- Status Filter --}}
            <div class="w-full sm:w-40">
                <div class="relative group">
                    <select x-model="status" @change="fetchBookings()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-purple-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">ទាំងអស់</option>
                        <option value="pending">រង់ចាំ</option>
                        <option value="confirmed">បានបញ្ជាក់</option>
                        <option value="completed">បានបញ្ចប់</option>
                        <option value="cancelled">បោះបង់</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            {{-- View Mode Toggle --}}
            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="setView('table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]">
                    <i class="fas fa-table-list"></i>
                </button>
                <button @click="setView('list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]">
                    <i class="fas fa-list-ul"></i>
                </button>
                <button @click="setView('grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-purple-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]">
                    <i class="fas fa-border-all"></i>
                </button>
            </div>

            {{-- Add Booking Button --}}
            <button @click="showAddModal = true" class="h-10 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-bold flex items-center gap-2 shadow-lg shadow-purple-500/20 active:scale-95 transition-all cursor-pointer">
                <i class="fas fa-plus-circle"></i>កក់សាលប្រជុំថ្មី
            </button>
        </div>
    </div>

    {{-- Loading Indicator --}}
    <div x-show="loading" x-cloak class="mb-4 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center">
        កំពុងដំណើរការ...
    </div>

    <div id="bookings-container" :class="loading ? 'opacity-40 pointer-events-none' : ''">
        @include('admin.meeting_bookings.partials.booking_list')
    </div>

    @include('admin.meeting_bookings.modals')

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('meetingBookingManager', () => ({
            viewMode: localStorage.getItem('meetingBookingView') || 'table',
            showAddModal: false,
            showEditModal: false,
            showDetailModal: false,
            showSlipModal: false,
            selectedSlip: null,
            loading: false,
            min_date: '{{ date("Y-m-d") }}',
            search: '{{ request("search", "") }}',
            status: '{{ request("status", "") }}',
            meetingRooms: @js($meetingRooms),
            rooms: @js($meetingRooms),
            selectedBooking: {},
            editingBooking: {},
            errors: {},
            viewSlip(slipUrl) {
                this.selectedSlip = slipUrl;
                this.showSlipModal = true;
            },
            getSlipUrl(slipPath) {
                if (!slipPath) return '';
                if (slipPath.startsWith('http://') || slipPath.startsWith('https://')) return slipPath;
                return `/storage/${slipPath}`;
            },
            newBooking: {
                customer_name: '',
                customer_phone: '',
                customer_email: '',
                meeting_room_id: '',
                start_date: '{{ date("Y-m-d") }}',
                end_date: '{{ date("Y-m-d") }}',
                start_time: '08:00',
                end_time: '17:00',
                total_hours: 9,
                total_price: 0,
                payment_status: 'paid',
                payment_method: 'cash',
                transaction_id: '',
                setup_style: '',
                attendees_count: 10,
                special_requests: ''
            },

            init() {
                this.fetchBookings();
                this.checkAvailableRooms();
                this.$watch('newBooking.meeting_room_id', () => this.calculateTotal());
                this.$watch('newBooking.start_date', () => { this.calculateTotal(); this.checkAvailableRooms(); });
                this.$watch('newBooking.end_date', () => { this.calculateTotal(); this.checkAvailableRooms(); });
                this.$watch('newBooking.start_time', () => { this.calculateTotal(); this.checkAvailableRooms(); });
                this.$watch('newBooking.end_time', () => { this.calculateTotal(); this.checkAvailableRooms(); });

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

            busyRoomIds: [],

            isRoomBusy(roomOrId) {
                if (!roomOrId) return false;
                let roomId = roomOrId;
                let roomObj = null;

                if (typeof roomOrId === 'object') {
                    roomId = roomOrId.id;
                    roomObj = roomOrId;
                } else {
                    roomObj = (this.meetingRooms || []).find(r => String(r.id) === String(roomOrId));
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

            async checkAvailableRooms(excludeId = null, targetObj = null) {
                let obj = targetObj || this.newBooking;
                let sDate = obj.start_date;
                let eDate = obj.end_date;
                let sTime = obj.start_time;
                let eTime = obj.end_time;
                if (!sDate || !eDate) return;
                try {
                    let res = await axios.get('/admin/meeting-bookings/available-rooms', {
                        params: { 
                            start_date: sDate, 
                            end_date: eDate, 
                            start_time: sTime, 
                            end_time: eTime, 
                            exclude_booking_id: excludeId 
                        }
                    });
                    if (res.data && res.data.success) {
                        this.busyRoomIds = res.data.busy_room_ids || [];
                        if (res.data.rooms && res.data.rooms.length > 0) {
                            this.meetingRooms = res.data.rooms;
                        }
                    }
                } catch(e) {
                    console.error(e);
                }
            },

            setView(mode) {
                this.viewMode = mode;
                localStorage.setItem('meetingBookingView', mode);
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

            calculateTotal() {
                const room = this.meetingRooms.find(r => r.id == this.newBooking.meeting_room_id);
                const basePrice = (room && room.room_type) ? parseFloat(room.room_type.base_price) : 0;
                let hours = 1;
                if (this.newBooking.start_time && this.newBooking.end_time) {
                    const start = parseInt(this.newBooking.start_time.split(':')[0]);
                    const end = parseInt(this.newBooking.end_time.split(':')[0]);
                    if (end > start) {
                        hours = end - start;
                    }
                }

                let days = 1;
                if (this.newBooking.start_date && this.newBooking.end_date) {
                    const d1 = new Date(this.newBooking.start_date);
                    const d2 = new Date(this.newBooking.end_date);
                    if (d2 >= d1) {
                        days = Math.max(1, Math.ceil(Math.abs(d2 - d1) / (1000 * 60 * 60 * 24)) + 1);
                    }
                }

                this.newBooking.total_hours = hours;
                this.newBooking.total_price = (basePrice * hours * days).toFixed(2);
            },

            calculateEditTotal() {
                const room = this.meetingRooms.find(r => r.id == this.editingBooking.meeting_room_id);
                const basePrice = (room && room.room_type) ? parseFloat(room.room_type.base_price) : 0;
                let hours = 1;
                if (this.editingBooking.start_time && this.editingBooking.end_time) {
                    const start = parseInt(this.editingBooking.start_time.split(':')[0]);
                    const end = parseInt(this.editingBooking.end_time.split(':')[0]);
                    if (end > start) {
                        hours = end - start;
                    }
                }

                let days = 1;
                if (this.editingBooking.start_date && this.editingBooking.end_date) {
                    const d1 = new Date(this.editingBooking.start_date);
                    const d2 = new Date(this.editingBooking.end_date);
                    if (d2 >= d1) {
                        days = Math.max(1, Math.ceil(Math.abs(d2 - d1) / (1000 * 60 * 60 * 24)) + 1);
                    }
                }

                this.editingBooking.total_hours = hours;
                this.editingBooking.total_price = (basePrice * hours * days).toFixed(2);
            },

            handleEditDateTimeChange() {
                this.calculateEditTotal();
                if (this.editingBooking && this.editingBooking.id) {
                    this.checkAvailableRooms(this.editingBooking.id, this.editingBooking);
                }
            },

            formatDateInput(d) {
                if (!d) return '';
                if (typeof d === 'string') {
                    let parts = d.split('T')[0].split(' ')[0].split('-');
                    if (parts.length === 3) {
                        return `${parts[0]}-${parts[1].padStart(2, '0')}-${parts[2].padStart(2, '0')}`;
                    }
                }
                return d;
            },

            formatTimeInput(t) {
                if (!t) return '08:00';
                if (typeof t === 'string') {
                    let parts = t.split(':');
                    if (parts.length >= 2) {
                        return `${parts[0].padStart(2, '0')}:${parts[1].padStart(2, '0')}`;
                    }
                }
                return t;
            },

            formatSetupStyle(style) {
                if (!style) return 'ធម្មតា (Standard)';
                const map = {
                    'Classroom': 'ថ្នាក់រៀន',
                    'Theater': 'មហោស្រព / សាលប្រជុំ',
                    'U-Shape': 'អក្សរ យូ',
                    'Boardroom': 'ប្រជុំក្រុមប្រឹក្សា',
                    'Banquet': 'តុមូលពិធីលៀងសាយភោជន៍',
                    'Cocktail': 'ជប់លៀងឈរ',
                    'Hollow Square': 'ការ៉េចតុកោណ',
                    'Cabaret': 'តុមូលកន្លះវង់',
                    'Custom': 'រៀបចំពិសេសតាមការស្នើសុំ'
                };
                return map[style] || style;
            },

            calculateDays(sDate, eDate) {
                if (!sDate || !eDate) return 1;
                let s = new Date(sDate);
                let e = new Date(eDate);
                let diff = Math.round((e - s) / (1000 * 60 * 60 * 24)) + 1;
                return isNaN(diff) || diff < 1 ? 1 : diff;
            },

            formatTimeKhmer(t) {
                if (!t) return '';
                t = String(t).trim();
                if (/AM/i.test(t)) return t.replace(/AM/i, '').trim() + ' ព្រឹក';
                if (/PM/i.test(t)) return t.replace(/PM/i, '').trim() + ' ល្ងាច';
                let parts = t.split(':');
                if (parts.length >= 2) {
                    let h = parseInt(parts[0], 10);
                    let m = parts[1];
                    return h < 12 ? `${parts[0].padStart(2, '0')}:${m} ព្រឹក` : `${parts[0].padStart(2, '0')}:${m} ល្ងាច`;
                }
                return t;
            },

            editBooking(booking) {
                const uName = booking.user ? booking.user.name : '';
                const uPhone = booking.user ? booking.user.phone : '';
                const uEmail = booking.user ? booking.user.email : '';

                const sDate = this.formatDateInput(booking.start_date);
                const eDate = this.formatDateInput(booking.end_date);
                const sTime = this.formatTimeInput(booking.start_time);
                const eTime = this.formatTimeInput(booking.end_time);

                this.editingBooking = {
                    id: booking.id,
                    customer_name: booking.customer_name || uName,
                    customer_phone: booking.customer_phone || uPhone,
                    customer_email: booking.customer_email || uEmail,
                    meeting_room_id: booking.meeting_room_id,
                    start_date: sDate,
                    end_date: eDate,
                    start_time: sTime,
                    end_time: eTime,
                    total_hours: booking.total_hours || 9,
                    attendees_count: booking.attendees_count || 10,
                    setup_style: booking.setup_style || '',
                    payment_status: (booking.payment ? booking.payment.status : 'paid'),
                    payment_method: booking.payment_method || (booking.payment ? booking.payment.method : 'cash'),
                    transaction_id: (booking.payment ? booking.payment.transaction_id : ''),
                    status: booking.status || 'pending',
                    total_price: booking.total_price,
                    special_requests: booking.special_requests || ''
                };
                this.checkAvailableRooms(booking.id, this.editingBooking);
                this.showEditModal = true;
            },

            viewDetail(booking) {
                this.selectedBooking = booking;
                this.showDetailModal = true;
            },

            async fetchBookings(url = null) {
                this.loading = true;
                let fetchUrl = url || '{{ route("meeting-bookings.index") }}';
                try {
                    const res = await axios.get(fetchUrl, {
                        params: {
                            search: this.search,
                            status: this.status
                        },
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    document.getElementById('bookings-container').innerHTML = res.data;
                } catch (err) {
                    console.error(err);
                } finally {
                    this.loading = false;
                }
            },

            async saveBooking() {
                this.loading = true;
                this.errors = {};
                try {
                    const res = await axios.post('{{ route("meeting-bookings.store") }}', this.newBooking, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (res.data && res.data.success) {
                        this.showAddModal = false;
                        this.resetForm();
                        this.fetchBookings();

                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'success',
                                message: res.data.message || 'បានកក់សាលប្រជុំដោយជោគជ័យ!'
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
                        const serverMsg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'មានបញ្ហាបច្ចេកទេសក្នុងការរក្សាទុក!';
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                message: serverMsg
                            }
                        }));
                    }
                } finally {
                    this.loading = false;
                }
            },

            async updateBooking() {
                this.loading = true;
                this.errors = {};
                try {
                    const res = await axios.put('/admin/meeting-bookings/' + this.editingBooking.id, this.editingBooking, {
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
                                message: res.data.message || 'បានធ្វើបច្ចុប្បន្នភាពកក់សាលប្រជុំដោយជោគជ័យ!'
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

            async deleteBooking(bookingId) {
                const result = await Swal.fire({
                    title: 'តើអ្នកពិតជាចង់លុបការកក់សាលប្រជុំនេះមែនទេ?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'លុប',
                    cancelButtonText: 'បោះបង់',
                    reverseButtons: true
                });

                if (result.isConfirmed) {
                    this.loading = true;
                    try {
                        const res = await axios.delete('/admin/meeting-bookings/' + bookingId, {
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        if (res.data && res.data.success) {
                            this.fetchBookings();
                            window.dispatchEvent(new CustomEvent('toast', {
                                detail: {
                                    type: 'success',
                                    message: res.data.message || 'បានលុបការកក់សាលប្រជុំដោយជោគជ័យ!'
                                }
                            }));
                        }
                    } catch (err) {
                        console.error(err);
                        window.dispatchEvent(new CustomEvent('toast', {
                            detail: {
                                type: 'error',
                                message: 'មិនអាចលុបទិន្នន័យបានឡើយ!'
                            }
                        }));
                    } finally {
                        this.loading = false;
                    }
                }
            },

            resetForm() {
                this.newBooking = {
                    customer_name: '',
                    customer_phone: '',
                    customer_email: '',
                    meeting_room_id: '',
                    start_date: '{{ date("Y-m-d") }}',
                    end_date: '{{ date("Y-m-d") }}',
                    start_time: '08:00',
                    end_time: '17:00',
                    total_hours: 9,
                    total_price: 0,
                    payment_status: 'paid',
                    payment_method: 'cash',
                    transaction_id: '',
                    setup_style: '',
                    attendees_count: 10,
                    special_requests: ''
                };
                this.errors = {};
            }
        }));
    });
</script>

@endsection