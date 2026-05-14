<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark', mobileMenuOpen: false }"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('fontawesome-icon/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header_style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300 overflow-x-hidden">

    <div x-data="cartSystem()" x-init="getCount()">
        <div class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-800 hidden md:block">
            <div class="container mx-auto px-6 py-2 flex justify-between items-center text-[11px] font-medium text-gray-500 dark:text-gray-400">
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2"><i class="fas fa-phone text-blue-500"></i> +855 964 301 974</span>
                    <span class="flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i> info@pntpalace.com</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-blue-500"></i> ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្ដត្បូងឃ្មុំ</span>
                </div>
            </div>
        </div>

        <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-[-1] overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-200/20 dark:bg-blue-900/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-300/20 dark:bg-blue-800/10 rounded-full blur-[120px] animate-pulse"></div>
        </div>

        <nav class="bg-white/80 dark:bg-gray-950/80 backdrop-blur-md sticky top-0 z-100 border-b border-gray-100 dark:border-gray-800">
            <div class="container mx-auto px-4 md:px-6 py-3 flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-blue-900 dark:text-blue-400 flex items-center gap-2 group">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center text-white font-bold group-hover:bg-yellow-700 transition">
                        <img src="{{ asset('images/logo/P&t Palace Hotel.png') }}" alt="Logo">
                    </div>
                    <span class="hidden sm:inline">ភីអេនធី</span>
                </a>

                <ul class="hidden lg:flex space-x-7 font-medium">
                    <li><a href="{{ route('home') }}" class="transition {{ Route::is('home') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}"> ទំព័រដើម</a> </li>
                    <li><a href="{{ route('frontend.rooms') }}" class="transition {{ Route::is('frontend.rooms') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">បន្ទប់ស្នាក់នៅ</a></li>
                    <li><a href="{{ route('frontend.meeting') }}" class="transition {{ Route::is('frontend.meeting') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">សាលប្រជុំ</a></li>
                    <li><a href="{{ route('frontend.facilities') }}" class="transition {{ Route::is('frontend.facilities') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">សេវាកម្ម</a></li>
                    <li><a href="{{ route('frontend.about') }}" class="transition {{ Route::is('frontend.about') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">អំពីយើង</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="transition {{ Route::is('frontend.contact') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">ទំនាក់ទំនង</a></li>
                </ul>

                <div class="flex items-center space-x-3 md:space-x-4">
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 w-10 h-10 flex items-center justify-center transition hover:ring-2 ring-blue-400/50 text-blue-600 dark:text-yellow-400">
                        <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                    </button>

                    <div class="hidden xl:flex items-center gap-4 border-l pl-4 dark:border-gray-700">
                        @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none group">
                                <div class="text-right hidden sm:block">
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase">គណនី</p>
                                    <p class="text-sm font-bold dark:text-white group-hover:text-blue-600 transition">{{ Auth::user()->name }}</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-lg overflow-hidden border-2 border-white dark:border-gray-800">
                                    @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="flex items-center justify-center w-full h-full bg-blue-500 text-white font-bold text-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    @endif
                                </div>
                            </button>

                            <div x-show="open" x-cloak x-transition class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-chart-line text-blue-500"></i>ផ្ទាំងគ្រប់គ្រង</a>
                                @endif
                                <a href="{{ route('booking.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-calendar-check text-blue-500"></i>ការកក់របស់ខ្ញុំ</a>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 border-b dark:border-gray-700"><i class="fas fa-user-cog text-blue-500"></i>ប្រវត្ថិរូប</a>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition mt-1">
                                        <i class="fas fa-sign-out-alt"></i>ចាកចេញ
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-blue-600 dark:text-gray-300">ចូលប្រើ</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 px-5 py-2 rounded-xl transition">ចុះឈ្មោះ</a>
                        @endauth
                    </div>

                    <div class="relative" x-data="{ cartOpen: false }">
                        <button @click="cartOpen = !cartOpen" class="relative p-2 text-gray-700 dark:text-white">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span x-show="count > 0" x-text="count" class="absolute top-0 right-0 bg-red-500 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full"></span>
                        </button>

                        <div x-show="cartOpen" @click.away="cartOpen = false" x-cloak x-transition
                            class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden z-50 border border-gray-100 dark:border-gray-700">
                            <div class="p-4 border-b dark:border-gray-700">
                                <h5 class="font-bold">បន្ទប់ដែលបានជ្រើសរើស</h5>
                            </div>
                            <div class="max-h-60 overflow-y-auto">
                                <template x-if="cartItems.length === 0">
                                    <div class="p-4 text-center text-gray-500 text-sm">មិនទាន់មានទិន្នន័យ</div>
                                </template>
                                <template x-for="item in cartItems" :key="item.id">
                                    <div class="p-3 flex items-center gap-3 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <img :src="'/storage/' + item.image" class="w-12 h-12 rounded-lg object-cover">
                                        <div class="flex-1">
                                            <p x-text="item.name" class="text-sm font-bold line-clamp-1"></p>
                                            <p x-text="'$' + item.price" class="text-xs text-blue-600 font-semibold"></p>
                                        </div>
                                        <button @click="removeFromCart(item.id)" class="text-red-400 hover:text-red-600">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div class="p-4 bg-gray-50 dark:bg-gray-900/50">
                                <a href="/checkout" class="block text-center bg-blue-600 text-white py-2 rounded-xl text-sm font-bold">ទៅកាន់ទំព័រទូទាត់ប្រាក់</a>
                            </div>
                        </div>
                    </div>

                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="lg:hidden text-2xl p-2 focus:outline-none dark:text-white z-[100] relative">
                        <i class="fa-solid" :class="mobileMenuOpen ? 'fa-xmark' : 'fa-bars'"></i>
                    </button>
                </div>

                <!-- Mobile Menu Panel -->
                <div x-show="mobileMenuOpen" x-cloak x-transition @click.away="mobileMenuOpen = false"
                    class="lg:hidden bg-white dark:bg-gray-950 border-b dark:border-gray-800 absolute w-full top-full left-0 shadow-2xl z-[90]">
                    <ul class="flex flex-col p-5 space-y-2 font-medium">
                        <li><a href="{{ route('home') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl dark:text-white">ទំព័រដើម</a></li>
                        <li><a href="{{ route('frontend.rooms') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl dark:text-white">បន្ទប់ស្នាក់នៅ</a></li>
                        <li><a href="{{ route('frontend.meeting') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl dark:text-white">សាលប្រជុំ</a></li>
                        <li><a href="{{ route('frontend.facilities') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl dark:text-white">សេវាកម្ម</a></li>
                        <li><a href="{{ route('frontend.about') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl dark:text-white">អំពីយើង</a></li>
                        <li><a href="{{ route('frontend.contact') }}" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl dark:text-white">ទំនាក់ទំនង</a></li>

                        @auth
                        <li class="pt-4 border-t dark:border-gray-800">
                            <span class="px-4 text-xs font-bold text-gray-400 uppercase">គណនីរបស់អ្នក</span>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 dark:text-white">ប្រវត្ថិរូប</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-red-500 font-bold">ចាកចេញ</button>
                            </form>
                        </li>
                        @else
                        <li class="grid grid-cols-2 gap-3 pt-5 border-t dark:border-gray-800">
                            <a href="{{ route('login') }}" class="text-center py-3 rounded-xl border dark:border-gray-700 text-sm dark:text-white">ចូលប្រើ</a>
                            <a href="{{ route('register') }}" class="text-center py-3 rounded-xl bg-blue-600 text-white text-sm">ចុះឈ្មោះ</a>
                        </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <x-alert /> @yield('content')
        </main>

        <footer class="py-10 bg-white border-t border-gray-200  dark:border-gray-800 dark:bg-[#0b1120]">
            <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <h3 class="text-3xl font-extrabold text-blue-500 mb-4 italic">សណ្ឋាគារ ភីអេនធី ផាលេស</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed">ផ្តល់ជូននូវបទពិសោធន៍ស្នាក់នៅដ៏ល្អបំផុត និងផាសុកភាពបំផុតសម្រាប់ដំណើរកម្សាន្តរបស់អ្នក។</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6">ទំនាក់ទំនង</h4>
                    <ul class="space-y-4 text-gray-500 dark:text-gray-400">
                        <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-blue-500"></i>ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្ដត្បូងឃ្មុំ</li>
                        <li class="flex items-center gap-3"><i class="fas fa-phone text-blue-500"></i>+855 964 301 974</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6">តាមដានយើងតាមរយៈ</h4>
                    <div class="flex gap-4">
                        <a href="#" class="w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-sky-500 hover:text-white transition-all"><i class="fab fa-telegram-plane"></i></a>
                        <a href="#" class="w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-red-400 hover:text-white transition-all"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-red-700 hover:text-white transition-all"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="w-11 h-11 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center hover:bg-emerald-900 hover:text-white transition-all"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-800 mt-12 pt-8 text-center text-sm text-gray-500">
                &copy; 2026 សណ្ឋាគារ ភីអេនធី។ រក្សាសិទ្ធិគ្រប់យ៉ាង។
            </div>
        </footer>

        <div x-data="chatSystem()" class="fixed bottom-8 right-6 z-[100] flex flex-col items-end gap-4">

            <!-- Chat Window -->
            <div x-show="openChat"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-cloak
                class="w-[350px] md:w-[400px] bg-white dark:bg-gray-900 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800 mb-4">

                <!-- Header: Gradient Blue -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-400 p-4 text-white flex justify-between items-center shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                                <i class="fab fa-facebook-messenger text-blue-600 text-xl"></i>
                            </div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">P&T Palace Support</h4>
                            <p class="text-[10px] opacity-90 italic">Online</p>
                        </div>
                    </div>
                    <button @click="openChat = false" class="hover:bg-white/20 w-8 h-8 rounded-full transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Messages Area -->
                <div id="chat-box" class="h-[350px] overflow-y-auto p-4 bg-gray-50 dark:bg-slate-950 flex flex-col gap-4">
                    <!-- Message from Admin -->
                    <div class="flex items-start gap-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex-shrink-0 flex items-center justify-center">
                            <i class="fab fa-facebook-messenger text-blue-600 text-xs"></i>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%] border border-gray-100 dark:border-gray-700">
                            <p class="text-sm text-gray-700 dark:text-gray-300">សួស្តី! តើយើងអាចជួយអ្វីអ្នកបានខ្លះ?</p>
                            <span class="text-[9px] text-gray-400 mt-1 block">12:33 PM</span>
                        </div>
                    </div>

                    <!-- Preview Images Area -->
                    <div x-show="previews.length > 0" class="p-3 bg-blue-50 dark:bg-blue-900/20 border-2 border-dashed border-blue-200 dark:border-blue-800 rounded-2xl grid grid-cols-3 gap-2">
                        <template x-for="(src, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="src" class="w-full h-20 object-cover rounded-lg shadow-md">
                                <button @click="removeImage(index)" class="absolute -top-2 -right-2 bg-red-500 text-white w-5 h-5 rounded-full text-[10px] flex items-center justify-center shadow-lg">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-white dark:bg-gray-900 border-t dark:border-gray-800">
                    <div class="flex items-center gap-3 bg-gray-100 dark:bg-gray-800 px-4 py-2 rounded-2xl">
                        <label class="cursor-pointer text-gray-500 hover:text-blue-600 transition-colors">
                            <input type="file" multiple class="hidden" @change="previewFiles" accept="image/*">
                            <i class="fas fa-paperclip"></i>
                        </label>

                        <input type="text" x-model="newMessage" @keydown.enter="sendChat"
                            placeholder="សរសេរសារ..."
                            class="flex-1 bg-transparent border-none focus:ring-0 text-sm dark:text-white outline-none">

                        <button @click="sendChat"
                            class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 active:scale-95 transition-all">
                            <i class="fas fa-paper-plane text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button id="scrollTopBtn"
                onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-full shadow-[0_10px_25px_-5px_rgba(37,99,235,0.4)] transition-all duration-500 translate-y-20 opacity-0 group hover:shadow-[0_20px_30px_-10px_rgba(37,99,235,0.6)] hover:-translate-y-1 active:scale-90 pointer-events-none">
                <span class="absolute inset-0 rounded-full bg-blue-500 animate-ping opacity-20 group-hover:opacity-40"></span>
                <i class="fas fa-arrow-up text-lg relative z-10 transition-transform duration-300 group-hover:-translate-y-1"></i>
            </button>

            <button @click="openChat = !openChat"
                class="w-12 h-12 bg-[#0084FF] text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all relative group">

                <span x-show="!openChat" class="absolute inset-0 rounded-full bg-blue-400 animate-ping opacity-20"></span>

                <i x-show="!openChat" class="fab fa-facebook-messenger text-2xl"></i>
                <i x-show="openChat" class="fas fa-chevron-down text-xl" x-cloak></i>

                <span x-show="!openChat" class="absolute right-full mr-1 px-2 py-1 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                    ឆាតមកយើងខ្ញុំ
                </span>
            </button>
        </div>
    </div>


    <!-- <script src="//unpkg.com/alpinejs" defer></script> -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('fontawesome-icon/js/all.min.js') }}"></script>
    <script src="{{ asset('js/scroll.js') }}"></script>
    <script src="{{ asset('js/slide.js') }}"></script>
    <script src="{{ asset('js/date_for_select_search.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }
    </script>

    <script>
        const scrollTopBtn = document.getElementById('scrollTopBtn');

        window.onscroll = function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                scrollTopBtn.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                scrollTopBtn.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
            } else {
                scrollTopBtn.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
                scrollTopBtn.classList.remove('translate-y-0', 'opacity-100', 'pointer-events-auto');
            }
        };
    </script>

    <script>
        function previewImages(input) {
            const previewArea = document.getElementById('image-preview-area');
            const files = input.files;

            if (files && files.length > 0) {
                previewArea.classList.remove('hidden');

                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = "relative w-16 h-16 rounded-lg overflow-hidden border border-gray-200 shadow-sm";
                        div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover">
                    <button onclick="this.parentElement.remove(); checkPreviewArea();" 
                        class="absolute top-0 right-0 bg-red-500 text-white w-5 h-5 flex items-center justify-center rounded-bl-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-times text-[8px]"></i>
                    </button>
                `;
                        previewArea.appendChild(div);
                    };
                    reader.readAsDataURL(files[i]);
                }
            }
        }

        function checkPreviewArea() {
            const previewArea = document.getElementById('image-preview-area');
            if (previewArea.children.length === 0) {
                previewArea.classList.add('hidden');
                document.getElementById('chat-file-input').value = ""; // Clear input
            }
        }

        function sendMessage() {
            const textInput = document.getElementById('chat-text-input');
            const msg = textInput.value.trim();

            if (msg !== "") {
                alert("សាររបស់អ្នក: " + msg);
                textInput.value = "";
                document.getElementById('image-preview-area').innerHTML = "";
                document.getElementById('image-preview-area').classList.add('hidden');
            }
        }
    </script>
</body>

</html>