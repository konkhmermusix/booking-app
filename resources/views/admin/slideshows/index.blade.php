@extends('layouts.admin')

@section('title', 'គ្រប់គ្រងបដារ')
@section('content')
<div class="space-y-6" x-data="{ showAddModal: false, showEditModal: false, showDetailModal: false, currentSlide: {} }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-[1.5rem] shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងបដារ</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">Slideshow Management</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
            <button @click="showAddModal = true" class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fas fa-plus-circle"></i> បន្ថែម
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-[1.5rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50 dark:bg-gray-900/50">
                    <tr>

                        <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">រូបភាព</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ចំណងជើង</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ចំណងជើងរង</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">លំដាប់</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider">ស្ថានភាព</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                    @forelse($slides as $slide)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <img src="{{ asset('storage/'.$slide->image_path) }}" class="w-20 h-12 rounded-xl object-cover border dark:border-gray-700">
                        </td>
                        <td class="px-6 py-4 font-bold text-blue-600 dark:text-blue-400">{{ $slide->title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $slide->subtitle }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ $slide->order_column }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $slide->is_active ? 'បង្ហាញ' : 'មិនបង្ហាញ' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium">
                            <div class="flex justify-end items-center gap-1">
                                <button type="button" @click="currentSlide = {{ $slide->toJson() }}; showDetailModal = true" class="p-2 text-gray-400 hover:text-blue-500 transition-colors" title="មើលលម្អិត"><i class="fas fa-eye text-sm"></i></button>
                                <button type="button" @click="currentSlide = {{ $slide->toJson() }}; showEditModal = true" class="p-2 text-gray-400 hover:text-amber-500 transition-colors" title="កែប្រែ"><i class="fas fa-edit text-sm"></i></button>
                                <button type="button"
                                    onclick="confirmDelete('{{ $slide->id }}')"
                                    class="p-2 text-gray-400 hover:text-red-500 transition-colors"
                                    title="លុប">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <div class="col-span-full">@include('admin.rooms.partials.empty_state')</div>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-sm border-none transition-colors">
        <div class="dark:text-white">
            {{ $slides->links() }}
        </div>
    </div>
    @include('admin.slideshows.modals')
</div>

<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'តើអ្នកប្រាកដទេ?',
            text: "អ្នកនឹងមិនអាចត្រឡប់ទិន្នន័យនេះមកវិញបានទេ!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'លុប',
            cancelButtonText: 'បោះបង់',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('delete-form');
                form.action = `/admin/slideshows/${id}`;
                form.submit();
            }
        })
    }
</script>

@endsection