@extends('layouts.admin')

@section('title', 'គ្រប់គ្រងរូបភាព')

@section('content')
<div class="p-2 sm:p-2" x-data="{ previews: [], showUpload: false }">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-white dark:bg-gray-900 p-4 rounded-2xl shadow-sm mb-6">
        <div>
            <h2 class="text-lg font-bold dark:text-white">គ្រប់គ្រងរូបភាព</h2>
            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-semibold">សណ្ឋាគារ៖ {{ $hotel->name }}</p>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">

            <button @click="showUpload = !showUpload"
                class="h-10 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl shadow-md text-sm font-bold flex items-center gap-2 transition-all active:scale-95">
                <i class="fa-solid" :class="showUpload ? 'fa-xmark' : 'fa-plus-circle'"></i>
                <span x-text="showUpload ? 'បោះបង់' : 'បន្ថែម'"></span>
            </button>
        </div>
    </div>

    <div x-show="showUpload"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="mb-10 bg-white dark:bg-gray-900 p-8 rounded-2xl border-none shadow-xl shadow-blue-500/5">

        <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="relative group border-4 border-dashed border-gray-100 dark:border-gray-800 rounded-2xl p-10 text-center transition-all hover:border-blue-500/50 hover:bg-blue-50/10">
                    <input type="file" name="images[]" multiple accept="image/*" required
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        @change="previews = Array.from($event.target.files).map(file => URL.createObjectURL(file))">

                    <div class="space-y-4">
                        <div class="w-20 h-20 bg-blue-50 dark:bg-blue-500/10 text-blue-500 rounded-2xl flex items-center justify-center mx-auto transition-transform group-hover:scale-110">
                            <i class="fa-solid fa-images text-3xl"></i>
                        </div>
                        <div>
                            <p class="font-black uppercase tracking-widest text-sm text-gray-500 dark:text-gray-300">ទាញរូបភាពដាក់ទីនេះ</p>
                            <p class="text-[10px] text-gray-400 uppercase mt-1">ឬចុចដើម្បីជ្រើសរើស (JPG, PNG, WEBP)</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-between">
                    <div>
                        <label class="block text-[11px] font-black uppercase text-gray-400 mb-4 tracking-widest ml-2">រូបភាពដែលបានរើស (<span x-text="previews.length"></span>)</label>
                        <div class="grid grid-cols-4 gap-3 max-h-48 overflow-y-auto p-2">
                            <template x-for="(src, index) in previews" :key="index">
                                <div class="aspect-square rounded-2xl overflow-hidden border-2 border-white dark:border-gray-800 shadow-sm relative group">
                                    <img :src="src" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-all"></div>
                                </div>
                            </template>
                            <template x-if="previews.length === 0">
                                <div class="col-span-4 py-10 text-center bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">មិនទាន់មានរូបភាព</span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-4 rounded-2xl">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="hidden peer">
                            <div class="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-[10px] font-black uppercase text-gray-500 tracking-widest">បង្ហាញភ្លាមៗ</span>
                        </label>

                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white rounded-xl font-black uppercase text-[11px] tracking-widest shadow-lg shadow-blue-500/25 hover:bg-blue-700 transition-all active:scale-95">
                            រក្សាទុក
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @forelse($hotel->galleries as $gallery)
        <div class="group relative aspect-[3/4] rounded-2xl overflow-hidden bg-white dark:bg-gray-900 shadow-sm transition-all hover:shadow-2xl hover:-translate-y-2 border-4 border-white dark:border-gray-800">

            <img src="{{ asset('storage/' . $gallery->image) }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 {{ !$gallery->is_active ? 'opacity-40 grayscale' : '' }}">

            <div class="absolute top-4 left-4">
                <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-tighter shadow-sm {{ $gallery->is_active ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white' }}">
                    {{ $gallery->is_active ? 'សកម្ម' : 'ផ្អាក' }}
                </span>
            </div>

            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col justify-end p-6">
                <div class="flex gap-2">
                    <form action="{{ route('galleries.update', $gallery->id) }}" method="POST" class="flex-1">
                        @csrf @method('PUT')
                        <input type="hidden" name="is_active" value="{{ $gallery->is_active ? 0 : 1 }}">

                        <button type="submit" class="w-full py-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg" title="កែប្រែ">
                            {{ $gallery->is_active ? 'បិទ' : 'បើក' }}
                        </button>
                    </form>

                    <form action="{{ route('galleries.destroy', $gallery->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="button" onclick="confirmDelete(this.form)"
                            class="w-10 h-10 bg-red-500 text-white rounded-xl flex items-center justify-center hover:bg-red-600 transition-all" title="លុប">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-gray-50 dark:bg-gray-800/30 rounded-2xl border-4 border-dashed border-gray-100 dark:border-gray-800">
            <div class="text-gray-300 mb-4 text-6xl">
                <i class="fa-solid fa-images"></i>
            </div>
            <h3 class="text-gray-400 font-black uppercase tracking-widest">មិនទាន់មានរូបភាពក្នុង Gallery នៅឡើយទេ</h3>
        </div>
        @endforelse
    </div>
</div>

@endsection