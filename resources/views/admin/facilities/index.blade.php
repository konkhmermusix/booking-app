@extends('layouts.admin')
@section('title', 'បញ្ជីគ្រឿងបរិក្ខារ')

@section('content')
<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, showDetailModal: false, currentFacility: {}}">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold dark:text-white">គ្រប់គ្រងគ្រឿងបរិក្ខារ</h2>
            <p class="text-gray-500 dark:text-gray-400">គ្រប់គ្រងសម្ភារៈក្នុងបន្ទប់ និងសណ្ឋាគារ</p>
        </div>

        <button @click="showAddModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-2xl shadow-xl shadow-blue-500/20 transition-all flex items-center justify-center gap-2 font-bold">
            <i class="fas fa-plus-circle"></i>បន្ថែម
        </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-2xl border dark:border-gray-800 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">គ្រឿងបរិក្ខារ</th>
                    <th class="px-6 py-4">ប្រភេទ</th>
                    <th class="px-6 py-4">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-800">
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
                            {{ $facility->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <button @click="currentFacility = {{ $facility->toJson() }}; showDetailModal = true" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button @click="currentFacility = {{ $facility->toJson() }}; showEditModal = true" class="p-2 text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-lg transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>

                            <form action="{{ route('facilities.destroy', $facility) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors" onclick="return confirm('តើអ្នកពិតជាចង់លុបទិន្នន័យនេះមែនទេ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="px-6 py-4 border-t dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
            {{ $facilities->links() }}
        </div>
    </div>

    @include('admin.facilities.modals')
</div>
@endsection