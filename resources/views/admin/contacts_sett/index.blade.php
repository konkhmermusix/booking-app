@extends('layouts.admin')

@section('title', 'បញ្ជីព័ត៌មានទំនាក់ទំនង')

@section('content')
<div class="p-2 sm:p-2" x-data="{ showAddModal: false, showEditModal: false, showEditModal: false, currentSetting: {} }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងព័ត៌មានទំនាក់ទំនង</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">គ្រប់គ្រងលេខទូរស័ព្ទ អ៊ីមែល និងទីតាំងផែនទី</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែម
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm dark:border-gray-800">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-sm uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">ព័ត៌មាន / Label</th>
                    <th class="px-6 py-4">តម្លៃ (Value)</th>
                    <th class="px-6 py-4">រូបតំណាង (Icon)</th>
                    <th class="px-6 py-4 text-center">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @foreach($settings as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            {{-- ប្តូរ Class ទៅតាមតម្លៃ color របស់ item --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg border
                            {{ $item->color == 'emerald' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20' : 
                            ($item->color == 'red' ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20' : 
                            ($item->color == 'amber' ? 'bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20' : 
                            ($item->color == 'purple' ? 'bg-purple-50 text-purple-600 dark:bg-purple-500/10 dark:text-purple-400 dark:border-purple-500/20' : 
                            'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20'))) }}">

                                <i class="{{ $item->icon ?? 'fas fa-info-circle' }}"></i>
                            </div>

                            <div>
                                <div class="font-bold dark:text-white">{{ $item->label }}</div>
                                <div class="text-xs text-gray-400 uppercase tracking-tighter">{{ $item->key }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-xs truncate text-sm font-medium dark:text-gray-300">
                            {{ $item->value }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="max-w-xs truncate text-sm font-medium dark:text-gray-300">
                            {{ $item->icon }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $item->status ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                            {{ $item->status ? 'បង្ហាញ' : 'មិនបង្ហាញ' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button @click="currentSetting = {{ json_encode($item) }}; showDetailModal = true"
                                class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលម្អិត">
                                <i class="fas fa-eye text-sm"></i>
                            </button>

                            <button @click="currentSetting = {{ json_encode($item) }}; showEditModal = true"
                                class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ">
                                <i class="fas fa-edit text-sm"></i>
                            </button>

                            <form action="{{ route('contacts_sett.destroy', $item->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    onclick="confirmDelete(this.form)"
                                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    title="លុប">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if(method_exists($settings, 'links'))
    <div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
        <div class="dark:text-white">
            {{ $settings->links() }}
        </div>
    </div>
    @endif

    @include('admin.contacts_sett.modals')
</div>

@endsection