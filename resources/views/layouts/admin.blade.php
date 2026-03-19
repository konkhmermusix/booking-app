<!DOCTYPE html>
<html lang="km"
    x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarStatus') !== 'false', 
        mobileOpen: false,
        isDark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    }"
    :class="{ 'dark': isDark }"
    class="transition-colors duration-300">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNT Hotel - @yield('title', 'Admin')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('css/tailwind.css') }}"></script> -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            darkMode: 'class'
        }
        // ការពារការ Flicker ពណ៌សពេល Refresh Page
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        aside {
            z-index: 9999 !important;
        }

        .swal2-container {
            z-index: 9000 !important;
        }

        body.swal2-shown {
            padding-right: 0 !important;
        }

        body {
            font-family: 'Kantumruy Pro', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-950 text-gray-800 dark:text-gray-200 h-screen overflow-hidden flex" x-cloak>

    <div x-show="mobileOpen" @click="mobileOpen = false"
        class="fixed inset-0 bg-black/50 z-40 md:hidden backdrop-blur-sm transition-opacity"
        x-transition:enter="duration-300" x-transition:leave="duration-200"></div>

    <aside
        class="bg-[#002B5B] dark:bg-gray-900 text-white transition-all duration-300 ease-in-out flex flex-col z-[9999] fixed inset-y-0 left-0 md:relative shadow-2xl"
        :class="{ 'w-64': sidebarOpen, 'w-20': !sidebarOpen, 'translate-x-0': mobileOpen, '-translate-x-full': !mobileOpen && window.innerWidth < 768, 'md:translate-x-0': true }">

        <div class="h-16 flex items-center px-6 border-b border-white/10 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-yellow-400 rounded-lg flex-center shadow-lg text-blue-900 font-bold flex items-center justify-center">P</div>
                <span class="text-xl font-bold tracking-wider" x-show="sidebarOpen" x-transition>PNT <span class="text-yellow-400">ADMIN</span></span>
            </div>
        </div>

        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto no-scrollbar">
            @php
            $navItems = [
            ['route' => 'dashboard', 'icon' => 'fa-chart-line', 'label' => 'ផ្ទាំងគ្រប់គ្រង'],
            ['route' => 'bookings.index', 'icon' => 'fa-calendar-check', 'label' => 'បញ្ជីកក់បន្ទប់'],
            ['route' => 'hotels.index', 'icon' => 'fa-hotel', 'label' => 'បញ្ជីសណ្ឋាគារ'],
            ];
            @endphp

            @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}"
                class="flex items-center gap-4 p-2 rounded-xl transition-all {{ request()->routeIs($item['route']) ? 'bg-blue-600 shadow-lg text-white' : 'hover:bg-white/10 text-gray-400 hover:text-white' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                <i class="fas {{ $item['icon'] }} w-6 text-center"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>{{ $item['label'] }}</span>
            </a>
            @endforeach

            <div x-data="{ open: {{ request()->routeIs('rooms.*', 'room_types.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-4 p-2 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-bed w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium" x-transition>គ្រប់គ្រងបន្ទប់</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition>
                    <a href="{{ route('rooms.index') }}" class="block p-2 text-sm {{ request()->routeIs('rooms.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white">បញ្ជីបន្ទប់</a>
                    <a href="{{ route('room_types.index') }}" class="block p-2 text-sm {{ request()->routeIs('room_types.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white">បញ្ជីប្រភេទបន្ទប់</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('facilities.*', 'slideshows.*', 'tours.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-4 p-2 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-globe w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium" x-transition>គ្រប់គ្រងគេហទំព័រ</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition>
                    <a href="{{ route('slideshows.index') }}" class="block p-2 text-sm {{ request()->routeIs('slideshows.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white">បញ្ជីបដារ</a>
                    <a href="{{ route('facilities.index') }}" class="block p-2 text-sm {{ request()->routeIs('facilities.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white">បញ្ជីគ្រឿងបរិក្ខារ</a>
                    <a href="{{ route('tours.index') }}" class="block p-2 text-sm {{ request()->routeIs('tours.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white">បញ្ជីកន្លែងទេសចរណ៍</a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" class="w-full flex items-center gap-4 p-2 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-user w-6 text-center"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium" x-transition>កំណត់សិទ្ធប្រើប្រាស់</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition>
                    <a href="{{ route('users.index') }}" class="block p-2 text-sm {{ request()->routeIs('users.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white">បញ្ជីអ្នកប្រើប្រាស់</a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-4 p-2 rounded-xl text-red-400 hover:bg-red-500/10 transition-all group" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform"></i>
                    <span x-show="sidebarOpen" class="font-medium" x-transition>ចាកចេញ</span>
                </button>
            </form>

            <div x-show="sidebarOpen" x-transition class="mt-2 text-[10px] text-gray-500 text-center uppercase tracking-widest">
                pnt palace hotel
            </div>
        </div>
    </aside>

    <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden" x-transition x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="h-16 bg-white dark:bg-gray-900 border-b dark:border-gray-800 flex items-center justify-between px-4 md:px-6 shrink-0 shadow-sm z-30 transition-colors duration-300">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarStatus', sidebarOpen)"
                    class="hidden md:flex p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 transition-colors">
                    <i class="fas" :class="sidebarOpen ? 'fa-indent' : 'fa-outdent'"></i>
                </button>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="hidden lg:flex items-center bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-xl border border-transparent focus-within:border-blue-500 transition-all w-64 shadow-sm">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                    <input type="text" placeholder="ស្វែងរក..." class="bg-transparent border-none outline-none text-sm ml-2 w-full dark:text-white">
                </div>
            </div>

            <div class="flex items-center gap-2 md:gap-4">

                <button @click="isDark = !isDark; localStorage.theme = isDark ? 'dark' : 'light'; document.documentElement.classList.toggle('dark', isDark)"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-800 text-gray-500 hover:ring-2 ring-blue-500/20 transition-all shrink-0 shadow-sm">

                    <i x-show="isDark" class="fas fa-sun text-yellow-400 text-lg"></i>

                    <i x-show="!isDark" class="fas fa-moon text-blue-600 text-lg"></i>
                </button>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-3 p-1 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                        <div class="hidden md:block text-right">
                            <p class="text-sm font-bold leading-none dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 mt-1 uppercase tracking-tight">{{ Auth::user()->role }}</p>
                        </div>
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=002B5B&color=fff' }}"
                            class="w-9 h-9 rounded-lg border-2 border-blue-500/20 object-cover shadow-sm">
                    </button>

                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:leave="transition ease-in duration-75"
                        class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border dark:border-gray-700 py-2 z-50">

                        <div class="px-4 py-2 border-b dark:border-gray-700 mb-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">គណនីគ្រប់គ្រង</p>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                            <i class="fas fa-user-circle text-blue-500 "></i> កែប្រែព័ត៌មាន
                        </a>

                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                            <i class="fas fa-globe text-blue-500 "></i> ទៅគេហទំព័រ
                        </a>

                        <hr class="my-1 border-gray-100 dark:border-gray-700">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                <i class="fas fa-sign-out-alt"></i> ចាកចេញពីគណនី
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-950/50 transition-colors duration-300">
            <x-toast />
            <x-confirm-delete />
            <div x-data="{ init: false }"
                x-init="setTimeout(() => init = true, 50)"
                x-show="init"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0">

                @yield('content')

            </div>
        </main>
    </div>

</body>

</html>