@extends('layouts.app')
@section('title', 'បន្ទប់ស្នាក់នៅ')

@section('content')

<header class="relative h-[40vh] flex items-center justify-center text-white">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1920"
            class="w-full h-full object-cover brightness-[0.4]" alt="Banner">
    </div>
    <div class="relative z-10 text-center px-4" data-aos="zoom-in">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">បន្ទប់ស្នាក់នៅប្រណីត</h1>
        <p class="text-lg opacity-80">ស្វែងរកកន្លែងសម្រាកដ៏ល្អឥតខ្ចោះសម្រាប់អ្នក</p>
    </div>
</header>

<section class="sticky top-[60px] z-[90] bg-white/95 dark:bg-gray-950/95 backdrop-blur border-b dark:border-gray-800 py-4 shadow-sm">
    <div class="container mx-auto px-4 flex flex-wrap items-center justify-between gap-4">
        <div class="flex gap-2 overflow-x-auto no-scrollbar">
            {{-- ប៊ូតុង "ទាំងអស់" --}}
            <a href="{{ route('frontend.rooms', request()->except('type')) }}"
                class="px-6 py-2 rounded-full text-sm font-bold transition whitespace-nowrap {{ !request('type') ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800' }}">
                ទាំងអស់
            </a>

            {{-- បង្ហាញប៊ូតុងតាមឈ្មោះដែល Admin បានបញ្ចូលក្នុង Database --}}
            @foreach($categories as $category)
            <a href="{{ route('frontend.rooms', array_merge(request()->query(), ['type' => $category->name])) }}"
                class="px-6 py-2 rounded-full text-sm font-bold transition whitespace-nowrap 
            {{ request('type') == $category->name ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300' }}">
                {{ $category->name }}
            </a>
            @endforeach
        </div>

        <form action="{{ route('frontend.rooms') }}" method="GET" id="sortForm" class="flex items-center gap-2">
            @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
            <label class="text-xs font-bold text-gray-400 uppercase">តម្រៀបតាម:</label>
            <select name="sort" onchange="this.form.submit()" class="bg-gray-100 dark:bg-gray-800 border-none rounded-xl text-sm font-bold px-4 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>តម្លៃ: ទាប → ខ្ពស់</option>
                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>តម្លៃ: ខ្ពស់ → ទាប</option>
            </select>
        </form>
    </div>
</section>

<section class="py-16 container mx-auto px-4">
    <div id="room-container" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @forelse($roomTypes as $type)
        <div class="room-card group bg-white dark:bg-gray-900 rounded-[2.5rem] overflow-hidden shadow-xl border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row transition hover:shadow-2xl"
            data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">

            <div class="md:w-2/5 h-64 md:h-auto overflow-hidden relative">
                <img src="{{ $type->images->isNotEmpty() ? asset('storage/' . $type->images->first()->image_path) : 'https://via.placeholder.com/800x600?text=No+Image' }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition duration-700">

                <span class="absolute top-5 left-5 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-[10px] font-bold text-blue-600 uppercase shadow-sm">
                    {{ $type->name }}
                </span>
            </div>

            <div class="md:w-3/5 p-8 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-2xl font-bold">{{ $type->name }}</h3>
                        <div class="text-right">
                            <span class="text-2xl font-black text-blue-600">${{ number_format($type->base_price, 2) }}</span>
                            <p class="text-[10px] text-gray-400 uppercase">ក្នុងមួយយប់</p>
                        </div>
                    </div>

                    <div class="flex gap-4 mb-6 text-xs font-medium">
                        <span class="text-gray-500"><i class="fas fa-user-group mr-1"></i> {{ $type->max_guests }} នាក់</span>
                        @if($type->available_rooms_count > 0)
                        <span class="text-green-500"><i class="fas fa-check-circle mr-1"></i> សល់ {{ $type->available_rooms_count }} បន្ទប់</span>
                        @else
                        <span class="text-red-500"><i class="fas fa-times-circle mr-1"></i> អស់បន្ទប់</span>
                        @endif
                    </div>

                    <ul class="grid grid-cols-2 gap-y-2 mb-8 text-sm text-gray-600 dark:text-gray-400">
                        @foreach($type->facilities->take(4) as $facility)
                        <li><i class="{{ $facility->icon ?? 'fas fa-check-circle' }} text-blue-500 mr-2"></i> {{ $facility->name }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('frontend.details', $type->id) }}"
                        class="flex-1 text-center py-3 rounded-2xl border-2 border-blue-600 text-blue-600 font-bold hover:bg-blue-50 transition text-sm">
                        ព័ត៌មានលម្អិត
                    </a>

                    @auth
                    @if($type->available_rooms_count > 0)
                    <button onclick="openBookingModal({{ $type->id }}, '{{ $type->name }}')"
                        class="flex-1 py-3 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition text-sm">
                        កក់ឥឡូវនេះ
                    </button>
                    @else
                    <button disabled class="flex-1 py-3 rounded-2xl bg-gray-400 text-white font-bold cursor-not-allowed text-sm">
                        អស់បន្ទប់ហើយ
                    </button>
                    @endif
                    @else
                    <a href="{{ route('login') }}" class="flex-1 text-center py-3 rounded-2xl bg-orange-500 text-white font-bold hover:bg-orange-600 transition text-sm">
                        សូមចូលប្រើដើម្បីកក់
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500">មិនមានបន្ទប់ដែលអ្នកស្វែងរកឡើយ។</p>
        </div>
        @endforelse
    </div>

    <div class="mt-16 flex justify-center">
        {{ $roomTypes->links() }}
    </div>
</section>

<div id="bookingModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[9999] backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-900 rounded-3xl p-8 w-full max-w-md shadow-2xl scale-95 transition-transform duration-300">
        <h2 class="text-2xl font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-calendar-check text-blue-600"></i> កក់បន្ទប់
        </h2>

        <form id="bookingForm" class="space-y-4">
            <input type="hidden" id="room_type_id" name="room_type_id">

            <div>
                <label class="block text-sm font-bold mb-1">ប្រភេទបន្ទប់</label>
                <input type="text" id="modal_room_name" class="w-full bg-gray-50 dark:bg-gray-800 border-none rounded-xl p-3" readonly>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold mb-1">Check In</label>
                    <input type="date" name="check_in" min="{{ date('Y-m-d') }}" class="w-full border-gray-200 dark:border-gray-700 rounded-xl p-3" required>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Check Out</label>
                    <input type="date" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border-gray-200 dark:border-gray-700 rounded-xl p-3" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold mb-1">ចំនួនអ្នកស្នាក់នៅ</label>
                <input type="number" name="guests" min="1" value="1" class="w-full border-gray-200 dark:border-gray-700 rounded-xl p-3" required>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeBookingModal()" class="flex-1 bg-gray-100 dark:bg-gray-800 py-3 rounded-xl font-bold">បោះបង់</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">បញ្ជាក់ការកក់</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openBookingModal(id, name) {
        document.getElementById("room_type_id").value = id;
        document.getElementById("modal_room_name").value = name;
        const modal = document.getElementById("bookingModal");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        setTimeout(() => modal.querySelector('div').classList.add('scale-100'), 10);
    }

    function closeBookingModal() {
        const modal = document.getElementById("bookingModal");
        modal.querySelector('div').classList.remove('scale-100');
        setTimeout(() => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }, 200);
    }

    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = e.target.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> កំពុងបញ្ជូន...';
        btn.disabled = true;

        let formData = new FormData(this);
        fetch("/booking/store", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                alert(data.message || "ការកក់ទទួលបានជោគជ័យ!");
                location.reload();
            })
            .catch(err => alert("មានបញ្ហាអ្វីមួយបានកើតឡើង!"))
            .finally(() => {
                btn.innerHTML = 'បញ្ជាក់ការកក់';
                btn.disabled = false;
            });
    });
</script>
@endpush