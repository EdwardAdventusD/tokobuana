$(document).ready(function () {
  $(".produk1").owlCarousel({
    loop: true,
    margin: 10,
    responsive: {
      600: {
        items: 5,
      },
    },
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    smartSpeed: 5000,
    rtl: true,
  });

  $(".produk2").owlCarousel({
    loop: true,
    margin: 10,
    responsive: {
      600: {
        items: 5,
      },
    },
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    smartSpeed: 5000,
  });
});

window.addEventListener("scroll", () => {
  const sections = document.querySelectorAll("section");
  const navLinks = document.querySelectorAll(".nav-link");

  sections.forEach((section, index) => {
    const rect = section.getBoundingClientRect();
    if (rect.top <= 100 && rect.bottom >= 100) {
      navLinks.forEach((navLink) => {
        navLink.classList.remove("nav-active");
      });
      navLinks[index].classList.add("nav-active");
    }
  });
});
