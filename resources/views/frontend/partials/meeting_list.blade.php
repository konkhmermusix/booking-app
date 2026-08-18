<div id="room-list-container" :class="view === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-8' : 'flex flex-col gap-6'">

    @forelse($meetingRooms as $meeting)
    <div x-data="{ 
            isMeetingModalOpen: false, 
            selectedMeetingRoomTypeId: null,
            startDate: new Date().toISOString().split('T')[0],
            endDate: new Date().toISOString().split('T')[0],
            openMeetingModal(id) {
                this.selectedMeetingRoomTypeId = id;
                if (!this.startDate) this.startDate = new Date().toISOString().split('T')[0];
                if (!this.endDate) this.endDate = new Date().toISOString().split('T')[0];
                this.isMeetingModalOpen = true;
            }
        }"
        :class="view === 'grid' ? 'flex-col' : 'flex-col md:flex-row'"
        class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden flex border border-gray-100 dark:border-gray-700 w-full">

        <div :class="view === 'grid' ? 'relative aspect-video w-full' : 'relative aspect-video w-full md:w-72 lg:w-96 md:h-auto shrink-0'"
            class="overflow-hidden">
            @php
            $image = $meeting->images->where('is_primary', true)->first() ?? $meeting->images->first();
            @endphp

            @if($image)
            <img src="{{ asset('storage/' . $image->image_path) }}"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                alt="{{ $meeting->name }}">
            @else
            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                <i class="fas fa-handshake text-4xl"></i>
            </div>
            @endif
            <div class="absolute bottom-4 left-4 z-20">
                <div class="bg-blue-600/95 backdrop-blur-md text-white px-3 py-1.5 rounded-xl shadow-lg border border-white/20">
                    <span class="text-[10px] opacity-80 block leading-none">ចាប់ពី</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg font-black">${{ $meeting->base_price == floor($meeting->base_price) ? number_format($meeting->base_price, 0) : number_format($meeting->base_price, 2) }}</span>
                        <span class="text-[10px] opacity-80">/ម៉ោង</span>
                    </div>
                    <span class="text-[10px] font-semibold block opacity-90 font-mono">({{ number_format($meeting->base_price * $khrRate) }} ៛)</span>
                </div>
            </div>

            @if(($meeting->reviews_avg_rating ?? 0) >= 4.5)
            <div class="absolute top-4 left-4 z-20 bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg uppercase shadow-lg">
                <i class="fas fa-fire mr-1 text-[8px]"></i> ពេញនិយម
            </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
        </div>

        <div class="p-6 flex flex-col flex-grow justify-between min-w-0">
            <div>
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start mb-2 gap-2 sm:gap-4">
                    <div class="min-w-0">
                        <h3 class="text-xl font-black text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-1">
                            {{ $meeting->name }}
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-bold ml-1">({{ $meeting->max_guests }} នាក់)</span>
                        </h3>

                        <div class="flex items-center gap-2 mt-1">
                            <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400">
                                {{ number_format($meeting->reviews_avg_rating ?? 0, 1) }}
                            </span>
                            <span class="text-gray-300">|</span>
                            <span class="text-[10px] text-blue-500 font-semibold uppercase whitespace-nowrap">
                                {{ $meeting->reviews_count ?? 0 }} ការវាយតម្លៃ
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center sm:items-end sm:flex-col gap-2 sm:gap-1 mt-1 sm:mt-0 shrink-0">
                        <div class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-2 py-0.5 sm:py-1 rounded-md border border-blue-100 dark:border-blue-800/50">
                            <span class="text-sm font-black">{{ number_format($meeting->reviews_avg_rating ?? 0, 1) }}</span>
                        </div>
                        <span class="text-[9px] font-bold text-gray-400 uppercase whitespace-nowrap">
                            @if(($meeting->reviews_count ?? 0) > 0)
                            {{ $meeting->reviews_avg_rating >= 4 ? 'បានណែនាំ' : 'ល្អ' }}
                            @else
                            គ្មានការវាយតម្លៃ
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 my-3 text-xs">
                    @if(isset($hasDateFilter) && !$hasDateFilter)
                    <p class="text-blue-600 dark:text-blue-400 font-medium flex items-center">
                        <i class="fas fa-info-circle mr-1.5"></i> សូមជ្រើសរើសថ្ងៃខែ និងម៉ោងប្រជុំដើម្បីកក់
                    </p>
                    @elseif(($meeting->available_rooms_count ?? 0) > 2)
                    <p class="text-green-600 dark:text-green-400 font-medium flex items-center">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> 🟢 ទំនេរសម្រាប់កក់ {{ $meeting->available_rooms_count }} សាល
                    </p>
                    @elseif(($meeting->available_rooms_count ?? 0) > 0)
                    <p class="text-amber-600 dark:text-amber-400 font-bold flex items-center">
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> 🟢 នៅសល់ត្រឹមតែ {{ $meeting->available_rooms_count }} សាលប៉ុណ្ណោះ!
                    </p>
                    @else
                    <p class="text-red-500 font-medium flex items-center">
                        <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1.5"></span> 🔴 ពេញ (កក់អស់ហើយសម្រាប់កាលបរិច្ឆេទនេះ)
                    </p>
                    @endif
                </div>

                <p x-show="view === 'list'" class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2 mt-2">
                    រៀបចំកម្មវិធីប្រជុំ ឬសិក្ខាសាលារបស់លោកអ្នកនៅក្នុង {{ $meeting->name }} ដែលមានទីតាំងធំទូលាយ បរិក្ខារបច្ចេកវិទ្យាទំនើប និងសេវាកម្មអាជីព។
                </p>
            </div>

            <div class="space-y-4">
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($meeting->facilities->take(4) as $facility)
                    <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700/50 px-2.5 py-1 rounded-lg border border-gray-100 dark:border-gray-600">
                        <i class="{{ $facility->icon }} text-blue-500 text-[10px]"></i>
                        <span class="text-[10px] text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap">{{ $facility->name }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2 border-gray-100 dark:border-gray-700/50">
                    <button @click="openMeetingModal({{ $meeting->id }})"
                        class="flex items-center justify-center bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 transition-all text-sm active:scale-95">
                        <span>កក់ឥឡូវនេះ</span>
                    </button>

                    <a href="{{ route('frontend.meeting_details', $meeting->id) }}"
                        class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-2.5 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm active:scale-95">
                        <span>មើលលម្អិត</span>
                    </a>
                </div>
            </div>
        </div>

        <div x-show="isMeetingModalOpen" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-10">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isMeetingModalOpen = false"></div>

                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
                    x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                    <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                        <div class="flex items-center gap-4">
                            <div>
                                <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">រៀបចំម៉ោងពេលប្រជុំ</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីថែមទៅកន្ដ្រក</p>
                            </div>
                        </div>
                        <button @click="isMeetingModalOpen = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
                    </div>

                    <form action="{{ route('cart.add.meeting') }}" method="POST" class="space-y-4">
                        @csrf

                        <input type="hidden" name="room_type_id" :value="selectedMeetingRoomTypeId">

                        <div class="p-4 space-y-2 max-h-[60vh] overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចាប់ផ្តើមប្រជុំ
                                    </label>
                                    <input type="date" name="start_date" x-model="startDate" min="{{ date('Y-m-d') }}" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃបញ្ចប់ប្រជុំ
                                    </label>
                                    <input type="date" name="end_date" x-model="endDate" :min="startDate" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងចាប់ផ្តើម (រាល់ថ្ងៃ)
                                    </label>
                                    <input type="time" name="start_time" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>

                                <div class="space-y-1">
                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        <i class="fas fa-clock alt text-blue-600 mr-1"></i> ម៉ោងបញ្ចប់ (រាល់ថ្ងៃ)
                                    </label>
                                    <input type="time" name="end_time" required
                                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                </div>
                            </div>
                        </div>

                        <div class="px-7 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                            <button type="button"
                                @click="isMeetingModalOpen = false"
                                class="px-6 h-11 flex items-center justify-center bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-xl transition-all text-sm active:scale-95">
                                បោះបង់
                            </button>

                            <button type="submit"
                                class="px-6 h-11 flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm shadow-md shadow-blue-500/20 transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                                <div class="flex items-center gap-2">
                                    <span>បន្ថែមទៅក្នុងបញ្ជីកក់</span>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center">
        <div class="mb-4">
            <i class="fas fa-calendar-times text-5xl text-gray-300 dark:text-gray-600"></i>
        </div>
        <h3 class="text-gray-400 font-bold">រកមិនឃើញសាលប្រជុំដែលអ្នកស្វែងរកទេ</h3>
    </div>
    @endforelse
</div>