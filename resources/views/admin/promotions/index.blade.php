@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងការបញ្ចុះតម្លៃ')

@section('content')
<div class="p-2 sm:p-2" x-data="{
        viewMode: localStorage.getItem('promoView') || 'table',  
        showAddModal: false, showEditModal: false, showDetailModal: false,
        currentPromo: {}, 
        search: '{{ request('search') }}', status: '{{ request('status') }}',
        loading: false,

        // Method សម្រាប់ទាញទិន្នន័យ
        async fetchPromos(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('promotions.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { search: this.search, status: this.status },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('promos-container').innerHTML = response.data;
            } catch (error) { 
                console.error('Fetch Error:', error); 
            }
            this.loading = false;
        },

        // Method សម្រាប់លុបប្រូម៉ូសិន
        async deletePromo(id) {
            try {
                const response = await axios.delete(`{{ route('promotions.index') }}/${id}`, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });

                if (response.data.status === 'success') {
                    await this.fetchPromos();
                    alert(response.data.message);
                }
            } catch (error) {
                console.error('Delete Error:', error);
                alert('មានបញ្ហាក្នុងការលុប!');
            }
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងការបញ្ចុះតម្លៃ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Special Offers Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchPromos()" placeholder="ស្វែងរកចំណងជើង..."
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <div class="w-full sm:w-25">
                <div class="relative group">
                    <select x-model="status" @change="fetchPromos()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">ទាំងអស់</option>
                        <option value="1">បង្ហាញ</option>
                        <option value="0">មិនបង្ហាញ</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <div class="flex bg-gray-100 dark:bg-gray-800/50 p-1 rounded-xl h-10 items-center">
                <button @click="viewMode = 'table'; localStorage.setItem('promoView', 'table')" :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="បង្ហាញជាតារាង"><i class="fas fa-table-list"></i></button>
                <button @click="viewMode = 'list'; localStorage.setItem('promoView', 'list')" :class="viewMode === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="បង្ហាញជាបញ្ជី"><i class="fas fa-list-ul"></i></button>
                <button @click="viewMode = 'grid'; localStorage.setItem('promoView', 'grid')" :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'" class="w-8 h-full rounded-lg transition-all flex items-center justify-center text-[10px]" title="បង្ហាញជាប្លុក"><i class="fas fa-th-large"></i></button>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែមប្រូម៉ូសិន
            </button>
        </div>
    </div>

    <div x-show="loading" x-cloak class="mb-10 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-4 py-3 rounded-xl text-sm animate-pulse text-center">
        កំពុងដំណើរការ...
    </div>

    <div id="promos-container" :class="loading ? 'opacity-40 pointer-events-none' : ''" class="transition-opacity duration-300">

        @include('admin.promotions.partials.promo_list')
    </div>

    @include('admin.promotions.modals')
</div>

<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a, .custom-pagination a, #promos-container .pagination a');
        if (link) {
            e.preventDefault();
            const alpineElement = document.querySelector('[x-data]');
            if (alpineElement && typeof Alpine !== 'undefined' && Alpine.$data) {
                Alpine.$data(alpineElement).fetchPromos(link.href);
            } else if (alpineElement && alpineElement.__x && alpineElement.__x.$data) {
                alpineElement.__x.$data.fetchPromos(link.href);
            }
        }
    });
</script>
@endsection