window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
}

document.addEventListener("DOMContentLoaded", function () {
    const scrollTopBtn = document.getElementById("scrollTopBtn");
    let lastScrollTop = 0;

    window.addEventListener(
        "scroll",
        function () {
            let currentScroll =
                window.pageYOffset || document.documentElement.scrollTop;
            if (currentScroll > 200) {
                if (currentScroll < lastScrollTop) {
                    scrollTopBtn.classList.remove(
                        "translate-y-20",
                        "opacity-0",
                        "pointer-events-none"
                    );
                    scrollTopBtn.classList.add("translate-y-0", "opacity-100");
                } else {
                    scrollTopBtn.classList.remove(
                        "translate-y-0",
                        "opacity-100"
                    );
                    scrollTopBtn.classList.add(
                        "translate-y-20",
                        "opacity-0",
                        "pointer-events-none"
                    );
                }
            } else {
                scrollTopBtn.classList.remove("translate-y-0", "opacity-100");
                scrollTopBtn.classList.add(
                    "translate-y-20",
                    "opacity-0",
                    "pointer-events-none"
                );
            }

            lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
        },
        {
            passive: true,
        }
    );
});
