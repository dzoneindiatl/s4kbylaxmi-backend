


$(".submit").on("click", function () {
    $(this).toggleClass("active"), $(".search").toggleClass("active");
});

$(document).ready(function () {
    $("#banner-slider").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        items: 1,
    });
});
$(document).ready(function () {
    $("#banneroffer-slider").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        items: 1,
    });
});
$(document).ready(function () {
    $("#women-seller-slider").owlCarousel({
        loop: true, // Infinite loop
        margin: 10, // Margin between items
        nav: true, // Enable previous/next buttons
        autoplay: true, // Enable auto-slide
        autoplayTimeout: 3000, // Set slide interval (in milliseconds)
        autoplayHoverPause: true, // Pause on hover
             responsive: {
            0: {
                items: 1, // For small screens
            },
            600: {
                items: 2, // For small screens
            },
            768: {
                items: 3, // For medium screens
            },
            1000: {
                items: 4, // For large screens
            },
        },
    });
});

$(document).ready(function () {
    $("#best-men-seller").owlCarousel({
        loop: true, // Infinite loop
        margin: 10, // Margin between items
        nav: true, // Enable previous/next buttons
        autoplay: true, // Enable auto-slide
        autoplayTimeout: 3000, // Set slide interval (in milliseconds)
        autoplayHoverPause: true, // Pause on hover
            responsive: {
            0: {
                items: 1, // For small screens
            },
            600: {
                items: 2, // For small screens
            },
            768: {
                items: 3, // For medium screens
            },
            1000: {
                items: 4, // For large screens
            },
        },
    });
});

$(document).ready(function () {
    $("#shop-by-categories").owlCarousel({
        loop: true, // Infinite loop
        margin: 10, // Margin between items
        nav: true, // Enable previous/next buttons
        autoplay: false, // Enable auto-slide
        autoplayTimeout: 3000, // Set slide interval (in milliseconds)
        autoplayHoverPause: true, // Pause on hover
        responsive: {
            0: {
                items: 1, // For small screens
            },
            600: {
                items: 2, // For medium screens
            },
            1000: {
                items: 3, // For large screens
            },
        },
    });
});

$(document).ready(function () {
    $("#testimonials").owlCarousel({
        loop: true, // Infinite loop
        margin: 10, // Margin between items
        nav: true, // Enable previous/next buttons
        autoplay: true, // Enable auto-slide
        autoplayTimeout: 3000, // Set slide interval (in milliseconds)
        autoplayHoverPause: true, // Pause on hover
        responsive: {
            0: {
                items: 1, // For small screens
            },
            600: {
                items: 2, // For medium screens
            },
            1000: {
                items: 4, // For large screens
            },
        },
    });
});


$(document).ready(function () {
    $("#productRandom").owlCarousel({
        loop: true, // Infinite loop
        margin: 10, // Margin between items
        nav: true, // Enable previous/next buttons
        autoplay: true, // Enable auto-slide
        autoplayTimeout: 3000, // Set slide interval (in milliseconds)
        autoplayHoverPause: true, // Pause on hover
        responsive: {
            0: {
                items: 1, // For small screens
            },
            600: {
                items: 2, // For medium screens
            },
            1000: {
                items: 2, // For large screens
            },
        },
    });
});

$(document).ready(function () {
    $("#product").owlCarousel({
        loop: true, // Infinite loop
        margin: 10, // Margin between items
        nav: true, // Enable previous/next buttons
        autoplay: true, // Enable auto-slide
        autoplayTimeout: 3000, // Set slide interval (in milliseconds)
        autoplayHoverPause: true, // Pause on hover
        responsive: {
            0: {
                items: 1, // For small screens
            },
            600: {
                items: 2, // For medium screens
            },
            1000: {
                items: 4, // For large screens
            },
        },
    });
});

$(document).ready(function () {
    $("#blogs").owlCarousel({
        loop: true, // Infinite loop
        margin: 10, // Margin between items
        nav: true, // Enable previous/next buttons
        autoplay: true, // Enable auto-slide
        autoplayTimeout: 3000, // Set slide interval (in milliseconds)
        autoplayHoverPause: true, // Pause on hover
        responsive: {
            0: {
                items: 1, // For small screens
            },
            600: {
                items: 1, // For medium screens
            },
            1000: {
                items: 1, // For large screens
            },
        },
    });
});




$('.owl-carousel.out-meet').owlCarousel({
    loop:true,
    margin:25,
    nav:true,
    autoplay:true,
    responsive:{
    0:{
       items:1
    },
    600:{
       items:3
    },
    1000:{
       items:4
    }
    }
    })
    