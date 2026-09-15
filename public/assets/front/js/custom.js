 // your custome placeholder goes here!
    var ph = "Search for style",
    searchBar = $("#search"),
    // placeholder loop counter
    phCount = 0;

    // function to return random number between
    // with min/max range
    function randDelay(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    // function to print placeholder text in a
    // 'typing' effect
    function printLetter(string, el) {
        // split string into character seperated array
        var arr = string.split(""),
            input = el,
            // store full placeholder
            origString = string,
            // get current placeholder value
            curPlace = $(input).attr("placeholder"),
            // append next letter to current placeholder
            placeholder = curPlace + arr[phCount];

        setTimeout(function () {
            // print placeholder text
            $(input).attr("placeholder", placeholder);
            // increase loop count
            phCount++;
            // run loop until placeholder is fully printed
            if (phCount < arr.length) {
                printLetter(origString, input);
            }
            // use random speed to simulate
            // 'human' typing
        }, randDelay(50, 90));
    }

    // function to init animation
    function placeholder() {
        $(searchBar).attr("placeholder", "");
        printLetter(ph, searchBar);
    }

    placeholder();
    $(".submit").click(function (e) {
        phCount = 0;
        e.preventDefault();
        placeholder();
    });

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
                    items: 1, // For medium screens
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
                    items: 1, // For medium screens
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
                    items: 1, // For medium screens
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
                    items: 1, // For medium screens
                },
                1000: {
                    items: 4, // For large screens
                },
            },
        });
    });
        
    $(document).ready(function () {
        $("#productListing").owlCarousel({
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
                    items: 4, // For large screens
                },
            },
        });
    });

    $("#our_store").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            autoplay: true,
            autoplayTimeout: 2000,
            autoplayHoverPause: true,
            responsive: {
                0: { items: 1 },
                600: { items: 1 },
                1000: { items: 3 }
            }
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
       
        $(".sign-in").click(function () {
            $(".signup-in-sec").addClass("intro");
        });
    
        $(".sign-in").click(function () {
            $(".sign-in-sec").addClass("hide-sign");
        });
    
        $(".sign-in-btn").click(function () {
            $(".signup-in-sec").removeClass("intro");
        });
    
        $(".sign-in-btn").click(function () {
            $(".sign-in-sec").removeClass("hide-sign");
        });
    
        $(".four-grid").click(function () {
            $(".side-filter-box").toggleClass("filter-box-show");
        });
    
        $(".two-two-grid").click(function () {
            $(".product-sec.women-seller.women-seller-slider .row > div").each(function () {
                if ($(this).hasClass("col-md-3")) {
                    $(this).removeClass("col-md-3").addClass("col-md-6");
                } else if ($(this).hasClass("col-md-6")) {
                    $(this).removeClass("col-md-6").addClass("col-md-3");
                }
            });
        });

        $(".three-three-grid").click(function () {
            $(".product-sec.women-seller.women-seller-slider .row > div").each(function () {
                if ($(this).hasClass("col-md-3")) {
                    $(this).removeClass("col-md-3").addClass("col-md-4");
                } else if ($(this).hasClass("col-md-6")) {
                    $(this).removeClass("col-md-6").addClass("col-md-4");
                }else if ($(this).hasClass("col-md-4")) {
                    $(this).removeClass("col-md-4").addClass("col-md-3");
                }
            });
        });


        $('#sort_by').change(function () {
            let sortValue = $(this).val();
    
            //console.log(sortValue);
    
            $.ajax({
                url: '/product/product-sort-filter', // Replace with your route
                type: 'GET',
                data: {
                    sort_by: sortValue
                },
                success: function (response) {
    
                    console.log(response.html);
                    // Replace product list section with filtered results
                    $('#product_list').html(response.html);
                }
            });
        });
    });
    
    
    // /* Custom cart js */

    //       $(document).ready(function() {
    //         // On "Add to Cart" button click
    //         $('.addToCartBtn').on('click', function(e) {
    //             e.preventDefault();
                
    //             var quantity = parseInt($('.quantityInput').val()) || 1;
    //             //var productName = "{{ $product->name }}"; 
    //             var productName =$('#productTitle').text();
    //             "@foreach($product->product_main_images as $image)"
    //                 "@if($image['graphic_type'] == 'image')"
    //                     var productImage = "uploads/products/{{ str_replace('/public', '', $image['graphic']) }}";
    //                 "@endif"
    //             "@endforeach"
    //             var productId = "{{ $product->id }}";
    //             var productWeight = "{{ $product->weight }}";
    //             var productWeightType = "{{ $product->weight_type }}";
    //             // var fullPrice = {{ $product->buying_price }};
    //             var activeVariant = $('.variant-list .active');
    //             var selectedVid = activeVariant.attr('data-vid');
    //             var priceTag = $('.price-tag');
    //             //var discountedPrice = "{{ $product->selling_price }}";
    //             var discountedPrice = parseFloat(document.getElementById("productPrice").innerText.trim().replace(/[^\d.]/g, ''));
    //             var originalPrice = "{{ $product->buying_price }}";
    //             var discountType = "{{ $product->discount_type }}";
    //             var discountAmount = "{{ $product->discount }}";
                
    //             // Safe checks for size, color, and pattern
    //             var selectedSizeEl = $('li[data-type="Size"].active');
    //             var selectedColorEl = $('li[data-type="color"].active');
    //             var selectedPatternEl = $('li[data-type="Pattern"].active');

    //             var selectedSize = selectedSizeEl.length ? selectedSizeEl.attr('data-value') : null;
    //             var selectedColor = selectedColorEl.length ? selectedColorEl.attr('data-value') : null;
    //             var selectedPattern = selectedPatternEl.length ? selectedPatternEl.attr('data-value') : null;

    //             // Get background image from selected color element if exists
    //             var colorImageUrl = productImage;
    //             if (selectedColorEl.length) {
    //                 var bgImg = selectedColorEl.css('background-image');
    //                 var match = /url\(["']?([^"')]+)["']?\)/.exec(bgImg);
    //                     if (match) {
    //                         colorImageUrl = match[1];
    //                     }
    //             }

                
    //             // If size variant is required, validate it
    //             if ($('li[data-type="Size"]').length && !selectedSize) {
    //                 alert("Please select a size.");
    //                 return;
    //             }
    //             // Build final product data object
    //             var productData = {
    //             name: productName,
    //             quantity: quantity,
    //             size: selectedSize,
    //             color: selectedColor,
    //             pattern: selectedPattern,
    //             // price: productPrice,
    //             price: discountedPrice,
    //             image: colorImageUrl,

    //             productId: productId,
    //             weight: productWeight,
    //             weightType: productWeightType,
    //             productVariantId: selectedVid,
    //             originalPrice: originalPrice,
    //             discountType: discountType,
    //             discountAmount: discountAmount
    //             };

    //             var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    //             var existingItemIndex = cartItems.findIndex(function(item) {
    //             return item.name === productData.name &&
    //                     item.size === productData.size &&
    //                     item.color === productData.color &&
    //                     item.pattern === productData.pattern;
    //             });
                

    //             if (existingItemIndex > -1) {
    //             cartItems[existingItemIndex].quantity = 
    //                 parseInt(cartItems[existingItemIndex].quantity) + parseInt(productData.quantity);
    //             } else {
    //             cartItems.push(productData);
    //             }

    //             localStorage.setItem('cartItems', JSON.stringify(cartItems));
    //             console.log('Cart Items saved:', cartItems);
    //             //console.log('Image:', colorImageUrl);
    //             displayCartItems();
    //             var modal = new bootstrap.Modal(document.getElementById('addtocatt'));
    //             modal.show();

    //             //$('.modal-overlay, #addtocatt').fadeIn();
    //         });

    //         // $('.close-modal').on('click', function () {
    //         //     $('#productModal, .modal-overlay').hide();
    //         // });

    //         // $('.modal-overlay').on('click', function () {
    //         //     $('#productModal, .modal-overlay').hide();
    //         // });

    //         // Remove individual product block
    //         $(document).on('click', '.close-product', function() {
    //             var index = $(this).data('index');
    //             console.log("card delete index"+index);
    //             var cartItems = JSON.parse(localStorage.getItem('cartItems'));
    //             cartItems.splice(index, 1);
    //             localStorage.setItem('cartItems', JSON.stringify(cartItems));
    //             displayCartItems();
    //         });

    //         // $('#view-bag').on('click', function() {
    //         //     window.location.href = '/cart';
    //         // });

    //         // $('#checkout').on('click', function() {
    //         //     window.location.href = '/checkoutBag';
    //         // });

    //       function displayCartItems() {
    //             var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    //             var productListContainer = $('#productListContainer');
    //             productListContainer.empty(); // Assuming it's a <div>, not a <ul>
            
    //             cartItems.forEach(function(item, index) {
    //                 var productHTML = `
    //                     <div class="add-cart-list" data-index="${index}">
    //                         <div class="ac-l">
    //                             <div class="ac-l-left">
    //                                 <figure><img src="${item.image}" /></figure>
    //                                 <a class="trash-icon close-product" data-index="${index}">Delete</a>
    //                             </div>
    //                             <div class="ac-l-right">
    //                                 <span class="offer-tag"><i class="fa-regular fa-clock"></i> Limited time offer</span>
    //                                 <h4>${item.name}</h4>
    //                                 <p class="s-text">Size: ${item.size}</p>
    //                                 <p class="s-text">Color: ${item.color}</p>
    //                                 <p class="s-text">Pattern: ${item.pattern}</p>
    //                             </div>
    //                         </div>
    //                         <div class="ac-r">
    //                             <div class="price-tag"><span>₹${item.price * item.quantity}</span></div>
    //                             <div class="input-increment">
    //                                 <span class="input-number-decrement addToDecQtyBtn">–</span>
    //                                 <input class="input-number quantityInput" type="text" data-name="${item.name}" data-size="${item.size}" data-color="${item.color}" data-pattern="${item.pattern}" value="${item.quantity}" min="1" max="10" />
    //                                 <span class="input-number-increment addToNewQtyBtn">+</span>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 `;
    //                 productListContainer.append(productHTML);
    //             });
            
    //             //bindQuantityButtons(); // Call after DOM is updated
    //         }

            
    //         // Display cart items when modal is opened
    //         if (localStorage.getItem('cartItems')) {
    //             displayCartItems();
    //         }
    //     });
        
        // function bindQuantityButtons() {
        //     $('.input-number-increment').off('click').on('click', function () {
        //         let input = $(this).siblings('input');
        //         let val = parseInt(input.val()) || 0;
        //         if (val < parseInt(input.attr('max'))) {
        //             input.val(val + 1).trigger('change');
        //         }
        //     });
        
        //     $('.input-number-decrement').off('click').on('click', function () {
        //         let input = $(this).siblings('input');
        //         let val = parseInt(input.val()) || 0;
        //         if (val > parseInt(input.attr('min'))) {
        //             input.val(val - 1).trigger('change');
        //         }
        //     });
        // }
        
