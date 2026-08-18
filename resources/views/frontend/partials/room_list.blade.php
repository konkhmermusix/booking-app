<div id="room-list-container" :class="view === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-8' : 'flex flex-col gap-6'">

    @forelse($roomTypes as $stay)
    <div :class="view === 'grid' ? 'flex-col' : 'flex-col md:flex-row'"
        class="group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 dark:border-gray-800 flex w-full">
        <div :class="view === 'grid' ? 'h-64 w-full' : 'h-60 sm:h-72 md:h-auto md:w-80 lg:w-96'"
            class="relative overflow-hidden shrink-0">

            @php
            $image = $stay->images->where('is_primary', true)->first() ?? $stay->images->first();
            @endphp

            @if($image)
            <img src="{{ asset('storage/' . $image->image_path) }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                alt="{{ $stay->name }}">
            @else
            <div class="w-full h-full bg-gray-200 dark:bg-gray-800 flex items-center justify-center text-gray-400">
                <i class="fas fa-image text-4xl"></i>
            </div>
            @endif

            <div class="absolute bottom-4 left-4 z-20">
                <div class="bg-blue-600/95 backdrop-blur-md text-white px-3 py-1.5 rounded-xl shadow-lg border border-white/20">
                    <span class="text-[10px] opacity-80 block leading-none">ចាប់ពី</span>
                    <div class="flex items-baseline gap-1">
                        <span class="text-lg font-black">${{ $stay->base_price == floor($stay->base_price) ? number_format($stay->base_price, 0) : number_format($stay->base_price, 2) }}</span>
                        <span class="text-[10px] opacity-80">/យប់</span>
                    </div>
                    <span class="text-[10px] font-semibold block opacity-90 font-mono">({{ number_format($stay->base_price * $khrRate) }} ៛)</span>
                </div>
            </div>

            @if(($stay->reviews_avg_rating ?? 0) >= 4.5)
            <div class="absolute top-4 left-4 z-20 bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded-lg uppercase shadow-lg">
                <i class="fas fa-fire mr-1 text-[8px]"></i> ពេញនិយម
            </div>
            @endif

            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
        </div>

        <!-- ផ្នែកព័ត៌មាន (Content Section) -->
        <div class="p-6 flex flex-col justify-between flex-grow">
            <div>
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                    <div>
                        <h3 class="text-xl font-black text-gray-800 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-1">
                            {{ $stay->name }}
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-bold ml-1">({{ $stay->max_guests }} នាក់)</span>
                        </h3>

                        <div class="flex items-center gap-2 mt-1.5">
                            <i class="fas fa-star text-yellow-400 text-xs"></i>
                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400">
                                {{ number_format($stay->reviews_avg_rating ?? 0, 1) }}
                            </span>
                            <span class="text-gray-300 dark:text-gray-700">|</span>
                            <span class="text-[11px] text-blue-500 font-semibold">
                                {{ $stay->reviews_count ?? 0 }} ការវាយតម្លៃ
                            </span>
                        </div>
                    </div>

                    <div class="flex sm:flex-col items-center sm:items-end justify-between sm:justify-start bg-gray-50 sm:bg-transparent dark:bg-gray-800/50 sm:dark:bg-transparent p-2 sm:p-0 rounded-xl">
                        <div class="bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-800/50 hidden sm:block">
                            <span class="text-sm font-black">{{ number_format($stay->reviews_avg_rating ?? 0, 1) }}</span>
                        </div>
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">
                            @if(($stay->reviews_count ?? 0) > 0)
                            {{ $stay->reviews_avg_rating >= 4 ? 'បានណែនាំ' : 'ល្អ' }}
                            @else
                            មិនទាន់មានការវាយតម្លៃ
                            @endif
                        </span>
                    </div>
                </div>

                <p x-show="view === 'list'" class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2 transition-all">
                    បទពិសោធន៍ស្នាក់នៅដ៏អស្ចារ្យជាមួយបន្ទប់ {{ $stay->name }} ដែលបំពាក់ដោយបរិក្ខារ និងផាសុកភាពសម្រាប់លោកអ្នក។
                </p>

                <div class="flex flex-wrap gap-4 items-center mb-4">
                    @if(isset($hasDateFilter) && !$hasDateFilter)
                    <p class="text-[11px] text-blue-600 dark:text-blue-400 font-medium flex items-center">
                        <i class="fas fa-info-circle mr-1.5"></i> សូមជ្រើសរើសថ្ងៃខែស្នាក់នៅដើម្បីពិនិត្យបន្ទប់ទំនេរ
                    </p>
                    @elseif(($stay->available_rooms_count ?? 0) > 3)
                    <p class="text-[11px] text-green-600 dark:text-green-400 font-medium flex items-center">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-1.5 animate-pulse"></span> 🟢 ទំនេរសម្រាប់កក់ {{ $stay->available_rooms_count }} បន្ទប់
                    </p>
                    @elseif(($stay->available_rooms_count ?? 0) > 0)
                    <p class="text-[11px] text-amber-600 dark:text-amber-400 font-bold flex items-center">
                        <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> 🟢 នៅសល់ត្រឹមតែ {{ $stay->available_rooms_count }} បន្ទប់ប៉ុណ្ណោះ!
                    </p>
                    @else
                    <p class="text-[11px] text-red-500 font-medium flex items-center">
                        <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-1.5"></span> 🔴 ពេញ (កក់អស់ហើយសម្រាប់កាលបរិច្ឆេទនេះ)
                    </p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    @foreach($stay->facilities->take(3) as $facility)
                    <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700/50 px-2 py-1 rounded-md border border-gray-100 dark:border-gray-600">
                        <i class="{{ $facility->icon }} text-blue-500 text-[10px]"></i>
                        <span class="text-[10px] text-gray-600 dark:text-gray-300 font-medium">{{ $facility->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div x-data="{ 
                    isHotelModalOpen: false, 
                    selectedRoomTypeId: null, 
                    checkInDate: '',
                    checkOutDate: '',
                    minCheckIn: '',
                    minCheckOut: '',
                    
                    init() {
                        let today = new Date();
                        let offset = today.getTimezoneOffset();
                        let localToday = new Date(today.getTime() - (offset * 60 * 1000));
                        this.minCheckIn = localToday.toISOString().split('T')[0];
                        this.checkInDate = this.minCheckIn;
                        
                        let tomorrow = new Date();
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        let localTomorrow = new Date(tomorrow.getTime() - (offset * 60 * 1000));
                        this.minCheckOut = localTomorrow.toISOString().split('T')[0];
                        this.checkOutDate = this.minCheckOut;
                    },
                    
                    openHotelModal(id) {
                        this.selectedRoomTypeId = id;
                        if (!this.checkInDate) this.checkInDate = this.minCheckIn;
                        if (!this.checkOutDate) this.checkOutDate = this.minCheckOut;
                        this.isHotelModalOpen = true;
                    },
                    
                    handleCheckInChange() {
                        if (this.checkInDate) {
                            let dateIn = new Date(this.checkInDate);
                            dateIn.setDate(dateIn.getDate() + 1);
                            
                            let offset = dateIn.getTimezoneOffset();
                            let localNextDate = new Date(dateIn.getTime() - (offset * 60 * 1000));
                            this.minCheckOut = localNextDate.toISOString().split('T')[0];
                
                            if (this.checkOutDate && this.checkOutDate <= this.checkInDate) {
                                this.checkOutDate = this.minCheckOut;
                            }
                        }
                    }
                }">

                <div class="mt-auto grid grid-cols-2 gap-3">
                    <button @click="openHotelModal({{ $stay->id }})"
                        class="flex items-center justify-center bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all text-sm active:scale-95">
                        <span>កក់ឥឡូវនេះ</span>
                    </button>

                    <a href="{{ route('frontend.room_details', $stay->id) }}"
                        class="flex items-center justify-center bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all text-sm">
                        <span>មើលលម្អិត</span>
                    </a>
                </div>

                <div x-show="isHotelModalOpen" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
                    <div class="flex items-center justify-center min-h-screen px-4 py-10">
                        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isHotelModalOpen = true"></div>

                        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-3xl relative border-none overflow-hidden transition-all"
                            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">

                            <div class="px-7 py-3 flex justify-between items-center bg-white dark:bg-gray-900 border-b dark:border-gray-800">
                                <div class="flex items-center gap-4">
                                    <div>
                                        <h3 class="font-black text-xl dark:text-white uppercase tracking-tight">ជ្រើសរើសថ្ងៃខែស្នាក់នៅ</h3>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">បំពេញព័ត៌មានខាងក្រោមដើម្បីថែមទៅកន្ដ្រក</p>
                                    </div>
                                </div>
                                <button @click="isHotelModalOpen = false" class="text-gray-400 hover:text-red-500 text-3xl transition-all hover:rotate-90">&times;</button>
                            </div>

                            <form action="{{ route('cart.add.hotel') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="room_type_id" :value="selectedRoomTypeId">
                                <div class="p-4 space-y-2 max-h-[60vh] overflow-y-auto custom-scrollbar">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <div class="space-y-1">
                                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                                <i class="fas fa-calendar-alt text-blue-500 mr-1"></i> ថ្ងៃចូលស្នាក់នៅ
                                            </label>
                                            <input type="date" name="check_in" x-model="checkInDate" :min="minCheckIn" @change="handleCheckInChange()" required
                                                class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                        </div>

                                        <div class="space-y-1">
                                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">
                                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃចាកចេញ
                                            </label>
                                            <input type="date" name="check_out" x-model="checkOutDate" :min="minCheckOut" required
                                                class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                        </div>
                                    </div>
                                </div>

                                <div class="px-7 py-4 bg-gray-50 dark:bg-gray-900 flex justify-end items-center gap-3 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button"
                                        @click="isHotelModalOpen = false"
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
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white dark:bg-gray-900 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
        <i class="fas fa-bed text-gray-300 dark:text-gray-700 text-5xl mb-3 block"></i>
        <h3 class="text-gray-400 dark:text-gray-500 font-bold">មិនមានបន្ទប់ស្នាក់នៅឡើយទេ</h3>
    </div>
    @endforelse
</div>