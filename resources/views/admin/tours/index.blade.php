@extends('layouts.admin')
@section('title', 'បញ្ជីកន្លែងទេសចរណ៍')

@section('content')

<div class="space-y-6"
    x-data="{
viewMode: localStorage.getItem('tourView') || 'list',
showAddModal:false,
showEditModal:false,
currentTour:{}
}">

    <!-- Header -->

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-5 rounded-[2rem] border dark:border-gray-800 shadow-sm">

        <div>
            <h2 class="text-2xl font-bold dark:text-white">គ្រប់គ្រងកន្លែងទេសចរណ៍</h2>
        </div>

        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">

            <form action="{{ route('tours.index') }}" method="GET"
                class="flex flex-col sm:flex-row items-center gap-3">

                <div class="relative w-full sm:w-64">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>

                    <input type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="ស្វែងរកកន្លែង..."
                        class="w-full pl-10 pr-4 h-[52px] bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 dark:text-white">

                </div>

                <select name="status"
                    onchange="this.form.submit()"
                    class="h-[52px] px-4 bg-gray-50 dark:bg-gray-800 border-none rounded-2xl text-sm">

                    <option value="">ស្ថានភាពទាំងអស់</option>

                    <option value="1"
                        {{ request('status')=='1'?'selected':'' }}>
                        សកម្ម
                    </option>

                    <option value="0"
                        {{ request('status')=='0'?'selected':'' }}>
                        ផ្អាក
                    </option>

                </select>

            </form>

            <!-- view mode -->

            <div class="flex bg-gray-100 dark:bg-gray-800 p-1.5 rounded-2xl h-[52px] items-center">

                <button
                    @click="viewMode='list'; localStorage.setItem('tourView','list')"
                    :class="viewMode==='list' ? 'bg-white shadow text-blue-600' : 'text-gray-400'"
                    class="w-10 h-full rounded-xl flex items-center justify-center"> <i class="fas fa-list"></i> </button>

                <button
                    @click="viewMode='grid'; localStorage.setItem('tourView','grid')"
                    :class="viewMode==='grid' ? 'bg-white shadow text-blue-600' : 'text-gray-400'"
                    class="w-10 h-full rounded-xl flex items-center justify-center"> <i class="fas fa-th"></i> </button>

            </div>

            <button
                @click="showAddModal=true"
                class="h-[52px] px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl flex items-center gap-2 font-bold">

                <i class="fas fa-plus-circle"></i> <span>បន្ថែមកន្លែង</span>

            </button>

        </div>

    </div>

    <!-- LIST VIEW -->

    <template x-if="viewMode==='list'">

        <div class="bg-white dark:bg-gray-900 rounded-[2rem] border dark:border-gray-800 overflow-hidden shadow-sm">

            <table class="w-full text-left">

                <thead class="bg-gray-50 dark:bg-gray-800 border-b">

                    <tr>

                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">រូបភាព</th>

                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">ឈ្មោះ</th>

                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">ចម្ងាយ</th>

                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">Google Map</th>

                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase">ស្ថានភាព</th>

                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">សកម្មភាព</th>

                    </tr>

                </thead>

                <tbody class="divide-y dark:divide-gray-800">

                    @foreach($tours as $tour)

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">

                        <td class="px-6 py-4">

                            <img
                                src="{{ $tour->image ? asset('storage/'.$tour->image) : 'https://via.placeholder.com/80' }}"
                                class="w-14 h-14 rounded-xl object-cover">

                        </td>

                        <td class="px-6 py-4 font-bold dark:text-white">

                            {{ $tour->name }}

                        </td>

                        <td class="px-6 py-4">

                            {{ $tour->distance }}

                        </td>

                        <td class="px-6 py-4">

                            <a
                                href="{{ $tour->google_map_link }}"
                                target="_blank"
                                class="text-blue-600 hover:underline">
                                View Map </a>

                        </td>

                        <td class="px-6 py-4">

                            @if($tour->status)

                            <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">
                                សកម្ម
                            </span>

                            @else

                            <span class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">
                                ផ្អាក
                            </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-right">

                            <div class="flex justify-end gap-2">

                                <button
                                    @click="currentTour={{ $tour->toJson() }}; showEditModal=true"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg text-amber-600 hover:bg-amber-50">

                                    <i class="fas fa-edit"></i>

                                </button>

                                <form
                                    action="{{ route('tours.destroy',$tour->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg text-red-600 hover:bg-red-50">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </template>

    <!-- GRID VIEW -->

    <template x-if="viewMode==='grid'">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach($tours as $tour)

            <div class="bg-white dark:bg-gray-900 rounded-[2rem] border dark:border-gray-800 p-5 shadow-sm hover:shadow-md">

                <div class="aspect-video rounded-xl overflow-hidden mb-4">

                    <img
                        src="{{ $tour->image ? asset('storage/'.$tour->image) : 'https://via.placeholder.com/400' }}"
                        class="w-full h-full object-cover">

                </div>

                <h3 class="font-bold text-lg dark:text-white mb-2">

                    {{ $tour->name }}

                </h3>

                <p class="text-sm text-gray-500 mb-2">

                    {{ $tour->distance }}

                </p>

                <a
                    href="{{ $tour->google_map_link }}"
                    target="_blank"
                    class="text-blue-600 text-sm">

                    View Google Map

                </a>

                <div class="flex gap-2 mt-4">

                    <button
                        @click="currentTour={{ $tour->toJson() }}; showEditModal=true"
                        class="flex-1 h-10 bg-gray-100 rounded-xl font-bold text-sm hover:bg-blue-600 hover:text-white">

                        Edit

                    </button>

                    <form
                        action="{{ route('tours.destroy',$tour->id) }}"
                        method="POST"
                        class="flex-1">

                        @csrf
                        @method('DELETE')

                        <button
                            class="w-full h-10 bg-red-100 text-red-600 rounded-xl font-bold">

                            Delete

                        </button>

                    </form>

                </div>

            </div>

            @endforeach

        </div>

    </template>

    <!-- Pagination -->

    <div class="bg-white dark:bg-gray-900 p-5 rounded-[2rem] border dark:border-gray-800">

        {{ $tours->links() }}

    </div>

    @include('admin.tours.modals')

</div>

@endsection