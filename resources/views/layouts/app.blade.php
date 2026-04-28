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

    <div class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-800 hidden md:block">
        <div class="container mx-auto px-6 py-2 flex justify-between items-center text-[11px] font-medium text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-6">
                <span class="flex items-center gap-2"><i class="fas fa-phone text-blue-500"></i> +855 964 301 974</span>
                <span class="flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i> info@pntpalace.com</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-blue-500"></i> {{ __('auth.footer-address') }}</span>
            </div>
        </div>
    </div>

    <div class="fixed top-0 left-0 w-full h-full pointer-events-none z-[-1] overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-200/20 dark:bg-blue-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-blue-300/20 dark:bg-blue-800/10 rounded-full blur-[120px] animate-pulse"></div>
    </div>

    <nav class="bg-white/80 dark:bg-gray-950/80 backdrop-blur-md sticky top-0 z-[100] border-b border-gray-100 dark:border-gray-800">
        <div class="container mx-auto px-4 md:px-6 py-3 flex justify-between items-center">

            <a href="/" class="text-2xl font-bold text-blue-900 dark:text-blue-400 flex items-center gap-2 group">
                <div class="w-12 h-12 bg-blue-900 rounded-lg flex items-center justify-center text-white font-bold group-hover:bg-blue-700 transition">
                    <img src="{{ asset('images/logo/P&t Palace Hotel.png') }}" alt="" srcset="">
                </div>
                <span class="hidden sm:inline">ភីអេនធី</span>
            </a>

            <ul class="hidden lg:flex space-x-7 font-medium">
                {{-- Home --}}
                <li>
                    <a href="{{ route('home') }}"
                        class="transition {{ Route::is('home') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">
                        {{ __('auth.nav-home') }}
                    </a>
                </li>

                {{-- Rooms --}}
                <li>
                    <a href="{{ route('frontend.rooms') }}"
                        class="transition {{ Route::is('frontend.rooms') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">
                        {{ __('auth.nav-rooms') }}
                    </a>
                </li>

                {{-- Meeting --}}
                <li>
                    <a href="{{ route('frontend.meeting') }}"
                        class="transition {{ Route::is('frontend.meeting') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">
                        {{ __('auth.nav-meetings') }}
                    </a>
                </li>

                {{-- Facilities --}}
                <li>
                    <a href="{{ route('frontend.facilities') }}"
                        class="transition {{ Route::is('frontend.facilities') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">
                        {{ __('auth.nav-facilities') }}
                    </a>
                </li>

                {{-- About --}}
                <li>
                    <a href="{{ route('frontend.about') }}"
                        class="transition {{ Route::is('frontend.about') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">
                        {{ __('auth.nav-about') }}
                    </a>
                </li>

                {{-- Contact --}}
                <li>
                    <a href="{{ route('frontend.contact') }}"
                        class="transition {{ Route::is('frontend.contact') ? 'text-blue-600 font-bold border-b-2 border-blue-600' : 'hover:text-blue-600 dark:hover:text-blue-400' }}">
                        {{ __('auth.nav-contact') }}
                    </a>
                </li>
            </ul>

            <div class="flex items-center space-x-3 md:space-x-4">
                <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-full p-1 shadow-inner">
                    <button @click="switchLang('kh')"
                        class="px-2 py-1 text-[10px] font-black rounded transition"
                        :class=" '{{ app()->getLocale() }}' == 'kh' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-400' ">
                        KH
                    </button>
                    <button @click="switchLang('en')"
                        class="px-2 py-1 text-[10px] font-black rounded transition"
                        :class=" '{{ app()->getLocale() }}' == 'en' ? 'bg-white dark:bg-gray-700 text-blue-600 shadow-sm' : 'text-gray-400' ">
                        EN
                    </button>
                </div>

                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                    class="p-2 rounded-full bg-gray-100 dark:bg-gray-800 w-10 h-10 flex items-center justify-center transition hover:ring-2 ring-blue-400/50 text-blue-600 dark:text-yellow-400">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                <div class="hidden xl:flex items-center gap-4 border-l pl-4 dark:border-gray-700">
                    @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 focus:outline-none group">
                            <div class="text-right hidden sm:block">
                                <p class="text-[10px] text-gray-500 dark:text-gray-400 uppercase">{{ __('auth.account') }}</p>
                                <p class="text-sm font-bold dark:text-white group-hover:text-blue-600 transition">{{ Auth::user()->name }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white shadow-lg overflow-hidden border-2 border-white dark:border-gray-800">
                                @if(Auth::user()->avatar)

                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                    alt="{{ Auth::user()->name }}"
                                    class="w-full h-full object-cover">
                                @else

                                <div class="flex items-center justify-center w-full h-full bg-blue-500 text-white font-bold text-sm">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                @endif
                            </div>
                        </button>

                        <div x-show="open" x-cloak x-transition class="absolute right-0 mt-3 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 py-2 z-50">

                            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'staff')
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-chart-line text-blue-500"></i>{{ __('auth.dashboard') }}</a>
                            <a href="/my-bookings" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-calendar-check text-blue-500"></i>{{ __('auth.my_booking') }}</a>
                            @else
                            <a href="/my-bookings" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700"><i class="fas fa-calendar-check text-blue-500"></i>{{ __('auth.my_booking') }}</a>
                            @endif
                            <a href="/profile" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 border-b dark:border-gray-700"><i class="fas fa-user-cog text-blue-500"></i>{{ __('auth.profile') }}</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition mt-1">
                                    <i class="fas fa-sign-out-alt"></i>{{ __('auth.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:text-blue-600 dark:text-gray-300">{{ __('auth.login') }}</a>
                    <a href="{{ route('register') }}" class="text-sm font-bold bg-blue-600 text-white hover:bg-blue-700 px-5 py-2 rounded-xl transition">{{ __('auth.register') }}</a>
                    @endauth
                </div>

                <a href="/booking" class="hidden sm:flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-full text-xs font-bold hover:bg-blue-700 transition shadow-lg">
                    <i class="fas fa-calendar-check animate-bounce"></i> {{ __('auth.nav-book') }}
                </a>

                <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-2xl p-2 focus:outline-none">
                    <i class="fas" :class="mobileMenuOpen ? 'fa-times' : 'fa-bars'"></i>
                </button>
            </div>

            <div x-show="mobileMenuOpen" x-cloak x-transition
                class="lg:hidden bg-white dark:bg-gray-950 border-b dark:border-gray-800 absolute w-full top-full left-0 shadow-2xl z-[90]">
                <ul class="flex flex-col p-5 space-y-2 font-medium">
                    <li><a href="/" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl">{{ __('auth.nav-home') }}</a></li>
                    <li><a href="/rooms" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl">{{ __('auth.nav-rooms') }}</a></li>
                    <li><a href="/meeting" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl">{{ __('auth.nav-meetings') }}</a></li>
                    <li><a href="/about" class="block px-4 py-3 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-xl">{{ __('auth.nav-about') }}</a></li>

                    @auth
                    <li class="pt-4 border-t dark:border-gray-800">
                        <span class="px-4 text-xs font-bold text-gray-400 uppercase">គណនីរបស់អ្នក</span>
                        <a href="/profile" class="block px-4 py-3">Profile Setting</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-red-500 font-bold">ចាកចេញ</button>
                        </form>
                    </li>
                    @else
                    <li class="grid grid-cols-2 gap-3 pt-5 border-t dark:border-gray-800">
                        <a href="{{ route('login') }}" class="text-center py-3 rounded-xl border dark:border-gray-700 text-sm">ចូលប្រើ</a>
                        <a href="{{ route('register') }}" class="text-center py-3 rounded-xl bg-blue-600 text-white text-sm">ចុះឈ្មោះ</a>
                    </li>
                    @endauth
                </ul>
                <div class="flex items-center space-x-3 md:space-x-4">
                    <a href="/booking" class="flex items-center gap-2 bg-blue-600 text-white px-20 py-2.5 rounded-full text-xs font-bold hover:bg-blue-700 transition shadow-lg">
                        <i class="fas fa-calendar-check animate-bounce"></i> {{ __('auth.nav-book') }}
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main>
        <x-alert /> @yield('content')
        <!-- </main> class="min-h-screen flex items-center justify-center py-3"> -->
        <!-- <div class="container mx-auto px-4"> -->
        <!-- </div> -->
    </main>

    <footer class="py-16 bg-white dark:bg-gray-950 border-t border-gray-200 dark:border-gray-800">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <h3 class="text-3xl font-extrabold text-blue-500 mb-4 italic">PNT HOTEL</h3>
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ __('auth.footer-desc') }}</p>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-6">{{ __('auth.nav-contact') }}</h4>
                <ul class="space-y-4 text-gray-500 dark:text-gray-400">
                    <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-blue-500"></i> {{ __('auth.footer-address') }}</li>
                    <li class="flex items-center gap-3"><i class="fas fa-phone text-blue-500"></i> +855 964 301 974</li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-bold mb-6">{{ __('auth.footer-follow') }}</h4>
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
            &copy; 2026 PNT Hotel. {{ __('auth.footer-copy') }}.
        </div>
    </footer>

    <div x-data="{ openChat: false }" class="fixed bottom-8 right-1 z-[100] flex flex-col items-end gap-4">

        <div x-show="openChat"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-10 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-10 scale-95"
            class="w-[330px] md:w-[370px] bg-white dark:bg-gray-900 rounded-[1.5rem] shadow-2xl border border-gray-100 dark:border-gray-800 overflow-hidden mb-2"
            x-cloak>

            <div class="bg-blue-600 p-3 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center border border-white/30">
                        <i class="fab fa-facebook-messenger text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">P&T Palace Support</h4>
                        <p class="text-[10px] opacity-80 uppercase tracking-wider">Online (Reply in minutes)</p>
                    </div>
                </div>
                <button @click="openChat = false" class="hover:bg-black/10 w-8 h-8 rounded-full transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>


            <div id="chat-messages" class="h-80 p-6 overflow-y-auto bg-gray-50 dark:bg-slate-950/50 flex flex-col gap-3">
                <div class="bg-blue-100 dark:bg-blue-900/30 p-4 rounded-2xl rounded-tl-none text-sm text-blue-900 dark:text-blue-100 max-w-[85%]">
                    សួស្តី! អ្នកអាចផ្ញើសារ ឬរូបភាពមកយើងបាន។
                </div>

                <div id="image-preview-area" class="hidden flex flex-wrap gap-2 p-2 bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-blue-200">
                </div>
            </div>

            <div class="p-4 bg-white dark:bg-gray-900 border-t dark:border-gray-800">
                <div class="flex items-center gap-2 bg-gray-100 dark:bg-gray-800 p-2 rounded-2xl">
                    <button class="w-10 h-10 flex items-center justify-center text-gray-400 hover:text-blue-600 transition-colors">
                        <label class="cursor-pointer">
                            <input type="file" id="chat-file-input" class="hidden" accept="image/*" onchange="previewImages(this)">
                            <i class="fas fa-image text-lg"></i>
                        </label>
                    </button>

                    <input type="text" id="chat-text-input" placeholder="សរសេរសារ..."
                        class="flex-1 bg-transparent border-none focus:ring-0 text-sm dark:text-white outline-none">

                    <button onclick="sendMessage()" class="w-10 h-10 bg-blue-600 text-white rounded-xl shadow-lg flex items-center justify-center hover:bg-blue-700 transition-all">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </button>
                </div>
            </div>

        </div>

        <button @click="openChat = !openChat"
            class="w-12 h-12 bg-[#0084FF] text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 transition-all relative group">

            <span x-show="!openChat" class="absolute inset-0 rounded-full bg-blue-400 animate-ping opacity-20"></span>

            <i x-show="!openChat" class="fab fa-facebook-messenger text-2xl"></i>
            <i x-show="openChat" class="fas fa-chevron-down text-xl" x-cloak></i>

            <span x-show="!openChat" class="absolute right-full mr-1 px-2 py-1 bg-gray-800 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                ឆាតមកយើងខ្ញុំ
            </span>
        </button>


        <button id="scrollTopBtn"
            onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-full shadow-[0_10px_25px_-5px_rgba(37,99,235,0.4)] transition-all duration-500 translate-y-20 opacity-0 group hover:shadow-[0_20px_30px_-10px_rgba(37,99,235,0.6)] hover:-translate-y-1 active:scale-90 pointer-events-none">
            <span class="absolute inset-0 rounded-full bg-blue-500 animate-ping opacity-20 group-hover:opacity-40"></span>
            <i class="fas fa-arrow-up text-lg relative z-10 transition-transform duration-300 group-hover:-translate-y-1"></i>
        </button>

    </div>

    <!-- scroll up btn -->
    <!-- <button id="scrollTopBtn"
        class="fixed bottom-8 right-8 z-50 flex items-center justify-center w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-400 text-white rounded-full shadow-[0_10px_25px_-5px_rgba(37,99,235,0.4)] transition-all duration-500 translate-y-20 opacity-0 group hover:shadow-[0_20px_30px_-10px_rgba(37,99,235,0.6)] hover:-translate-y-1 active:scale-90">
        <span class="absolute inset-0 rounded-full bg-blue-500 animate-ping opacity-20 group-hover:opacity-40"></span>
        <i
            class="fas fa-arrow-up text-lg relative z-10 transition-transform duration-300 group-hover:-translate-y-1"></i>
    </button> -->



    <!-- <script src="//unpkg.com/alpinejs" defer></script> -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('fontawesome-icon/js/all.min.js') }}"></script>
    <script src="{{ asset('js/scroll.js') }}"></script>
    <script src="{{ asset('js/slide.js') }}"></script>
    <script src="{{ asset('js/date_for_select_search.js') }}"></script>

    <script>
        // Axios Configuration for Laravel CSRF
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        }

        function switchLang(lang) {
            axios.post('/change-language', {
                    locale: lang
                })
                .then(response => {
                    if (response.data.success) {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Language Switch Error:', error));
        }
    </script>

    <script>
        const scrollTopBtn = document.getElementById('scrollTopBtn');

        window.onscroll = function() {
            if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                // បង្ហាញប៊ូតុង Scroll Top
                scrollTopBtn.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
                scrollTopBtn.classList.add('translate-y-0', 'opacity-100', 'pointer-events-auto');
            } else {
                // លាក់ប៊ូតុង Scroll Top
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
                previewArea.classList.remove('hidden'); // បង្ហាញ Area

                for (let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // បង្កើត HTML សម្រាប់រូបភាពនីមួយៗ
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

        // មុខងារពិនិត្យមើល បើអស់រូបភាព ត្រូវលាក់ Area វិញ
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
                // បន្ថែម Logic ផ្ញើសារទៅកាន់ Server នៅទីនេះ (Pusher/Reverb)
                alert("សាររបស់អ្នក: " + msg);
                textInput.value = ""; // Clear text
                document.getElementById('image-preview-area').innerHTML = ""; // Clear images
                document.getElementById('image-preview-area').classList.add('hidden');
            }
        }
    </script>

</body>

</html>