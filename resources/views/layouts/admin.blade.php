<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ 
        sidebarOpen: localStorage.getItem('sidebarStatus') !== 'false', 
        mobileOpen: false,
        isDark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
    }"
    x-init="$watch('isDark', val => {
        if (val) {
            localStorage.theme = 'dark';
        } else {
            localStorage.theme = 'light';
        }
    })"
    :class="{ 'dark': isDark }"
    class="transition-colors duration-300">


@php
$dynLogoUrl = $contactSettings['logo_url'];
$dynSiteName = $contactSettings['site_name'];
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ $dynLogoUrl }}">

    <title>@yield('title') | {{ $dynSiteName }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/spotlight.js@0.7.8/dist/spotlight.bundle.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        input:focus,
        select:focus,
        textarea:focus,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: none !important;
            border-color: transparent !important;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.4) !important;
        }

        aside {
            z-index: 9999 !important;
        }

        .swal2-container {
            z-index: 9000 !important;
        }

        html.swal2-shown,
        body.swal2-shown {
            height: 100vh !important;
            overflow: hidden !important;
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

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.4);
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(107, 114, 128, 0.7);
        }

        .ck-editor__editable {
            min-height: 200px;
        }

        .ck-editor__editable_inline {
            min-height: 200px;
            border-radius: 0 0 15px 15px !important;
            padding: 0 20px !important;
        }

        .ck-toolbar {
            border-radius: 15px 15px 0 0 !important;
            background-color: #f9fafb !important;
            border: 2px solid #f9fafb !important;
        }

        .dark .ck-editor__editable {
            background-color: #1f2937 !important;
            color: white !important;
        }

        @media print {

            aside,
            header,
            button,
            .print-hide,
            nav,
            #scrollTopBtn,
            .pagination,
            .fa-trash,
            .fa-edit {
                display: none !important;
            }

            body,
            html {
                height: auto !important;
                overflow: visible !important;
                background-color: white !important;
                color: black !important;
            }

            main,
            .flex-1,
            .p-6,
            .space-y-6 {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                display: block !important;
                background-color: white !important;
            }

            .bg-white,
            .dark\:bg-gray-900,
            .bg-gray-50,
            .dark\:bg-slate-950 {
                background-color: white !important;
                color: black !important;
                border: none !important;
                box-shadow: none !important;
            }

            .shadow-sm,
            .shadow-md,
            .shadow-lg,
            .shadow-xl,
            .shadow-2xl {
                box-shadow: none !important;
            }

            canvas {
                max-width: 100% !important;
            }

            tr {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-950 text-gray-800 dark:text-gray-200 h-screen overflow-hidden flex">

    <div x-show="mobileOpen" @click="mobileOpen = false"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden backdrop-blur-sm transition-opacity"
        x-transition:enter="duration-300" x-transition:leave="duration-200"></div>

    <aside
        class="bg-[#002B5B] dark:bg-gray-900 text-white transition-all duration-300 ease-in-out flex flex-col h-screen shrink-0 z-50 fixed inset-y-0 left-0 md:relative md:z-30 shadow-2xl"
        :class="{ 
            'w-64': sidebarOpen, 
            'w-20': !sidebarOpen, 
            'translate-x-0': mobileOpen || window.innerWidth >= 768, 
            '-translate-x-full': !mobileOpen && window.innerWidth < 768
        }">

        {{-- Logo Section --}}
        <div class="h-16 flex items-center px-6 border-b border-white/10 shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-yellow-400 rounded-lg shadow-lg text-blue-900 font-bold flex items-center justify-center overflow-hidden p-0.5">
                    <img src="{{ $dynLogoUrl }}" alt="{{ $dynSiteName }}" class="w-full h-full object-contain">
                </div>
                <span class="text-xl font-bold tracking-wider" x-show="sidebarOpen" x-transition>{{ $dynSiteName }}</span>
            </div>
        </div>

        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto no-scrollbar">
            {{-- Admin links --}}
            @if(auth()->user()->role === 'admin')

            @php
            $navItems = [
            ['route' => 'dashboard', 'icon' => 'fa-solid fa-bars-progress', 'label' => 'ផ្ទាំងគ្រប់គ្រង'],
            ['route' => 'calendar.index', 'icon' => 'fa-solid fa-calendar', 'label' => 'គ្រប់គ្រងកាលវិភាគ'],
            ['route' => 'messages.index', 'icon' => 'fa-solid fa-message', 'label' => 'គ្រប់គ្រងសារ'],
            ];
            @endphp

            @foreach($navItems as $item)
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top + 5; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ hover: false, topPos: 0 }">
                <a href="{{ route($item['route']) }}"
                    @click="if(window.innerWidth < 1024) mobileOpen = false"
                    class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs($item['route']) ? 'bg-blue-600 shadow-lg text-white' : 'hover:bg-white/10 text-gray-400 hover:text-white' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="{{ $item['icon'] }} w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>{{ $item['label'] }}</span>
                </a>
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 92px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-1"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="fixed px-3 py-2 bg-gray-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap z-[99999] pointer-events-none border border-white/20">
                    {{ $item['label'] }}
                </div>
            </div>
            @endforeach

            {{-- 1. គ្រប់គ្រងការកក់ Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('room-bookings.*', 'meeting-bookings.*', 'bookings.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('room-bookings.*', 'meeting-bookings.*', 'bookings.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-calendar-check w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រងការកក់</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('room-bookings.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('room-bookings.*') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">
                        <i class="fas fa-bed mr-2 text-xs"></i> កក់បន្ទប់ស្នាក់នៅ
                    </a>
                    <a href="{{ route('meeting-bookings.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('meeting-bookings.*') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">
                        <i class="fas fa-users mr-2 text-xs"></i> កក់សាលប្រជុំ
                    </a>
                    <a href="{{ route('bookings.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('bookings.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">
                        <i class="fas fa-list-alt mr-2 text-xs"></i> បញ្ជីកក់បន្ទប់
                    </a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រងការកក់</span>
                            <i class="fas fa-calendar-check text-xs"></i>
                        </div>
                        <a href="{{ route('room-bookings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('room-bookings.*') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-bed text-xs w-4"></i> កក់បន្ទប់ស្នាក់នៅ
                        </a>
                        <a href="{{ route('meeting-bookings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('meeting-bookings.*') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-users text-xs w-4"></i> កក់សាលប្រជុំ
                        </a>
                        <a href="{{ route('bookings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('bookings.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-list-alt text-xs w-4"></i> បញ្ជីកក់បន្ទប់
                        </a>
                    </div>
                </div>
            </div>

            {{-- 2. គ្រប់គ្រងបន្ទប់ Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('hotels.*', 'room_types.*', 'rooms.*', 'facilities.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('hotels.*', 'room_types.*', 'rooms.*', 'facilities.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-bed w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រងបន្ទប់</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('hotels.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('hotels.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីសណ្ឋាគារ</a>
                    <a href="{{ route('room_types.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('room_types.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីប្រភេទបន្ទប់</a>
                    <a href="{{ route('rooms.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('rooms.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីបន្ទប់</a>
                    <a href="{{ route('facilities.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('facilities.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីគ្រឿងបរិក្ខារ</a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រងបន្ទប់</span>
                            <i class="fas fa-bed text-xs"></i>
                        </div>
                        <a href="{{ route('hotels.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('hotels.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-hotel text-xs w-4"></i> បញ្ជីសណ្ឋាគារ
                        </a>
                        <a href="{{ route('room_types.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('room_types.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-list text-xs w-4"></i> បញ្ជីប្រភេទបន្ទប់
                        </a>
                        <a href="{{ route('rooms.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('rooms.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-door-closed text-xs w-4"></i> បញ្ជីបន្ទប់
                        </a>
                        <a href="{{ route('facilities.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('facilities.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-concierge-bell text-xs w-4"></i> បញ្ជីគ្រឿងបរិក្ខារ
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. គ្រប់គ្រងគេហទំព័រ Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('slideshows.*', 'tours.*', 'abouts.*', 'contacts_sett.*', 'galleries.*', 'posts.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('slideshows.*', 'tours.*', 'abouts.*', 'contacts_sett.*', 'galleries.*', 'posts.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-globe w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រងគេហទំព័រ</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('slideshows.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('slideshows.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីបដារ</a>
                    <a href="{{ route('tours.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('tours.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីទេសចរណ៍</a>
                    <a href="{{ route('abouts.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('abouts.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីអំពីយើង</a>
                    <a href="{{ route('contacts_sett.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('contacts_sett.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីទំនាក់ទំនង</a>
                    <a href="{{ route('galleries.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('galleries.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីរូបភាព</a>
                    <a href="{{ route('posts.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('posts.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីព័ត៌មានថ្មីៗ</a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រងគេហទំព័រ</span>
                            <i class="fas fa-globe text-xs"></i>
                        </div>
                        <a href="{{ route('slideshows.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('slideshows.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-images text-xs w-4"></i> បញ្ជីបដារ
                        </a>
                        <a href="{{ route('tours.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('tours.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-route text-xs w-4"></i> បញ្ជីទេសចរណ៍
                        </a>
                        <a href="{{ route('abouts.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('abouts.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-info-circle text-xs w-4"></i> បញ្ជីអំពីយើង
                        </a>
                        <a href="{{ route('contacts_sett.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('contacts_sett.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-address-book text-xs w-4"></i> បញ្ជីទំនាក់ទំនង
                        </a>
                        <a href="{{ route('galleries.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('galleries.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-photo-video text-xs w-4"></i> បញ្ជីរូបភាព
                        </a>
                        <a href="{{ route('posts.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('posts.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-newspaper text-xs w-4"></i> បញ្ជីព័ត៌មានថ្មីៗ
                        </a>
                    </div>
                </div>
            </div>

            {{-- 4. គ្រប់គ្រងបញ្ចុះតម្លៃ Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('promotions.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('promotions.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-tag w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រងបញ្ចុះតម្លៃ</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('promotions.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('promotions.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">គ្រប់គ្រងបញ្ចុះតម្លៃ</a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រងបញ្ចុះតម្លៃ</span>
                            <i class="fas fa-tag text-xs"></i>
                        </div>
                        <a href="{{ route('promotions.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('promotions.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-tags text-xs w-4"></i> បញ្ជីបញ្ចុះតម្លៃ
                        </a>
                    </div>
                </div>
            </div>

            {{-- 5. គ្រប់គ្រងរបាយការណ៍ Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('reportrooms.*', 'reportmeetings.*', 'reportpayments.*', 'reportcustomers.*', 'reportroomstatus.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('reportrooms.*', 'reportmeetings.*', 'reportpayments.*', 'reportcustomers.*', 'reportroomstatus.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-chart-line w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រងរបាយការណ៍</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('reportrooms.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('reportrooms.index') ? 'text-blue-400 font-bold bg-white/5' : 'text-gray-400' }} hover:text-white">
                        <i class="fas fa-bed mr-2 text-[10px]"></i>របាយការណ៍កក់បន្ទប់
                    </a>
                    <a href="{{ route('reportmeetings.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('reportmeetings.index') ? 'text-blue-400 font-bold bg-white/5' : 'text-gray-400' }} hover:text-white">
                        <i class="fas fa-users mr-2 text-[10px]"></i>របាយការណ៍កក់សាលប្រជុំ
                    </a>
                    <a href="{{ route('reportpayments.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('reportpayments.index') ? 'text-blue-400 font-bold bg-white/5' : 'text-gray-400' }} hover:text-white">
                        <i class="fas fa-credit-card mr-2 text-[10px]"></i>របាយការណ៍ការបង់ប្រាក់
                    </a>
                    <a href="{{ route('reportcustomers.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('reportcustomers.index') ? 'text-blue-400 font-bold bg-white/5' : 'text-gray-400' }} hover:text-white">
                        <i class="fas fa-user-friends mr-2 text-[10px]"></i>របាយការណ៍អតិថិជន
                    </a>
                    <a href="{{ route('reportroomstatus.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('reportroomstatus.index') ? 'text-blue-400 font-bold bg-white/5' : 'text-gray-400' }} hover:text-white">
                        <i class="fas fa-door-open mr-2 text-[10px]"></i>របាយការណ៍ស្ថានភាពបន្ទប់
                    </a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រងរបាយការណ៍</span>
                            <i class="fas fa-chart-line text-xs"></i>
                        </div>
                        <a href="{{ route('reportrooms.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('reportrooms.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-bed text-xs w-4"></i> របាយការណ៍កក់បន្ទប់
                        </a>
                        <a href="{{ route('reportmeetings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('reportmeetings.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-users text-xs w-4"></i> របាយការណ៍កក់សាលប្រជុំ
                        </a>
                        <a href="{{ route('reportpayments.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('reportpayments.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-credit-card text-xs w-4"></i> របាយការណ៍ការបង់ប្រាក់
                        </a>
                        <a href="{{ route('reportcustomers.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('reportcustomers.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-user-friends text-xs w-4"></i> របាយការណ៍អតិថិជន
                        </a>
                        <a href="{{ route('reportroomstatus.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('reportroomstatus.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-door-open text-xs w-4"></i> របាយការណ៍ស្ថានភាពបន្ទប់
                        </a>
                    </div>
                </div>
            </div>

            {{-- 6. គ្រប់គ្រង់ប្រព័ន្ធ Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('users.*', 'contact.*', 'reviews.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('notifications.*', 'users.*', 'contact.*', 'reviews.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-user-cog w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រង់ប្រព័ន្ធ</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('admin.notifications.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.notifications.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">ការជូនដំណឹងក្នុងប្រព័ន្ធ</a>
                    <a href="{{ route('users.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('users.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីអ្នកប្រើប្រាស់</a>
                    <a href="{{ route('contact.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('contact.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">ការផ្ដល់មតិពីភ្ញៀវ</a>
                    <a href="{{ route('reviews.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('reviews.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">ការវាយតម្លៃពីភ្ញៀវ</a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រង់ប្រព័ន្ធ</span>
                            <i class="fas fa-user-cog text-xs"></i>
                        </div>
                        <a href="{{ route('admin.notifications.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('admin.notifications.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-bell text-xs w-4"></i> ការជូនដំណឹងក្នុងប្រព័ន្ធ
                        </a>
                        <a href="{{ route('users.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('users.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-users-cog text-xs w-4"></i> បញ្ជីអ្នកប្រើប្រាស់
                        </a>
                        <a href="{{ route('contact.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('contact.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-comments text-xs w-4"></i> ការផ្ដល់មតិពីភ្ញៀវ
                        </a>
                        <a href="{{ route('reviews.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('reviews.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-star text-xs w-4"></i> ការវាយតម្លៃពីភ្ញៀវ
                        </a>
                    </div>
                </div>
            </div>
            @endif
            {{-- end Admin Links --}}

            {{-- Staff links --}}
            @if(auth()->user()->role === 'staff')

            @php
            $navItems = [
            ['route' => 'dashboard', 'icon' => 'fa-chart-line', 'label' => 'ផ្ទាំងគ្រប់គ្រង'],
            ['route' => 'calendar.index', 'icon' => 'fa-calendar', 'label' => 'គ្រប់គ្រងកាលវិភាគ'],
            ];
            @endphp

            @foreach($navItems as $item)
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top + 5; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ hover: false, topPos: 0 }">
                <a href="{{ route($item['route']) }}"
                    @click="if(window.innerWidth < 1024) mobileOpen = false"
                    class="flex items-center gap-4 p-3 rounded-xl transition-all {{ request()->routeIs($item['route']) ? 'bg-blue-600 shadow-lg text-white' : 'hover:bg-white/10 text-gray-400 hover:text-white' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas {{ $item['icon'] }} w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>{{ $item['label'] }}</span>
                </a>
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 92px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-1"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    class="fixed px-3 py-2 bg-gray-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap z-[99999] pointer-events-none border border-white/20">
                    {{ $item['label'] }}
                </div>
            </div>
            @endforeach

            {{-- Staff Rooms Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('hotels.*', 'room_types.*', 'rooms.*', 'facilities.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('hotels.*', 'room_types.*', 'rooms.*', 'facilities.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-bed w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រងបន្ទប់</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('hotels.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('hotels.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីសណ្ឋាគារ</a>
                    <a href="{{ route('room_types.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('room_types.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីប្រភេទបន្ទប់</a>
                    <a href="{{ route('rooms.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('rooms.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីបន្ទប់</a>
                    <a href="{{ route('facilities.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('facilities.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">បញ្ជីគ្រឿងបរិក្ខារ</a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រងបន្ទប់</span>
                            <i class="fas fa-bed text-xs"></i>
                        </div>
                        <a href="{{ route('hotels.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('hotels.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-hotel text-xs w-4"></i> បញ្ជីសណ្ឋាគារ
                        </a>
                        <a href="{{ route('room_types.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('room_types.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-list text-xs w-4"></i> បញ្ជីប្រភេទបន្ទប់
                        </a>
                        <a href="{{ route('rooms.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('rooms.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-door-closed text-xs w-4"></i> បញ្ជីបន្ទប់
                        </a>
                        <a href="{{ route('facilities.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('facilities.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-concierge-bell text-xs w-4"></i> បញ្ជីគ្រឿងបរិក្ខារ
                        </a>
                    </div>
                </div>
            </div>

            {{-- Staff Booking Dropdown --}}
            <div class="relative"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top; hover = true; }"
                @mouseleave="if(!sidebarOpen) { hover = false; }"
                x-data="{ 
                    open: {{ request()->routeIs('room-bookings.*', 'meeting-bookings.*', 'bookings.*') ? 'true' : 'false' }},
                    hover: false,
                    topPos: 0
                }">
                <button @click="if(sidebarOpen) open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl hover:bg-white/10 text-gray-400 hover:text-white transition-all {{ request()->routeIs('room-bookings.*', 'meeting-bookings.*', 'bookings.*') ? 'bg-white/10 text-white' : '' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                    <i class="fas fa-calendar-check w-6 text-center text-lg"></i>
                    <span x-show="sidebarOpen" class="flex-1 text-left font-medium">គ្រប់គ្រងការកក់</span>
                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </button>

                {{-- Inline Dropdown --}}
                <div x-show="open && sidebarOpen" class="pl-12 mt-1 space-y-1" x-transition x-cloak>
                    <a href="{{ route('room-bookings.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('room-bookings.*') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">
                        <i class="fas fa-bed mr-2 text-xs"></i> កក់បន្ទប់ស្នាក់នៅ
                    </a>
                    <a href="{{ route('meeting-bookings.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('meeting-bookings.*') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">
                        <i class="fas fa-users mr-2 text-xs"></i> កក់សាលប្រជុំ
                    </a>
                    <a href="{{ route('bookings.index') }}" @click="if(window.innerWidth < 1024) mobileOpen = false" class="block p-2.5 text-sm rounded-lg transition-colors {{ request()->routeIs('bookings.index') ? 'text-white font-bold bg-white/10' : 'text-gray-400' }} hover:text-white hover:bg-white/5">
                        <i class="fas fa-list-alt mr-2 text-xs"></i> បញ្ជីកក់បន្ទប់
                    </a>
                </div>

                {{-- Collapsed Hover Flyout Dropdown --}}
                <div x-show="!sidebarOpen && hover"
                    :style="'top: ' + topPos + 'px; left: 80px;'"
                    x-cloak
                    x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 translate-x-2 scale-95"
                    x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                    x-transition:leave="transition ease-in duration-100"
                    x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-x-2 scale-95"
                    class="fixed pl-3 w-64 z-[99999]">
                    <div class="bg-[#002B5B] dark:bg-gray-900 border border-white/20 rounded-xl shadow-2xl p-2 space-y-1">
                        <div class="px-3 py-2 text-xs font-bold text-yellow-400 border-b border-white/10 flex items-center justify-between">
                            <span>គ្រប់គ្រងការកក់</span>
                            <i class="fas fa-calendar-check text-xs"></i>
                        </div>
                        <a href="{{ route('room-bookings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('room-bookings.*') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-bed text-xs w-4"></i> កក់បន្ទប់ស្នាក់នៅ
                        </a>
                        <a href="{{ route('meeting-bookings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('meeting-bookings.*') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-users text-xs w-4"></i> កក់សាលប្រជុំ
                        </a>
                        <a href="{{ route('bookings.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-colors {{ request()->routeIs('bookings.index') ? 'text-white font-bold bg-white/15' : 'text-gray-300' }} hover:text-white hover:bg-white/10 flex items-center gap-2">
                            <i class="fas fa-list-alt text-xs w-4"></i> បញ្ជីកក់បន្ទប់
                        </a>
                    </div>
                </div>
            </div>
            @endif

            {{-- end Staff link --}}
        </nav>

        <div class="p-4 border-t border-white/10 relative mt-auto shrink-0" x-data="{ tooltip: false, topPos: 0 }">
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>

            <button type="button" onclick="confirmLogout()"
                @mouseenter="if(!sidebarOpen) { topPos = $el.getBoundingClientRect().top + 5; tooltip = true; }"
                @mouseleave="tooltip = false"
                class="w-full flex items-center gap-4 p-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-all group" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                <i class="fas fa-sign-out-alt group-hover:-translate-x-1 transition-transform text-lg"></i>
                <span x-show="sidebarOpen" class="font-medium" x-transition>ចាកចេញ</span>
            </button>

            <div x-show="!sidebarOpen && tooltip"
                :style="'top: ' + topPos + 'px; left: 92px;'"
                x-cloak
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-x-1"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="fixed px-3 py-2 bg-gray-900 text-white text-xs font-semibold rounded-lg shadow-xl whitespace-nowrap z-[99999] pointer-events-none border border-white/20">
                ចាកចេញ
            </div>

            <div x-show="sidebarOpen" x-transition class="mt-2 text-[10px] text-gray-500 text-center uppercase tracking-widest">
                សណ្ឋាគារ ភីអេនធី ផាលេស
            </div>
        </div>
    </aside>

    <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden backdrop-blur-sm" x-transition x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="h-16 bg-white dark:bg-gray-900 border-b dark:border-gray-800 flex items-center justify-between px-4 md:px-6 shrink-0 shadow-sm z-30 transition-colors duration-300">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarStatus', sidebarOpen)"
                    class="hidden md:flex p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 transition-colors"
                    title="បើក/បិទ ម៉ឺនុយ">
                    <i class="fas" :class="sidebarOpen ? 'fa-indent' : 'fa-outdent'"></i>
                </button>

                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500">
                    <i class="fas fa-bars"></i>
                </button>

                <form action="{{ route('admin.global-search') }}" method="GET" class="hidden lg:flex items-center bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-xl focus-within:border-blue-500 transition-all w-64 border border-transparent focus-within:border-blue-500/50">
                    <i class="fas fa-search text-gray-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ស្វែងរក..." class="bg-transparent border-none outline-none text-sm ml-2 w-full dark:text-white">
                </form>
            </div>

            <div class="flex items-center gap-3 md:gap-4">
                <button @click="isDark = !isDark; localStorage.theme = isDark ? 'dark' : 'light'"
                    class="w-10 h-10 flex items-center justify-center rounded-2xl transition-all duration-300 shrink-0 shadow-sm border"
                    :class="isDark ? 'bg-gray-800 border-gray-700 text-yellow-400' : 'bg-white border-gray-100 text-blue-600'">
                    <i x-show="isDark" x-cloak class="fas fa-sun text-xl animate-pulse"></i>
                    <i x-show="!isDark" x-cloak class="fas fa-moon text-xl"></i>
                </button>

                {{-- Notifications Dropdown --}}
                <div class="relative" x-data="{ notifOpen: false, notifTab: 'all' }" @click.away="notifOpen = false">
                    <button type="button" @click="notifOpen = !notifOpen"
                        class="relative w-10 h-10 flex items-center justify-center rounded-2xl transition-all duration-300 shrink-0 shadow-sm border focus:outline-none"
                        :class="isDark ? 'bg-gray-800 border-gray-700 text-gray-300 hover:text-white' : 'bg-white border-gray-100 text-gray-600 hover:text-blue-600'"
                        title="ការជូនដំណឹង">
                        <i class="fas fa-bell text-xl"></i>
                        @if(($adminUnreadCount ?? 0) > 0)
                        <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white shadow-md animate-pulse">
                            {{ $adminUnreadCount > 99 ? '99+' : $adminUnreadCount }}
                        </span>
                        @endif
                    </button>

                    <div x-show="notifOpen" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
                        class="fixed left-4 right-4 top-20 mx-auto max-w-sm sm:absolute sm:left-auto sm:right-0 sm:top-full sm:mt-2.5 sm:w-96 sm:max-w-none bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-100 dark:border-gray-700 py-3 z-50 overflow-hidden">

                        {{-- Header --}}
                        <div class="px-5 pb-3 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    <i class="fas fa-bell text-sm"></i>
                                </div>
                                <h3 class="font-bold text-sm text-gray-800 dark:text-white">ការជូនដំណឹង</h3>
                            </div>
                            @if(($adminUnreadCount ?? 0) > 0)
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400">
                                {{ $adminUnreadCount }} ថ្មី
                            </span>
                            @else
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
                                ធ្វើបច្ចុប្បន្នភាព
                            </span>
                            @endif
                        </div>

                        {{-- Filter Tabs --}}
                        <div class="flex border-b border-gray-100 dark:border-gray-700 px-3 bg-gray-50/50 dark:bg-gray-900/30 text-xs font-semibold">
                            <button type="button" @click="notifTab = 'all'"
                                class="py-2.5 px-3 border-b-2 transition focus:outline-none"
                                :class="notifTab === 'all' ? 'border-blue-600 text-blue-600 dark:text-blue-400 font-bold' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700'">
                                ទាំងអស់
                            </button>
                            <button type="button" @click="notifTab = 'booking'"
                                class="py-2.5 px-3 border-b-2 transition flex items-center gap-1.5 focus:outline-none"
                                :class="notifTab === 'booking' ? 'border-blue-600 text-blue-600 dark:text-blue-400 font-bold' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700'">
                                ការកក់
                            </button>
                            <button type="button" @click="notifTab = 'message'"
                                class="py-2.5 px-3 border-b-2 transition flex items-center gap-1.5 focus:outline-none"
                                :class="notifTab === 'message' ? 'border-blue-600 text-blue-600 dark:text-blue-400 font-bold' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700'">
                                សារ & វាយតម្លៃ
                            </button>
                        </div>

                        {{-- Scrollable Notification List --}}
                        <div style="max-height: 250px; overflow-y: auto; -webkit-overflow-scrolling: touch;" class="custom-scrollbar divide-y divide-gray-50 dark:divide-gray-700/50">
                            @forelse(($adminNotifications ?? []) as $item)
                            <a href="{{ $item->url }}"
                                x-show="notifTab === 'all' || notifTab === '{{ $item->type }}'"
                                class="flex items-start gap-3.5 px-5 py-3.5 hover:bg-blue-50/50 dark:hover:bg-gray-700/40 transition group">
                                <div class="w-10 h-10 rounded-2xl {{ $item->icon_bg }} flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform">
                                    <i class="{{ $item->icon }} text-base"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-1 mb-1">
                                        <p class="text-xs font-bold text-gray-800 dark:text-gray-100 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                            {{ $item->title }}
                                        </p>
                                        @if($item->is_unread)
                                        <span class="w-2 h-2 rounded-full bg-blue-600 shrink-0"></span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-1 mb-1">
                                        {{ $item->description }}
                                    </p>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">
                                        <i class="far fa-clock mr-1"></i>{{ $item->time }}
                                    </span>
                                </div>
                            </a>
                            @empty
                            <div class="py-8 text-center px-4">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400 mx-auto mb-2">
                                    <i class="fas fa-check text-lg"></i>
                                </div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">គ្មានការជូនដំណឹងទេ</p>
                            </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        <div class="px-4 pt-2.5 pb-1 bg-gray-50 dark:bg-gray-900/60 border-t  dark:border-gray-700 text-center">
                            <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline inline-flex items-center gap-1.5">
                                មើលការជូនដំណឹងទាំងអស់
                            </a>
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 p-1">
                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-bold leading-none dark:text-white">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-500 mt-1 [&::first-letter]:uppercase tracking-tight">{{ Auth::user()->role }}</p>
                        </div>
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=002B5B&color=fff' }}"
                            class="w-9 h-9 rounded-lg border-2 border-blue-500/20 object-cover shadow-sm">
                    </button>

                    <div x-show="open" x-cloak
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:leave="transition ease-in duration-75"
                        class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl dark:border-gray-700 py-2 z-50">

                        <div class="px-4 py-2 border-b dark:border-gray-700 mb-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">គណនីគ្រប់គ្រង</p>
                        </div>

                        <a href="{{ route('setting.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                            <i class="fas fa-user-circle text-blue-500 "></i> កែប្រែព័ត៌មាន
                        </a>

                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                            <i class="fas fa-globe text-blue-500 "></i> ទៅគេហទំព័រ
                        </a>

                        <hr class="my-1 border-gray-100 dark:border-gray-700">

                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                            @csrf
                        </form>

                        <button type="button" onclick="confirmLogout()" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <i class="fas fa-sign-out-alt"></i> ចាកចេញ
                        </button>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content Area --}}
        <main class="flex-1 overflow-y-auto overflow-x-hidden w-full min-w-0 p-2 sm:p-4 lg:p-6 bg-gray-50 dark:bg-gray-950/50 transition-colors duration-300">
            <x-toast />
            <x-confirm-delete />

            <div class="w-full min-w-0">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'តើអ្នកពិតជាចង់ចាកចេញមែនទេ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ចាកចេញ',
                cancelButtonText: 'បោះបង់',
                reverseButtons: true,
                customClass: {
                    popup: 'bg-gray-900 border border-white/10 rounded-2xl shadow-xl',
                    title: 'text-white font-bold',
                    htmlContainer: 'text-gray-400'
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>

    <script>
        let editorInstance = null;

        function initEditor(el) {

            if (editorInstance) {
                return;
            }

            if (typeof ClassicEditor !== 'undefined') {
                ClassicEditor
                    .create(el)
                    .then(editor => {
                        editorInstance = editor;
                    });
            }
        }
    </script>

    <script>
        function viewRoomDetail(data) {
            window.Alpine.store('yourStoreName', data);

            let root = document.querySelector('[x-data]');
            if (root && root.__x) {
                root.__x.$data.currentRoomType = data;
                root.__x.$data.showDetailModal = true;
            } else {
                this.currentRoomType = data;
                this.showDetailModal = true;
            }
        }

        function prepareEditModal(data) {
            if (typeof openEditModal === 'function') {
                openEditModal(data);
            } else {
                let root = document.querySelector('[x-data]');
                if (root && root.__x) {
                    root.__x.$data.openEditModal(data);
                }
            }
        }
    </script>

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            if (calendarEl && typeof FullCalendar !== 'undefined') {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },
                    events: '{{ route("calendar.events") }}',
                    eventClick: function(info) {
                        alert('បន្ទប់៖ ' + info.event.title + '\nស្ថានភាព៖ ' + info.event.extendedProps.status);
                    }
                });
                calendar.render();
            }
        });
    </script>

    <script>
        function adminNotificationsData() {
            return {
                notifOpen: false,
                notifTab: 'all',
                loading: false,
                unreadCount: {
                    {
                        $adminUnreadCount ?? 0
                    }
                },
                items: @json($adminNotifications ?? []),

                init() {
                    this.fetchNotifications();
                    setInterval(() => {
                        this.fetchNotifications();
                    }, 30000);
                },

                fetchNotifications() {
                    this.loading = true;
                    fetch('{{ route("admin.notifications") }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.unreadCount = data.unread_count;
                                this.items = data.notifications;
                            }
                        })
                        .catch(err => console.error(err))
                        .finally(() => {
                            this.loading = false;
                        });
                },

                markAllAsRead() {
                    fetch('{{ route("admin.notifications.mark-read") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                this.unreadCount = 0;
                                this.items = this.items.map(i => ({
                                    ...i,
                                    is_unread: false
                                }));
                            }
                        });
                },

                get filteredItems() {
                    if (this.notifTab === 'all') return this.items;
                    return this.items.filter(i => i.type === this.notifTab);
                }
            }
        }
    </script>
    @stack('scripts')
    {{-- CKEditor CDN Fallback: load only if Vite bundle did not expose ClassicEditor --}}
    <script>
        if (typeof window.ClassicEditor === 'undefined') {
            var ckScript = document.createElement('script');
            ckScript.src = 'https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js';
            ckScript.onload = function() {
                window.ClassicEditor = ClassicEditor;
                // Re-dispatch DOMContentLoaded equivalent for CKEditor pages
                document.dispatchEvent(new Event('ckeditor-ready'));
            };
            document.head.appendChild(ckScript);
        }
    </script>
</body>

</html>