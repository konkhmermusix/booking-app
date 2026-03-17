@extends('layouts.app') {{-- បើអ្នកមាន Layout រួម --}}

@section('content')
<div class="py-20 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-center text-gray-900 dark:text-white mb-12 italic">
            Hotel Facilities & Services
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- WiFi Card -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center group hover:bg-blue-600 transition-all duration-300">
                <div class="text-4xl text-blue-600 group-hover:text-white mb-4">
                    <i class="fas fa-wifi"></i>
                </div>
                <h3 class="text-xl font-bold dark:text-white group-hover:text-white">High-Speed WiFi</h3>
                <p class="text-gray-500 dark:text-gray-400 group-hover:text-blue-100 mt-2">ឥតគិតថ្លៃគ្រប់ទីកន្លែងក្នុងសណ្ឋាគារ</p>
            </div>

            <!-- Parking Card -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center group hover:bg-green-600 transition-all duration-300">
                <div class="text-4xl text-green-600 group-hover:text-white mb-4">
                    <i class="fas fa-parking"></i>
                </div>
                <h3 class="text-xl font-bold dark:text-white group-hover:text-white">Secure Parking</h3>
                <p class="text-gray-500 dark:text-gray-400 group-hover:text-green-100 mt-2">ចំណតរថយន្តមានសុវត្ថិភាព ២៤ ម៉ោង</p>
            </div>

            <!-- Restaurant Card -->
            <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center group hover:bg-orange-600 transition-all duration-300">
                <div class="text-4xl text-orange-600 group-hover:text-white mb-4">
                    <i class="fas fa-utensils"></i>
                </div>
                <h3 class="text-xl font-bold dark:text-white group-hover:text-white">Restaurant</h3>
                <p class="text-gray-500 dark:text-gray-400 group-hover:text-orange-100 mt-2">ម្ហូបខ្មែរ និងអឺរ៉ុបរសជាតិឈ្ងុយឆ្ងាញ់</p>
            </div>
        </div>
    </div>
</div>
@endsection