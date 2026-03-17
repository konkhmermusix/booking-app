@extends('layouts.app')
@section('title', 'ទំនាក់ទំនង')
@section('content')

<section class="py-16 bg-blue-900 dark:bg-blue-800 text-white text-center">
    <h1 class="text-4xl font-bold mb-4">ទំនាក់ទំនងមកយើង</h1>
    <p class="opacity-80 max-w-xl mx-auto px-4">យើងនៅទីនេះដើម្បីជួយលោកអ្នក ២៤/៧។ សូមផ្ញើសារមកយើងសម្រាប់រាល់ចម្ងល់
        ឬការកក់ទុកផ្សេងៗ។</p>
</section>

<div class="container mx-auto px-4 -mt-10 mb-20">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1 space-y-6">
            <div
                class="contact-card bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-lg border dark:border-gray-800 flex items-center gap-5">
                <div
                    class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div>
                    <h4 class="font-bold">លេខទូរស័ព្ទ</h4>
                    <p class="text-sm text-gray-500">(+855) 12 345 678</p>
                </div>
            </div>

            <div
                class="contact-card bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-lg border dark:border-gray-800 flex items-center gap-5">
                <div
                    class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center text-green-600 dark:text-green-400 text-2xl">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <h4 class="font-bold">អ៊ីមែល</h4>
                    <p class="text-sm text-gray-500">info@pnt-hotel.com</p>
                </div>
            </div>

            <div
                class="contact-card bg-white dark:bg-gray-900 p-6 rounded-3xl shadow-lg border dark:border-gray-800 flex items-center gap-5">
                <div
                    class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center text-red-600 dark:text-red-400 text-2xl">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                    <h4 class="font-bold">អាសយដ្ឋាន</h4>
                    <p class="text-sm text-gray-500">ផ្លូវជាតិលេខ ៦, សៀមរាប, កម្ពុជា</p>
                </div>
            </div>

            <div class="bg-blue-600 rounded-3xl p-8 text-white shadow-xl">
                <h4 class="font-bold mb-6 text-center">តាមដានពួកយើងលើបណ្តាញសង្គម</h4>
                <div class="flex justify-around text-3xl">
                    <a href="#" class="hover:scale-125 transition-transform"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="hover:scale-125 transition-transform"><i class="fab fa-telegram"></i></a>
                    <a href="#" class="hover:scale-125 transition-transform"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:scale-125 transition-transform"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>

        <div
            class="lg:col-span-2 bg-white dark:bg-gray-900 rounded-3xl shadow-lg border dark:border-gray-800 p-8 md:p-10">
            <h3 class="text-2xl font-bold mb-8 flex items-center gap-3">
                <i class="fas fa-paper-plane text-blue-600"></i> ផ្ញើសារមកយើង
            </h3>
            <form action="#" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-2 ml-1">ឈ្មោះរបស់អ្នក</label>
                        <input type="text" placeholder="បញ្ចូលឈ្មោះ..."
                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2 ml-1">អ៊ីមែល</label>
                        <input type="email" placeholder="example@gmail.com"
                            class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-2 ml-1">សាររបស់អ្នក</label>
                    <textarea rows="5" placeholder="តើអ្នកចង់ឱ្យយើងជួយអ្វីខ្លះ?"
                        class="w-full bg-gray-50 dark:bg-gray-800 border-none p-4 rounded-2xl focus:ring-2 ring-blue-500 outline-none transition"></textarea>
                </div>
                <button type="submit"
                    class="w-full md:w-max bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all active:scale-95">
                    ផ្ញើសារឥឡូវនេះ <i class="fas fa-chevron-right ml-2 text-xs"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="mt-16 rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-gray-800 h-[450px]">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d124231.252030114!2d103.7753381673859!3d13.367302450516643!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31101700680d4677%3A0x62804368153f3e6a!2sSiem%20Reap!5e0!3m2!1sen!2skh!4v1700000000000!5m2!1sen!2skh"
            class="w-full h-full" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>


@endsection