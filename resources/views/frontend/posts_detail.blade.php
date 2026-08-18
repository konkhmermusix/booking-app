@extends('layouts.app')
@section('title', $post->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Back Button --}}
    <div class="mb-6">
        <a href="{{ route('frontend.posts') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            <span>ត្រឡប់ទៅកាន់បញ្ជីព័ត៌មាន</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <article class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">

                {{-- Author Header --}}
                <div class="p-5 flex items-center justify-between border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-11 h-11 rounded-full overflow-hidden bg-blue-600 text-white flex items-center justify-center font-black text-sm border-2 border-blue-100 dark:border-gray-700 shadow-sm flex-shrink-0">
                            @if(!empty($post->user->avatar))
                            <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                            @else
                            {{ mb_substr($post->user->name ?? 'PNT', 0, 1, 'utf-8') }}
                            @endif
                        </div>

                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                                <span class="cursor-pointer hover:underline">{{ $post->user->name ?? 'PNT Palace Hotel' }}</span>
                            </h2>
                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400 font-medium mt-0.5">
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                <span>•</span>
                                <span class="flex items-center gap-1 text-[11px] text-gray-400">
                                    <i class="fa-regular fa-eye"></i> {{ number_format($post->views) }} មើល
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full border border-blue-100 dark:border-blue-800">
                            ព័ត៌មានផ្លូវការ
                        </span>
                    </div>
                </div>

                {{-- Post Content Body --}}
                <div class="p-6">
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white mb-6 leading-tight">
                        {{ $post->title }}
                    </h1>

                    <div class="prose max-w-none text-base text-gray-800 dark:text-gray-200 leading-relaxed dark:prose-invert space-y-4">
                        {!! function_exists('clean') ? clean($post->content) : $post->content !!}
                    </div>
                </div>

                {{-- Image Gallery --}}
                @if(!empty($post->images) && is_array($post->images) && count($post->images) > 0)
                <div class="p-6 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/50">
                    <p class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-images text-blue-500"></i> អាល់ប៊ុមរូបភាព ({{ count($post->images) }} សន្លឹក)
                    </p>

                    <div class="space-y-4">
                        @foreach($post->images as $image)
                        <div class="w-full rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 shadow-sm">
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $post->title }}" class="w-full h-auto max-h-[650px] object-contain mx-auto transition-transform hover:scale-[1.01] duration-300">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </article>
        </div>

        {{-- Sidebar Related Posts --}}
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-sm border border-gray-200 dark:border-gray-800 sticky top-20">
                <h3 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider mb-5 pb-3 border-b dark:border-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-newspaper text-blue-500"></i> ការបង្ហោះថ្មីៗផ្សេងទៀត
                </h3>

                <div class="space-y-4">
                    @forelse($relatedPosts as $relPost)
                    <a href="{{ route('frontend.posts_detail', $relPost->id) }}" class="flex gap-3.5 group block p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-gray-100 dark:bg-gray-800 flex-shrink-0 border border-gray-100 dark:border-gray-800">
                            @if(!empty($relPost->images) && is_array($relPost->images) && count($relPost->images) > 0)
                            <img src="{{ asset('storage/' . $relPost->images[0]) }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-200 dark:bg-gray-800">
                                <i class="fa-solid fa-image text-lg"></i>
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <h4 class="text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug mb-1.5">
                                {{ $relPost->title }}
                            </h4>
                            <div class="flex items-center gap-2 text-[10px] text-gray-400 dark:text-gray-500">
                                <span class="truncate font-semibold text-gray-600 dark:text-gray-400">
                                    {{ $relPost->user->name ?? 'Admin' }}
                                </span>
                                <span>•</span>
                                <span>{{ $relPost->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                    @empty
                    <p class="text-xs text-gray-400 text-center py-4">គ្មានការបង្ហោះផ្សេងទៀតឡើយ</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection