@extends('layouts.admin')

@section('title', 'បញ្ជីសារទំនាក់ទំនង')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 

        showEditModal: false,
        showDetailModal: false,
        currentMessage: { id: '', name: '', email: '', tell: '', description: '', status: '', created_at: '' },
        search: '', status: '',
        loading: false,

        editMessage(message) {
            let tempDiv = document.createElement('div');
            tempDiv.innerHTML = message.description;
            let plainText = tempDiv.textContent || tempDiv.innerText || '';

            this.currentMessage = { ...message, description: plainText.trim() };
            this.showEditModal = true;
        },

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
            let tempDiv = document.createElement('div');
            tempDiv.innerHTML = message.description.replace(/<\/p>/g, '\n').replace(/<br\s*\/?>/g, '\n'); 
            
            let plainText = tempDiv.textContent || tempDiv.innerText || '';

            this.currentMessage = { 
                ...message, 
                description: plainText.trim() 
            };
            this.showDetailModal = true;
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">បញ្ជីសារទំនាក់ទំនងភ្ញៀវ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Customer Inquiries</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchMessages()" placeholder="ស្វែងរកឈ្មោះ ឬអ៊ីមែល និងលេខទូរស័ព្ទ"
                    class="w-full pl-8 pr-4 h-10 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm font-medium">
            </div>

            <div class="w-full sm:w-32">
                <div class="relative group">
                    <select x-model="status" @change="fetchMessages()"
                        class="w-full h-10 px-3 rounded-xl border-none bg-gray-50 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition-all appearance-none text-sm font-medium relative z-0">
                        <option value="">ទាំងអស់</option>
                        <option value="unread">មិនទាន់អាន</option>
                        <option value="completed">បានអានរួច</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                </div>
            </div>

            <button @click="fetchMessages()" class="h-10 w-10 flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-500 rounded-xl hover:bg-gray-200 transition-all">
                <i class="fas fa-sync-alt" :class="loading ? 'animate-spin' : ''"></i>
            </button>
        </div>
    </div>

    <div id="messages-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.contacts.partials.messages_list')
    </div>

    @include('admin.contacts.modals')
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