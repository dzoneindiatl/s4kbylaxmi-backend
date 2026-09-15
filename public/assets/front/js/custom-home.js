/* =========================================
   HERO SLIDER
========================================= */

$(document).ready(function () {

    if ($('.hero_slider .slides').length) {

        $('.hero_slider .slides').slick({
            dots: true,
            arrows: false,
            fade: true,
            autoplay: true,
            autoplaySpeed: 4500,
            pauseOnHover: true,
            pauseOnFocus: false,
            cssEase: 'linear',
            appendDots: $('.hero_slider'),
            customPaging: function () {
                return '<button class="dot"></button>';
            }
        });

    }

});


/* =========================================
   CATEGORY ANIMATION
========================================= */

document.addEventListener('DOMContentLoaded', function () {
    const section = document.querySelector('.fw_categories');
    if (section) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.setAttribute('data-inview', 'true');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.2
        });
        observer.observe(section);
    }

});


/* =========================================
   SHOP BY CATEGORY SLIDER
========================================= */

$(document).ready(function () {
    if ($('.fw_refresh_slider').length) {
        $('.fw_refresh_slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
            speed: 800,

            prevArrow: $('.fw_prev_01'),
            nextArrow: $('.fw_next_01'),

            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2.5
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 770,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                }
            ]
        });

    }

});

/* =========================================
   HOME PRODUCT SLIDER SLIDER (Trending Prodoct)
========================================= */

$(document).ready(function () {
    if ($('.fw_product_grid').length) {
        $('.fw_product_grid').slick({
            slidesToShow: 5.7,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
             autoplay: true,
            speed: 800,

            prevArrow: $('.fw_prev'),
            nextArrow: $('.fw_next'),

            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2.5
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
});



/* =========================================
   HOME PRODUCT SLIDER SLIDER (Best seller)
========================================= */

$(document).ready(function () {
    if ($('.best_seller').length) {
        $('.best_seller').slick({
            slidesToShow: 4.7,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
            speed: 800,

            prevArrow: $('.fw_prev_05'),
            nextArrow: $('.fw_next_05'),

            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2.5
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
});


// GALLERY-------------------------------------------------------------------------------------------------

const cards = document.querySelectorAll('.room-card');
const categories = document.querySelectorAll('.room-categories a');

let current = 0;

function updateCarousel() {
    cards.forEach(card => {
        card.className = 'room-card';
    });

    categories.forEach(cat => {
        cat.classList.remove('active');
    });
    const total = cards.length;
    const prev = (current - 1 + total) % total;
    const next = (current + 1) % total;
    cards[current].classList.add('center');
    cards[prev].classList.add('left');
    cards[next].classList.add('right');
    categories[current].classList.add('active');
    cards.forEach((card, index) => {
        if (
            index !== current &&
            index !== prev &&
            index !== next
        ) {

            if (index < current) {
                card.classList.add('hidden-left');
            } else {
                card.classList.add('hidden-right');
            }
        }
    });
}

document.querySelector('.next')
    .addEventListener('click', () => {
        current =
            (current + 1) % cards.length;
        updateCarousel();
    });

document.querySelector('.prev')
    .addEventListener('click', () => {
        current =
            (current - 1 + cards.length) %
            cards.length;
        updateCarousel();
    });

categories.forEach(category => {
    category.addEventListener('click', function (e) {
        e.preventDefault();
        current =
            parseInt(this.dataset.index);
        updateCarousel();
    });
});

updateCarousel();

// PRODUCT CARD DISPLAY-----------------------------------------------------------------
var swiper = new Swiper(".product_card_swiper", {
    effect: "coverflow",
    loop: true,
    grabCursor: true,
    navigation: true,
    centeredSlides: true,
    slidesPerView: "1",
    coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true
    },
    navigation: {
        nextEl: '.nav-btn.next',
        prevEl: '.nav-btn.prev',
    },

    pagination: {
        el: ".swiper-pagination"
    },
    breakpoints: {
        // when window width is >= 320px
        320: {
            slidesPerView: 1.5
        },
        // when window width is >= 480px
        580: {
            slidesPerView: 2
        },
        // when window width is >= 480px
        767: {
            slidesPerView: 3
        },
        992: {
            slidesPerView: 3.5
        },
        1200: {
            slidesPerView: 4
        },
        1400: {
            slidesPerView: 4.5
        }
    }
});

// INSTA GALLERY SCROLL----------------------------------------------------------------------------------------------------------

$(document).ready(function () {
    if ($('.instagram-slider').length) {
        $('.instagram-slider').slick({
            slidesToShow: 5.7,
            slidesToScroll: 1,
            infinite: true,
            arrows: true,
            speed: 800,
            autoplay: true,

            prevArrow: $('.fw_prev_insta'),
            nextArrow: $('.fw_next_insta'),

            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2.5
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });
    }
});



// SEARCH -----------------------------------------------------------------------

const searchBtn = document.querySelector(".search_icon");
const searchOverlay = document.querySelector(".search-overlay");
const closeSearch = document.querySelector(".close-search");

function closeSearchPopup() {
    searchOverlay.classList.remove("active");
}

searchBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    searchOverlay.classList.add("active");
});

closeSearch.addEventListener("click", closeSearchPopup);

document.addEventListener("click", (e) => {

    if (
        searchOverlay.classList.contains("active") &&
        !searchOverlay.contains(e.target) &&
        !searchBtn.contains(e.target)
    ) {
        closeSearchPopup();
    }

});




//MEGA MENU (NAVIGATION) ---------------------------------------------------------------------------------------
document.querySelectorAll(
    '.has-mega > a, .has-dropdown > a').forEach(item => {
        item.addEventListener('click', function (e) {
            if (window.innerWidth <= 1155) {
                e.preventDefault();
                this.parentElement.classList.toggle('active');
            }
        });
    });

//MOBILE MENU----------------------------------------------------
const menuBtn = document.querySelector(".mobile_menu");
const mainNav = document.querySelector(".main-nav");
const closeNav = document.querySelector(".nav-close");

menuBtn.addEventListener("click", () => {
    mainNav.classList.toggle("active");
    menuBtn.classList.toggle("active");
});

closeNav.addEventListener("click", () => {
    mainNav.classList.toggle("active");
    menuBtn.classList.toggle("active");
});


// TESTIMONIAL SLIDER------------------------------------------------------------------
$('.testimonial-slider').slick({
    slidesToShow: 1,   // 1 STACK PER SLIDE
    slidesToScroll: 1,
    arrows: true,
    dots: false,
    infinite: true,
    autoplay: false,
    autoplaySpeed: 4000,
    prevArrow: $('.fw_prev_02'),
    nextArrow: $('.fw_next_02'),
});

//CART DROPDOWN----------------------------------------------------------------------------------------
const cartButton = document.querySelector('.cart_button');
const cartWrapper = document.querySelector('.cart-wrapper');
const cartDropdown = document.querySelector('.cart-dropdown');

// open close
cartButton.addEventListener('click', function (e) {
    e.stopPropagation();
    cartWrapper.classList.toggle('active');
});

// dropdown ke andar click
cartDropdown.addEventListener('click', function (e) {
    e.stopPropagation();
});

// outside click
document.addEventListener('click', function (e) {
    if (!cartWrapper.contains(e.target)) {
        cartWrapper.classList.remove('active');
    }
});


// STICKY HEADER
const header = document.querySelector('.header_container');
window.addEventListener('scroll', () => {
    if (window.scrollY > 100) {
        header.classList.add('sticky-header');
    } else {
        header.classList.remove('sticky-header');
    }
});
