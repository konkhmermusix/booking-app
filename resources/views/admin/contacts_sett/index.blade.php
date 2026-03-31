@extends('layouts.admin')
@section('title', 'គ្រប់គ្រងព័ត៌មានទំនាក់ទំនង')

@section('content')
<div class="p-2 sm:p-2" x-data="{ 
        showAddModal: false, 
        showEditModal: false, 
        showDeleteModal: false,
        currentContact: {},
        currentContact: {},
        search: '', 
        status: '',
        loading: false,

        async fetchContacts() {
            this.loading = true;
            try {
                const response = await axios.get('{{ route('contacts_sett.index') }}', {
                    params: { search: this.search, status: this.status },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                document.getElementById('contacts-container').innerHTML = response.data;
            } catch (error) { console.error('Error:', error); }
            this.loading = false;
        },

        openEdit(contact) {
            this.currentContact = { ...contact };
            this.showEditModal = true;
        }
    }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-[1.5rem] shadow-sm mb-6 border border-gray-100 dark:border-gray-800">
        <div>
            <h2 class="text-lg font-bold dark:text-white">ព័ត៌មានទំនាក់ទំនង</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Contact & Map Settings</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <div class="relative w-full sm:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[10px]"></i>
                <input type="text" x-model="search" @input.debounce.500ms="fetchContacts()" placeholder="ស្វែងរកតាមចំណងជើង..."
                    class="w-full pl-8 pr-4 h-10 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-blue-500/50 dark:text-white transition-all">
            </div>

            <div class="w-full sm:w-32">
                <select x-model="status" @change="fetchContacts()" class="w-full h-10 px-3 bg-gray-50 dark:bg-gray-800 border-none rounded-xl text-sm font-medium cursor-pointer dark:text-gray-300">
                    <option value="">ស្ថានភាព</option>
                    <option value="1">បង្ហាញ (Active)</option>
                    <option value="0">លាក់ (Inactive)</option>
                </select>
            </div>

            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែម
            </button>
        </div>
    </div>

    <div id="contacts-container" :class="loading ? 'opacity-40' : ''" class="transition-opacity duration-300">
        @include('admin.contacts_sett.partials.contacts_sett_list')
    </div>

    @include('admin.contacts_sett.modals')
</div>
@endsection