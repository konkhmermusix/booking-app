@extends('layouts.app')
@section('title', $roomType->name . ' - ព័ត៌មានលម្អិតសាលប្រជុំ')
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

    .ck-content ul {
        list-style-type: disc;
        padding-left: 40px;
    }

    .ck-content ol {
        list-style-type: decimal;
        padding-left: 40px;
    }
</style>

<div class="w-full bg-gray-50 dark:bg-[#0b1120] py-10">
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
                        <div class="flex items-baseline gap-1 flex-wrap">
                            <h2 class="text-2xl md:text-3xl font-black text-blue-600 dark:text-blue-400">
                                ${{ $roomType->base_price == floor($roomType->base_price) ? number_format($roomType->base_price, 0) : number_format($roomType->base_price, 2) }}
                            </h2>
                            <span class="text-xs font-bold text-gray-400 font-mono">({{ number_format($roomType->base_price * $khrRate) }} ៛)</span>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">/ ម៉ោង</p>
                        </div>
                    </div>

                    <div id="totalPriceWrapper" class="hidden">
                        <div id="totalPriceDisplay"
                            class="w-full bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 p-3.5 rounded-xl text-blue-700 dark:text-blue-400 font-bold text-sm flex items-center shadow-sm">
                        </div>
                    </div>

                    <form action="{{ route('cart.add.meeting') }}" method="POST" class="space-y-4">
                        @csrf

                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">
                        <input type="hidden" name="meeting_id" value="{{ $roomType->meeting_id }}">

                        <div class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                    <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចាប់ផ្តើមប្រជុំ
                                </label>
                                <input type="date" name="start_date" id="start_date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required
                                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                    <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃបញ្ចប់ប្រជុំ
                                </label>
                                <input type="date" name="end_date" id="end_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                        <i class="fas fa-clock text-blue-600 mr-1"></i> ម៉ោងផ្តើម
                                    </label>
                                    <input type="time" name="start_time" id="start_time" value="08:00" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase ml-1">
                                        <i class="fas fa-clock text-blue-600 mr-1"></i> ម៉ោងបញ្ចប់
                                    </label>
                                    <input type="time" name="end_time" id="end_time" value="17:00" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 p-3 rounded-xl focus:ring-2 ring-blue-500 outline-none text-gray-900 dark:text-white text-sm h-[52px]">
                                </div>
                            </div>
                        </div>
                        <div id="availabilityStatus" class="mt-2 text-xs font-bold transition-all">
                            @if(($availableRoomsCount ?? 1) > 0)
                                <span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 p-2.5 rounded-xl border border-emerald-100 dark:border-emerald-900/50">
                                    <i class="fas fa-check-circle text-emerald-500"></i> ទំនេរសម្រាប់កក់ {{ $availableRoomsCount }} សាលប្រជុំ
                                </span>
                            @else
                                <span class="text-rose-600 dark:text-rose-400 flex items-center gap-1.5 bg-rose-50 dark:bg-rose-950/40 p-2.5 rounded-xl border border-rose-100 dark:border-rose-900/50">
                                    <i class="fas fa-exclamation-circle text-rose-500"></i> ពេញ (កក់អស់ហើយសម្រាប់ម៉ោងនេះ)
                                </span>
                            @endif
                        </div>

                        <button type="submit" id="bookNowBtn"
                            @if(($availableRoomsCount ?? 1) <= 0) disabled @endif
                            class="w-full h-12 flex items-center justify-center gap-2 font-bold rounded-2xl text-sm transition-all shadow-md {{ ($availableRoomsCount ?? 1) <= 0 ? 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed shadow-none' : 'bg-blue-600 hover:bg-blue-700 text-white active:scale-95 shadow-blue-500/20' }}">
                            <i id="btnIcon" class="fas {{ ($availableRoomsCount ?? 1) <= 0 ? 'fa-calendar-times' : 'fa-check' }}"></i>
                            <span id="btnText">{{ ($availableRoomsCount ?? 1) <= 0 ? 'ពេញ (កក់អស់ហើយ)' : 'កក់ឥឡូវនេះ' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>{{-- Close top grid --}}

        {{-- MIDDLE: DETAILS & FACILITIES --}}
        <div class="mt-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800 p-5 md:p-8 transition-colors duration-300">
            <h2 class="text-2xl md:text-4xl font-black mb-4 text-gray-900 dark:text-white">
                {{ $roomType->name }}
            </h2>

            <div class="flex flex-wrap gap-3 mb-8 text-gray-700 dark:text-gray-300">
                <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                    <i class="fas fa-users mr-2 text-blue-600 dark:text-blue-400"></i>ចំណុះ៖ {{ $roomType->max_guests }} នាក់
                </span>
                @if($roomType->beds)
                <span class="px-4 py-2 bg-gray-100 dark:bg-gray-800 rounded-xl text-sm font-medium">
                    <i class="fas fa-chair mr-2 text-blue-600 dark:text-blue-400"></i>តុ/កៅអី៖ {{ $roomType->beds }}
                </span>
                @endif
            </div>

            <h2 class="text-xl md:text-2xl font-bold mb-3 border-l-4 border-blue-600 dark:border-blue-500 pl-3 text-gray-900 dark:text-white">
                ព័ត៌មានលម្អិតសាលប្រជុំ
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

        {{-- BOTTOM: REVIEWS --}}
        <div class="mt-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-800 p-5 md:p-8 transition-colors duration-300">
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                <div class="lg:col-span-2 space-y-5" id="reviewsContainer">
                    @forelse($roomType->reviews->where('parent_id', null) as $review)
                    <div class="bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-gray-100 dark:border-gray-800/60">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                @if($review->user && $review->user->avatar)
                                    <img src="{{ asset('storage/' . $review->user->avatar) }}" class="w-9 h-9 rounded-xl object-cover shadow-sm">
                                @else
                                    <div class="w-9 h-9 bg-blue-600 text-white font-bold rounded-xl flex items-center justify-center uppercase text-sm">
                                        {{ mb_substr($review->name, 0, 1) }}
                                    </div>
                                @endif
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

                        <div id="review-comment-{{ $review->id }}" class="text-gray-600 dark:text-gray-300 text-sm pl-12 break-words [word-break:break-word]">
                            {!! $review->comment !!}
                        </div>

                        <div id="edit-box-{{ $review->id }}" class="hidden mt-2 pl-12">
                            <form id="edit-form-{{ $review->id }}" action="{{ route('frontend.meeting_details.update', $review->id) }}" method="POST" class="space-y-2 edit-review-form">
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
                                    <button type="button" onclick="document.getElementById('edit-box-{{ $review->id }}').classList.add('hidden')" class="bg-gray-400 text-white px-3 py-1.5 rounded-lg text-xs font-bold">បោះបង់</button>
                                </div>
                            </form>
                        </div>

                        <div class="mt-3 pl-12 flex items-center gap-4">
                            <button onclick="document.getElementById('reply-box-{{ $review->id }}').classList.toggle('hidden')"
                                class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-reply fa-flip-horizontal"></i> ឆ្លើយតប
                            </button>

                            @if(Auth::check() && ($review->user_id == Auth::id() || Auth::id() == 1))
                            <button onclick="document.getElementById('edit-box-{{ $review->id }}').classList.toggle('hidden')"
                                class="text-xs font-bold text-amber-600 hover:underline flex items-center gap-1">
                                <i class="fas fa-edit"></i> កែប្រែមតិ
                            </button>

                            <form action="{{ route('frontend.meeting_details.delete', $review->id) }}" method="POST" class="inline delete-review-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-600 hover:underline flex items-center gap-1">
                                    <i class="fas fa-trash-alt"></i> លុបមតិ
                                </button>
                            </form>
                            @endif
                        </div>

                        {{-- Form សម្រាប់ផ្ញើមតិឆ្លើយតប --}}
                        <div id="reply-box-{{ $review->id }}" class="hidden mt-4 pl-12">
                            <form action="{{ route('frontend.meeting_details.replay', $roomType->id) }}" method="POST" class="flex gap-2 reply-review-form">
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
                                        @if($reply->user && $reply->user->avatar)
                                            <img src="{{ asset('storage/' . $reply->user->avatar) }}" class="w-7 h-7 rounded-lg object-cover shadow-sm">
                                        @else
                                            <div class="w-7 h-7 bg-gray-600 text-white font-bold rounded-lg flex items-center justify-center uppercase text-xs">
                                                {{ mb_substr($reply->name, 0, 1) }}
                                            </div>
                                        @endif
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

                                <div id="reply-comment-{{ $reply->id }}" class="text-gray-600 dark:text-gray-300 text-xs pl-9 break-words [word-break:break-word]">
                                    {!! $reply->comment !!}
                                </div>

                                <div id="edit-reply-box-{{ $reply->id }}" class="hidden mt-2 pl-9">
                                    <form id="edit-reply-form-{{ $reply->id }}" action="{{ route('frontend.meeting_details.update', $reply->id) }}" method="POST" class="space-y-2 edit-reply-form">
                                        @csrf
                                        @method('PUT')
                                        <textarea name="comment" required class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-2 rounded-xl outline-none text-xs dark:text-white focus:ring-1 ring-blue-500" rows="2">{!! strip_tags($reply->comment) !!}</textarea>
                                        <div class="flex gap-2">
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-2.5 py-1 rounded-md text-[11px] font-bold">កែប្រែមតិ</button>
                                            <button type="button" onclick="document.getElementById('edit-reply-box-{{ $reply->id }}').classList.add('hidden')" class="bg-gray-400 text-white px-2.5 py-1 rounded-md text-[11px] font-bold">បោះបង់</button>
                                        </div>
                                    </form>
                                </div>

                                @if(Auth::check() && ($reply->user_id == Auth::id() || Auth::id() == 1))
                                <div class="mt-2 pl-9 flex items-center gap-3 border-t border-gray-50 dark:border-gray-800 pt-1.5">
                                    <button onclick="document.getElementById('edit-reply-box-{{ $reply->id }}').classList.toggle('hidden')"
                                        class="text-[11px] font-bold text-amber-600 hover:underline">
                                        កែប្រែមតិ
                                    </button>
                                    <form action="{{ route('frontend.meeting_details.delete', $reply->id) }}" method="POST" class="inline delete-reply-form">
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
                    <div class="empty-review-msg text-sm text-gray-400 py-12 text-center bg-gray-50 dark:bg-gray-800/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                        <i class="fas fa-info-circle mr-1"></i> មិនទាន់មានការវាយតម្លៃឡើយ
                    </div>
                    @endforelse
                </div>

                <div class="bg-gray-50 dark:bg-gray-800/40 p-5 rounded-2xl border border-gray-100 dark:border-gray-800 h-fit">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <i class="fas fa-star text-amber-400"></i>
                        វាយតម្លៃសាលប្រជុំ
                    </h3>

                    <div id="review-toast" class="hidden mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2"></div>

                    <form id="reviewForm" class="space-y-4">
                        @csrf
                        <input type="hidden" name="room_type_id" value="{{ $roomType->id }}">

                        @auth
                        <div class="p-3 bg-white dark:bg-gray-900 rounded-xl border border-gray-100 dark:border-gray-700 flex items-center gap-3">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-8 h-8 rounded-lg object-cover shadow-sm">
                            @else
                                <div class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center text-xs font-bold uppercase">
                                    {{ mb_substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-xs font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-gray-400">គណនីរបស់អ្នក</p>
                            </div>
                        </div>

                        @else
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 ml-1">ឈ្មោះរបស់អ្នក *</label>
                            <input type="text" name="name" required placeholder="ឈ្មោះរបស់អ្នក..."
                                class="w-full bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 p-3 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm">
                        </div>
                        @endauth

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 ml-1">ផ្តល់ពិន្ទុផ្កាយ *</label>
                            <div class="flex items-center gap-1 bg-white dark:bg-gray-900 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700" id="starRating">
                                @for($s = 1; $s <= 5; $s++)
                                <button type="button" data-val="{{ $s }}"
                                    class="star-btn text-2xl text-amber-400 hover:scale-110 transition-transform focus:outline-none">
                                    ★
                                </button>
                                @endfor
                            </div>
                            <input type="hidden" name="rating" id="ratingInput" value="5" required>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 ml-1">មតិយោបល់</label>
                            <textarea name="comment" rows="3" placeholder="សរសេរមតិយោបល់..."
                                class="w-full p-3 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm resize-none transition-all"></textarea>
                        </div>

                        <button type="submit" id="reviewSubmitBtn"
                            class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-all active:scale-95 shadow-sm">
                            <span>ផ្ញើការវាយតម្លៃ</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = ['start_date', 'end_date', 'start_time', 'end_time'].map(id => document.getElementById(id));
        inputs.forEach(input => {
            if (input) {
                input.addEventListener('change', calculateMeetingPrice);
                input.addEventListener('input', calculateMeetingPrice);
            }
        });
        calculateMeetingPrice();
    });

    function calculateMeetingPrice() {
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const startTime = document.getElementById('start_time');
        const endTime = document.getElementById('end_time');
        const wrapper = document.getElementById('totalPriceWrapper');
        const display = document.getElementById('totalPriceDisplay');
        const bookNowBtn = document.getElementById('bookNowBtn');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');
        const availStatus = document.getElementById('availabilityStatus');
        if (!startDate || !endDate || !startTime || !endTime || !wrapper || !display) return;

        const d1 = new Date(startDate.value);
        const d2 = new Date(endDate.value);
        if (isNaN(d1.getTime()) || isNaN(d2.getTime()) || d2 < d1) {
            wrapper.classList.add('hidden');
            if (bookNowBtn && btnText && btnIcon) {
                bookNowBtn.disabled = true;
                bookNowBtn.className = "w-full h-12 flex items-center justify-center gap-2 font-bold rounded-2xl text-sm transition-all shadow-none bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed";
                btnIcon.className = "fas fa-exclamation-triangle";
                btnText.textContent = "កាលបរិច្ឆេទមិនត្រឹមត្រូវ";
            }
            if (availStatus) {
                availStatus.innerHTML = `<span class="text-amber-600 dark:text-amber-400 flex items-center gap-1.5 bg-amber-50 dark:bg-amber-950/40 p-2.5 rounded-xl border border-amber-100 dark:border-amber-900/50"><i class="fas fa-exclamation-triangle text-amber-500"></i> ថ្ងៃបញ្ចប់ត្រូវតែសមស្របជាមួយថ្ងៃចាប់ផ្តើម</span>`;
            }
            return;
        }

        // Days count (inclusive of both start & end date)
        const days = Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;

        // Hours per day calculation
        const [h1, m1] = (startTime.value || '').split(':').map(Number);
        const [h2, m2] = (endTime.value || '').split(':').map(Number);
        const hoursPerDay = (h2 + m2 / 60) - (h1 + m1 / 60);

        if (isNaN(hoursPerDay) || hoursPerDay <= 0) {
            wrapper.classList.add('hidden');
            if (bookNowBtn && btnText && btnIcon) {
                bookNowBtn.disabled = true;
                bookNowBtn.className = "w-full h-12 flex items-center justify-center gap-2 font-bold rounded-2xl text-sm transition-all shadow-none bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed";
                btnIcon.className = "fas fa-exclamation-triangle";
                btnText.textContent = "ម៉ោងមិនត្រឹមត្រូវ";
            }
            if (availStatus) {
                availStatus.innerHTML = `<span class="text-amber-600 dark:text-amber-400 flex items-center gap-1.5 bg-amber-50 dark:bg-amber-950/40 p-2.5 rounded-xl border border-amber-100 dark:border-amber-900/50"><i class="fas fa-exclamation-triangle text-amber-500"></i> ម៉ោងបញ្ចប់ត្រូវតែក្រោយម៉ោងចាប់ផ្តើម</span>`;
            }
            return;
        }

        const totalHours = Math.round(days * hoursPerDay * 10) / 10;
        const hourlyPrice = parseFloat('{{ $roomType->base_price }}') || 0;
        const total = totalHours * hourlyPrice;

        wrapper.classList.remove('hidden');
        display.innerHTML = `${totalHours} ម៉ោង &times; $${hourlyPrice.toLocaleString()} = <span class="ml-auto font-black text-lg text-blue-600 dark:text-blue-400">$${total.toLocaleString()} <span class="text-xs font-normal text-gray-400 font-mono">(${(total * khrRate).toLocaleString()} ៛)</span></span>`;

        // Dynamic Availability Check
        fetch(`{{ route('frontend.meeting.check_availability') }}?room_type_id={{ $roomType->id }}&start_date=${startDate.value}&end_date=${endDate.value}&start_time=${startTime.value}&end_time=${endTime.value}`)
            .then(res => res.json())
            .then(data => {
                if (bookNowBtn && btnText && btnIcon && availStatus) {
                    if (data.available && data.count > 0) {
                        bookNowBtn.disabled = false;
                        bookNowBtn.className = "w-full h-12 flex items-center justify-center gap-2 font-bold rounded-2xl text-sm transition-all shadow-md bg-blue-600 hover:bg-blue-700 text-white active:scale-95 shadow-blue-500/20";
                        btnIcon.className = "fas fa-check";
                        btnText.textContent = "កក់ឥឡូវនេះ";
                        availStatus.innerHTML = `<span class="text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 p-2.5 rounded-xl border border-emerald-100 dark:border-emerald-900/50"><i class="fas fa-check-circle text-emerald-500"></i> ទំនេរសម្រាប់កក់ ${data.count} សាលប្រជុំ</span>`;
                    } else {
                        bookNowBtn.disabled = true;
                        bookNowBtn.className = "w-full h-12 flex items-center justify-center gap-2 font-bold rounded-2xl text-sm transition-all shadow-none bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed";
                        btnIcon.className = "fas fa-calendar-times";
                        btnText.textContent = "ពេញ (កក់អស់ហើយ)";
                        availStatus.innerHTML = `<span class="text-rose-600 dark:text-rose-400 flex items-center gap-1.5 bg-rose-50 dark:bg-rose-950/40 p-2.5 rounded-xl border border-rose-100 dark:border-rose-900/50"><i class="fas fa-exclamation-circle text-rose-500"></i> ពេញ (កក់អស់ហើយសម្រាប់ម៉ោងនេះ)</span>`;
                    }
                }
            })
            .catch(err => console.error(err));
    }


    function changeImage(element) {
        const mainImage = document.getElementById('mainImage');
        if (!mainImage) return;

        mainImage.src = element.src;

        document.querySelectorAll('.thumbItem').forEach(thumb => {
            thumb.classList.remove('border-blue-500');
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

    // Star Rating Handler
    const starBtns = document.querySelectorAll('.star-btn');
    const ratingInput = document.getElementById('ratingInput');

    function updateStars(val) {
        if (!starBtns.length) return;
        starBtns.forEach(btn => {
            const btnVal = parseInt(btn.getAttribute('data-val'));
            if (btnVal <= val) {
                btn.classList.remove('text-gray-300', 'dark:text-gray-600');
                btn.classList.add('text-amber-400');
            } else {
                btn.classList.remove('text-amber-400');
                btn.classList.add('text-gray-300', 'dark:text-gray-600');
            }
        });
    }

    if (starBtns.length) {
        starBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const val = parseInt(this.getAttribute('data-val'));
                ratingInput.value = val;
                updateStars(val);
            });
            btn.addEventListener('mouseenter', function() {
                const val = parseInt(this.getAttribute('data-val'));
                updateStars(val);
            });
        });
        document.getElementById('starRating')?.addEventListener('mouseleave', function() {
            updateStars(parseInt(ratingInput?.value || 5));
        });
    }

    // AJAX Review Form Submit
    const reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const btn = document.getElementById('reviewSubmitBtn');
            const toast = document.getElementById('review-toast');
            const formData = new FormData(this);

            btn.disabled = true;
            btn.innerHTML = `<i class="fas fa-spinner fa-spin text-xs"></i> <span>កំពុងផ្ញើ...</span>`;

            fetch("{{ route('frontend.meeting_details.store', $roomType->id) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = `<span>ផ្ញើការវាយតម្លៃ</span>`;

                toast.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800';
                toast.innerHTML = `<i class="fas fa-check-circle text-emerald-500"></i> ${data.message || 'បានផ្ញើការវាយតម្លៃដោយជោគជ័យ!'}`;
                toast.classList.remove('hidden');

                const container = document.getElementById('reviewsContainer');
                if (container) {
                    const emptyMsg = container.querySelector('.empty-review-msg');
                    if (emptyMsg) emptyMsg.remove();

                    const name = formData.get('name') || '{{ Auth::check() ? Auth::user()->name : "ភ្ញៀវ" }}';
                    const rating = parseInt(formData.get('rating') || 5);
                    const comment = formData.get('comment') || '';

                    let starsHtml = '';
                    for (let i = 1; i <= 5; i++) {
                        starsHtml += `<i class="${i <= rating ? 'fas' : 'far'} fa-star"></i>`;
                    }

                    const initial = name.trim().charAt(0).toUpperCase();

                    const newCard = document.createElement('div');
                    newCard.className = 'bg-gray-50 dark:bg-gray-800/40 p-4 rounded-2xl border border-gray-100 dark:border-gray-800/60 transition-all duration-500';
                    newCard.innerHTML = `
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-600 text-white font-bold rounded-xl flex items-center justify-center uppercase text-sm">
                                    ${initial}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">${name}</h4>
                                    <p class="text-[11px] text-gray-400 dark:text-gray-500">ទើបតែបន្ថែម</p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 text-xs gap-0.5">
                                ${starsHtml}
                            </div>
                        </div>
                        <div class="text-gray-600 dark:text-gray-300 text-sm pl-12">
                            ${comment}
                        </div>
                    `;
                    container.prepend(newCard);
                }

                const commentInput = reviewForm.querySelector('textarea[name="comment"]');
                if (commentInput) commentInput.value = '';
                ratingInput.value = 5;
                updateStars(5);

                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 4000);
            })
            .catch(err => {
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = `<span>ផ្ញើការវាយតម្លៃ</span>`;
                toast.className = 'mb-4 p-3 rounded-xl text-xs font-semibold flex items-center gap-2 bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800';
                toast.innerHTML = `<i class="fas fa-check-circle text-emerald-500"></i> បានផ្ញើការវាយតម្លៃដោយជោគជ័យ!`;
                toast.classList.remove('hidden');
                setTimeout(() => { toast.classList.add('hidden'); }, 4000);
            });
        });
    }

    // AJAX Event Delegation for Reply, Edit, and Delete Forms
    document.getElementById('reviewsContainer')?.addEventListener('submit', function(e) {
        const form = e.target;
        if (!form) return;

        const isDelete = form.classList.contains('delete-review-form') || form.classList.contains('delete-reply-form') || !!form.querySelector('input[name="_method"][value="DELETE"]');
        const isEdit = form.classList.contains('edit-review-form') || form.classList.contains('edit-reply-form') || !!form.querySelector('input[name="_method"][value="PUT"]');
        const isReply = form.classList.contains('reply-review-form') || form.action.includes('/reply');

        // 1. Reply Form
        if (isReply) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (btn) btn.disabled = false;
                if (data.success && data.reply) {
                    const reviewCard = form.closest('.bg-gray-50, .dark\\:bg-gray-800\\/40');
                    let repliesBlock = reviewCard.querySelector('.mt-4.pl-12.space-y-3');
                    if (!repliesBlock) {
                        repliesBlock = document.createElement('div');
                        repliesBlock.className = 'mt-4 pl-12 space-y-3 border-l-2 border-gray-200 dark:border-gray-700';
                        form.parentElement.after(repliesBlock);
                    }

                    const replyHtml = `
                        <div class="bg-white dark:bg-gray-900/60 p-3 rounded-xl border border-gray-100/80 dark:border-gray-800/40 ml-2" id="reply-item-${data.reply.id}">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-gray-600 text-white font-bold rounded-lg flex items-center justify-center uppercase text-xs">
                                        ${data.reply.name.trim().charAt(0).toUpperCase()}
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-gray-900 dark:text-white flex items-center gap-1">
                                            ${data.reply.name}
                                            ${data.reply.is_admin ? '<span class="bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400 text-[9px] px-1.5 py-0.5 rounded font-black">ADMIN</span>' : ''}
                                        </h5>
                                        <p class="text-[10px] text-gray-400">${data.reply.created_at}</p>
                                    </div>
                                </div>
                            </div>
                            <div id="reply-comment-${data.reply.id}" class="text-gray-600 dark:text-gray-300 text-xs pl-9">
                                ${data.reply.comment}
                            </div>
                        </div>
                    `;
                    repliesBlock.insertAdjacentHTML('beforeend', replyHtml);
                    form.reset();
                    form.parentElement.classList.add('hidden');
                }
            })
            .catch(err => {
                if (btn) btn.disabled = false;
                console.error(err);
            });
        }

        // 2. Edit Form
        else if (isEdit) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (btn) btn.disabled = false;
                if (data.success) {
                    const parentCard = form.closest('.bg-gray-50, .bg-white, .dark\\:bg-gray-800\\/40, .dark\\:bg-gray-900\\/60');
                    if (parentCard) {
                        const commentEl = parentCard.querySelector('[id^="review-comment-"], [id^="reply-comment-"]');
                        if (commentEl) commentEl.innerHTML = data.comment;
                    }
                    form.parentElement.classList.add('hidden');
                }
            })
            .catch(err => {
                if (btn) btn.disabled = false;
                console.error(err);
            });
        }

        // 3. Delete Form
        else if (isDelete) {
            e.preventDefault();
            const btn = form.querySelector('button[type="submit"]');
            if (btn) btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const targetItem = form.closest('.bg-white, .bg-gray-50, .dark\\:bg-gray-800\\/40, .dark\\:bg-gray-900\\/60');
                    if (targetItem) {
                        targetItem.style.transition = 'all 0.3s ease';
                        targetItem.style.opacity = '0';
                        targetItem.style.transform = 'scale(0.95)';
                        setTimeout(() => targetItem.remove(), 300);
                    }
                }
            })
            .catch(err => {
                if (btn) btn.disabled = false;
                console.error(err);
            });
        }
    });
</script>

@endsection