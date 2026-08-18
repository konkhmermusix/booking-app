<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark', mobileMenuOpen: false }"
    :class="{ 'dark': darkMode }"
    class="scroll-smooth">

@php
    $dynLogoUrl = $contactSettings['logo_url'];
    $dynSiteName = $contactSettings['site_name'];
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ $dynLogoUrl }}">
    <title>@yield('title') | {{ $dynSiteName }}</title>

    <meta name="description"
        content="{{ $dynSiteName }} ផ្តល់សេវាកម្មកក់បន្ទប់អនឡាញ ងាយស្រួល រហ័ស និងមានបន្ទប់ស្នាក់នៅគុណភាពល្អ">

    <meta name="keywords"
        content="P&T Palace Hotel, Hotel Cambodia, Hotel Booking Cambodia, Online Room Booking, Hotel Reservation, Tbong Khmum Hotel, Cambodia Hotel">

    <meta name="author" content="{{ $dynSiteName }}">

    <!-- Search Engine -->
    <meta name="robots" content="index, follow">

    <!-- Open Graph (Facebook / Telegram / Social Share) -->
    <meta property="og:title" content="{{ $dynSiteName }} - Online Booking">
    <meta property="og:description"
        content="Book your hotel room easily with {{ $dynSiteName }}. Comfortable rooms and online reservation system.">
    <meta property="og:image" content="{{ $dynLogoUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $dynSiteName }}">
    <meta name="twitter:description" content="Online Hotel Booking System">
    <meta name="twitter:image" content="{{ $dynLogoUrl }}">

    <!-- Security -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ $dynLogoUrl }}">

    <!-- CSS Linkings -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('fontawesome-icon/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('swiper/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header_style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/spotlight.js@0.7.8/dist/spotlight.bundle.js"></script>

    <!-- Core Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    <div>
        {{-- TOP INFORMATION BAR --}}
        <header class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-800 transition-colors">
            <div class="container mx-auto px-4 sm:px-6 py-2.5 md:py-2 flex flex-col md:flex-row justify-between items-center text-[11px] font-medium text-gray-500 dark:text-gray-400 gap-2 md:gap-0 text-center md:text-left">

                @php
                    $dynPhone = $contactSettings['phone'];
                    $dynEmail = $contactSettings['email'];
                    $dynAddress = $contactSettings['address'];
                    $dynLogoUrl = $contactSettings['logo_url'];
                    $dynSiteName = $contactSettings['site_name'];
                @endphp

                <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $dynPhone) }}" class="flex items-center gap-2 whitespace-nowrap hover:text-blue-500 transition-colors">
                        <i class="fas fa-phone text-blue-500"></i> {{ $dynPhone }}
                    </a>
                    <a href="mailto:{{ $dynEmail }}" class="flex items-center gap-2 whitespace-nowrap hover:text-blue-500 transition-colors">
                        <i class="fas fa-envelope text-blue-500"></i> {{ $dynEmail }}
                    </a>
                </div>

                <div class="flex items-center justify-center gap-4 max-w-full md:max-w-xl text-center md:text-right leading-relaxed">
                    <span class="flex items-center justify-center gap-2">
                        <i class="fas fa-map-marker-alt text-blue-500 flex-shrink-0"></i>
                        <span>{{ $dynAddress }}</span>
                    </span>
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
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center text-white font-bold group-hover:bg-yellow-700 transition overflow-hidden p-1">
                        <img src="{{ $dynLogoUrl }}" alt="{{ $dynSiteName }} Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="hidden sm:inline">{{ $dynSiteName }}</span>
                </a>

                <ul class="hidden lg:flex space-x-7 font-medium">
                    <li><a href="{{ route('home') }}" class="py-2 transition {{ Route::is('home') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">ទំព័រដើម</a></li>
                    <li><a href="{{ route('frontend.rooms') }}" class="py-2 transition {{ Route::is('frontend.rooms') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">បន្ទប់ស្នាក់នៅ</a></li>
                    <li><a href="{{ route('frontend.meeting') }}" class="py-2 transition {{ Route::is('frontend.meeting') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">សាលប្រជុំ</a></li>
                    <li><a href="{{ route('frontend.posts') }}" class="py-2 transition {{ Route::is('frontend.posts') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">ព័ត៌មាន</a></li>
                    <li><a href="{{ route('frontend.facilities') }}" class="py-2 transition {{ Route::is('frontend.facilities') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">សេវាកម្ម</a></li>
                    <li><a href="{{ route('frontend.about') }}" class="py-2 transition {{ Route::is('frontend.about') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">អំពីយើង</a></li>
                    <li><a href="{{ route('frontend.contact') }}" class="py-2 transition {{ Route::is('frontend.contact') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">ទំនាក់ទំនង</a></li>
                </ul>

                <div class="flex items-center space-x-2 md:space-x-4">

                    {{-- 1. Dark Mode Toggle Icon Button --}}
                    <button
                        @click="darkMode = !darkMode; 
                            localStorage.setItem('theme', darkMode ? 'dark' : 'light');
                            darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')"
                        type="button"
                        title="ប្តូរពន្លឺ / ងងឹត (Dark Mode)"
                        class="w-10 h-10 rounded-2xl bg-gray-100/80 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-800/80 flex items-center justify-center transition-all duration-300 text-gray-700 dark:text-amber-400 hover:bg-blue-50/70 dark:hover:bg-gray-700/80 hover:border-blue-200 dark:hover:border-gray-700 hover:scale-105 shadow-xs cursor-pointer group">
                        <span class="text-base leading-none transition-transform duration-300 group-hover:rotate-12"
                            x-html="darkMode ? `<i class='fa-solid fa-sun text-amber-400 text-lg'></i>` : `<i class='fa-solid fa-moon text-blue-600 text-base'></i>`">
                        </span>
                    </button>

                    <!-- ២. ផ្នែកគណនី (Auth) -->
                    <div class="hidden xl:flex items-center gap-3 border-l pl-3 dark:border-gray-800">
                        @auth
                        <div x-data="{ open: false }" class="relative" @click.away="open = false">
                            <button @click="open = !open" class="flex items-center gap-3 p-1.5 pr-3.5 rounded-2xl bg-gray-100/80 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-800/80 hover:bg-blue-50/70 dark:hover:bg-gray-700/80 hover:border-blue-200 dark:hover:border-gray-700 transition-all duration-200 group focus:outline-none cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white shadow-sm overflow-hidden shrink-0">
                                    @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                    <span class="flex items-center justify-center w-full h-full bg-blue-600 text-white font-bold text-xs">
                                        {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="text-left leading-tight pr-1">
                                    <p class="text-[9px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest">គណនី</p>
                                    <p class="text-xs font-extrabold text-gray-800 dark:text-white group-hover:text-blue-600 transition truncate max-w-[110px]">{{ Auth::user()->name }}</p>
                                </div>
                                <i class="fa-solid fa-chevron-down text-[10px] text-gray-400 group-hover:text-blue-500 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                            </button>

                            <!-- Dropdown គណនី -->
                            <div x-show="open" x-cloak x-transition class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">
                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-chart-line text-blue-500"></i>ផ្ទាំងគ្រប់គ្រង</a>
                                @endif
                                <a href="{{ route('mybookings') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-calendar-check text-blue-500"></i>ការកក់របស់ខ្ញុំ</a>
                                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-bell text-blue-500"></i>ការជូនដំណឹង</a>
                                <a href="{{ route('setting.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 border-b dark:border-gray-700"><i class="fas fa-user-cog text-blue-500"></i>ការកំណត់</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                                <button type="button" onclick="confirmLogout(event)" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition mt-1 cursor-pointer">
                                    <i class="fas fa-sign-out-alt"></i>ចាកចេញ
                                </button>
                            </div>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="text-sm font-medium hover:text-blue-600 dark:text-gray-300">ចូលប្រើ</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 px-5 py-2 rounded-xl transition">ចុះឈ្មោះ</a>
                        @endauth
                    </div>

                    {{-- 2.5 Customer Notification Bell Dropdown --}}
                    @auth
                    <div x-data="{
                        openNotif: false,
                        unreadCount: 0,
                        notifications: [],
                        loadingNotifs: false,
                        fetchCustomerNotifs() {
                            this.loadingNotifs = true;
                            fetch('{{ route('customer.notifications') }}', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                this.unreadCount = data.count || 0;
                                this.notifications = data.notifications || [];
                                this.loadingNotifs = false;
                            })
                            .catch(err => {
                                console.error('Customer notif fetch error:', err);
                                this.loadingNotifs = false;
                            });
                        },
                        markAllRead() {
                            const unreadIds = this.notifications.filter(n => n.is_unread).map(n => n.id);
                            if (unreadIds.length === 0) return;
                            fetch('{{ route('customer.notifications.mark-read') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ ids: unreadIds })
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    this.unreadCount = 0;
                                    this.notifications.forEach(n => n.is_unread = false);
                                }
                            });
                        }
                    }" x-init="fetchCustomerNotifs(); setInterval(() => fetchCustomerNotifs(), 15000);" class="relative" @click.away="openNotif = false">
                        <button @click="openNotif = !openNotif" type="button" title="ការជូនដំណឹង"
                            class="w-10 h-10 rounded-2xl bg-gray-100/80 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-800/80 flex items-center justify-center transition-all duration-300 text-blue-600 dark:text-amber-400 hover:bg-blue-50/70 dark:hover:bg-gray-700/80 hover:border-blue-200 dark:hover:border-gray-700 hover:scale-105 shadow-xs relative group cursor-pointer">
                            <i class="fa-solid fa-bell text-base group-hover:rotate-12 transition-transform"></i>
                            <template x-if="unreadCount > 0">
                                <span class="absolute -top-1 -right-1 min-w-[20px] h-[20px] px-1 bg-amber-500 text-white text-[10px] font-extrabold rounded-full flex items-center justify-center shadow-md animate-bounce" x-text="unreadCount"></span>
                            </template>
                        </button>

                        {{-- Notification Dropdown --}}
                        <div x-show="openNotif" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="fixed left-4 right-4 top-20 mx-auto max-w-sm sm:absolute sm:left-auto sm:right-0 sm:top-full sm:mt-2.5 sm:w-96 sm:max-w-none bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 py-3 z-50 overflow-hidden">
                            <div class="px-4 pb-3 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bell text-blue-600 dark:text-amber-400 text-sm"></i>
                                    <h3 class="text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider">ការជូនដំណឹងរបស់អ្នក</h3>
                                </div>
                                <template x-if="unreadCount > 0">
                                    <button @click="markAllRead()" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline cursor-pointer">
                                        អានទាំងអស់
                                    </button>
                                </template>
                            </div>

                            <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800/50">
                                <template x-if="notifications.length === 0">
                                    <div class="py-8 text-center text-xs text-gray-400">
                                        <i class="far fa-bell-slash text-2xl block mb-2 opacity-50"></i>
                                        មិនទាន់មានការជូនដំណឹងនៅឡើយទេ
                                    </div>
                                </template>

                                <template x-for="item in notifications" :key="item.id">
                                    <a :href="item.url" @click="openNotif = false" class="flex items-start gap-3 p-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-colors relative" :class="item.is_unread ? 'bg-amber-50/40 dark:bg-amber-950/10' : ''">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 text-xs shadow-xs" :class="item.icon_bg">
                                            <i :class="item.icon"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1 mb-0.5 min-w-0">
                                                <h4 class="text-xs font-bold text-gray-900 dark:text-white truncate min-w-0" x-text="item.title"></h4>
                                                <template x-if="item.is_unread">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                                </template>
                                            </div>
                                            <p class="text-[11px] text-gray-600 dark:text-gray-400 leading-tight line-clamp-2" x-text="item.description"></p>
                                            <span class="text-[9px] text-gray-400 font-medium block mt-1" x-text="item.time"></span>
                                        </div>
                                    </a>
                                </template>
                            </div>

                            <div class="pt-2.5 px-4 border-t border-gray-100 dark:border-gray-800 text-center">
                                <a href="{{ route('notifications.index') }}" @click="openNotif = false" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1">
                                    <span>មើលការជូនដំណឹងទាំងអស់</span>
                                    <i class="fas fa-arrow-right text-[9px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endauth

                    {{-- 3. Cart / Booking List Icon Button --}}
                    @php $headerCartCount = count(session('cart', [])); @endphp
                    <div class="relative">
                        <a href="{{ route('cart.index') }}"
                            title="បញ្ជីកក់ / កន្ត្រក"
                            class="w-10 h-10 rounded-2xl bg-gray-100/80 dark:bg-gray-800/80 border border-gray-100 dark:border-gray-800/80 flex items-center justify-center transition-all duration-300 text-blue-600 dark:text-amber-400 hover:bg-blue-50/70 dark:hover:bg-gray-700/80 hover:border-blue-200 dark:hover:border-gray-700 hover:scale-105 shadow-xs relative group">
                            <i class="fa-solid fa-clipboard-list text-base group-hover:scale-110 transition-transform"></i>
                            @if($headerCartCount > 0)
                            <span class="absolute -top-1 -right-1 min-w-[20px] h-[20px] px-1 bg-red-500 text-white text-[10px] font-extrabold rounded-full flex items-center justify-center shadow-md animate-pulse">
                                {{ $headerCartCount }}
                            </span>
                            @endif
                        </a>
                    </div>

                    <!-- ៤. ប៊ូតុង Mobile Menu -->
                    <div class="inline-block lg:hidden">
                        <button @click="mobileMenuOpen = true"
                            class="p-2 text-2xl text-gray-700 dark:text-white rounded-xl bg-gray-100 dark:bg-gray-800 w-10 h-10 flex items-center justify-center hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        {{-- navigation bar for desktop mobile app --}}
        <div x-show="mobileMenuOpen" class="fixed inset-0 z-100 overflow-hidden" x-cloak>
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" x-show="mobileMenuOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"></div>
            <div class="absolute inset-y-0 right-0 max-w-full flex pl-10">
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
                                    <i class="fa-solid fa-house text-lg {{ Route::is('home') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>ទំព័រដើម</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.rooms') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.rooms') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fa-solid fa-bed text-lg {{ Route::is('frontend.rooms') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>បន្ទប់ស្នាក់នៅ</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.meeting') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.meeting') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fa-solid fa-users text-lg {{ Route::is('frontend.meeting') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>សាលប្រជុំ</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.posts') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.posts') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fa-solid fa-newspaper text-lg {{ Route::is('frontend.posts') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>ព័ត៌មាន</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.facilities') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.facilities') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fa-solid fa-bell-concierge text-lg {{ Route::is('frontend.facilities') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>សេវាកម្ម</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.about') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.about') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fa-solid fa-circle-info text-lg {{ Route::is('frontend.about') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                                    <span>អំពីយើង</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('frontend.contact') }}" @click="mobileMenuOpen = false"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition group {{ Route::is('frontend.contact') ? 'bg-blue-50 text-blue-600 font-bold dark:bg-blue-950/40 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900/50' }}">
                                    <i class="fa-solid fa-address-book text-lg {{ Route::is('frontend.contact') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
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
                                    <span class="flex items-center justify-center w-full h-full bg-blue-500 text-white font-bold text-sm">
                                        {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1, 'UTF-8'), 'UTF-8') }}
                                    </span>
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
                                <a href="{{ route('mybookings') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl transition">
                                    <i class="fas fa-calendar-check text-gray-400 w-5"></i><span>ការកក់របស់ខ្ញុំ</span>
                                </a>
                                <a href="{{ route('notifications.index') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl transition">
                                    <i class="fas fa-bell text-gray-400 w-5"></i><span>ការជូនដំណឹង</span>
                                </a>
                                <a href="{{ route('setting.edit') }}" @click="mobileMenuOpen = false" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 rounded-xl transition">
                                    <i class="fas fa-user-cog text-gray-400 w-5"></i><span>ការកំណត់</span>
                                </a>
                                <button type="button" @click="mobileMenuOpen = false; confirmLogout(event)" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl transition mt-1 cursor-pointer">
                                    <i class="fas fa-sign-out-alt text-red-500 w-5"></i><span>ចាកចេញ</span>
                                </button>
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
            <div class="container mx-auto px-4 sm:px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                    <h3 class="text-2xl font-extrabold text-blue-500 mb-4 italic tracking-wide">{{ $dynSiteName }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 leading-relaxed text-sm">
                        {{ $contactSettings['description'] ?? $contactSettings['footer_desc'] ?? 'ផ្តល់ជូននូវបទពិសោធន៍ស្នាក់នៅដ៏ល្អឥតខ្ចោះ ប្រកបដោយផាសុកភាព និងភាពកក់ក្តៅបំផុតសម្រាប់រាល់ដំណើរកម្សាន្ត និងបេសកកម្មរបស់លោកអ្នក។' }}
                    </p>
                </div>

                <div>
                    <h4 class="text-md font-bold mb-4 text-gray-800 dark:text-white uppercase tracking-wider">ទំនាក់ទំនង</h4>
                    <ul class="space-y-3 text-gray-500 dark:text-gray-400 text-sm">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-map-marker-alt text-blue-500 mt-1 shrink-0"></i>
                            <span>{{ $dynAddress }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-blue-500 shrink-0"></i>
                            <a href="tel:{{ preg_replace('/[^\d+]/', '', $dynPhone) }}" class="hover:text-blue-500 transition-colors">{{ $dynPhone }}</a>
                        </li>
                        @if(!empty($dynEmail))
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-blue-500 shrink-0"></i>
                            <a href="mailto:{{ $dynEmail }}" class="hover:text-blue-500 transition-colors">{{ $dynEmail }}</a>
                        </li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h4 class="text-md font-bold mb-4 text-gray-800 dark:text-white uppercase tracking-wider">ព័ត៌មានបន្ថែម</h4>
                    <ul class="space-y-3 text-gray-500 dark:text-gray-400 text-sm">
                        <li>
                            <a href="{{ route('policies.terms') }}" class="hover:text-blue-500 transition-colors flex items-center gap-2">
                                <i class="fas fa-file-alt text-blue-500 w-4 text-center"></i> លក្ខខណ្ឌនៃការប្រើប្រាស់
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('policies.privacy') }}" class="hover:text-blue-500 transition-colors flex items-center gap-2">
                                <i class="fas fa-shield-alt text-blue-500 w-4 text-center"></i> គោលការណ៍ឯកជនភាព
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('policies.reviews') }}" class="hover:text-blue-500 transition-colors flex items-center gap-2">
                                <i class="fas fa-star text-blue-500 w-4 text-center"></i> មតិកែលម្អពីអតិថិជន
                            </a>
                        </li>
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
                &copy; {{ date('Y') }} {{ $dynSiteName }}។ រក្សាសិទ្ធិគ្រប់យ៉ាង។
            </div>
        </footer>

        {{-- FLOATING SYSTEM WIDGETS (Livechat & Scroll up) --}}
        <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">


            <div class="flex items-center gap-3">
                <button id="scrollTopBtn"
                    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="fixed bottom-6 right-20 z-50 flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-full shadow-[0_10px_25px_-5px_rgba(37,99,235,0.4)] transition-all duration-500 translate-y-20 opacity-0 group hover:shadow-[0_20px_30px_-10px_rgba(37,99,235,0.6)] hover:-translate-y-1 active:scale-90 pointer-events-none cursor-pointer">
                    <i class="fas fa-arrow-up text-md"></i>
                </button>

                <a href="{{ route('chat.index') }}"
                    class="w-12 h-12 bg-[#0084FF] text-white rounded-full shadow-xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all relative group cursor-pointer">
                    <span class="absolute inset-0 rounded-full bg-blue-400 animate-ping opacity-30"></span>
                    <i class="fab fa-facebook-messenger text-xl"></i>
                    <span class="absolute right-full mr-3 px-2 py-1 bg-gray-950 text-white text-[11px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap shadow-md pointer-events-none">
                        ឆាតមកយើងខ្ញុំ
                    </span>
                </a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        if (typeof tailwind !== 'undefined') {
            tailwind.config = {
                darkMode: 'class',
            };
        }

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        if (window.axios) {
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            }
        } else if (typeof axios !== 'undefined') {
            window.axios = axios;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
            }
        }

        window.confirmLogout = function(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            function submitLogout() {
                let form = document.getElementById('global-logout-form') || document.getElementById('logout-form') || document.querySelector('form[action="{{ route("logout") }}"]');
                if (!form) {
                    form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("logout") }}';
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }
                    document.body.appendChild(form);
                }
                form.submit();
            }

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'តើអ្នកពិតជាចង់ចាកចេញមែនទេ?',
                    text: 'លោកអ្នកនឹងត្រូវចាកចេញពីប្រព័ន្ធ!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'ចាកចេញ',
                    cancelButtonText: 'បោះបង់',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-2xl dark:bg-gray-900 dark:text-white dark:border dark:border-gray-800',
                        title: 'font-bold text-gray-800 dark:text-white',
                        confirmButton: 'rounded-xl px-4 py-2 text-sm font-semibold',
                        cancelButton: 'rounded-xl px-4 py-2 text-sm font-semibold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitLogout();
                    }
                });
            } else {
                if (confirm('តើអ្នកពិតជាចង់ចាកចេញមែនទេ?')) {
                    submitLogout();
                }
            }
        };
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatSystem', (config) => ({
                conversationId: config.conversationId,
                currentUserId: config.currentUserId,
                openChat: false,
                newMessage: '',
                previews: [],
                selectedFiles: [],

                init() {
                    // បើកដំណើរការស្តាប់ការឆ្លើយតប Real-time ពី Admin តាមរយៈ WebSockets (Laravel Echo)
                    if (this.conversationId && typeof Echo !== 'undefined') {
                        Echo.private(`chat.${this.conversationId}`)
                            .listen('MessageSent', (e) => {
                                // ប្រសិនបើសារនោះមិនមែនជារបស់ខ្លួនឯងផ្ញើទេ (គឺ Admin ជាអ្នកឆ្លើយមក)
                                if (e.message.user_id !== this.currentUserId) {
                                    this.renderIncomingMessage(e.message);
                                }
                            });
                    }
                    this.scrollToBottom();
                },

                // ១. មុខងារ Preview រូបភាពពេល User ជ្រើសរើស File
                previewFiles(event) {
                    const files = Array.from(event.target.files);

                    files.forEach(file => {
                        this.selectedFiles.push(file);

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.previews.push(e.target.result);
                        };
                        reader.readAsDataURL(file);
                    });

                    this.scrollToBottom();
                },

                // ២. មុខងារលុបរូបភាពចេញវិញពីប្រអប់ផ្ញើ
                removeImage(index) {
                    this.previews.splice(index, 1);
                    this.selectedFiles.splice(index, 1);
                },

                // ៣. មុខងារបញ្ជូនសារ (Send Chat) ទៅកាន់ Backend
                sendChat() {
                    if (this.newMessage.trim() === '' && this.selectedFiles.length === 0) return;

                    const chatBox = document.getElementById('chat-box');
                    const currentTime = new Date().toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    let formData = new FormData();
                    formData.append('message', this.newMessage);

                    this.selectedFiles.forEach((file) => {
                        formData.append('images[]', file);
                    });

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                    // បង្ហាញសារនៅលើ UI ភ្លាមៗ (Optimistic UI សម្រាប់ Client ខ្លួនឯង)
                    let messageContent = '';
                    if (this.previews.length > 0) {
                        messageContent += `<div class="grid grid-cols-2 gap-1 mb-2">`;
                        this.previews.forEach(src => {
                            messageContent += `<img src="${src}" class="w-full h-24 object-cover rounded-lg">`;
                        });
                        messageContent += `</div>`;
                    }
                    if (this.newMessage.trim() !== '') {
                        messageContent += `<p class="text-sm">${this.escapeHtml(this.newMessage)}</p>`;
                    }

                    const userMsgHtml = `
                        <div class="flex items-start gap-2 justify-end">
                            <div class="bg-blue-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm max-w-[80%]">
                                ${messageContent}
                                <span class="text-[9px] text-blue-200 mt-1 block text-right">${currentTime}</span>
                            </div>
                        </div>
                    `;
                    chatBox.insertAdjacentHTML('beforeend', userMsgHtml);
                    this.scrollToBottom();

                    // សំអាតតម្លៃលើ Input ភ្លាមៗ
                    this.newMessage = '';
                    this.previews = [];
                    this.selectedFiles = [];
                    document.getElementById('client-file-input').value = '';

                    // បាញ់ទិន្នន័យទៅកាន់ Laravel Backend តាមរយៈ Fetch
                    fetch('/chat/send', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('សារផ្ញើចេញជោគជ័យ!');
                        })
                        .catch(error => {
                            console.error('មានបញ្ហាក្នុងការផ្ញើសារ:', error);
                        });
                },

                // ៤. មុខងារចាប់បង្កើត HTML ពេលមានសារលោតមកពីខាង Admin (Real-time Receiver)
                renderIncomingMessage(msg) {
                    const chatBox = document.getElementById('chat-box');
                    const time = msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    }) : new Date().toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    let mediaHtml = '';
                    if (msg.images) {
                        mediaHtml = `
                            <div class="grid grid-cols-2 gap-1 mb-2">
                                <img src="/storage/${msg.images}" class="w-full h-24 object-cover rounded-lg">
                            </div>
                        `;
                    }

                    const adminMsgHtml = `
                        <div class="flex items-start gap-2">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex-shrink-0 flex items-center justify-center">
                                <i class="fab fa-facebook-messenger text-blue-600 dark:text-blue-400 text-xs"></i>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%] border border-gray-100 dark:border-gray-700">
                                ${mediaHtml}
                                ${msg.message ? `<p class="text-sm text-gray-700 dark:text-gray-300">${this.escapeHtml(msg.message)}</p>` : ''}
                                <span class="text-[9px] text-gray-400 mt-1 block">${time}</span>
                            </div>
                        </div>
                    `;
                    chatBox.insertAdjacentHTML('beforeend', adminMsgHtml);
                    this.scrollToBottom();
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const chatBox = document.getElementById('chat-box');
                        if (chatBox) {
                            chatBox.scrollTop = chatBox.scrollHeight;
                        }
                    });
                },

                escapeHtml(text) {
                    return text
                        .replace(/&/g, "&amp;")
                        .replace(/</g, "&lt;")
                        .replace(/>/g, "&gt;")
                        .replace(/"/g, "&quot;")
                        .replace(/'/g, "&#039;");
                }
            }));
        });
    </script>

</body>

</html>