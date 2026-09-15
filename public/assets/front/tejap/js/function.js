(function ($) {

	// Sticky Header 
	$(window).scroll(function () {
		if ($('.header').length) {
			let mainHeader = $('.header').height();
			let windowpos = $(window).scrollTop();
			if (windowpos >= mainHeader) {
				$('.header-sticky').addClass('sticked');
			} else {
				$('.header-sticky').removeClass('sticked');
			}
		}
	});	

	// Mega Menu
	$(".mega-menu-item > li").hover(
		function () {
			$(this).addClass("result_hover");			  
		},
		function () {
			$(this).removeClass("result_hover");			  
		}
	);
	$(".navbar-nav > li.has-children").hover(
		function () {
			$('.mega-menu-item > li:first-child').addClass('result_hover');		  
		},
		function () {
			$('.mega-menu-item > li:first-child').removeClass('result_hover');			  
		}
	);

	// Respoonsive Menu		  
	$(".navbar-nav li").click(function (event) {
		// stop bootstrap.js to hide the parents
		event.stopPropagation();
		// hide the open children
		$(this).find(".sub-menu").removeClass('open');
		// add 'open' class to all parents with class 'dropdown-submenu'
		$(this).parents(".sub-menu").addClass('open');
		// this is also open (or was)
		$(this).toggleClass('open');
	});

	$( ".navbar-toggler" ).click(function(event) {
		$('body').toggleClass('menu-open');
		$('.menu-overlay').toggleClass('open');
	});	

	$( ".menu-overlay" ).click(function(event) {
		$('body').removeClass('menu-open');
		$('.menu-overlay').removeClass('open');
		$('.navbar-toggler').addClass('collapsed');
		$('.navbar-collapse').removeClass('show');
	});			

	// Secarh Section
	$('.search-icon').click(function () {
		$('.header-search').addClass('open');
		$('body').addClass('search-open');		
		$('.search-icon').addClass('d-none');
		$('.search-cancel').removeClass('d-none');
		$('.header-menu').addClass('d-none');
		$('.header-logo').addClass('d-none');		
	});
	$('.search-cancel').click(function () {
		$('.header-search').removeClass('open');
		$('body').removeClass('search-open');
		$('.search-icon').removeClass('d-none');
		$('.search-cancel').addClass('d-none');
		$('.header-menu').removeClass('d-none');
		$('.header-logo').removeClass('d-none');		
	});

	// Cart Action
	$('.cart-menu a').click(function () {
		$('.cart-dropdown').toggleClass('open');		
		$('.cart-arrow').toggleClass('open');	
		$('.account-dropdown').removeClass('open');	
	});

	// Account Action
	$('.account-toggle').hover(function () {
		$('.cart-dropdown').removeClass('open');		
		$('.cart-arrow').removeClass('open');	
	});

	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		$('.account-toggle').click(function () {
			$('.account-dropdown').toggleClass('open');		
			$('.cart-dropdown').removeClass('open');		
			$('.cart-arrow').removeClass('open');				
		});
	}

	// Scrool Function Back to  Top And Transparent Header
	$(window).scroll(function () {
		let scroll = $(window).scrollTop();
		if (scroll >= 100) {
			$('.scroll-top').fadeIn(300);
			$(".header-fixed").addClass("fix");
			$(".header-transparent").addClass("transparency");
		} else {
			$('.scroll-top').fadeOut(300);
			$(".header-fixed").removeClass("fix");
			$(".header-transparent").removeClass("transparency");
		}
	});
	// Scrool Function Back to  Top   
	$('.scroll-top').click(function () {
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});

	// Form Focus
	$("input, select, textarea").on('focus blur', function(){ 
		$(this).closest('.form-group').toggleClass('focus');
	});
	$('input, select, textarea').on('blur', function(event) {
		let inputValue = this.value;
		if (inputValue) {     
			$(this).closest('.form-group').addClass('has-value'); 	
		} else {    
			$(this).closest('.form-group').removeClass('has-value')	
		}
	});
	$("input, select, textarea").change(function(){
		$(this).closest('.form-group').addClass('has-value'); 	
	});
	$('input, select, textarea').each(function(){
		if ($(this).val()){
			$(this).closest('.form-group').addClass("has-value");
		}
	});

	// Password Show 	
	$('.password-icon').click(function(){ 	
		$( this ).text(($(".password-icon").text() == 'SHOW') ? 'HIDE' : 'SHOW').fadeIn();
		let input = $(".password-input");		
		if (input.attr("type") === "password") {
			input.attr("type", "text");			 
		} else {
			input.attr("type", "password");			 
		}		
	});	

	//Animated	
	new WOW().init();

	// Sticky About Header 
	$(window).scroll(function () {
		if ($('.page-banner-section').length) {
			let mainHeader = $('.page-banner-section').height();
			let windowpos = $(window).scrollTop();
			if (windowpos >= mainHeader) {
				$('.about-navigation').addClass('sticked');
			} else {
				$('.about-navigation').removeClass('sticked');
			}
		}
	});	

	// Click menu to find div id with length
	document.querySelectorAll('.about-navigation a[href^="#"]').forEach(anchor => {
		anchor.addEventListener('click', function (e) {
			e.preventDefault();			
			$('html, body').animate({scrollTop: $($.attr(this, 'href')).offset().top - 140}, 500);
			//$('body').removeClass('menu-open');
			//$('.menu-overlay').removeClass('open');
			//$('.navbar-toggler').addClass('collapsed');
			//$('.navbar-collapse').removeClass('show');
		});		
	});	

	// Slider Carousel
	$('.slider-carousel').slick({
		arrows: true,
		dots: true,
		slidesToShow: 1,
		slidesToScroll: 1,
		infinite: true,
		swipe: true,
		//fade: true,
		cssEase: "cubic-bezier(0.7, 0, 0.3, 1)",
		touchThreshold: 100,
		pauseOnHover: false,
		touchMove: true,
		draggable: true,
		autoplay: true,
		speed: 500,
		autoplaySpeed: 8e3,
		prevArrow: '<div class="slick-prev"> <svg width="64px" height="64px" viewBox="-5 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#010101"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-421.000000, -1195.000000)" fill="#010101"> <path d="M423.429,1206.98 L434.686,1196.7 C435.079,1196.31 435.079,1195.67 434.686,1195.28 C434.293,1194.89 433.655,1194.89 433.263,1195.28 L421.282,1206.22 C421.073,1206.43 420.983,1206.71 420.998,1206.98 C420.983,1207.26 421.073,1207.54 421.282,1207.75 L433.263,1218.69 C433.655,1219.08 434.293,1219.08 434.686,1218.69 C435.079,1218.29 435.079,1217.66 434.686,1217.27 L423.429,1206.98" id="chevron-left" sketch:type="MSShapeGroup"> </path> </g> </g> </svg></div>',
		nextArrow: '<div class="slick-next"><svg width="64px" height="64px" viewBox="-5 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#010101" stroke="#010101"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-473.000000, -1195.000000)" fill="#010101"> <path d="M486.717,1206.22 L474.71,1195.28 C474.316,1194.89 473.678,1194.89 473.283,1195.28 C472.89,1195.67 472.89,1196.31 473.283,1196.7 L484.566,1206.98 L473.283,1217.27 C472.89,1217.66 472.89,1218.29 473.283,1218.69 C473.678,1219.08 474.316,1219.08 474.71,1218.69 L486.717,1207.75 C486.927,1207.54 487.017,1207.26 487.003,1206.98 C487.017,1206.71 486.927,1206.43 486.717,1206.22" id="chevron-right" sketch:type="MSShapeGroup"> </path> </g> </g> </svg></div>',		
	});


	// Announcement Carousel
	$('.header-announcement-carousel').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 1,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 3000,
		pauseOnHover: false,
		arrows: false,
		prevArrow: '<div class="slick-prev"> <svg width="64px" height="64px" viewBox="-5 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#010101"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-421.000000, -1195.000000)" fill="#010101"> <path d="M423.429,1206.98 L434.686,1196.7 C435.079,1196.31 435.079,1195.67 434.686,1195.28 C434.293,1194.89 433.655,1194.89 433.263,1195.28 L421.282,1206.22 C421.073,1206.43 420.983,1206.71 420.998,1206.98 C420.983,1207.26 421.073,1207.54 421.282,1207.75 L433.263,1218.69 C433.655,1219.08 434.293,1219.08 434.686,1218.69 C435.079,1218.29 435.079,1217.66 434.686,1217.27 L423.429,1206.98" id="chevron-left" sketch:type="MSShapeGroup"> </path> </g> </g> </svg></div>',
		nextArrow: '<div class="slick-next"><svg width="64px" height="64px" viewBox="-5 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#010101" stroke="#010101"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-473.000000, -1195.000000)" fill="#010101"> <path d="M486.717,1206.22 L474.71,1195.28 C474.316,1194.89 473.678,1194.89 473.283,1195.28 C472.89,1195.67 472.89,1196.31 473.283,1196.7 L484.566,1206.98 L473.283,1217.27 C472.89,1217.66 472.89,1218.29 473.283,1218.69 C473.678,1219.08 474.316,1219.08 474.71,1218.69 L486.717,1207.75 C486.927,1207.54 487.017,1207.26 487.003,1206.98 C487.017,1206.71 486.927,1206.43 486.717,1206.22" id="chevron-right" sketch:type="MSShapeGroup"> </path> </g> </g> </svg></div>',	
	});

	// Product Carousel
	$('.product-carousel').slick({
		dots: false,
		infinite: false,
		speed: 300,
		slidesToShow: 4,
		slidesToScroll: 1,
		autoplay: true,
		autoplaySpeed: 3000,
		pauseOnHover: false,
		arrows: true,
		prevArrow: '<div class="slick-prev"> <svg width="64px" height="64px" viewBox="-5 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#010101"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-421.000000, -1195.000000)" fill="#010101"> <path d="M423.429,1206.98 L434.686,1196.7 C435.079,1196.31 435.079,1195.67 434.686,1195.28 C434.293,1194.89 433.655,1194.89 433.263,1195.28 L421.282,1206.22 C421.073,1206.43 420.983,1206.71 420.998,1206.98 C420.983,1207.26 421.073,1207.54 421.282,1207.75 L433.263,1218.69 C433.655,1219.08 434.293,1219.08 434.686,1218.69 C435.079,1218.29 435.079,1217.66 434.686,1217.27 L423.429,1206.98" id="chevron-left" sketch:type="MSShapeGroup"> </path> </g> </g> </svg></div>',
		nextArrow: '<div class="slick-next"><svg width="64px" height="64px" viewBox="-5 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#010101" stroke="#010101"><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"> <g id="Icon-Set" sketch:type="MSLayerGroup" transform="translate(-473.000000, -1195.000000)" fill="#010101"> <path d="M486.717,1206.22 L474.71,1195.28 C474.316,1194.89 473.678,1194.89 473.283,1195.28 C472.89,1195.67 472.89,1196.31 473.283,1196.7 L484.566,1206.98 L473.283,1217.27 C472.89,1217.66 472.89,1218.29 473.283,1218.69 C473.678,1219.08 474.316,1219.08 474.71,1218.69 L486.717,1207.75 C486.927,1207.54 487.017,1207.26 487.003,1206.98 C487.017,1206.71 486.927,1206.43 486.717,1206.22" id="chevron-right" sketch:type="MSShapeGroup"> </path> </g> </g> </svg></div>',		
		responsive: [
			{
				breakpoint: 991,
				settings: {
					slidesToShow: 3,
				}
			},
			{
				breakpoint: 767,
				settings: {
					slidesToShow: 2,
				}
			},
			{
				breakpoint: 575,
				settings: {
					slidesToShow: 2,
				}
			}
		]
	});

	// Testimonial Carousel
	$('.testimonial-carousel').slick({
		dots: false,
		infinite: true,
		speed: 300,
		slidesToShow: 3,
		slidesToScroll: 1, 
		autoplay: true,
		autoplaySpeed: 3000, 
		pauseOnHover:false,
		centerMode: true,
		centerPadding: '0px',
		arrows: true,
		prevArrow: '<div class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>',
		nextArrow: '<div class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>',
		responsive: [
			{
			breakpoint: 992,
			settings: {
				slidesToShow: 2,
			}
			},
			{
			breakpoint: 768,
			settings: {
				slidesToShow: 1,
			}
			},
			{
			breakpoint: 576,
			settings: {
				slidesToShow: 1,   
			}
			} 
		]
	});	

	// Filter Product
	$('.filter-toggle').click(function(){   
		$('.product-sidebar-filter').toggleClass('filter-display');
		$('body').toggleClass('filter-open');
	});
	$('.product-filter-overlay, .filter-close').click(function(){    
		$('.product-sidebar-filter').removeClass('filter-display');
		$('body').removeClass('filter-open');
	});   
		   
	// display Product View
	$('.product-display-mode #grid').click(function(){    
		$('.products').addClass('columns-2');  
		$('.products').removeClass('columns-3'); 
		$('.product-display-mode #grid').addClass('active');  
		$('.product-display-mode #grid_large').removeClass('active');  			
	});
	$('.product-display-mode #grid_large').click(function(){    
		$('.products').addClass('columns-3');  
		$('.products').removeClass('columns-2'); 	
		$('.product-display-mode #grid').removeClass('active');  
		$('.product-display-mode #grid_large').addClass('active');  		
	});

	$('.mobile-view-mode #grid').click(function(){    
		$('.products').addClass('columns-1');  
		$('.products').removeClass('columns-2'); 
		$('.mobile-view-mode #grid').addClass('active');  
		$('.mobile-view-mode #grid_large').removeClass('active');  			
	});
	$('.mobile-view-mode #grid_large').click(function(){    
		$('.products').addClass('columns-2');  
		$('.products').removeClass('columns-1'); 	
		$('.mobile-view-mode #grid').removeClass('active');  
		$('.mobile-view-mode #grid_large').addClass('active');  		
	});
	// Widget Open hide
	// $('.product-widget-title').click(function(){  
	// 	$( this ).toggleClass('open');	
	// 	$( this ).parents(".product-widget-item").toggleClass('open');		
	// });	
	
	// Price-slider		
	$( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 100000,
		values: [ 0, 100000 ],
		slide: function( event, ui ) {
		$( "#amount" ).val( "₹" + ui.values[ 0 ] + " - ₹" + ui.values[ 1 ] );
		}
	});
	$( "#amount" ).val( "₹" + $( "#slider-range" ).slider( "values", 0 ) + " - ₹" + $( "#slider-range" ).slider( "values", 1 ) );

	// Product Gallery
	$('.product-gallery-slider').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		arrows: false,
		fade: true,
		cssEase: "cubic-bezier(0.7, 0, 0.3, 1)",
		touchThreshold: 100,
		pauseOnHover: false,
		touchMove: false,
		draggable: false,
		autoplay: false,
		pauseOnHover: true,
		adaptiveHeight: true,
		asNavFor: '.product-gallery-thumbs'
	  });
	  $('.product-gallery-thumbs').slick({
		slidesToShow: 4,
		slidesToScroll: 1,
		asNavFor: '.product-gallery-slider',
		vertical: true,
		  verticalSwiping: true,
		dots: false,
		arrows: false,
		//centerMode: true,
		//centerPadding: '0px',
		focusOnSelect: true,
		responsive: [				
			{
			breakpoint: 767,
			settings: {
				vertical: false,
				  verticalSwiping: false,
			}
			},				 
		]
	  });	
	  
	// Quantity Button plus minus		 
	$(".qty-btn").off('click.changeQuantity').on('click.changeQuantity', function(e) {
		e.preventDefault();
		e.stopPropagation();	
		let oldValue = $('.qty').val(),
			newVal = 1;
		let totalinvent = $('.qty').attr('maxlength');	
		if($(this).hasClass('inc')) {
		if(parseInt(oldValue) < parseInt(totalinvent)) {
		newVal = parseInt(oldValue) + 1;
		}
		}
		else if(oldValue > 1) {
		newVal = parseInt(oldValue) - 1;
		}

		$(".qty").val(newVal);	
		
	});	
	
	// Product Float
	$(window).scroll(function () {
		if ($('.single-product-details').length) {
			let producfloat = $('.single-product-imagesummery').height();
			let windowpos = $(window).scrollTop();
			if (windowpos >= producfloat) {
				$('.product-float-section').addClass('product-float-visible');
			} else {
				$('.product-float-section').removeClass('product-float-visible');
			}
		}
	});	

	// Edit Account Info
	$(".enable-disable-value").click(function() {     
		$(this).parents('.account-section').toggleClass('open'); 	
		$(this).find('.enable-value').toggleClass('d-none'); 
		$(this).find('.disable-value').toggleClass('d-none'); 
	});	
	$("#add-new-address").click(function() {     
		$(this).addClass('d-none'); 
	});	
	$('#new-address .cancel').click(function(){  
		$('#new-address').toggleClass('show');
		$("#add-new-address").removeClass('d-none');
	 });

	

}(jQuery));	

 // Copy Link
 function myFunction() {
	// Get the text field
	let copyText = document.getElementById("myinvite");
  
	// Select the text field
	copyText.select();
	copyText.setSelectionRange(0, 99999); // For mobile devices
  
	 // Copy the text inside the text field
	navigator.clipboard.writeText(copyText.value);
  
	// Alert the copied text
	alert("Copied the text: " + copyText.value);
}; 