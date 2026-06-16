@extends('layouts.admin')
@section('title', 'ការគ្រប់គ្រងការកក់')

@section('content')

<div class="p-2 sm:p-2" x-data="bookingManager()" x-init="init()">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងការកក់</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Real-time Booking System</p>
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
                        <option value="">ស្ថានភាពទាំងអស់</option>
                        <option value="pending">រង់ចាំ</option>
                        <option value="confirmed">បញ្ជាក់ហើយ</option>
                        <option value="completed">បញ្ចប់</option>
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

    <div x-show="loading" x-cloak class="mb-4 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse​ text-center">
        កំពុងដំណើរការ...
    </div>

    <div id="bookings-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">
        @include('admin.bookings.partials.booking_list')
    </div>

    @include('admin.bookings.modals')

</div>

<script>
    function bookingManager() {
        return {
            viewMode: localStorage.getItem('bookingView') || 'table',
            showAddModal: false,
            showEditModal: false,
            showDetailModal: false,

            editingBooking: {},
            selectedBooking: {},

            loading: false,
            search: '{{ request('
            search ') }}',
            status: '{{ request('
            status ') }}',
            rooms: @js($rooms),
            hotels: @js($hotels),
            errors: {},
            notyf: null,
            min_date: '',

            newBooking: {
                hotel_id: '',
                room_id: '',
                check_in: '',
                check_out: '',
                price_per_night: 0,
                total_price: 0,
                payment_method: 'cash',
                special_requests: ''
            },

            editBooking(booking) {
                // ចម្លងទិន្នន័យចេញពី Object booking ក្នុងជួរនីមួយៗ
                this.editingBooking = {
                    id: booking.id,
                    room_id: booking.room_id,
                    check_in: booking.check_in,
                    check_out: booking.check_out,
                    payment_method: booking.payment_method,
                    total_price: booking.total_price,
                    special_requests: booking.special_requests
                };
                this.showEditModal = true;
            },



            viewDetail(booking) {
                this.selectedBooking = booking;
                this.showDetailModal = true;
            },

            init() {
                this.min_date = this.formatDate(new Date());
                // កំណត់ Notyf Configuration
                this.notyf = new Notyf({
                    duration: 3000,
                    position: {
                        x: 'right',
                        y: 'top'
                    },
                    dismissible: true,
                    types: [{
                            type: 'success',
                            background: '#10b981', // ពណ៌ Emerald-600
                            icon: {
                                className: 'fas fa-check-circle',
                                color: '#fff'
                            }
                        },
                        {
                            type: 'error',
                            background: '#ef4444', // ពណ៌ Red-500
                            icon: {
                                className: 'fas fa-exclamation-circle',
                                color: '#fff'
                            }
                        }
                    ]
                });
                this.fetchBookings();

                this.$watch('newBooking.room_id', () => this.calculateTotal());
                this.$watch('newBooking.check_in', () => this.calculateTotal());
                this.$watch('newBooking.check_out', () => this.calculateTotal());
            },

            formatDate(date) {
                let yyyy = date.getFullYear();
                let mm = String(date.getMonth() + 1).padStart(2, '0');
                let dd = String(date.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            },

            setView(mode) {
                this.viewMode = mode;
                localStorage.setItem('bookingView', mode);
            },

            // គណនាតម្លៃសរុប (JavaScript Logic)
            calculateTotal() {
                const room = this.rooms.find(r => r.id == this.newBooking.room_id);
                if (room) {
                    this.newBooking.price_per_night = room.room_type.base_price;
                    this.newBooking.hotel_id = room.hotel_id;
                }

                if (this.newBooking.check_in && this.newBooking.check_out) {
                    const start = new Date(this.newBooking.check_in);
                    const end = new Date(this.newBooking.check_out);

                    if (end > start) {
                        const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                        this.newBooking.total_price = diffDays * this.newBooking.price_per_night;
                    } else {
                        this.newBooking.total_price = 0;
                    }
                }
            },

            async fetchBookings(url = null) {
                this.loading = true;
                let fetchUrl = url || '{{ route("bookings.index") }}';

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
                    const res = await axios.post('{{ route("bookings.store") }}', this.newBooking);
                    if (res.data.success) {
                        this.showAddModal = false;
                        this.resetForm();

                        // បង្ហាញ Notyf ជោគជ័យ
                        this.notyf.success(res.data.message);

                        this.fetchBookings();
                    }
                } catch (err) {
                    if (err.response?.status === 422) {
                        this.errors = err.response.data.errors;
                    } else {
                        console.error(err);
                        alert('មានបញ្ហាបច្ចេកទេសក្នុងការរក្សាទុក!');
                    }
                } finally {
                    this.loading = false;
                }
            },

            async updateBooking() {
                this.loading = true;
                try {
                    const res = await axios.put(`/admin/bookings/${this.editingBooking.id}`, this.editingBooking);
                    if (res.data.success) {
                        this.showEditModal = false;
                        this.notyf.success(res.data.message);
                        this.fetchBookings(); // ទាញទិន្នន័យថ្មីមកបង្ហាញក្នុង Table
                    }
                } catch (err) {
                    this.notyf.error("មិនអាចកែសម្រួលបានទេ!");
                } finally {
                    this.loading = false;
                }
            },

            resetForm() {
                this.newBooking = {
                    hotel_id: '',
                    room_id: '',
                    check_in: '',
                    check_out: '',
                    total_price: 0,
                    price_per_night: 0,
                    payment_method: 'cash'
                };
                this.errors = {};
            },

            resetFormCategory() {
                // សម្អាតតម្លៃចាស់ៗចេញពេល Admin ប្តូរប្រភេទនៃការកក់នៅលើ UI
                this.newBooking.room_id = '';
                this.newBooking.meeting_room_id = '';
                this.newBooking.total_price = 0;
                this.newBooking.total_hours = '';
                this.newBooking.attendees_count = '';
                this.newBooking.setup_style = '';
                this.errors = {};
            }
        }
    }

    // Pagination Click Handler
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const alpine = Alpine.$data(document.querySelector('[x-data]'));
            alpine.fetchBookings(link.href);
        }
    });
</script>

@endsection