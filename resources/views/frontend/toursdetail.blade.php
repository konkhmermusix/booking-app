@extends('layouts.app')
@section('title', $tour->name)
@section('content')

<section class="bg-gray-50 dark:bg-[#0b1120] min-h-screen pb-12">
    <div class="relative h-[40vh] md:h-[55vh] overflow-hidden">
        @php
        $randomImage = collect($tour->image)->random();
        @endphp

        <img src="{{ asset('storage/' . ($randomImage ?? 'default.jpg')) }}"
            class="w-full h-full object-cover" alt="{{ $tour->name }}">

        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight uppercase">
                {{ $tour->name }}
            </h1>
            <div class="h-1.5 w-20 bg-blue-600 mx-auto rounded-full"></div>
        </div>
    </div>

    <div class="mx-auto px-4 sm:px-6 lg:px-8 mt-10 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <div class="lg:col-span-3 order-last lg:order-first">
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 sticky top-24">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-6 w-1.5 bg-blue-600 rounded-full"></div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight">
                            កន្លែងផ្សេងៗ
                        </h2>
                    </div>

                    <ul class="space-y-4">
                        @foreach($otherTours as $item)
                        <li>
                            <a href="{{ route('toursdetail', $item->id) }}" class="flex items-start gap-3 p-3 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all group">

                                <div>
                                    <p class="text-sm font-bold text-gray-700 dark:text-gray-200 group-hover:text-blue-600 transition-colors">
                                        {{ $item->name }}
                                    </p>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-9">
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 sticky top-24">
                    <div class="flex items-center justify-between gap-4 mb-8 pb-6 border-b border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-3">
                            <div class="h-6 w-1.5 bg-blue-600 rounded-full"></div>
                            <h2 class="text-xl md:text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight">
                                ព័ត៌មានលម្អិត
                            </h2>
                        </div>
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 dark:text-blue-400 hover:gap-3 transition-all group">
                            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
                            <span>ត្រឡប់ទៅវិញ</span>
                        </a>
                    </div>

                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        <div class="text-gray-600 dark:text-gray-300 leading-[2.2] text-lg space-y-6 font-medium">
                            {!! nl2br(e($tour->description)) !!}
                        </div>
                    </div>

                    @if(count($tour->image ?? []) > 1)
                    <div class="mt-12 pt-10 border-t border-gray-100 dark:border-gray-800">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-6 w-1.5 bg-blue-600 rounded-full"></div>
                            <h2 class="text-xl md:text-2xl font-black text-gray-800 dark:text-white uppercase tracking-tight">
                                រូបភាពពីទីតាំងនេះ
                            </h2>
                        </div>
                        <div class="columns-1 md:columns-2 gap-4 space-y-4">
                            @foreach(array_slice($tour->image, 0) as $img)
                            <div class="relative group break-inside-avoid rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all border border-gray-100 dark:border-gray-800">
                                <img src="{{ asset('storage/' . $img) }}"
                                    class="w-full h-auto object-cover hover:scale-105 transition duration-700"
                                    loading="lazy">

                                <button type="button"
                                    onclick="openImageModal('{{ asset('storage/' . $img) }}')"
                                    class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-3 py-2 rounded-xl shadow opacity-0 group-hover:opacity-100 hover:bg-blue-600 hover:text-white duration-300 transition-opacity">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Image Modal -->
<div id="imageModal" class="fixed inset-0 z-[999] hidden bg-black/95 backdrop-blur-sm flex items-center justify-center p-4">
    <!-- ប៊ូតុងបិទ -->
    <button onclick="closeImageModal()" class="absolute top-5 right-5 text-white text-3xl hover:text-blue-500 transition-colors">
        <i class="fas fa-times"></i>
    </button>

    <!-- កន្លែងបង្ហាញរូបភាព -->
    <div class="max-w-5xl w-full flex justify-center">
        <img id="modalImage" src="" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain">
    </div>
</div>

<script>
    function openImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');

        modalImg.src = imageSrc; // ដាក់ URL រូបភាពចូលក្នុង Modal
        modal.classList.remove('hidden'); // បង្ហាញ Modal
        document.body.style.overflow = 'hidden'; // បិទ scroll bar របស់ page កុំឱ្យរញ៉េរញ៉ៃ
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden'); // លាក់ Modal
        document.body.style.overflow = 'auto'; // បើក scroll bar វិញ
    }

    // បិទ Modal ពេលចុចលើផ្ទៃខ្មៅ (ខាងក្រៅរូបភាព)
    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeImageModal();
        }
    }
</script>
@endsection