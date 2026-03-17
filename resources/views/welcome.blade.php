<!DOCTYPE html>
<html lang="km">

<head>
       
    <meta charset="UTF-8">
        <title></title>
       
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro&display=swap" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
       
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
       
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
        <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
        }
    </style>
</head>

<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300 font-['Kantumruy_Pro',_sans-serif]">
    <nav class="bg-white shadow-sm p-4">
              <div class="container mx-auto flex justify-between">ls</div>
    </nav>
        <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-[-1] overflow-hidden">
                <div
                        class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-200/20 dark:bg-blue-900/10 rounded-full blur-[120px] animate-pulse">
                    </div>
                <div
                        class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-300/20 dark:bg-blue-800/10 rounded-full blur-[120px] animate-pulse">
                    </div>
            </div>
        <nav class="bg-white/80 dark:bg-gray-950/80 backdrop-blur-md sticky top-0 z-[100] border-b border-gray-100 dark:border-gray-800">
                <div class="container mx-auto px-4 md:px-6 py-4 flex justify-between items-center">

                        <!-- Logo -->
                        <div class="text-2xl font-bold text-blue-900 dark:text-blue-400 flex items-center gap-2" data-key="title">
                                <img src="" alt=""> ភីអេនធី ហូទែល
                         </div>

                        <!-- មឺនុយសម្រាប់ Desktop -->
                        <ul class="hidden lg:flex space-x-7 font-medium">
                                <li><a href="index.html" class="hover:text-blue-600 dark:hover:text-blue-400 transition"
                                                data-key="nav-home">ទំព័រដើម</a></li>
                                <li><a href="rooms.html" class="hover:text-blue-600 dark:hover:text-blue-400 transition"
                                                data-key="nav-rooms">បន្ទប់</a></li>
                                <li><a href="meeting.html" class="hover:text-blue-600 dark:hover:text-blue-400 transition"
                                                data-key="nav-meetings">បន្ទប់ប្រជុំ</a></li>
                                <li><a href="#facilities" class="hover:text-blue-600 dark:hover:text-blue-400 transition"
                                                data-key="nav-facilities">សេវាកម្ម</a></li>
                                <li><a href="about.html" class="hover:text-blue-600 dark:hover:text-blue-400 transition"
                                                data-key="nav-about">អំពីយើង</a></li>
                                <li><a href="contact.html" class="hover:text-blue-600 dark:hover:text-blue-400 transition"
                                                data-key="nav-contact">ទំនាក់ទំនង</a></li>
                         </ul>

                        <!-- ផ្នែកប៊ូតុងសកម្មភាព (Actions) -->
                        <div class="flex items-center space-x-3 md:space-x-4">

                                <!-- ប្តូរភាសា (Language) -->
                                <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-full p-1 shadow-inner">
                                        <button onclick="changeLang('km')" id="btn-km"
                                                class="px-2 py-1 rounded-full text-[10px] font-bold transition-all duration-300 bg-blue-600 text-white shadow-sm">KM</button>
                                        <button onclick="changeLang('en')" id="btn-en"
                                                class="px-2 py-1 rounded-full text-[10px] font-bold transition-all duration-300 text-gray-500">EN</button>
                                    </div>

                                <!-- Theme Toggle -->
                                <button id="theme-toggle"
                                        class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 w-10 h-10 flex items-center justify-center transition hover:ring-2 ring-blue-400/50 text-blue-600 dark:text-yellow-400">
                                        <i id="theme-icon" class="fas fa-moon"></i>
                                    </button>

                                <!-- Login / Register (Desktop) -->
                                <div class="hidden xl:flex items-center gap-4 border-l pl-4 dark:border-gray-700">
                                        @auth
                                        <div x-data="{ open: false }" class="relative">

                                                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none group">
                                                        <div class="text-right hidden sm:block">
                                                                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase tracking-wider">គណនី</p>
                                                                <p class="text-sm font-bold dark:text-white group-hover:text-blue-600 transition">{{ Auth::user()->name }}</p>
                                                            </div>
                                                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-200 dark:shadow-none transition group-hover:scale-105">
                                                                <i class="fas fa-user-circle text-xl"></i>
                                                            </div>
                                                        <i class="fas fa-chevron-down text-[10px] text-gray-400 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                                                    </button>

                                                <div x-show="open"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                                        x-transition:leave="transition ease-in duration-75"
                                                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                                        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                                        class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">

                                                        <div class="px-4 py-2 border-b dark:border-gray-700 mb-2">
                                                                <p class="text-xs text-gray-400">ចូលប្រើជាៈ</p>
                                                                <p class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase">{{ Auth::user()->role }}</p>
                                                            </div>

                                                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                                                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                                                <i class="fas fa-chart-line w-5 text-blue-500"></i> Dashboard
                                                            </a>
                                                        @else
                                                        <a href="/my-bookings" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                                                                <i class="fas fa-calendar-check w-5 text-blue-500"></i> My Booking
                                                            </a>
                                                        @endif

                                                        <a href="/profile" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 transition border-b dark:border-gray-700">
                                                                <i class="fas fa-user-cog w-5 text-blue-500"></i> Profile Setting
                                                            </a>

                                                        <form action="{{ route('logout') }}" method="POST" class="block">
                                                                @csrf
                                                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition mt-1">
                                                                        <i class="fas fa-sign-out-alt w-5"></i> ចាកចេញ
                                                                    </button>
                                                            </form>
                                                    </div>
                                            </div>

                                        @else
                                        {{-- ប៊ូតុង Login/Register ពេលមិនទាន់ចូលប្រើ --}}
                                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-blue-600 dark:text-gray-300 px-3 py-2">ចូលប្រើ</a>
                                        <a href="{{ route('register') }}" class="text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 px-5 py-2 rounded-xl transition">ចុះឈ្មោះ</a>
                                        @endauth
                                    </div>

                                <!-- ប៊ូតុងកក់ (Book Now) -->
                                <button
                                        class="hidden sm:block bg-blue-600 text-white px-5 py-2.5 rounded-full hover:bg-blue-700 transition active:scale-95 text-sm font-bold shadow-lg shadow-blue-200 dark:shadow-none"
                                        data-key="nav-book">
                                        កក់ឥឡូវនេះ
                                    </button>

                                <!-- ប៊ូតុងមឺនុយ Mobile -->
                                <button id="menu-btn" class="lg:hidden text-2xl p-2 focus:outline-none">
                                        <i class="fas fa-bars"></i>
                                    </button>
                            </div>
                    </div>

                <!-- Mobile Menu Panel -->
                <div id="mobile-menu"
                        class="hidden lg:hidden bg-white dark:bg-gray-900 border-b dark:border-gray-800 absolute w-full left-0 shadow-2xl overflow-y-auto max-h-[90vh]">
                        <ul class="flex flex-col p-5 space-y-2 font-medium text-lg">
                                <li><a href="index.html"
                                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-xl transition"
                                                data-key="nav-home">ទំព័រដើម</a>
                                    </li>
                                <li><a href="rooms.html"
                                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-xl transition"
                                                data-key="nav-rooms">បន្ទប់</a>
                                    </li>
                                <li><a href="meetings.html"
                                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-xl transition"
                                                data-key="nav-meetings">បន្ទប់ប្រជុំ</a>
                                    </li>
                                <li><a href="#facilities"
                                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-xl transition"
                                                data-key="nav-facilities">សេវាកម្ម</a>
                                    </li>
                                <li><a href="about.html"
                                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-xl transition"
                                                data-key="nav-about">អំពីយើង</a>
                                    </li>
                                <li><a href="contact.html"
                                                class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-xl transition"
                                                data-key="nav-contact">ទំនាក់ទំនង</a>
                                    </li>

                                <!-- Login/Register សម្រាប់ Mobile -->

                                @auth
                                <li class="pt-4 pb-2 px-4 text-xs font-bold text-gray-400 uppercase border-t dark:border-gray-800 mt-2">គណនីរបស់អ្នក</li>
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                                <li><a href="{{ route('dashboard') }}" class="block px-4 py-3 text-blue-600 font-bold">Dashboard</a></li>
                                @else
                                <li><a href="/my-bookings" class="block px-4 py-3 text-blue-600 font-bold">My Booking</a></li>
                                @endif
                                <li><a href="/profile" class="block px-4 py-3">Profile Setting</a></li>
                                <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-3 text-red-500 font-bold">ចាកចេញ</button>
                                            </form>
                                    </li>
                                @else
                                <li class="grid grid-cols-2 gap-3 pt-5 mt-2 border-t dark:border-gray-800">
                                        <a href="{{ route('login') }}" class="text-center py-3 rounded-xl border border-gray-200 dark:border-gray-700 font-medium text-sm">ចូលប្រើ</a>
                                        <a href="{{ route('register') }}" class="text-center py-3 rounded-xl bg-blue-600 text-white font-medium text-sm">ចុះឈ្មោះ</a>
                                    </li>
                                @endauth

                                <li class="pt-2">
                                        <button class="w-full bg-blue-600 text-white py-4 rounded-xl font-bold shadow-lg text-base"
                                                data-key="nav-book">កក់ឥឡូវនេះ</button>
                                    </li>
                            </ul>
                    </div>
            </nav>

        <x-alert /> @yield('content')


        <!-- footer -->
        <footer class="py-16 bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800">
                <div class="container mx-auto px-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center md:text-left">

                                <div class="space-y-4">
                                        <h3 class="text-3xl font-extrabold tracking-tight text-blue-500" data-key="title">
                                                PNT HOTEL
                                            </h3>
                                        <p class="text-gray-500 dark:text-gray-400 leading-relaxed max-w-xs mx-auto md:mx-0"
                                                data-key="footer-desc">
                                                ផ្តល់ជូននូវបទពិសោធន៍ស្នាក់នៅដ៏ល្អបំផុត និងផាសុកភាពបំផុតសម្រាប់ដំណើរកម្សាន្តរបស់អ្នក។
                                            </p>
                                    </div>

                                <div>
                                        <h4 class="text-lg font-bold mb-6 text-gray-800 dark:text-gray-100" data-key="nav-contact">
                                                ទំនាក់ទំនង
                                            </h4>
                                        <ul class="space-y-4 text-gray-500 dark:text-gray-400">
                                                <li class="flex items-center justify-center md:justify-start gap-3">
                                                        <i class="fas fa-map-marker-alt text-blue-500"></i>
                                                        <span data-key="footer-address">ផ្លូវជាតិលេខ ៦, ក្រុងសៀមរាប</span>
                                                    </li>
                                                <li class="flex items-center justify-center md:justify-start gap-3">
                                                        <i class="fas fa-phone text-blue-500"></i>
                                                        <a href="tel:+85512345678" class="hover:text-blue-500 transition">+855 12 345 678</a>
                                                    </li>
                                                <li class="flex items-center justify-center md:justify-start gap-3">
                                                        <i class="fas fa-envelope text-blue-500"></i>
                                                        <a href="mailto:info@pnthotel.com"
                                                                class="hover:text-blue-500 transition">info@pnthotel.com</a>
                                                    </li>
                                            </ul>
                                    </div>

                                <div>
                                        <h4 class="text-lg font-bold mb-6 text-gray-800 dark:text-gray-100" data-key="footer-follow">
                                                តាមដានយើង
                                            </h4>
                                        <div class="flex justify-center md:justify-start gap-4">
                                                <a href="#"
                                                        class="group w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 transition-all duration-300 transform hover:scale-110">
                                                        <i class="fab fa-facebook-f text-gray-600 dark:text-gray-300 group-hover:text-white"></i>
                                                    </a>
                                                <a href="#"
                                                        class="group w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-sky-500 transition-all duration-300 transform hover:scale-110">
                                                        <i
                                                                class="fab fa-telegram-plane text-gray-600 dark:text-gray-300 group-hover:text-white"></i>
                                                    </a>
                                                <a href="#"
                                                        class="group w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-pink-600 transition-all duration-300 transform hover:scale-110">
                                                        <i class="fab fa-instagram text-gray-600 dark:text-gray-300 group-hover:text-white"></i>
                                                    </a>
                                            </div>
                                    </div>
                            </div>

                        <div class="border-t border-gray-100 dark:border-gray-800 mt-12 pt-8 text-center">
                                <p class="text-sm text-gray-500" data-key="footer-copy">
                                        &copy; 2026 <span class="font-semibold">PNT Hotel</span>. រក្សាសិទ្ធិគ្រប់យ៉ាង។
                                    </p>
                            </div>
                    </div>
            </footer>

        <x-script>
                {{-- អ្នកអាចបន្ថែម Script ពិសេសសម្រាប់តែ Page នេះនៅទីនេះ --}}
                <script>
            console.log('Website Scripts Loaded!');
        </script>
            </x-script>

        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
</body>

</html>

ធ្វើ Translate ដែល