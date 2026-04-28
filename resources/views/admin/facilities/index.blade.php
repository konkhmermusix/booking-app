@extends('layouts.admin')

@section('title', 'បញ្ជីគ្រឿងបរិក្ខារ')

@section('content')
<div class="p-2 sm:p-2" x-data="{ showAddModal: false, showEditModal: false, showDetailModal: false, currentFacility: {}}">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងគ្រឿងបរិក្ខារ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">គ្រប់គ្រងសម្ភារៈក្នុងបន្ទប់ និងសណ្ឋាគារ</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែម
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl dark:border-gray-800 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-sm uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">គ្រឿងបរិក្ខារ</th>
                    <th class="px-6 py-4">ប្រភេទ</th>
                    <th class="px-6 py-4">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @foreach($facilities as $facility)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-600 dark:text-blue-400 border dark:border-blue-500/20">
                                <i class="{{ $facility->icon ?? 'fas fa-box' }} text-lg"></i>
                            </div>
                            <div>
                                <div class="font-bold dark:text-white">{{ $facility->name }}</div>
                                <div class="text-xs text-gray-400">ID: #{{ str_pad($facility->id, 4, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm font-medium dark:text-gray-300">
                            {{ $facility->type === 'room' ? 'សម្រាប់បន្ទប់' : 'សម្រាប់សណ្ឋាគារ' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $facility->is_active ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                            {{ $facility->is_active ? 'បង្ហាញ' : 'មិនបង្ហាញ' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 text-right flex gap-3 justify-end">
                        <div class="flex justify-end gap-2 space-2">
                            <button @click="currentFacility = {{ $facility->toJson() }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                            <button @click="currentFacility = {{ $facility->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>

                            <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST" class="inline-block">
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

    <div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
        <div class="dark:text-white">
            {{ $facilities->links() }}
        </div>
    </div>

    @include('admin.facilities.modals')
</div>

@endsection