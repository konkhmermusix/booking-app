const scrollTopBtn = document.getElementById("scrollTopBtn");

window.addEventListener("scroll", () => {
  if (window.scrollY > 400) {
    // បង្ហាញប៊ូតុង
    scrollTopBtn.classList.remove(
      "translate-y-20",
      "opacity-0",
      "pointer-events-none"
    );
    scrollTopBtn.classList.add("translate-y-0", "opacity-100");
  } else {
    // លាក់ប៊ូតុង
    scrollTopBtn.classList.remove("translate-y-0", "opacity-100");
    scrollTopBtn.classList.add(
      "translate-y-20",
      "opacity-0",
      "pointer-events-none"
    );
  }
});

scrollTopBtn.addEventListener("click", () => {
  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
});
