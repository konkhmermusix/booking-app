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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNT Hotel - @yield('title', 'Admin')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('css/tailwind.css') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

    <aside
        class="bg-[#002B5B] dark:bg-gray-900 text-white transition-all duration-300 ease-in-out flex flex-col z-50 fixed inset-y-0 left-0 md:relative shadow-2xl"
        :class="{ 
            'w-64': sidebarOpen, 
            'w-20': !sidebarOpen,
            'translate-x-0': mobileOpen,
            '-translate-x-full': !mobileOpen && window.innerWidth < 768,
            'md:translate-x-0': true
        }">

        <div class="h-16 flex items-center px-6 border-b border-white/10 overflow-hidden shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-yellow-400 rounded-lg flex-shrink-0 flex items-center justify-center text-blue-900 font-bold shadow-lg">P</div>
                <span class="text-xl font-bold tracking-wider transition-all duration-300" x-show="sidebarOpen" x-transition>
                    PNT <span class="text-yellow-400">ADMIN</span>
                </span>
            </div>
        </div>

        <nav class="flex-1 py-6 px-3 space-y-1 overflow-y-auto no-scrollbar">
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-4 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 shadow-lg text-white' : 'hover:bg-white/10 text-gray-400 hover:text-white' }}"
                :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                <i class="fas fa-chart-line w-6 text-center text-lg"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>ផ្ទាំងគ្រប់គ្រង</span>
            </a>
            <a href="{{ route('bookings.index') }}"
                class="flex items-center gap-4 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('bookings.index') ? 'bg-blue-600 shadow-lg text-white' : 'hover:bg-white/10 text-gray-400 hover:text-white' }}"
                :class=" sidebarOpen ? 'justify-start' : 'justify-center'">
                <i class=" fas fa-calendar-check w-6 text-center text-lg"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>បញ្ជីកក់បន្ទប់</span>
            </a>

            <a href="{{ route('hotels.index') }}"
                class="flex items-center gap-4 p-3 rounded-xl transition-all duration-200 {{ request()->routeIs('hotels.index') ? 'bg-blue-600 shadow-lg text-white' : 'hover:bg-white/10 text-gray-400 hover:text-white' }}"
                :class=" sidebarOpen ? 'justify-start' : 'justify-center'">
                <i class=" fas fa-hotel w-6 text-center text-lg"></i>
                <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>បញ្ជីសណ្ឋាគារ</span>
            </a>

            <div x-data="{ open: {{ request()->routeIs('rooms.*') || request()->routeIs('room_types.*') ? 'true' : 'false' }} }" class="space-y-1">

                <button @click="open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl transition-all duration-200 hover:bg-white/10 text-gray-400 hover:text-white group"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">

                    <div class="flex items-center gap-4 flex-1">
                        <i class="fas fa-bed w-6 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>គ្រប់គ្រងបន្ទប់</span>
                    </div>

                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-xs transition-transform duration-200"
                        :class="open ? 'rotate-90' : ''"></i>
                </button>

                <div x-show="open && sidebarOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-12 space-y-1">

                    <a href="{{ route('rooms.index') }}"
                        class="block p-2 text-sm {{ request()->routeIs('rooms.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white transition-colors">
                        បញ្ជីបន្ទប់
                    </a>
                    <a href="{{ route('room_types.index') }}"
                        class="block p-2 text-sm {{ request()->routeIs('room_types.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white transition-colors">
                        បញ្ជីប្រភេទបន្ទប់
                    </a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('facilities.*') || request()->routeIs('slideshows.*') ? 'true' : 'false' }} }" class="space-y-1">

                <button @click="open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl transition-all duration-200 hover:bg-white/10 text-gray-400 hover:text-white group"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">

                    <div class="flex items-center gap-4 flex-1">
                        <i class="fas fa-tachometer-alt w-6 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>គ្រប់គ្រងគេហទំព័រ</span>
                    </div>

                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-xs transition-transform duration-200"
                        :class="open ? 'rotate-90' : ''"></i>
                </button>

                <div x-show="open && sidebarOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="pl-12 space-y-1">

                    <a href="{{ route('slideshows.index') }}"
                        class="block p-2 text-sm {{ request()->routeIs('slideshows.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white transition-colors">
                        បញ្ជីបដារ
                    </a>
                    <a href="{{ route('facilities.index') }}"
                        class="block p-2 text-sm {{ request()->routeIs('facilities.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white transition-colors">
                        បញ្ជីគ្រឿងបរិក្ខារ
                    </a>
                    <a href="{{ route('room_types.index') }}"
                        class="block p-2 text-sm {{ request()->routeIs('room_types.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white transition-colors">
                        បញ្ជីប្រភេទបន្ទប់
                    </a>
                </div>
            </div>

            <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="open = !open"
                    class="w-full flex items-center gap-4 p-3 rounded-xl transition-all duration-200 group {{ request()->is('users*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">

                    <div class="flex items-center gap-4 flex-1">
                        <i class="fas fa-user-shield w-6 text-center text-lg"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap font-medium" x-transition>គ្រប់គ្រងអ្នកប្រើ</span>
                    </div>

                    <i x-show="sidebarOpen" class="fas fa-chevron-right text-[10px] transition-transform duration-200"
                        :class="open ? 'rotate-90' : ''"></i>
                </button>

                <div x-show="open && sidebarOpen" x-cloak x-transition class="pl-12 space-y-1">
                    <a href="{{ route('users.index') }}"
                        class="block p-2 text-sm {{ request()->routeIs('users.index') ? 'text-white font-bold' : 'text-gray-400' }} hover:text-white transition-colors">
                        បញ្ជីអ្នកប្រើប្រាស់
                    </a>

                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/10 shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-4 p-3 rounded-xl transition-all duration-200 text-red-400 hover:bg-red-500/10 hover:text-red-300 group"
                    :class="sidebarOpen ? 'justify-start' : 'justify-center'">

                    <i class="fas fa-sign-out-alt text-lg transition-transform group-hover:-translate-x-1"></i>

                    <span x-show="sidebarOpen"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0"
                        class="whitespace-nowrap font-medium">
                        ចាកចេញពីគណនី
                    </span>
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
                            <i class="fas fa-user-circle text-blue-500 text-lg"></i> កែប្រែព័ត៌មានផ្ទាល់ខ្លួន
                        </a>

                        <hr class="my-1 border-gray-100 dark:border-gray-700">

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                <i class="fas fa-sign-out-alt text-lg"></i> ចាកចេញពីគណនី
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50 dark:bg-gray-950/50 transition-colors duration-300">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // បង្កើត Function សម្រាប់កំណត់ Style ទៅតាម Theme
        const getSweetAlertConfig = () => {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#111827' : '#ffffff',
                color: isDark ? '#ffffff' : '#1f2937',
                confirmButtonColor: '#2563eb',
                cancelButtonColor: isDark ? '#374151' : '#d1d5db',
            };
        };

        // 1. Success Message
        @if(session('success'))
        Swal.fire({
            ...getSweetAlertConfig(),
            icon: 'success',
            title: 'ជោគជ័យ',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            iconColor: '#10b981'
        });
        @endif

        // 2. Validation Errors (កែប្រែត្រង់នេះ)
        const validationErrors =
            @json($errors -> all());

        if (validationErrors.length > 0) {
            let errorList = '<div class="text-left text-sm mt-2"><ul class="list-disc list-inside space-y-1">';
            validationErrors.forEach(error => {
                errorList += `<h4>${error}</h4>`;
            });
            errorList += '</ul></div>';

            Swal.fire({
                ...getSweetAlertConfig(),
                icon: 'error',
                title: 'មានបញ្ហា!',
                html: errorList,
                confirmButtonText: 'យល់ព្រម',
                iconColor: '#ef4444'
            });
        }

        // 3. Delete Confirmation
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const config = getSweetAlertConfig();
                Swal.fire({
                    ...config,
                    title: 'តើអ្នកប្រាកដទេ?',
                    text: "ទិន្នន័យនេះនឹងត្រូវលុបជារៀងរហូត!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'យល់ព្រមលុប',
                    cancelButtonText: 'បោះបង់',
                    confirmButtonColor: '#ef4444',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                });
            });
        });
    </script>
</body>

</html>