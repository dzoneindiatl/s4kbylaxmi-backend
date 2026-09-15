    function selectVariant(el) {

        const {
            type,
            value,
            id,
            vid: variant_id,
            productid: product_id
        } = el.dataset;

        // document.querySelectorAll(`li[data-type="${type}"]`).forEach(item =>
        //     item.classList.remove('active')
        // );
        // el.classList.add('active');

        // update active varinet
        console.log('variant_id ', variant_id);

        $('.s-variant').removeClass('active');
        $(`.s-variant[data-vid="${variant_id}"]`).addClass('active');
        // update active varinet 

        // Update selected value display
        const selectedSpan = document.getElementById(`selected-value-${id}`);
        if (selectedSpan) selectedSpan.textContent = value;

        // Collect selected variant IDs
        const selectedVariantIds = $('.s-variant.active').map(function () {
            return $(this).data('vid');
        }).get();


        checkItemInCart();
        $.ajax({
            url: getVarient,
            type: 'POST',
            data: JSON.stringify({
                product_id,
                vsku: selectedVariantIds,
                variant_id,
                _token: window.csrfToken
            }),
            contentType: 'application/json',
            success: function (response) {
                const combination = response?.combination;
                if (!combination) return;

                const {
                    selling_price,
                    price,
                    sku,
                    discount,
                    discount_type,
                    qty,
                } = combination;

                // Calculate and display discount
                let discountText = "";
                if (discount_type === 'percentage') {
                    discountText = `${Math.floor(discount)}% OFF`;
                } else if (discount_type === 'flat' && price > 0) {
                    discountText = `₹${Math.floor(discount)} OFF`;
                }

                $('#productSku').html(sku);
                $('#productPrice').text(`₹${Math.floor(selling_price)}`);
                $('#pdpStrikedMrp').text(`₹${Math.floor(price)}`);
                var discount_product = price - selling_price;
                $('#discountShow').text(`₹ ${Math.floor(discount_product)} OFF`);

                // if (discount_type) {
                //     $('#pdpDiscountRight').text(discountText).show();
                // } else {
                //     $('#pdpDiscountRight').hide();
                // }

                const $addToCartBtn = $('.addToCartBtn');
                // $addToCartBtn.attr('data-sku', sku);
                $addToCartBtn.attr('data-price', price);
                $addToCartBtn.attr('data-saleprice', selling_price);
                $addToCartBtn.attr('data-discounttype', discount_type);
                $addToCartBtn.attr('data-discount', discount);

                if(qty==0){
                    $('.varient_less_than_min_qty_notice').html('Out Of Stock');
                    $('.add-to-cart').addClass('d-none');
                } else if(parseInt(minSellingQty) > parseInt(qty)){
                    $('.varient_less_than_min_qty_notice').html('<b>Only '+qty+' items are left</b>');
                    $('.add-to-cart').removeClass('d-none')
                }  else {
                    $('.varient_less_than_min_qty_notice').html('');
                    $('.add-to-cart').removeClass('d-none')
                }

                // Update product image slider
                if (response.images && response.images.length > 0) {
                    initCustomSlider(response.images);
                    //$('#carousel-wrapper').hide();
                    //$('#custom-slider').show();
                }
            },
            error: function (xhr) {
                console.error("Variant fetch error:", xhr.responseText);
            }
        });
    }


    checkItemInCart();

    function initCustomSlider(images = []) {
        if (!images.length) return;        
        $('.detail-grid-img').remove();
        $('#sync1').remove(); $('#sync2').remove();

        //console.log(images);
            
            var thumbnailRow = ""; 
            var slideRow = "";
            images.forEach((img, index) => {
                if (img.graphic_type === 'image') {
                    thumbnailRow += `
                        <li><img src="${img.graphic}" alt="${index}"></li>`;

                    slideRow += `<div class="product-gallery-image"><a data-fancybox="gallery" href="javascript:void(0)"><img src="${img.graphic}" alt=""></a></div>`;
                } 
            });

        console.log(' thumbnailRow ', thumbnailRow);
        console.log(' slideRow ', slideRow);

        if ($.fn.slick) {
            setTimeout(function() {
                if ($(".product-gallery-thumbs").hasClass('slick-initialized')) {
                    $(".product-gallery-thumbs").slick('unslick');
                }
                $(".product-gallery-thumbs").html(thumbnailRow).slick({
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
            }, 100);

            setTimeout(function() {
                if ($(".product-gallery-slider").hasClass('slick-initialized')) {
                    $(".product-gallery-slider").slick('unslick');
                }
                $(".product-gallery-slider").html(slideRow).slick({
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
            }, 100);
        } else {
            console.error("Slick plugin not loaded");
        }

    }
       

    
    function checkItemInCart() {
        const productId = $('#product_id').val();
        const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

        let selected = {};

        $('li.active[data-type]').each(function () {
            let type = ($(this).data('type') || '').toLowerCase(); // Normalize key
            let value = $(this).data('value');
            selected[type] = value;
        });

        let exists = cartItems.some(item => {
            if (item.productId != productId) return false;

            let variant = item.selectedVariants || {};
            let keys1 = Object.keys(selected);
            let keys2 = Object.keys(variant);

            if (keys1.length !== keys2.length) return false;

            return keys1.every(k => variant[k] === selected[k]);
        });

        if (exists) {
            $('.addToCartText').html("Go To Cart");
        } else {
            $('.addToCartText').html("Add To Cart");
        }
    }
