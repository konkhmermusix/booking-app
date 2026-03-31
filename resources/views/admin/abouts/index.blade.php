@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងប្រវត្តិរូប')

@section('content')
<div x-data="{ 
    showAddModal: false, 
    showEditModal: false, 
    showDetailModal: false, 
    currentHistory: {},
    search: '', 
    status: '', 
    loading: false,
    async fetchData(url = null) {
        this.loading = true;
        let fetchUrl = url || '{{ route('abouts.index') }}'; 
        try {
            const response = await axios.get(fetchUrl, {
                // បញ្ជូន status ទៅកាន់ Controller
                params: { search: this.search, status: this.status },
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            document.getElementById('history-container').innerHTML = response.data;
        } catch (error) { console.error(error); }
        this.loading = false;
    }
}" x-init="fetchData()">


    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-[1.5rem] shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">ប្រវត្តិរូបសង្ខេប</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Timeline Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-56">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchData()" placeholder="ស្វែងរកឆ្នាំ ឬចំណងជើង..."
                    class="w-full pl-8 pr-4 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
            </div>

            <div class="w-full sm:w-25">
                <select x-model="status" @change="fetchData()" class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium cursor-pointer">
                    <option value="">ទាំងអស់</option>
                    <option value="1">សកម្ម</option>
                    <option value="0">អសកម្ម</option>
                </select>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែម
            </button>
        </div>
    </div>

    <div id="history-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.abouts.partials.history-list')
    </div>

    @include('admin.abouts.modals')
</div>

<script>
    // Handle Pagination for Ajax
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            // ទាញយក Scope របស់ Alpine.js
            const alpine = document.querySelector('[x-data]').__x.$data;
            alpine.fetchData(link.href);
        }
    });
</script>
@endsection