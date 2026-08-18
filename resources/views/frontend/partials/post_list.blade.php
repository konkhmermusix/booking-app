{{-- Posts List --}}
@if($posts->count() > 0)
    <div class="space-y-6">
        @foreach($posts as $post)
            <article class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300">
                {{-- Author header --}}
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-blue-600 text-white flex items-center justify-center font-black text-sm border-2 border-white dark:border-gray-800 shadow-sm flex-shrink-0">
                            @if(!empty($post->user->avatar))
                                <img src="{{ asset('storage/' . $post->user->avatar) }}" alt="{{ $post->user->name }}" class="w-full h-full object-cover">
                            @else
                                {{ mb_substr($post->user->name ?? 'PNT', 0, 1, 'utf-8') }}
                            @endif
                        </div>

                        <div>
                            <h2 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                                {{ $post->user->name ?? 'PNT Palace Hotel' }}
                            </h2>
                            <div class="flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500 font-medium">
                                <span>{{ $post->created_at->diffForHumans() }}</span>
                                <span>•</span>
                                <i class="fa-solid fa-earth-americas text-[10px]" title="Public"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Post Content --}}
                <div class="px-4 pb-3">
                    <h3 class="text-base font-extrabold text-gray-900 dark:text-white mb-2 hover:text-blue-600 transition-colors">
                        <a href="{{ route('frontend.posts_detail', $post->id) }}">
                            {{ $post->title }}
                        </a>
                    </h3>
                    <div class="text-sm text-gray-700 dark:text-gray-300 font-normal leading-relaxed break-words">
                        @php
                            $plainText = strip_tags($post->content);
                            $characterLimit = 200;
                        @endphp

                        @if(mb_strlen($plainText, 'utf-8') > $characterLimit)
                            <span id="short-text-{{ $post->id }}">{!! mb_substr($plainText, 0, $characterLimit, 'utf-8') !!}...</span>
                            <span id="full-text-{{ $post->id }}" class="hidden">{!! $plainText !!}</span>
                            <button onclick="toggleText({{ $post->id }})" id="btn-{{ $post->id }}" class="text-blue-600 dark:text-blue-400 font-bold ml-1 hover:underline focus:outline-none">
                                មើលបន្ថែម
                            </button>
                        @else
                            {!! $plainText !!}
                        @endif
                    </div>
                </div>

                {{-- Post Images Grid --}}
                @php
                    $rawImages = $post->images;
                    if (is_string($rawImages)) {
                        $rawImages = json_decode($rawImages, true);
                    }
                    $images = is_array($rawImages) ? array_values($rawImages) : [];
                @endphp
                @if(!empty($images) && count($images) > 0)
                    @php $imgCount = count($images); @endphp
                    <div class="relative bg-gray-50 dark:bg-gray-950 border-t border-b border-gray-100 dark:border-gray-800">
                        <a href="{{ route('frontend.posts_detail', $post->id) }}" class="block">
                            @if($imgCount === 1)
                                <div class="w-full overflow-hidden">
                                    <img src="{{ asset('storage/' . ($images[0] ?? '')) }}" class="w-full h-auto max-h-[500px] object-cover mx-auto hover:scale-102 transition duration-500">
                                </div>
                            @elseif($imgCount === 2)
                                <div class="grid grid-cols-2 gap-0.5">
                                    <img src="{{ asset('storage/' . ($images[0] ?? '')) }}" class="w-full aspect-[4/5] object-cover hover:opacity-95 transition">
                                    <img src="{{ asset('storage/' . ($images[1] ?? '')) }}" class="w-full aspect-[4/5] object-cover hover:opacity-95 transition">
                                </div>
                            @elseif($imgCount === 3)
                                <div class="grid grid-cols-2 gap-0.5">
                                    <img src="{{ asset('storage/' . ($images[0] ?? '')) }}" class="col-span-2 w-full aspect-[16/10] object-cover hover:opacity-95 transition">
                                    <img src="{{ asset('storage/' . ($images[1] ?? '')) }}" class="w-full aspect-square object-cover hover:opacity-95 transition">
                                    <img src="{{ asset('storage/' . ($images[2] ?? '')) }}" class="w-full aspect-square object-cover hover:opacity-95 transition">
                                </div>
                            @elseif($imgCount >= 4)
                                <div class="grid grid-cols-3 gap-0.5">
                                    <img src="{{ asset('storage/' . ($images[0] ?? '')) }}" class="col-span-3 w-full aspect-[16/10] object-cover hover:opacity-95 transition">
                                    <img src="{{ asset('storage/' . ($images[1] ?? '')) }}" class="w-full aspect-square object-cover hover:opacity-95 transition">
                                    <img src="{{ asset('storage/' . ($images[2] ?? '')) }}" class="w-full aspect-square object-cover hover:opacity-95 transition">
                                    <div class="relative w-full aspect-square">
                                        <img src="{{ asset('storage/' . ($images[3] ?? '')) }}" class="w-full h-full object-cover">
                                        @if($imgCount > 4)
                                            <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px] flex items-center justify-center text-white font-black text-lg">
                                                +{{ $imgCount - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </a>
                    </div>
                @endif

                {{-- Post Footer views --}}
                <div class="px-4 py-3 flex justify-between items-center text-xs text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 font-medium bg-gray-50/50 dark:bg-gray-900/30">
                    <div class="flex items-center gap-1.5">
                        <i class="fa-regular fa-eye text-blue-500 text-sm"></i>
                        <span>បានចូលមើល {{ number_format($post->views) }} ដង</span>
                    </div>
                    <a href="{{ route('frontend.posts_detail', $post->id) }}" class="text-blue-600 dark:text-blue-400 font-bold hover:underline flex items-center gap-1">
                        អានលម្អិត <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </article>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8 flex justify-center">
        {{ $posts->links() }}
    </div>
@else
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-12 text-center shadow-sm border border-gray-100 dark:border-gray-800">
        <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
            <i class="fa-solid fa-newspaper text-2xl"></i>
        </div>
        <h4 class="font-bold text-gray-700 dark:text-gray-300">មិនទាន់មានការបង្ហោះព័ត៌មានទេ</h4>
        <p class="text-xs text-gray-400 mt-1">សូមមកពិនិត្យមើលសារជាថ្មីនៅពេលក្រោយ</p>
    </div>
@endif
