$(document).ready(function () {
  $('.banner_slider').slick({
    dots: false,
    arrows: true,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2000,
    speed: 2000,
    slidesToShow: 1,
    slidesToScroll: 1,
    adaptiveHeight: true
  });
});


$(document).ready(function () {

  $('.banner-vertical-slider').slick({
    dots: false,
    arrows: false,
    infinite: true,
    autoplay: true,
    autoplaySpeed: 2000,
    speed: 2000,
    slidesToShow: 2,
    slidesToScroll: 1,
    vertical: true,
    verticalSwiping: true,
  });

});

$('.product-slider').slick({
  slidesToShow: 4,
  slidesToScroll: 1,
  arrows: true,
  dots: false,
  infinite: true,
  speed: 400,

  responsive: [
    {
      breakpoint: 1200,
      settings: {
        slidesToShow: 3
      }
    },
    {
      breakpoint: 767,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 567,
      settings: {
        slidesToShow: 1
      }
    }
  ]
});


// Spin to Win popup: show after 4 seconds
setTimeout(function () {
  document.getElementById('spinOverlay').classList.add('active');
}, 4000);

function closeSpinPopup() {
  document.getElementById('spinOverlay').classList.remove('active');
}

document.getElementById('spinClose').addEventListener('click', closeSpinPopup);

// Close on overlay click (outside popup)
document.getElementById('spinOverlay').addEventListener('click', function (e) {
  if (e.target === this) closeSpinPopup();
});

var hasSpun = false;
function triggerSpin() {
  if (hasSpun) return;
  hasSpun = true;

  var wheel = document.getElementById('spinWheel');
  var extraDeg = Math.floor(Math.random() * 360);
  var totalDeg = 1800 + extraDeg;

  wheel.style.transition = 'transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99)';
  wheel.style.transform = 'rotate(' + totalDeg + 'deg)';

  setTimeout(function () {
    alert('🎉 Congratulations! You won 20% OFF your first order!\nUse code: SPIN20 at checkout.');
    closeSpinPopup();
  }, 4200);
}


document.addEventListener("DOMContentLoaded", function () {

  const buttons = document.querySelectorAll(".category-btn");
  const items = document.querySelectorAll(".furniture-item");

  buttons.forEach(button => {
    button.addEventListener("click", function () {

      // active button style
      buttons.forEach(btn => btn.classList.remove("active"));
      this.classList.add("active");

      const filter = this.getAttribute("data-filter");

      items.forEach(item => {
        const category = item.getAttribute("data-category");

        if (filter === "all" || filter === category) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      });

    });
  });

});



$('.offer-slider').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  dots: true,
  arrows: false,
  infinite: true,
  // autoplay: true,
  autoplaySpeed: 3000,

  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2
      }
    },
    {
      breakpoint: 576,
      settings: {
        slidesToShow: 1
      }
    }
  ]
});



// Set your sale end date here
const endDate = new Date("April 25, 2026 23:59:59").getTime();

const timer = setInterval(function () {

  const now = new Date().getTime();
  const distance = endDate - now;

  const days = Math.floor(distance / (1000 * 60 * 60 * 24));
  const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((distance % (1000 * 60)) / 1000);

  document.getElementById("days").innerHTML = days;
  document.getElementById("hours").innerHTML = hours;
  document.getElementById("minutes").innerHTML = minutes;
  document.getElementById("seconds").innerHTML = seconds;

  if (distance < 0) {
    clearInterval(timer);
    document.getElementById("countdown").innerHTML = "EXPIRED";
  }

}, 1000);


// popup call
function openPopup() {
  document.getElementById("cameraPopup").classList.add("active");
}

function closePopup() {
  document.getElementById("cameraPopup").classList.remove("active");
}

// cart pop up
function openCart() {
  document.getElementById("cartDrawer").classList.add("active");
  document.querySelector(".cart-overlay").classList.add("active");
}

function closeCart() {
  document.getElementById("cartDrawer").classList.remove("active");
  document.querySelector(".cart-overlay").classList.remove("active");
}

// search bar

function openSearch() {
  document.getElementById("searchPopup").classList.add("active");
  document.querySelector(".search-overlay").classList.add("active");
}

function closeSearch() {
  document.getElementById("searchPopup").classList.remove("active");
  document.querySelector(".search-overlay").classList.remove("active");
}

// Back to top button visibility
const btn = document.getElementById('backToTop');

window.onscroll = () => {
    btn.classList.toggle('visible', window.scrollY > 400);
};
