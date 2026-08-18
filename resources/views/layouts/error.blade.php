<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
    :class="{ 'dark': darkMode }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PNT Hotel')</title>

    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('fonts/font.css') }}">

    <style>
        body {
            font-family: 'Kantumruy Pro', sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 h-screen flex items-center justify-center p-4 transition-colors duration-300">

    <button id="theme-toggle"
        class="fixed top-5 right-5 w-12 h-12 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 shadow-lg text-xl transition-all hover:scale-110 active:scale-95">
        <i id="theme-icon" class="fas fa-moon text-blue-600"></i>
    </button>

    <main>
        <x-alert /> @yield('content')
    </main>


    @stack('scripts')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
    </script>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        const html = document.documentElement;

        function updateIcon() {
            if (html.classList.contains('dark')) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
                themeIcon.classList.remove('text-blue-600');
                themeIcon.classList.add('text-yellow-400');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
                themeIcon.classList.remove('text-yellow-400');
                themeIcon.classList.add('text-blue-600');
            }
        }

        updateIcon();

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
            updateIcon();
        });
    </script>
</body>

</html>