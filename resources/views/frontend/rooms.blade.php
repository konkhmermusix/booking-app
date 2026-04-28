@extends('layouts.app')
@section('title', 'បន្ទប់ស្នាក់នៅ')

@section('content')

<div x-data="{ 
    view: 'grid',
    loading: false, 
    currentUrl: '{{ request()->fullUrl() }}',
    fetchRooms(url) {
        this.loading = true;
        this.currentUrl = url;
        
        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                document.getElementById('room-list-container').innerHTML = html;
                window.history.pushState({ path: url }, '', url);
                this.loading = false;
            })
            .catch(err => {
                console.error(err);
                this.loading = false;
            });
    }
}" @popstate.window="location.reload()">

    <div class="bg-gray-50 dark:bg-[#0b1120] min-h-screen py-20">
        <div class="container mx-auto px-4">

            <div class="text-center mb-16">
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white uppercase tracking-tighter mb-4">
                    បន្ទប់ស្នាក់នៅ <span class="text-blue-600">&</span> សាលប្រជុំ <span class="text-blue-600">ភីអេនធី</span>
                </h1>
                <p class="text-gray-500 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                    ស្វាគមន៍មកកាន់សណ្ឋាគារ <span class="font-bold text-blue-600">ភីអេនធី</span> ដែលជាកន្លែងស្នាក់នៅ & សាលប្រជុំដ៏ល្អឥតខ្ចោះសម្រាប់អ្នកដែលកំពុងស្វែងរកការស្នាក់នៅដ៏អស្ចារ្យនៅក្នុងខេត្ដត្បូងឃ្មុំ។ យើងខ្ញុំមានបន្ទប់ទំនើបៗ និងសេវាកម្មលំដាប់ខ្ពស់ដែលត្រូវបានរចនាឡើងដើម្បីផ្តល់ជូននូវភាពងាយស្រួល និងសេវាកម្មដ៏ល្អឥតខ្ចោះសម្រាប់ភ្ញៀវជាតិ និងអន្តរជាតិ។
                </p>
                <div class="h-1.5 w-24 bg-blue-600 mx-auto mt-6 rounded-full"></div>
            </div>

            {{-- ================= SEARCH FORM (Normal Submit) ================= --}}
            <section class="container mx-auto px-4 -mt-10 relative z-50">
                <form action="{{ route('frontend.rooms') }}" method="GET">
                    <div class="bg-white dark:bg-gray-900 p-6 md:p-8 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="flex flex-col">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">ថ្ងៃចូលស្នាក់នៅ</label>
                            <input type="date" name="check_in" value="{{ $check_in }}" class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl dark:text-white text-sm h-[52px]">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">ថ្ងៃចាកចេញ</label>
                            <input type="date" name="check_out" value="{{ $check_out }}" class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3.5 rounded-xl dark:text-white text-sm h-[52px]">
                        </div>

                        <div class="flex flex-col">
                            <label class="text-[11px] font-bold text-gray-400 uppercase ml-2 mb-2">ប្រភេទបន្ទប់</label>
                            <select name="type" class="w-full bg-gray-50 dark:bg-gray-800 border-none px-4 rounded-xl dark:text-white text-sm h-[52px]">
                                <option value="">គ្រប់ប្រភេទទាំងអស់</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->name }}" {{ request('type') == $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col">
                            <label class="hidden lg:block text-[11px] mb-2 opacity-0">Search</label>
                            <button type="submit" class="w-full bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition-all h-[52px] flex items-center justify-center gap-2">
                                <i class="fas fa-search text-xs"></i> ស្វែងរក
                            </button>
                        </div>
                    </div>
                </form>
            </section>


            {{-- ================= FILTER + SORT (Alpine AJAX) ================= --}}
            <section class="sticky top-[60px] z-[90]  py-4 mt-4 ">
                <div class="container mx-auto px-4">
                    <div class="flex flex-wrap items-center justify-between gap-4">

                        {{-- 1. CATEGORY FILTER (Scrollable) --}}
                        <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0 no-scrollbar flex-grow lg:flex-grow-0">
                            <a href="{{ route('frontend.rooms', request()->except(['type', 'page'])) }}"
                                @click.prevent="fetchRooms($el.href)"
                                :class="!currentUrl.includes('type=') ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 dark:text-gray-300'"
                                class="px-5 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap">
                                ទាំងអស់
                            </a>

                            @foreach($categories as $category)
                            @php
                            $catUrl = route('frontend.rooms', array_merge(request()->query(), ['type' => $category->name, 'page' => 1]));
                            @endphp
                            <a href="{{ $catUrl }}"
                                @click.prevent="fetchRooms($el.href)"
                                :class="currentUrl.includes('type={{ urlencode($category->name) }}') ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 dark:text-gray-300'"
                                class="px-5 py-2 rounded-full text-sm font-medium transition-all whitespace-nowrap">
                                {{ $category->name }}
                            </a>
                            @endforeach
                        </div>

                        {{-- 2. ADDITIONAL FILTERS --}}
                        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">

                            {{-- SEARCH INPUT (Live Search) --}}
                            <div class="relative flex-grow sm:flex-grow-0">
                                <input type="text"
                                    placeholder="ស្វែងរកឈ្មោះបន្ទប់..."
                                    @input.debounce.500ms="
                            let url = new URL(currentUrl);
                            url.searchParams.set('search', $event.target.value);
                            url.searchParams.set('page', 1);
                            fetchRooms(url.toString());
                        "
                                    class="w-full sm:w-48 pl-9 pr-4 py-2 border rounded-xl text-sm bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:text-white outline-none focus:ring-2 ring-blue-500">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                            </div>

                            {{-- CAPACITY FILTER --}}
                            <select @change="
                        let url = new URL(currentUrl);
                        url.searchParams.set('guests', $event.target.value);
                        url.searchParams.set('page', 1);
                        fetchRooms(url.toString());
                    " class="border rounded-xl px-4 py-2 text-sm bg-white dark:bg-gray-800 dark:text-white dark:border-gray-700 outline-none">
                                <option value="">ចំនួនភ្ញៀវ</option>
                                @for($i=1; $i<=5; $i++)
                                    <option value="{{ $i }}" {{ request('guests') == $i ? 'selected' : '' }}>{{ $i }} នាក់{{ $i == 5 ? '+' : '' }}</option>
                                    @endfor
                            </select>

                            {{-- SORT PRICE --}}
                            <div class="relative">
                                <select @change="
                            let url = new URL(currentUrl);
                            url.searchParams.set('sort', $event.target.value);
                            fetchRooms(url.toString());
                        " class="border rounded-xl pl-4 pr-10 py-2 text-sm bg-white dark:bg-gray-800 dark:text-white dark:border-gray-700 appearance-none outline-none">
                                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>តម្លៃ: ទាប → ខ្ពស់</option>
                                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>តម្លៃ: ខ្ពស់ → ទាប</option>
                                </select>
                                <i class="fas fa-sort-amount-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                            </div>

                            <div class="flex items-center border dark:border-gray-700 rounded-xl p-0 bg-gray-50 dark:bg-gray-800">
                                {{-- Grid View Button --}}
                                <button @click="view = 'grid'"
                                    :class="view === 'grid' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                                    class="p-2 px-2 rounded-lg transition-all">
                                    <i class="fas fa-th-large"></i>
                                </button>

                                {{-- List View Button --}}
                                <button @click="view = 'list'"
                                    :class="view === 'list' ? 'bg-white dark:bg-gray-700 shadow-sm text-blue-600' : 'text-gray-400'"
                                    class="p-2 px-2 rounded-lg transition-all">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>

                            {{-- RESET BUTTON --}}
                            <button @click="fetchRooms('{{ route('frontend.rooms') }}')"
                                class="p-2 text-gray-500 hover:text-red-500 transition-colors" title="លុប Filter ទាំងអស់">
                                <i class="fas fa-undo-alt"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </section>


            {{-- ================= ROOM LIST ================= --}}
            <section class="py-16 container mx-auto px-4 relative">
                {{-- Loading Overlay --}}
                <div x-show="loading" class="absolute inset-0 bg-white/50 dark:bg-gray-900/50 z-10 flex justify-center py-20 transition-opacity">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-900 border-t-transparent"></div>
                </div>

                <div id="room-list-container">
                    @include('frontend.partials.room_list')
                </div>
            </section>
        </div>

        {{-- ================= BOOKING MODAL ================= --}}
        <div id="bookingModal" class="fixed inset-0 hidden items-center justify-center bg-black/60 z-[100] backdrop-blur-sm p-4">
            <div class="bg-white dark:bg-gray-900 p-8 rounded-3xl w-full max-w-md shadow-2xl transform transition-all scale-95 opacity-0 duration-300" id="modalContainer">
                <h2 class="text-2xl font-bold mb-6 dark:text-white">បញ្ជាក់ការកក់</h2>

                <form id="bookingForm">
                    @csrf
                    <input type="hidden" id="room_type_id" name="room_type_id">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">បន្ទប់ដែលរើស</label>
                        <input type="text" id="modal_room_name" readonly class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3 rounded-xl dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 text-blue-500">ថ្ងៃចូល</label>
                            <input type="date" name="check_in" value="{{ $check_in }}" class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3 rounded-xl dark:text-white text-sm" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1 text-red-500">ថ្ងៃចេញ</label>
                            <input type="date" name="check_out" value="{{ $check_out }}" class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3 rounded-xl dark:text-white text-sm" required>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">ចំនួនភ្ញៀវ</label>
                        <input type="number" name="guests" value="1" min="1" class="w-full bg-gray-50 dark:bg-gray-800 border-none p-3 rounded-xl dark:text-white" required>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeBookingModal()" class="flex-1 bg-gray-100 dark:bg-gray-800 dark:text-white py-3 rounded-2xl font-bold hover:bg-gray-200 transition">បោះបង់</button>
                        <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-2xl font-bold hover:bg-blue-700 shadow-lg transition">យល់ព្រមកក់</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const modal = document.getElementById('bookingModal');
    const modalContainer = document.getElementById('modalContainer');
    const bookingForm = document.getElementById('bookingForm');

    function openBookingModal(id, name) {
        document.getElementById('room_type_id').value = id;
        document.getElementById('modal_room_name').value = name;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modalContainer.classList.remove('scale-95', 'opacity-0');
            modalContainer.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeBookingModal() {
        modalContainer.classList.remove('scale-100', 'opacity-100');
        modalContainer.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    bookingForm.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'កំពុងផ្ញើទិន្នន័យ...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        fetch("{{ route('frontend.bookings.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'ជោគជ័យ!',
                        text: data.message
                    });
                    closeBookingModal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'បរាជ័យ!',
                        text: data.message
                    });
                }
            })
            .catch(() => Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'មានបញ្ហាបច្ចេកទេស!'
            }));
    });
</script>
@endpush