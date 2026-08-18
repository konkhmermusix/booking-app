document.addEventListener("DOMContentLoaded", function () {
    const slideshowEl = document.querySelector(".Slideshow");
    if (slideshowEl) {
        const slidesCount = slideshowEl.querySelectorAll(".swiper-slide").length;
        const swiper = new Swiper(".Slideshow", {
            loop: slidesCount > 1,
            grabCursor: true,
            touchEventsTarget: "container",
            speed: 1500, // ល្បឿនដូររូបភាព
            effect: "fade", // បែប Fade (រលាយ)
            fadeEffect: {
                crossFade: true,
            },
            autoplay: slidesCount > 1 ? {
                delay: 5000, // ៥ វិនាទីដូរម្តង
                disableOnInteraction: false,
            } : false,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    }
});
