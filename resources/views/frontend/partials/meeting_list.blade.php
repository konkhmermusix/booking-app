{{-- resources/views/frontend/partials/meeting_list.blade.php --}}

<div id="room-list-container"
    :class="view === 'grid' ? 'grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8' : 'flex flex-col gap-6'">

    @forelse($meetingRooms as $type)
    {{-- Card Container --}}
    <div :class="view === 'grid' ? 'flex-col' : 'md:flex-row'"
        class="group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 border border-gray-100 dark:border-gray-800 flex w-full">

        {{-- Image Section --}}
        <div :class="view === 'grid' ? 'h-64 w-full' : 'h-64 md:h-auto md:w-80 lg:w-96'"
            class="relative overflow-hidden shrink-0">
            <img src="{{ $type->images->isNotEmpty() ? asset('storage/'.$type->images->first()->image_path) : 'https://via.placeholder.com/800x600' }}"
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
                        {{ $type->name }}
                    </h3>
                    <div class="text-blue-600 dark:text-blue-400 font-black text-xl whitespace-nowrap">
                        ${{ number_format($type->base_price, 0) }}<span class="text-gray-400 text-xs font-normal">/កម្មវិធី</span>
                    </div>
                </div>

                {{-- Description --}}
                <p x-show="view === 'list'" class="text-gray-500 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                    រៀបចំកម្មវិធីប្រជុំ ឬសិក្ខាសាលារបស់លោកអ្នកនៅក្នុង {{ $type->name }} ដែលមានទីតាំងធំទូលាយ បរិក្ខារបច្ចេកវិទ្យាទំនើប និងសេវាកម្មអាជីព។
                </p>

                {{-- Meeting Facilities --}}
                <div class="flex flex-wrap gap-4 mb-4">
                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                        <i class="fas fa-users text-xs"></i>
                        <span class="text-xs font-bold">{{ $type->max_guests }} នាក់</span>
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

            {{-- Action Buttons --}}
            <div class="flex gap-3 mt-4">
                <a href="{{ route('frontend.meeting_details', $type->id) }}"
                    class="flex-1 text-center py-3 rounded-lg border border-gray-100 dark:border-gray-700 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-50 text-sm transition-all">
                    មើលព័ត៌មាន
                </a>
                <button @click="openBookingModal({{ $type->id }}, '{{ $type->name }}')"
                    class="flex-[1.5] bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-bold shadow-lg shadow-blue-200 dark:shadow-none transition-all text-sm">
                    កក់សាលប្រជុំឥឡូវនេះ
                </button>
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