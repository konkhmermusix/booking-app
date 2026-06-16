@extends('layouts.app')
@section('title', $roomType->name . ' - ព័ត៌មានលម្អិត')
@section('content')

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    /* កំណត់ទម្រង់អក្សរឡើងវិញសម្រាប់អត្ថបទដែលចេញពី CKEditor */
    .ck-content h1 {
        font-size: 2em;
        font-weight: bold;
        margin-top: 0.67em;
        margin-bottom: 0.67em;
    }

    .ck-content h2 {
        font-size: 1.5em;
        font-weight: bold;
        margin-top: 0.83em;
        margin-bottom: 0.83em;
    }

    .ck-content h3 {
        font-size: 1.17em;
        font-weight: bold;
        margin-top: 1em;
        margin-bottom: 1em;
    }

    .ck-content h4 {
        font-size: 1em;
        font-weight: bold;
        margin-top: 1.33em;
        margin-bottom: 1.33em;
    }

    .ck-content p {
        margin-top: 0;
        margin-bottom: 1rem;
    }

    /* បន្ថែមទម្រង់សម្រាប់ List ក្រែងលោវាអត់ចេញចំនុចចុច */
    .ck-content ul {
        list-style-type: disc;
        padding-left: 40px;
    }

    .ck-content ol {
        list-style-type: decimal;
        padding-left: 40px;
    }
</style>


