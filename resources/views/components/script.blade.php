    @props(['script'])

    <script>
        var swiper = new Swiper(".Slideshow", {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
                type: "progressbar",
            },
            effect: "fade", // ប្រើ Effect Fade ដើម្បីឱ្យការដូររូបភាពមើលទៅប្រណីត
        });
    </script>

    <script>
        const toggleBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        function updateIcon() {
            if (document.documentElement.classList.contains('dark')) {
                themeIcon.className = 'fas fa-sun text-yellow-500';
            } else {
                themeIcon.className = 'fas fa-moon text-blue-600';
            }
        }
        updateIcon();

        toggleBtn.addEventListener('click', () => {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
            updateIcon();
        });

        // Mobile Menu Logic
        const menu = document.getElementById('menu');
        menuBtn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menu.classList.toggle('flex');
        });
    </script>

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = menuBtn.querySelector('i');

        menuBtn.addEventListener('click', () => {
            // បើក ឬ បិទ Menu
            mobileMenu.classList.toggle('hidden');

            // ប្តូរ Icon ចុះឡើងរវាង bars (Menu) និង times (Close)
            if (mobileMenu.classList.contains('hidden')) {
                // បើ Menu លាក់ (Hidden) ឱ្យចេញរូប Menu
                menuIcon.classList.remove('fa-times');
                menuIcon.classList.add('fa-bars');
            } else {
                // បើ Menu បើក ឱ្យចេញរូបខ្វែង Close
                menuIcon.classList.remove('fa-bars');
                menuIcon.classList.add('fa-times');
            }
        });

        // បិទ Menu វិញនៅពេលចុចលើ Link ណាមួយ
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuIcon.classList.replace('fa-times', 'fa-bars');
            });
        });
    </script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        kantumruy: ['Kantumruy Pro', 'sans-serif']
                    },
                    colors: {
                        gold: {
                            500: '#D4AF37',
                            600: '#C5A028'
                        }
                    }
                }
            }
        }

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>