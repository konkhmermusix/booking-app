@extends('layouts.app')
@section('title', 'មតិកែលម្អ និងការវាយតម្លៃពីអតិថិជន | សណ្ឋាគារ ភីអេនធី ផាលេស')
@section('content')

<div class="mx-auto">
    {{-- Hero Section matching about.blade.php --}}
    <div class="pt-20 text-center mb-16 relative z-10">
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
            ការវាយតម្លៃ <span class="text-blue-600">ពីអតិថិជន</span>
        </h1>
        <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
            ស្វែងយល់ពីចំណាប់អារម្មណ៍ និងបទពិសោធន៍ដ៏ពិតប្រាកដរបស់ភ្ញៀវកិត្តិយស ដែលបានមកស្នាក់នៅ និងប្រើប្រាស់សេវាកម្មជាមួយយើង។
        </p>
        <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
    </div>

    {{-- Reviews Content Section matching about.blade.php style --}}
    <section class="py-10 bg-gray-50 dark:bg-[#0b1120]">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-1.5 bg-blue-600 rounded-full"></div>
                    <h4 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                        មតិកែលម្អទាំងអស់ពីអតិថិជន
                    </h4>
                </div>

                @if($reviews->count() > 0)
                @php $avgRating = round($reviews->avg('rating'), 1); @endphp
                <div class="flex items-center gap-4 bg-white dark:bg-gray-900 p-4 px-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
                    <div class="text-center border-r border-gray-100 dark:border-gray-800 pr-4">
                        <span class="text-3xl font-black text-blue-600 dark:text-blue-400 leading-none">{{ $avgRating }}</span>
                        <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">ពិន្ទុពេញ ៥</p>
                    </div>
                    <div>
                        <div class="flex text-amber-400 text-xs mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $avgRating ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            សរុបមាន {{ $reviews->count() }} ការវាយតម្លៃ
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($reviews as $review)
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl relative shadow-sm border border-gray-100 dark:border-gray-800 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex gap-1 text-amber-400 text-xs">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <i class="fas fa-quote-right text-blue-200 dark:text-blue-900/40 text-xl"></i>
                        </div>

                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-sm mb-6 break-words [word-break:break-word]">
                            {{ strip_tags($review->comment) }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 pt-4 border-t border-gray-100 dark:border-gray-800">
                        @if($review->user && $review->user->avatar)
                        <img src="{{ asset('storage/' . $review->user->avatar) }}"
                            alt="{{ $review->name }}"
                            class="w-10 h-10 object-cover rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        @else
                        <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-sm uppercase shadow-sm">
                            {{ mb_substr($review->name, 0, 1, 'UTF-8') }}
                        </div>
                        @endif

                        <div class="overflow-hidden flex-grow">
                            <h5 class="font-bold text-gray-900 dark:text-white text-xs truncate">{{ $review->name }}</h5>
                            <div class="flex items-center justify-between gap-1">
                                <span class="text-[11px] text-blue-600 dark:text-blue-400 font-medium truncate">
                                    {{ optional($review->roomType)->name ?? 'ភ្ញៀវកិត្តិយស' }}
                                </span>
                                <span class="text-[10px] text-gray-400 shrink-0">
                                    {{ $review->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20 bg-white dark:bg-gray-900 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                    <i class="fas fa-comment-slash text-gray-300 dark:text-gray-600 text-5xl mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-base">មិនទាន់មានការវាយតម្លៃនៅឡើយទេ</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</div>

@endsection