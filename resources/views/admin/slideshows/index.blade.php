@extends('layouts.admin')
@section('content')
<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, showDetailModal: false, currentSlide: {} }">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold dark:text-white uppercase tracking-tight">គ្រប់គ្រង Slideshow</h2>
        <button @click="showAddModal = true" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl shadow-lg transition-all flex items-center gap-2 font-bold">
            <i class="fas fa-plus"></i> បន្ថែមថ្មី
        </button>
    </div>

    <div class="bg-white dark:bg-gray-900 rounded-3xl border dark:border-gray-800 overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 dark:bg-gray-800/50 text-gray-400 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">រូបភាព</th>
                    <th class="px-6 py-4">ចំណងជើង</th>
                    <th class="px-6 py-4">ចំណងជើងរង</th>
                    <th class="px-6 py-4">លំដាប់</th>
                    <th class="px-6 py-4">ស្ថានភាព</th>
                    <th class="px-6 py-4 text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-800">
                @foreach($slides as $slide)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/'.$slide->image_path) }}" class="w-20 h-12 rounded-xl object-cover border dark:border-gray-700">
                    </td>
                    <td class="px-6 py-4 font-bold dark:text-white">{{ $slide->title ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-bold dark:text-white">{{ $slide->subtitle ?? 'N/A' }}</td>
                    <td class="px-6 py-4 dark:text-gray-400">#{{ $slide->order_column }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $slide->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right flex gap-3 justify-end">
                        <div class="flex justify-end gap-2 space-x-3">
                            <button @click="currentSlide = {{ $slide->toJson() }}; showDetailModal = true" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg"><i class="fas fa-eye"></i></button>
                            <button @click="currentSlide = {{ $slide->toJson() }}; showEditModal = true" class="text-amber-500 hover:bg-amber-50 p-2 rounded-lg"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('slideshows.destroy', $slide) }}" method="POST" class="inline" onsubmit="return confirm('លុប Slide នេះ?')">
                                @csrf @method('DELETE')
                                <button class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-all"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="p-4 border-t dark:border-gray-800">{{ $slides->links() }}</div>
    </div>
    @include('admin.slideshows.modals')
</div>
@endsection