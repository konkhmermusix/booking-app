{{-- resources/views/frontend/partials/meeting_list.blade.php --}}

<div id="room-list-container"
    :class="view === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-8' : 'flex flex-col gap-6'">

    @forelse($meetingRooms as $meeting)
    {{-- Card Container --}}
    <div :class="view === 'grid' ? 'flex-col' : 'md:flex-row'"
        class="group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-gray-800 flex w-full">

        {{-- Image Section --}}
        <div :class="view === 'grid' ? 'h-64 w-full' : 'h-64 md:h-auto md:w-80 lg:w-96'"
            class="relative overflow-hidden shrink-0">
            <img src="{{ $meeting->images->isNotEmpty() ? asset('storage/'.$meeting->images->first()->image_path) : 'https://via.placeholder.com/800x600' }}"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

            {{-- Badge --}}
            <div class="absolute top-4 left-4">
                <span class="bg-blue-600 text-white text-[10px] px-3 py-1 rounded-lg font-bold uppercase">
                    Meeting Hall
                </span>
            </div>
        </div>

        {{-- Content Section --}}
        <div class="p-6 flex flex-col justify-between flex-grow">
            <div>
                <div class="flex justify-between items-start mb-2 gap-2">
                    <h3 class="text-xl font-black text-gray-800 dark:text-white group-hover:text-blue-600 line-clamp-1">
                        {{ $meeting->name }}
                    </h3>
                    <div class="text-blue-600 dark:text-blue-400 font-black text-xl whitespace-nowrap">
                        ${{ number_format($meeting->base_price, 0) }}<span class="text-gray-400 text-xs font-normal">/កម្មវិធី</span>
                    </div>
                </div>

                {{-- Description --}}
                <p x-show="view === 'list'" class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                    រៀបចំកម្មវិធីប្រជុំ ឬសិក្ខាសាលារបស់លោកអ្នកនៅក្នុង {{ $meeting->name }} ដែលមានទីតាំងធំទូលាយ បរិក្ខារបច្ចេកវិទ្យាទំនើប និងសេវាកម្មអាជីព។
                </p>

                {{-- Meeting Facilities --}}
                <div class="flex flex-wrap gap-4 mb-4">
                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-users text-xs"></i>
                        <span class="text-xs font-bold">{{ $meeting->max_guests }} នាក់</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-desktop text-xs"></i>
                        <span class="text-xs font-bold">Projector</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-microphone-alt text-xs"></i>
                        <span class="text-xs font-bold">Sound System</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-clock text-xs text-orange-500"></i>
                        <span class="text-xs font-bold">កក់ទុកមុន</span>
                    </div>
                </div>
            </div>

            <div x-data="{ 
                isMeetingModalOpen: false, 
                selectedMeetingRoomTypeId: null,
                openMeetingModal(id) {
                    this.selectedMeetingRoomTypeId = id;
                    this.isMeetingModalOpen = true;
                }
            }">

                <div class="mt-5 grid grid-cols-2 gap-3">
                    <button @click="openMeetingModal({{ $meeting->id }})"
                        class="flex items-center justify-center bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all text-sm active:scale-95">
                        <span>កក់ឥឡូវនេះ</span>
                    </button>

                    <a href="{{ route('frontend.meeting_details', $meeting->id) }}"
                        class="flex items-center justify-center bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium py-3 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm ">
                        <span>មើលលម្អិត</span>
                    </a>
                </div>

                <div x-show="isMeetingModalOpen" class="fixed inset-0 z-100 overflow-y-auto" x-cloak>
                    <div class="flex items-center justify-center min-h-screen px-4 py-10">
                        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" @click="isMeetingModalOpen = true"></div>

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
                                            <input type="date" name="start_date" min="{{ date('Y-m-d') }}" required
                                                class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl focus:ring-2 ring-blue-500 outline-none dark:text-white text-sm h-[52px]">
                                        </div>

                                        <div class="space-y-1">
                                            <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">
                                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ថ្ងៃបញ្ចប់ប្រជុំ
                                            </label>
                                            <input type="date" name="end_date" min="{{ date('Y-m-d') }}" required
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

                                        <div class="space-y-1 md:col-span-2">
                                            <label class="block text-[11px] font-black uppercase text-gray-400 ml-2">
                                                <i class="fas fa-comments text-blue-600 mr-1"></i>មតិផ្សេងៗ
                                            </label>
                                            <textarea name="special_requests" rows="2"
                                                class="w-full p-5 rounded-2xl border-2 border-gray-50 dark:border-gray-800 bg-gray-50 dark:bg-gray-800 dark:text-white focus:border-blue-500 outline-none transition-all"
                                                placeholder="ចំនួនមនុស្សត្រូវស្នាក់នៅ ឬមិនចាំបាច់ក៏បាន ទុកឱ្យទំនេរ..."></textarea>
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
                                            <span>ថែមចូលកន្ត្រក</span>
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
    <div class="col-span-full py-20 text-center">
        <div class="mb-4">
            <i class="fas fa-calendar-times text-5xl text-gray-200"></i>
        </div>
        <h3 class="text-gray-400 font-bold">រកមិនឃើញសាលប្រជុំដែលអ្នកស្វែងរកទេ</h3>
    </div>
    @endforelse
</div>