<div class="container mx-auto">
    <section class="bg-gray-50 dark:bg-[#0b1120] py-10">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <div class="lg:col-span-2">
                    <div class="space-y-4">
                        <div class="relative overflow-hidden rounded-2xl shadow-xl group border border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
                            @if($roomType->images->count() > 0)
                            <img id="mainImage"
                                src="{{ asset('storage/' . $roomType->images->first()->image_path) }}"
                                class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover duration-700 group-hover:scale-105">

                            <button type="button" onclick="zoomMainImage()"
                                class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-3 py-2 rounded-xl shadow hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white text-gray-700 dark:text-gray-300 duration-300 focus:outline-none z-10">
                                <i class="fas fa-expand"></i>
                            </button>
                            @else
                            <img id="mainImage" src="{{ asset('storage/' . $roomType->image_path) }}"
                                class="w-full h-64 sm:h-80 md:h-[420px] lg:h-[520px] object-cover">

                            <button type="button" onclick="zoomMainImage()"
                                class="absolute top-3 right-3 bg-white/90 dark:bg-gray-900/90 backdrop-blur px-3 py-2 rounded-xl shadow hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white text-gray-700 dark:text-gray-300 duration-300 focus:outline-none z-10">
                                <i class="fas fa-expand"></i>
                            </button>
                            @endif
                        </div>

                        @if($roomType->images->count() > 0)
                        <div class="space-y-2">
                            <div class="relative group/slider">
                                <button onclick="scrollThumbs(-1)" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-800 dark:text-white shadow-lg w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white border border-gray-100 dark:border-gray-700 transition-colors opacity-0 group-hover/slider:opacity-100 duration-300">
                                    <i class="fas fa-chevron-left text-sm"></i>
                                </button>

                                <div id="thumbContainer" class="flex gap-3 overflow-x-auto pb-3 pt-1 custom-scrollbar scroll-smooth px-1">
                                    @foreach($roomType->images as $index => $img)
                                    <div class="flex-shrink-0 group/img">
                                        <img src="{{ asset('storage/' . $img->image_path) }}"
                                            data-index="{{ $index }}"
                                            onclick="changeImage(this)"
                                            class="thumbItem w-24 h-20 rounded-xl object-cover cursor-pointer border-2 {{ $index == 0 ? 'border-blue-500' : 'border-gray-100 dark:border-gray-800' }} hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-300 shadow-sm group-hover/img:scale-95">
                                    </div>
                                    @endforeach
                                </div>

                                <button onclick="scrollThumbs(1)" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white dark:bg-gray-800 dark:text-white shadow-lg w-9 h-9 rounded-full flex items-center justify-center hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white border border-gray-100 dark:border-gray-700 transition-colors opacity-0 group-hover/slider:opacity-100 duration-300">
                                    <i class="fas fa-chevron-right text-sm"></i>
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="space-y-2">
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 uppercase font-black tracking-wider italic">រូបភាពទាំងអស់នៅក្នុងអាល់ប៊ុម (Gallery)</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 italic">គ្មានរូបភាពផ្សេងទៀតទេ</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="sticky top-24 bg-white dark:bg-gray-900 rounded-2xl md:rounded-2xl shadow-xl p-4 md:p-6 space-y-5 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                        <div class="flex items-baseline justify-between border-b border-gray-50 dark:border-gray-800/60 pb-4">
                            <div class="flex items-baseline gap-1">
                                <h2 class="text-2xl md:text-3xl font-black text-blue-600 dark:text-blue-400">
                                    ${{ number_format($roomType->base_price,0) }}
                                </h2>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">/ យប់ </p>
                            </div>
                        </div>

                        <div id="totalPriceWrapper" class="hidden">
                            <div id="totalPriceDisplay"
                                class="w-full bg-green-50 dark:bg-green-950/40 border border-green-100 dark:border-green-900/50 p-3.5 rounded-xl text-green-700 dark:text-green-400 font-bold text-sm flex items-center">
                            </div>
                        </div>

                        <form action="{{ route('cart.add.hotel') }}" method="POST" class="space-y-4">
                            @csrf

                            <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                            <input type="hidden" name="promo_price" value="{{ $roomType->discounted_price }}">

                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូលស្នាក់នៅ
                                    </label>
                                    <input type="date" name="check_in" id="check_in" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]" required>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                        <i class="fas fa-calendar-alt text-blue-600 dark:text-blue-500 mr-1"></i> ថ្ងៃចាកចេញ
                                    </label>
                                    <input type="date" name="check_out" id="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]" required>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full h-12 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95">
                                កក់ឥឡូវនេះ
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BOTTOM: DETAILS & FACILITIES --}}
            <div class="mt-10 md:mt-14 bg-white dark:bg-gray-900 rounded-2xl md:rounded-3xl shadow-lg border border-gray-100 dark:border-gray-800 p-5 md:p-8 transition-colors duration-300">
                <h1 class="text-2xl md:text-4xl font-black mb-4 text-gray-900 dark:text-white">
                    {{ $roomType->name }}
                </h1>

                <div class="flex flex-wrap gap-3 mb-8 text-gray-700 dark:text-gray-300">
                    <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                        <i class="fas fa-bed mr-2 text-blue-600 dark:text-blue-400"></i>គ្រែ៖ {{ $roomType->beds ?? 1 }} គ្រែ
                    </span>
                    <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                        <i class="fas fa-users mr-2 text-blue-600 dark:text-blue-400"></i>ស្នាក់នៅបាន៖ {{ $roomType->max_guests }} នាក់
                    </span>
                </div>

                <h2 class="text-xl md:text-2xl font-bold mb-3 border-l-4 border-blue-600 dark:border-blue-500 pl-3 text-gray-900 dark:text-white">
                    ព័ត៌មានលម្អិតពីបន្ទប់
                </h2>
                <div class="text-gray-600 dark:text-gray-400 leading-relaxed mb-10 text-sm md:text-base ck-content">
                    {!! $roomType->description !!}
                </div>

                <h2 class="text-xl md:text-2xl font-bold mb-5 border-l-4 border-blue-600 dark:border-blue-500 pl-3 text-gray-900 dark:text-white">
                    គ្រឿងបរិក្ខារ និងបច្ចេកវិទ្យាដែលផ្តល់ជូន
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @forelse($roomType->facilities as $facility)
                    <div class="bg-gray-50 dark:bg-gray-800/40 rounded-2xl p-4 flex items-center gap-3 border border-gray-100 dark:border-gray-800 transition-colors duration-300">
                        <i class="{{ $facility->icon ?? 'fas fa-check-circle' }} text-blue-600 dark:text-blue-400 text-lg flex-shrink-0"></i>
                        <span class="font-medium text-gray-700 dark:text-gray-300 text-sm">
                            {{ $facility->name }}
                        </span>
                    </div>
                    @empty
                    <div class="text-sm text-gray-400 dark:text-gray-500 col-span-full py-6 text-center bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <i class="fas fa-info-circle mr-1"></i> មិនទាន់មានទិន្នន័យគ្រឿងបរិក្ខារនៅឡើយទេ។
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-10 bg-white dark:bg-gray-900 rounded-2xl md:rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800 p-5 md:p-8 transition-colors duration-300">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-5 mb-6 gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold border-l-4 border-blue-600 dark:border-blue-500 pl-3 text-gray-900 dark:text-white">
                        ការវាយតម្លៃ និងមតិយោបល់
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 pl-4">
                        សរុបមានការវាយតម្លៃចំនួន {{ $roomType->reviews->count() }}
                    </p>
                </div>

                @if($roomType->reviews->count() > 0)
                @php $avgRating = round($roomType->reviews->avg('rating'), 1); @endphp
                <div class="flex items-center gap-3 bg-blue-50 dark:bg-blue-950/30 px-4 py-2 rounded-xl border border-blue-100 dark:border-blue-900/40 w-fit">
                    <span class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $avgRating }}</span>
                    <div>
                        <div class="flex text-amber-400 text-xs">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $avgRating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 font-medium">ពិន្ទុពេញ ៥ ផ្កាយ</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6 max-h-[600px] overflow-y-auto pr-2 scrollbar-hide">
                    @forelse($roomType->reviews->where('parent_id', null) as $review)
                    <div class="bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-gray-100 dark:border-gray-800/60">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-600 text-white font-bold rounded-xl flex items-center justify-center uppercase text-sm">
                                    {{ mb_substr($review->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">{{ $review->name }}</h4>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                            </div>
                        </div>

                        <div id="review-comment-{{ $review->id }}" class="text-gray-600 dark:text-gray-300 text-sm pl-12">
                            {!! $review->comment !!}
                        </div>

                        <div id="edit-form-{{ $review->id }}" class="hidden mt-2 pl-12">
                            <form action="{{ route('frontend.room_details.update', $review->id) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PUT')
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs text-gray-400">កែផ្កាយ</span>
                                    <select name="rating" class="bg-white dark:bg-gray-900 border text-xs rounded-lg p-1 text-amber-500">
                                        @for($j = 5; $j >= 1; $j--)
                                        <option value="{{ $j }}" {{ $review->rating == $j ? 'selected' : '' }}>{{ str_repeat('🌟', $j) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <textarea name="comment" required class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-2.5 rounded-xl outline-none text-xs dark:text-white focus:ring-1 ring-blue-500" rows="2">{!! strip_tags($review->comment) !!}</textarea>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold">រក្សាទុក</button>
                                    <button type="button" onclick="document.getElementById('edit-form-{{ $review->id }}').classList.add('hidden')" class="bg-gray-400 text-white px-3 py-1.5 rounded-lg text-xs font-bold">បោះបង់</button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-3 pl-12 flex items-center gap-4">
                            <button onclick="document.getElementById('reply-form-{{ $review->id }}').classList.toggle('hidden')"
                                class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-reply fa-flip-horizontal"></i> ឆ្លើយតប
                            </button>

                            @if(Auth::check() && ($review->user_id == Auth::id() || Auth::id() == 1))
                            <button onclick="document.getElementById('edit-form-{{ $review->id }}').classList.toggle('hidden')"
                                class="text-xs font-bold text-amber-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-edit"></i> កែប្រែមតិ
                            </button>

                            <form action="{{ route('frontend.room_details.delete', $review->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-600 hover:underline flex items-center gap-1">
                                    <i class="fas fa-trash-alt"></i> លុបមតិ
                                </button>
                            </form>
                            @endif
                        </div>

                        {{-- Form សម្រាប់ផ្ញើមតិឆ្លើយតប --}}
                        <div id="reply-form-{{ $review->id }}" class="hidden mt-4 pl-12">
                            <form action="{{ route('frontend.room_details.replay', $roomType->id) }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                                <input type="hidden" name="parent_id" value="{{ $review->id }}">
                                <input type="text" name="comment" required placeholder="សរសេរការឆ្លើយតបរបស់អ្នកទីនេះ..."
                                    class="w-full bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-700 px-3 py-3 rounded-xl outline-none text-xs dark:text-white focus:ring-1 ring-blue-500">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap">
                                    ផ្ញើមតិ
                                </button>
                            </form>
                        </div>

                        @if($review->replies->count() > 0)
                        <div class="mt-4 pl-12 space-y-3 border-l-2 border-gray-200 dark:border-gray-700">
                            @foreach($review->replies as $reply)
                            <div class="bg-white dark:bg-gray-900/60 p-3 rounded-xl border border-gray-100/80 dark:border-gray-800/40 ml-2">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 bg-gray-600 text-white font-bold rounded-lg flex items-center justify-center uppercase text-xs">
                                            {{ mb_substr($reply->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h5 class="text-xs font-bold text-gray-900 dark:text-white flex items-center gap-1">
                                                {{ $reply->name }}
                                                @if($reply->user_id == 1)
                                                <span class="bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400 text-[9px] px-1.5 py-0.5 rounded font-black">ADMIN</span>
                                                @endif
                                            </h5>
                                            <p class="text-[10px] text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div id="reply-comment-{{ $reply->id }}" class="text-gray-600 dark:text-gray-300 text-xs pl-9">
                                    {!! $reply->comment !!}
                                </div>

                                <div id="edit-reply-form-{{ $reply->id }}" class="hidden mt-2 pl-9">
                                    <form action="{{ route('frontend.room_details.update', $reply->id) }}" method="POST" class="space-y-2">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="comment" required class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-2 rounded-xl outline-none text-xs dark:text-white focus:ring-1 ring-blue-500" rows="2">{!! strip_tags($reply->comment) !!}</textarea>
                                        <div class="flex gap-2">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-md text-[11px] font-bold">កែប្រែមតិ</button>
                                            <button type="button" onclick="document.getElementById('edit-reply-form-{{ $reply->id }}').classList.add('hidden')" class="bg-gray-400 text-white px-2.5 py-1 rounded-md text-[11px] font-bold">បោះបង់</button>
                                        </div>
                                    </form>
                                </div>

                                @if(Auth::check() && ($reply->user_id == Auth::id() || Auth::id() == 1))
                                <div class="mt-2 pl-9 flex items-center gap-3 border-t border-gray-50 dark:border-gray-800 pt-1.5">
                                    <button onclick="document.getElementById('edit-reply-form-{{ $reply->id }}').classList.toggle('hidden')"
                                        class="text-[11px] font-bold text-amber-600 hover:underline">
                                        កែប្រែមតិ
                                    </button>
                                    <form action="{{ route('frontend.room_details.delete', $reply->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] font-bold text-red-600 hover:underline">
                                            លុបមតិ
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-sm text-gray-400 py-12 text-center bg-gray-50 rounded-2xl">មិនទាន់មានការវាយតម្លៃឡើយ</div>
                    @endforelse
                </div>

                <div class="bg-gray-50 dark:bg-gray-800/30 border border-gray-100 dark:border-gray-800/80 p-5 rounded-2xl h-fit">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        វាយតម្លៃបន្ទប់ស្នាក់
                    </h3>

                    <form action="{{ route('frontend.room_details.store', $roomType->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                        @auth
                        <div class="mb-3 p-3.5 bg-blue-50 dark:bg-blue-950/20 rounded-xl border border-blue-100/50 dark:border-blue-900/30">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                <span class="text-blue-600 dark:text-blue-400 font-bold">{{ Auth::user()->name }}</span>
                            </p>
                        </div>
                        @else
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 ml-1">ឈ្មោះរបស់អ្នក *</label>
                            <input type="text" name="name" required placeholder="ឈ្មោះ"
                                class="w-full bg-gray-50 dark:bg-gray-800 border border-slate-200 dark:border-slate-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                        </div>
                        @endauth

                        <div class="flex flex-col">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                ផ្តល់ពិន្ទុផ្កាយ *
                            </label>
                            <div class="relative group">
                                <select name="rating" required
                                    class="w-full bg-gray-50 dark:bg-gray-800 border border-slate-200 dark:border-slate-700 px-4 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white appearance-none cursor-pointer transition-all font-medium text-sm h-[52px]">
                                    <option value="5" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟🌟🌟🌟 </option>
                                    <option value="4" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟🌟🌟 </option>
                                    <option value="3" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟🌟 </option>
                                    <option value="2" class="bg-white dark:bg-gray-800 text-amber-500">🌟🌟 </option>
                                    <option value="1" class="bg-white dark:bg-gray-800 text-amber-500">🌟 </option>
                                </select>
                                <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[10px] text-gray-400 pointer-events-none transition-transform group-focus-within:rotate-180"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1.5 ml-1">មតិយោបល់បន្ថែម</label>
                            <textarea name="comment" rows="3" placeholder="មតិយោបល់បន្ថែម..."
                                class="w-full p-4 bg-gray-50 dark:bg-gray-800 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm transition-all"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full h-11 flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-all active:scale-95 shadow-md shadow-blue-500/10">
                            ផ្ញើការវាយតម្លៃ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    const promoPrice = parseFloat("{{ $roomType->discounted_price }}") || 0;

    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');

        if (checkInInput && checkOutInput) {
            checkInInput.addEventListener('change', calculateStayPrice);
            checkOutInput.addEventListener('change', calculateStayPrice);
            calculateStayPrice();
        }
    });

    function changeImage(element) {
        const mainImage = document.getElementById('mainImage');
        if (!mainImage) return;

        mainImage.src = element.src;

        document.querySelectorAll('.thumbItem').forEach(thumb => {
            thumb.classList.remove('border-blue-600', 'border-blue-500');
            thumb.classList.add('border-gray-100', 'dark:border-gray-800');
        });

        element.classList.remove('border-gray-100', 'dark:border-gray-800');
        element.classList.add('border-blue-500');
    }

    function scrollThumbs(direction) {
        const container = document.getElementById('thumbContainer');
        if (container) {
            container.scrollBy({
                left: direction * 240,
                behavior: 'smooth'
            });
        }
    }

    function zoomMainImage() {
        const thumbElements = document.querySelectorAll('.thumbItem');
        const mainImage = document.getElementById('mainImage');

        if (!mainImage) return;

        let imagesArray = [];
        let activeIndex = 0;

        if (thumbElements.length > 0) {
            thumbElements.forEach((thumb, idx) => {
                imagesArray.push({
                    src: thumb.src
                });
                if (thumb.src === mainImage.src) {
                    activeIndex = idx;
                }
            });
        } else {
            imagesArray.push({
                src: mainImage.src
            });
        }

        if (typeof Spotlight !== 'undefined') {
            Spotlight.show(imagesArray, {
                index: activeIndex + 1,
                theme: 'dark',
                infinite: true
            });
        } else {
            window.open(mainImage.src, '_blank');
        }
    }
</script>

@endsection