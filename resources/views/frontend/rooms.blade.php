 @extends('layouts.app')
 @section('title', 'បន្ទប់')

 @section('content')

 <!-- banner -->
 <header class="relative h-[35vh] flex items-center justify-center text-white">
     <div class="absolute inset-0 z-0">
         <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=1920"
             class="w-full h-full object-cover brightness-[0.4]" alt="Banner">
     </div>
     <div class="relative z-10 text-center px-4">
         <h1 class="text-4xl md:text-5xl font-bold mb-4" data-key="rooms-title">បន្ទប់ស្នាក់នៅប្រណីត</h1>
         <p class="text-lg opacity-80" data-key="rooms-subtitle">ស្វែងរកកន្លែងសម្រាកដ៏ល្អឥតខ្ចោះសម្រាប់អ្នក</p>
     </div>
 </header>

 <section class="sticky top-[60px] z-[90] bg-white/90 dark:bg-gray-950/90 backdrop-blur border-b dark:border-gray-800 py-3 shadow-sm">
     <div class="container mx-auto px-4 flex flex-wrap items-center justify-between gap-4">
         <div class="flex gap-2 overflow-x-auto no-scrollbar">
             <button
                 class="filter-btn active px-6 py-2 bg-blue-600 text-white rounded-full text-sm font-bold transition">ទាំងអស់</button>
             <button
                 class="filter-btn px-6 py-2 bg-gray-100 dark:bg-gray-800 rounded-full text-sm font-bold hover:bg-gray-200 transition">គ្រែមួយ</button>
             <button
                 class="filter-btn px-6 py-2 bg-gray-100 dark:bg-gray-800 rounded-full text-sm font-bold hover:bg-gray-200 transition">គ្រែពីរ</button>
             <button
                 class="filter-btn px-6 py-2 bg-gray-100 dark:bg-gray-800 rounded-full text-sm font-bold hover:bg-gray-200 transition">វីអាយភី</button>
         </div>

     </div>
 </section>

 <section class="py-16 container mx-auto px-4">
     <div id="room-container" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
         @foreach($roomTypes as $type)
         <div class="room-card group bg-white dark:bg-gray-900 rounded-[2.5rem] overflow-hidden shadow-xl border border-gray-100 dark:border-gray-800 flex flex-col md:flex-row transition hover:shadow-2xl"
             data-category="{{ $type->name }}">

             <div class="md:w-2/5 h-64 md:h-auto overflow-hidden relative">
                 @if($type->images->isNotEmpty())
                 <img src="{{ asset('storage/' . $type->images->first()->image_path) }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                 @else
                 <img src="https://via.placeholder.com/800x600?text=No+Image"
                     class="w-full h-full object-cover">
                 @endif

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

                     <div class="flex gap-4 mb-6 text-xs text-gray-400 font-medium">
                         <span><i class="fas fa-user-group mr-1"></i> {{ $type->max_guests }} នាក់</span>
                         <span><i class="fas fa-door-open mr-1"></i> សល់ {{ $type->available_rooms_count }} បន្ទប់</span>
                     </div>

                     <ul class="grid grid-cols-2 gap-y-2 mb-8 text-sm text-gray-600 dark:text-gray-400">
                         @foreach($type->facilities->take(4) as $facility)
                         <li>
                             <i class="{{ $facility->icon ?? 'fas fa-check-circle' }} text-blue-500 mr-2"></i>
                             {{ $facility->name }}
                         </li>
                         @endforeach
                     </ul>
                 </div>

                 <div class="flex gap-3">
                     <a href="{{ route('frontend.show', $type->id) }}"
                         class="flex-1 text-center py-3 rounded-2xl border-2 border-blue-600 text-blue-600 font-bold hover:bg-blue-50 transition text-sm">
                         ព័ត៌មានលម្អិត
                     </a>

                     @auth
                     {{-- បើ Login ហើយ ទើបបង្ហាញប៊ូតុងកក់ --}}
                     @if($type->available_rooms_count > 0)
                     <button type="button"
                         onclick="openBookingModal({{ $type->id }}, '{{ $type->name }}', {{ $type->base_price }})"
                         class="flex-1 py-3 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition text-sm">
                         កក់ឥឡូវនេះ
                     </button>
                     @else
                     <button disabled class="flex-1 py-3 rounded-2xl bg-gray-400 text-white font-bold cursor-not-allowed text-sm">
                         អស់បន្ទប់ហើយ
                     </button>
                     @endif
                     @else
                     {{-- បើមិនទាន់ Login ទេ បង្ហាញប៊ូតុងឱ្យទៅ Login --}}
                     <a href="{{ route('login') }}"
                         class="flex-1 text-center py-3 rounded-2xl bg-orange-500 text-white font-bold hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition text-sm">
                         សូមចូលប្រើដើម្បីកក់
                     </a>
                     @endauth
                 </div>
             </div>
         </div>
         @endforeach
     </div>

     <!-- Booking Modal -->
     <div id="bookingModal"
         class="fixed inset-0 bg-black/60 hidden items-center justify-center z-[9999]">

         <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl">
             <h2 class="text-xl font-bold mb-4">កក់បន្ទប់</h2>

             <form id="bookingForm">
                 <input type="hidden" id="room_type_id" name="room_type_id">

                 <div class="mb-3">
                     <label>ប្រភេទបន្ទប់</label>
                     <input type="text" id="room_name" value="" class="w-full border p-2 rounded" readonly>
                 </div>

                 <div class="mb-3">
                     <label>Check In</label>
                     <input type="date" name="check_in" class="w-full border p-2 rounded" required>
                 </div>

                 <div class="mb-3">
                     <label>Check Out</label>
                     <input type="date" name="check_out" class="w-full border p-2 rounded" required>
                 </div>

                 <div class="mb-3">
                     <label>Guests</label>
                     <input type="number" name="guests" class="w-full border p-2 rounded" required>
                 </div>

                 <div class="flex gap-3 mt-4">
                     <button type="button"
                         onclick="closeBookingModal()"
                         class="flex-1 bg-gray-400 text-white py-2 rounded-lg">
                         បោះបង់
                     </button>
                     <button type="submit"
                         class="flex-1 bg-blue-600 text-white py-2 rounded-lg">
                         បញ្ជាក់ការកក់
                     </button>
                 </div>
             </form>
         </div>
     </div>
 </section>

 <section class="py-16 bg-gray-50 dark:bg-gray-950">
     <div class="container mx-auto px-4">
         <h2 class="text-2xl font-bold text-center mb-12">អ្វីដែលលោកអ្នកនឹងទទួលបាន</h2>
         <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
             <div class="text-center">
                 <i class="fas fa-concierge-bell text-3xl text-blue-500 mb-4"></i>
                 <h5 class="font-bold">សេវា ២៤ ម៉ោង</h5>
             </div>
             <div class="text-center">
                 <i class="fas fa-broom text-3xl text-blue-500 mb-4"></i>
                 <h5 class="font-bold">សម្អាតរៀងរាល់ថ្ងៃ</h5>
             </div>
             <div class="text-center">
                 <i class="fas fa-shield-alt text-3xl text-blue-500 mb-4"></i>
                 <h5 class="font-bold">សុវត្ថិភាពខ្ពស់</h5>
             </div>
             <div class="text-center">
                 <i class="fas fa-mug-hot text-3xl text-blue-500 mb-4"></i>
                 <h5 class="font-bold">កាហ្វេ និងតែ</h5>
             </div>
         </div>
     </div>
 </section>

 <section class="py-24 container mx-auto px-4">
     <div class="bg-blue-900 text-white rounded-[3rem] p-10 md:p-16 flex flex-col md:flex-row gap-12 items-center">
         <div class="md:w-1/2">
             <h2 class="text-3xl font-bold mb-6">គោលការណ៍កក់បន្ទប់</h2>
             <ul class="space-y-4 text-blue-100">
                 <li class="flex gap-4"><i class="fas fa-info-circle mt-1"></i> ការបោះបង់ការកក់ដោយឥតគិតថ្លៃមុន ២៤
                     ម៉ោង។
                 </li>
                 <li class="flex gap-4"><i class="fas fa-clock mt-1"></i> ម៉ោងចូលស្នាក់នៅ (Check-in): 2:00 PM</li>
                 <li class="flex gap-4"><i class="fas fa-clock mt-1"></i> ម៉ោងចេញ (Check-out): 12:00 PM</li>
                 <li class="flex gap-4"><i class="fas fa-smoking-ban mt-1"></i> ហាមជក់បារីក្នុងបន្ទប់ស្នាក់នៅ។</li>
             </ul>
         </div>
         <div class="md:w-1/2 text-center">
             <p class="text-xl mb-6">ត្រូវការជំនួយក្នុងការជ្រើសរើសបន្ទប់?</p>
             <a href="tel:+85512345678"
                 class="inline-block bg-yellow-500 text-blue-900 px-10 py-4 rounded-full font-bold text-lg hover:bg-yellow-400 transition">
                 <i class="fas fa-phone-alt mr-2"></i> ខលមកកាន់យើងឥឡូវនេះ
             </a>
         </div>
     </div>
 </section>


 <section id="location" class="py-24 bg-white dark:bg-gray-950">
     <div class="container mx-auto px-4 md:px-6">
         <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-center">

             <div class="lg:col-span-1 space-y-6">
                 <h2 class="text-3xl font-bold text-blue-900 dark:text-blue-400">ទីតាំងដ៏អំណោយផល</h2>
                 <p class="text-gray-500 dark:text-gray-400">សណ្ឋាគារយើងខ្ញុំស្ថិតនៅចំកណ្តាលបេះដូងក្រុងសៀមរាប
                     ងាយស្រួលធ្វើដំណើរទៅកាន់តំបន់ទេសចរណ៍ផ្សេងៗ។</p>

                 <div class="space-y-4">
                     <div
                         class="flex items-center gap-4 p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800">
                         <div
                             class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white shrink-0">
                             <i class="fas fa-plane"></i>
                         </div>
                         <div>
                             <h4 class="font-bold">ព្រលានយន្តហោះ</h4>
                             <p class="text-sm text-gray-500">២០ នាទី តាមរថយន្ត</p>
                         </div>
                     </div>
                 </div>
             </div>

             <div
                 class="lg:col-span-2 h-[450px] rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white dark:border-gray-800">
                 <iframe
                     src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124237.13501704646!2d103.77531778685141!3d13.355818049610266!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3110173820b43525%3A0x633dd7e604739577!2sSiem%20Reap!5e0!3m2!1sen!2skh!4v1700000000000"
                     class="w-full h-full grayscale-[0.2] contrast-[1.1]" allowfullscreen="" loading="lazy">
                 </iframe>
             </div>

         </div>
     </div>
 </section>


 @endsection

 <script>
     document.querySelectorAll('.filter-btn').forEach(button => {
         button.addEventListener('click', function() {
             // ដូរពណ៌ប៊ូតុង
             document.querySelectorAll('.filter-btn').forEach(btn => {
                 btn.classList.remove('active', 'bg-blue-600', 'text-white');
                 btn.classList.add('bg-gray-100', 'dark:bg-gray-800');
             });
             this.classList.add('active', 'bg-blue-600', 'text-white');
             this.classList.remove('bg-gray-100', 'dark:bg-gray-800');

             const filterValue = this.innerText.trim();
             const cards = document.querySelectorAll('.room-card');

             cards.forEach(card => {
                 if (filterValue === 'ទាំងអស់') {
                     card.style.display = 'flex';
                 } else {
                     // បើឈ្មោះ RoomType មានពាក្យដែលចង់ Filter
                     if (card.getAttribute('data-category').includes(filterValue)) {
                         card.style.display = 'flex';
                     } else {
                         card.style.display = 'none';
                     }
                 }
             });
         });
     });
 </script>


 <script>
     function openBookingModal() {
         const modal = document.getElementById("bookingModal")

         modal.classList.remove("hidden")
         modal.classList.add("flex")

     }

     function closeBookingModal() {

         const modal = document.getElementById("bookingModal")

         modal.classList.add("hidden")
         modal.classList.remove("flex")

     }



     document.getElementById('bookingForm').addEventListener('submit', function(e) {
         e.preventDefault();

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

                 alert("Booking Successful");

                 closeBookingModal();
                 location.reload();

             });
     });
 </script>