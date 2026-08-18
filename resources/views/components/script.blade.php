    @props(['script'])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector(".CoverflowSlider")) {
                var swiper = new Swiper(".CoverflowSlider", {
                    effect: "coverflow",
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: "auto",
                    loop: true,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                    },
                    coverflowEffect: {
                        rotate: 35,
                        stretch: 0,
                        depth: 120,
                        modifier: 1,
                        slideShadows: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                });
            } else if (document.querySelector(".Slideshow")) {
                var swiper = new Swiper(".Slideshow", {
                    loop: true,
                    grabCursor: true,
                    speed: 1500,
                    effect: "fade",
                    fadeEffect: {
                        crossFade: true,
                    },
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
                    },
                });
            }

            const toggleBtn = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            if (toggleBtn && themeIcon) {
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
            }

            const menuBtn = document.getElementById('menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            if (menuBtn && mobileMenu) {
                const menuIcon = menuBtn.querySelector('i');
                menuBtn.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                    if (menuIcon) {
                        if (mobileMenu.classList.contains('hidden')) {
                            menuIcon.classList.remove('fa-times');
                            menuIcon.classList.add('fa-bars');
                        } else {
                            menuIcon.classList.remove('fa-bars');
                            menuIcon.classList.add('fa-times');
                        }
                    }
                });

                document.querySelectorAll('#mobile-menu a').forEach(link => {
                    link.addEventListener('click', () => {
                        mobileMenu.classList.add('hidden');
                        if (menuIcon) menuIcon.classList.replace('fa-times', 'fa-bars');
                    });
                });
            }
        });
    </script>

    <script>
        if (typeof tailwind !== 'undefined') {
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
            };
        }

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>