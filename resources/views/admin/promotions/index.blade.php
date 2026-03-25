@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងការបញ្ចុះតម្លៃ')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 
        viewMode: 'table', 
        showAddModal: false, 
        showEditModal: false, // បន្ថែមសម្រាប់ Edit
        currentPromo: {},     // សម្រាប់កាន់ទិន្នន័យពេល Edit
        search: '', 
        status: '', 
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

        // Method សម្រាប់លុប (កែសម្រួល syntax ឱ្យត្រូវតាម Alpine.js)
        async deletePromo(id) {
            if (!confirm('តើអ្នកប្រាកដជាចង់លុបការបញ្ចុះតម្លៃនេះមែនទេ?')) return;
            
            try {
                const response = await axios.delete(`{{ route('promotions.index') }}/${id}`, {
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });

                if (response.data.status === 'success') {
                    // ហៅ fetchPromos ផ្ទាល់ពីក្នុង scope នេះតែម្តង
                    await this.fetchPromos();
                    alert(response.data.message);
                }
            } catch (error) {
                console.error('Delete Error:', error);
                alert('មានបញ្ហាក្នុងការលុប!');
            }
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-[1.5rem] shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white text-blue-600">គ្រប់គ្រងការបញ្ចុះតម្លៃ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Special Offers Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <input type="text" x-model="search" @input.debounce.500ms="fetchPromos()" placeholder="ស្វែងរកចំណងជើង..."
                class="w-full sm:w-56 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm">

            <select x-model="status" @change="fetchPromos()" class="h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm">
                <option value="">ស្ថានភាពទាំងអស់</option>
                <option value="1">កំពុងដំណើរការ</option>
                <option value="0">ផ្អាក</option>
            </select>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2">
                <i class="fas fa-plus-circle"></i> បន្ថែមប្រូម៉ូសិន
            </button>
        </div>
    </div>

    <div id="promos-container" :class="loading ? 'opacity-40' : ''">
        @include('admin.promotions.partials.promo-list')
    </div>

    @include('admin.promotions.modals')
</div>
@endsection