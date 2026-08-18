@extends('layouts.admin')

@section('title', 'ការវាយតម្លៃពីភ្ញៀវ')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 

        showEditModal: false,
        showDetailModal: false,
        currentReview: { id: '', name: '', email: '', rating: 5, comment: '', status: 1, created_at: '', room_type_name: '' },
        search: '', status: '',
        loading: false,

        editReview(review) {
            this.currentReview = { 
                id: review.id, 
                name: review.name, 
                email: review.user ? review.user.email : 'N/A', 
                rating: review.rating, 
                comment: review.comment || '', 
                status: review.status, 
                created_at: review.created_at,
                room_type_name: review.room_type ? review.room_type.name : 'N/A'
            };
            this.showEditModal = true;
        },

        async fetchReviews(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('reviews.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { search: this.search, status: this.status },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('reviews-container').innerHTML = response.data;
            } catch (error) { console.error('Error:', error); }
            this.loading = false;
        },
        
        openDetail(review) {
            this.currentReview = { 
                id: review.id, 
                name: review.name, 
                email: review.user ? review.user.email : 'N/A', 
                rating: review.rating, 
                comment: review.comment || '', 
                status: review.status, 
                created_at: review.created_at,
                created_at_formatted: new Date(review.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }),
                room_type_name: review.room_type ? review.room_type.name : 'N/A'
            };
            this.showDetailModal = true;
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">បញ្ជីការវាយតម្លៃពីភ្ញៀវ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Guest Reviews</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchReviews()" placeholder="ស្វែងរកឈ្មោះ ឬមតិយោបល់ និងប្រភេទបន្ទប់"
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <div class="w-full sm:w-32">
                <div class="relative group">
                    <select x-model="status" @change="fetchReviews()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">ទាំងអស់</option>
                        <option value="1">បង្ហាញ</option>
                        <option value="0">លាក់</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <button @click="fetchReviews()" class="h-10 w-10 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-xl hover:bg-gray-200 transition-all">
                <i class="fas fa-sync-alt" :class="loading ? 'animate-spin' : ''"></i>
            </button>
        </div>
    </div>

    <div id="reviews-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.reviews.partials.reviews_list')
    </div>

    @include('admin.reviews.modals')
</div>

<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const rootElement = document.querySelector('[x-data]');
            if (rootElement && typeof Alpine !== 'undefined' && Alpine.$data) {
                Alpine.$data(rootElement).fetchReviews(link.href);
            } else if (rootElement && rootElement.__x) {
                rootElement.__x.$data.fetchReviews(link.href);
            }
        }
    });
</script>

@endsection
