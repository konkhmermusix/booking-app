@extends('layouts.admin')

@section('title', 'បញ្ជីសារទំនាក់ទំនង')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 
        showDetailModal: false,
        currentMessage: { name: '', email: '', tell: '', description: '', status: '', created_at: '' },
        search: '', status: '',
        loading: false,

        async fetchMessages(url = null) {
            this.loading = true;
            let fetchUrl = url || '{{ route('contact.index') }}';
            try {
                const response = await axios.get(fetchUrl, {
                    params: { search: this.search, status: this.status },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('messages-container').innerHTML = response.data;
            } catch (error) { console.error('Error:', error); }
            this.loading = false;
        },

        openDetail(message) {
            this.currentMessage = message;
            this.showDetailModal = true;
            // ប្រសិនបើសារនៅ Unread យើងអាចហៅ Ajax ដើម្បីដូរទៅ Read នៅទីនេះ
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-[1.5rem] shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">សារទំនាក់ទំនង</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Customer Inquiries</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchMessages()" placeholder="ស្វែងរកឈ្មោះ ឬអ៊ីមែល..."
                    class="w-full pl-8 pr-4 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
            </div>

            <div class="w-full sm:w-32">
                <select x-model="status" @change="fetchMessages()" class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium cursor-pointer dark:text-gray-300">
                    <option value="">ទាំងអស់</option>
                    <option value="unread">មិនទាន់អាន</option>
                    <option value="pending">កំពុងដោះស្រាយ</option>
                    <option value="completed">រួចរាល់</option>
                </select>
            </div>

            <button @click="fetchMessages()" class="h-10 w-10 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-xl hover:bg-gray-200 transition-all">
                <i class="fas fa-sync-alt" :class="loading ? 'animate-spin' : ''"></i>
            </button>
        </div>
    </div>

    <div id="messages-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.contacts.partials.messages-list')
    </div>

    @include('admin.contacts.modal-detail')
</div>

<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            const alpine = document.querySelector('[x-data]').__x.$data;
            alpine.fetchMessages(link.href);
        }
    });
</script>

@endsection