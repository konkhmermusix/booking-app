document.addEventListener("DOMContentLoaded", function () {
    const swiper = new Swiper(".Slideshow", {
        loop: true,
        speed: 1500, // ល្បឿនដូររូបភាព
        effect: "fade", // បែប Fade (រលាយ)
        fadeEffect: {
            crossFade: true,
        },
        autoplay: {
            delay: 5000, // ៥ វិនាទីដូរម្តង
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
    });
});
