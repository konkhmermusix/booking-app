<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark', mobileMenuOpen: false }"
    :class="{ 'dark': darkMode }"
    class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'P&T Palace Hotel')</title>

    <!-- CSS Linkings -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="{{ asset('fontawesome-icon/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header_style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Core Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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

<body class="bg-white text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-300 overflow-x-hidden antialiased">
    <div x-data="cartSystem()" x-init="getCount()">

        {{-- TOP INFORMATION BAR --}}
        <header class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-800 hidden md:block transition-colors">
            <div class="container mx-auto px-6 py-2 flex justify-between items-center text-[11px] font-medium text-gray-500 dark:text-gray-400">
                <div class="flex items-center gap-6">
                    <span class="flex items-center gap-2"><i class="fas fa-phone text-blue-500"></i> +855 964 301 974</span>
                    <span class="flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i> info@pntpalace.com</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-blue-500"></i> ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្ដត្បូងឃ្មុំ</span>
                </div>
            </div>
        </header>

        {{-- Dynamic Ambient Background Ambient Glows --}}
        <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-[-1] overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-200/20 dark:bg-blue-900/10 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-300/20 dark:bg-blue-800/10 rounded-full blur-[120px] animate-pulse"></div>
        </div>

        {{-- MAIN NAVIGATION BAR --}}
        <nav class="bg-white/80 dark:bg-gray-950/80 backdrop-blur-md sticky top-0 z-40 border-b border-gray-100 dark:border-gray-800 transition-colors">
            <div class="container mx-auto px-4 md:px-6 py-3 flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-blue-900 dark:text-blue-400 flex items-center gap-2 group">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center text-white font-bold group-hover:bg-yellow-700 transition">
                        <img src="{{ asset('images/logo/P&t Palace Hotel.png') }}" alt="Logo">
                    </div>
                    <span class="hidden sm:inline">ភីអេនធី</span>
                </a>

                <ul class="hidden lg:flex space-x-7 font-medium">
                    <li><a href="{{ route('home') }}" class="py-2 transition {{ Route::is('home') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">ទំព័រដើម</a></li>
                    <li><a href="{{ route('frontend.rooms') }}" class="py-2 transition {{ Route::is('frontend.rooms') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">បន្ទប់ស្នាក់នៅ</a></li>
                    <li><a href="{{ route('frontend.meeting') }}" class="py-2 transition {{ Route::is('frontend.meeting') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">សាលប្រជុំ</a></li>
                    <li><a href="{{ route('frontend.facilities') }}" class="py-2 transition {{ Route::is('frontend.facilities') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">សេវាកម្ម</a></li>
                    <li><a href="{{ route('frontend.about') }}" class="py-2 transition {{ Route::is('frontend.about') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">អំពីយើង</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="py-2 transition {{ Route::is('frontend.contact') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">ទំនាក់ទំនង</a></li>
                </ul>

                <div class="flex items-center space-x-2 md:space-x-4">
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                        class="p-2 rounded-xl bg-gray-100 dark:bg-gray-800 w-10 h-10 flex items-center justify-center transition hover:ring-2 ring-blue-400/50 text-blue-600 dark:text-yellow-400">
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
                        <button @click="cartOpen = !cartOpen" class="relative p-2 text-gray-700 dark:text-white rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span x-show="count > 0" x-text="count" class="absolute top-0 right-0 bg-red-500 text-white text-[10px] w-5 h-5 flex items-center justify-center rounded-full"></span>
                        </button>

                        <div x-show="cartOpen" @click.away="cartOpen = false" x-cloak x-transition
                            class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden z-50 border border-gray-100 dark:border-gray-700">
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
                                <a href="{{ route('cart.index') }}" class="block text-center bg-blue-600 text-white py-2 rounded-xl text-sm font-bold hover:bg-blue-700 transition">ទៅកាន់ទំព័រកន្ដ្រក</a>
                            </div>
                        </div>
                    </div>

                    <button @click="mobileMenuOpen = true"
                        class="p-2 text-2xl text-gray-700 dark:text-white rounded-xl bg-gray-100 dark:bg-gray-800 w-10 h-10 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
            </div>
        </nav>

        {{-- navigation bar for desktop mobile app --}}
        <div x-show="mobileMenuOpen" class="fixed inset-0 z-100 overflow-hidden" x-cloak>
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" x-show="mobileMenuOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"></div>
            <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
                <!-- Main Mobile Drawer Container -->
                <div class="w-screen max-w-md bg-white dark:bg-gray-950 shadow-2xl flex flex-col h-screen transition-transform"
                    x-data="{ showConfirm: false }" x-show="mobileMenuOpen" x-transition:enter="transform transition ease-in-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full">

                    <div class="p-5 border-b border-gray-100 dark:border-gray-800/60 flex justify-between items-center bg-gray-50/70 dark:bg-gray-900/70 backdrop-blur-md sticky top-0 z-10">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400 flex items-center justify-center">
                                <i class="fa-solid fa-bars text-sm"></i>
                            </div>
                            <h2 class="text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                មីនុយ
                            </h2>
                        </div>

                        <button @click="mobileMenuOpen = false"
                            type="button"
                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-gray-100 text-gray-500 dark:bg-gray-900 dark:text-gray-400 hover:bg-red-500 hover:text-white dark:hover:bg-red-600 dark:hover:text-white transition-all duration-200 focus:outline-none cursor-pointer">
                            <i class="fa-solid fa-xmark text-md"></i>
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto p-5 space-y-8">
                        <ul class="space-y-1.5 font-medium">
                            <li>
                                <a href="{{ route('home') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('home') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fas fa-home text-lg {{ Route::is('home') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>ទំព័រដើម</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.rooms') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.rooms') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fas fa-bed text-lg {{ Route::is('frontend.rooms') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>បន្ទប់ស្នាក់នៅ</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.meeting') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.meeting') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fas fa-users text-lg {{ Route::is('frontend.meeting') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>សាលប្រជុំ</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.facilities') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.facilities') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fas fa-concierge-bell text-lg {{ Route::is('frontend.facilities') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>សេវាកម្ម</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.about') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.about') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fas fa-info-circle text-lg {{ Route::is('frontend.about') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>អំពីយើង</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.contact') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.contact') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fas fa-address-book text-lg {{ Route::is('frontend.contact') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>ទំនាក់ទំនង</span>
                                </a>
                            </li>
                        </ul>

                        <div class="pt-6 border-t border-gray-100 dark:border-gray-800/60">
                            @auth
                            <div class="flex items-center gap-3 p-3.5 mb-4 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800/50">
                                <div class="w-11 h-11 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-sm overflow-hidden border-2 border-white dark:border-gray-800">
                                    @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="flex items-center justify-center w-full h-full bg-blue-500 text-white font-bold text-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase font-bold tracking-wider">គណនីរបស់អ្នក</p>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                </div>
                            </div>

                            <div class="space-y-1">
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                                <a href="{{ route('dashboard') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl transition">
                                    <i class="fas fa-chart-line text-gray-400 w-5"></i><span>ផ្ទាំងគ្រប់គ្រង</span>
                                </a>
                                @endif
                                <a href="{{ route('booking.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl transition">
                                    <i class="fas fa-calendar-check text-gray-400 w-5"></i><span>ការកក់របស់ខ្ញុំ</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl transition">
                                    <i class="fas fa-user-cog text-gray-400 w-5"></i><span>ប្រវត្តិរូប</span>
                                </a>
                            </div>
                            @else
                            <div class="grid grid-cols-2 gap-3 px-2">
                                <a href="{{ route('login') }}" @click="mobileMenuOpen = false"
                                    class="text-center py-2 rounded-xl border border-gray-200 dark:border-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 transition cursor-pointer">
                                    ចូលប្រើ
                                </a>
                                <a href="{{ route('register') }}" @click="mobileMenuOpen = false"
                                    class="text-center py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-sm shadow-blue-500/10 cursor-pointer">
                                    ចុះឈ្មោះ
                                </a>
                            </div>
                            @endauth
                        </div>
                    </div>

                    <div class="p-4 border-t border-gray-100 dark:border-gray-800/60 bg-gray-50 dark:bg-gray-900 sticky bottom-0 z-10">
                        @auth
                        <button type="button" x-show="!showConfirm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" @click="showConfirm = true"
                            class="w-full flex items-center gap-3 px-4 py-2 text-red-500 text-sm font-bold hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl transition cursor-pointer">
                            <i class="fas fa-sign-out-alt text-lg"></i>
                            <span>ចាកចេញពីគណនី</span>
                        </button>

                        <div x-show="showConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                            class="bg-white dark:bg-gray-950 p-3 rounded-xl border border-red-100 dark:border-red-950/30 shadow-sm">

                            <p class="text-xs font-semibold text-center text-gray-600 dark:text-gray-400 mb-3">
                                តើអ្នកពិតជាចង់ចាកចេញពីគណនីមែនទេ?
                            </p>

                            <div class="grid grid-cols-2 gap-2">
                                <button type="button"
                                    @click="showConfirm = false"
                                    class="py-2 text-xs font-bold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 transition text-center cursor-pointer">
                                    បោះបង់
                                </button>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-2 text-xs font-bold text-white bg-red-500 rounded-lg hover:bg-red-600 transition text-center shadow-sm cursor-pointer">
                                        ចាកចេញ
                                    </button>
                                </form>
                            </div>
                        </div>
                        @else
                        <p class="text-xs text-center text-gray-400 dark:text-gray-500 py-2">សូមចូលប្រើប្រាស់ដើម្បីគ្រប់គ្រងគណនី</p>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <main class="min-h-screen">
            <x-alert />
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="py-12 bg-white border-t border-gray-200 dark:border-gray-800 dark:bg-[#0b1120] transition-colors">
            <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
                <div>
                    <h3 class="text-2xl font-extrabold text-blue-500 mb-4 italic tracking-wide">សណ្ឋាគារ ភីអេនធី ផាលេស</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">ផ្តល់ជូននូវបទពិសោធន៍ស្នាក់នៅដ៏ល្អបំផុត និងផាសុកភាពបំផុតសម្រាប់ដំណើរកម្សាន្តរបស់អ្នក។</p>
                </div>
                <div>
                    <h4 class="text-md font-bold mb-4 text-gray-800 dark:text-white uppercase tracking-wider">ទំនាក់ទំនង</h4>
                    <ul class="space-y-3 text-gray-500 dark:text-gray-400 text-sm">
                        <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt text-blue-500 mt-1"></i><span>ភូមិនិគមលើ ឃុំស្រឡប់ ស្រុកត្បូងឃ្មុំ ខេត្ដត្បូងឃ្មុំ</span></li>
                        <li class="flex items-center gap-3"><i class="fas fa-phone text-blue-500"></i><span>+855 964 301 974</span></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-md font-bold mb-4 text-gray-800 dark:text-white uppercase tracking-wider">តាមដានយើងតាមរយៈ</h4>
                    <div class="flex flex-wrap gap-3">
                        @if(isset($contactSettings) && count($contactSettings) > 0)

                        @if(!empty($contactSettings['facebook']))
                        <a href="{{ $contactSettings['facebook'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Facebook"
                            class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl flex items-center justify-center transition-all shadow-sm hover:text-white cursor-pointer"
                            style="--hover-bg: #1877F2;"
                            onmouseover="this.style.backgroundColor=this.style.getPropertyValue('--hover-bg')"
                            onmouseout="this.style.backgroundColor=''">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        @endif

                        @if(!empty($contactSettings['telegram']))
                        <a href="{{ $contactSettings['telegram'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="Telegram"
                            class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl flex items-center justify-center transition-all shadow-sm hover:text-white cursor-pointer"
                            style="--hover-bg: #0088cc;"
                            onmouseover="this.style.backgroundColor=this.style.getPropertyValue('--hover-bg')"
                            onmouseout="this.style.backgroundColor=''">
                            <i class="fab fa-telegram-plane"></i>
                        </a>
                        @endif

                        @if(!empty($contactSettings['youtube']))
                        <a href="{{ $contactSettings['youtube'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="YouTube"
                            class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl flex items-center justify-center transition-all shadow-sm hover:text-white cursor-pointer"
                            style="--hover-bg: #FF0000;"
                            onmouseover="this.style.backgroundColor=this.style.getPropertyValue('--hover-bg')"
                            onmouseout="this.style.backgroundColor=''">
                            <i class="fab fa-youtube"></i>
                        </a>
                        @endif

                        @if(!empty($contactSettings['tiktok']))
                        <a href="{{ $contactSettings['tiktok'] }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            title="TikTok"
                            class="w-10 h-10 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 rounded-xl flex items-center justify-center transition-all shadow-sm hover:text-white cursor-pointer"
                            style="--hover-bg: #000000;"
                            onmouseover="this.style.backgroundColor=this.style.getPropertyValue('--hover-bg')"
                            onmouseout="this.style.backgroundColor=''">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        @endif

                        @else
                        <a href="#" class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all shadow-sm"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center hover:bg-sky-500 hover:text-white transition-all shadow-sm"><i class="fab fa-telegram-plane"></i></a>
                        <a href="#" class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center hover:bg-red-700 hover:text-white transition-all shadow-sm"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="w-10 h-10 bg-gray-100 dark:bg-gray-800 rounded-xl flex items-center justify-center hover:bg-black hover:text-white transition-all shadow-sm"><i class="fab fa-tiktok"></i></a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-100 dark:border-gray-900 mt-12 pt-8 text-center text-xs text-gray-400">
                &copy; 2026 សណ្ឋាគារ ភីអេនធី។ រក្សាសិទ្ធិគ្រប់យ៉ាង។
            </div>
        </footer>

        {{-- FLOATING SYSTEM WIDGETS (Livechat & Scroll up) --}}
        <div x-data="chatSystem()" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">

            <div x-show="openChat" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-10 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-10 scale-95" x-cloak
                class="w-[340px] sm:w-[380px] bg-white dark:bg-gray-900 rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-800 mb-2">

                <div class="bg-gradient-to-r from-blue-600 to-blue-500 p-4 text-white flex justify-between items-center shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center backdrop-blur-sm">
                                <i class="fab fa-facebook-messenger text-white text-xl"></i>
                            </div>
                            <span class="absolute bottom-[-2px] right-[-2px] w-3 h-3 bg-green-400 border-2 border-white dark:border-gray-900 rounded-full"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm">P&T Palace Support</h4>
                            <p class="text-[10px] opacity-80 italic">Online</p>
                        </div>
                    </div>
                    <button @click="openChat = false" class="hover:bg-white/20 w-8 h-8 rounded-lg flex items-center justify-center transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                {{-- Live Chat Message List Area --}}
                <div id="chat-box" class="h-[320px] overflow-y-auto p-4 bg-gray-50 dark:bg-slate-950 flex flex-col gap-4">
                    <div class="flex items-start gap-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex-shrink-0 flex items-center justify-center">
                            <i class="fab fa-facebook-messenger text-blue-600 dark:text-blue-400 text-xs"></i>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%] border border-gray-100 dark:border-gray-700">
                            <p class="text-sm text-gray-700 dark:text-gray-300">សួស្តី! តើយើងអាចជួយអ្វីអ្នកបានខ្លះ?</p>
                            <span class="text-[9px] text-gray-400 mt-1 block">12:33 PM</span>
                        </div>
                    </div>

                    <div x-show="previews.length > 0" class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-dashed border-blue-300 dark:border-blue-800 rounded-xl grid grid-cols-3 gap-2">
                        <template x-for="(src, index) in previews" :key="index">
                            <div class="relative group">
                                <img :src="src" class="w-full h-16 object-cover rounded-lg shadow-sm">
                                <button @click="removeImage(index)" class="absolute -top-1.5 -right-1.5 bg-red-500 text-white w-4 h-4 rounded-full text-[9px] flex items-center justify-center shadow">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="p-3 bg-white dark:bg-gray-900 border-t dark:border-gray-800">
                    <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-xl">
                        <label class="cursor-pointer text-gray-400 hover:text-blue-500 p-1 transition-colors">
                            <input type="file" multiple class="hidden" @change="previewFiles" accept="image/*">
                            <i class="fas fa-paperclip"></i>
                        </label>
                        <input type="text" x-model="newMessage" @keydown.enter="sendChat" placeholder="សរសេរសារ..." class="flex-1 bg-transparent border-none focus:ring-0 text-sm dark:text-white outline-none">
                        <button @click="sendChat" class="w-8 h-8 bg-blue-600 text-white rounded-lg flex items-center justify-center hover:bg-blue-700 transition-all shadow-sm"><i class="fas fa-paper-plane text-xs"></i></button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button id="scrollTopBtn"
                    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="fixed bottom-6 right-20 z-50 flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-full shadow-[0_10px_25px_-5px_rgba(37,99,235,0.4)] transition-all duration-500 translate-y-20 opacity-0 group hover:shadow-[0_20px_30px_-10px_rgba(37,99,235,0.6)] hover:-translate-y-1 active:scale-90 pointer-events-none cursor-pointer">
                    <i class="fas fa-arrow-up text-md"></i>
                </button>

                <button @click="openChat = !openChat"
                    class="w-12 h-12 bg-[#0084FF] text-white rounded-xl shadow-xl flex items-center justify-center hover:scale-105 active:scale-95 transition-all relative group">
                    <span x-show="!openChat" class="absolute inset-0 rounded-xl bg-blue-400 animate-ping opacity-20"></span>
                    <i x-show="!openChat" class="fab fa-facebook-messenger text-2xl"></i>
                    <i x-show="openChat" class="fas fa-chevron-down text-xl" x-cloak></i>
                    <span class="absolute right-full mr-3 px-2 py-1 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-md pointer-events-none">ឆាតមកយើងខ្ញុំ</span>
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript External Scripts Core Asset Management Libraries -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('fontawesome-icon/js/all.min.js') }}"></script>
    <script src="{{ asset('js/scroll.js') }}"></script>
    <script src="{{ asset('js/slide.js') }}"></script>
    <script src="{{ asset('js/date_for_select_search.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- For Chat and Scroll UI Actions --}}
    <script>
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const scrollTopBtn = document.getElementById("scrollTopBtn");
            let lastScrollTop = 0;

            window.addEventListener("scroll", function() {
                let currentScroll = window.pageYOffset || document.documentElement.scrollTop;
                if (currentScroll > 200) {

                    if (currentScroll < lastScrollTop) {
                        scrollTopBtn.classList.remove("translate-y-20", "opacity-0", "pointer-events-none");
                        scrollTopBtn.classList.add("translate-y-0", "opacity-100");
                    } else {
                        scrollTopBtn.classList.remove("translate-y-0", "opacity-100");
                        scrollTopBtn.classList.add("translate-y-20", "opacity-0", "pointer-events-none");
                    }

                } else {
                    scrollTopBtn.classList.remove("translate-y-0", "opacity-100");
                    scrollTopBtn.classList.add("translate-y-20", "opacity-0", "pointer-events-none");
                }

                lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
            }, {
                passive: true
            });
        });
    </script>
</body>

</html>