function getSelectedVariants(cartItems = []) {
    let selected = {};

    const productId = $('#product_id').val();

    $('li.active[data-type]').each(function () {
        let type = $(this).data('type').toLowerCase();
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


    return exists ? null : selected;
}

$(document).on('click', '.addToCartBtn', function () {
    let button = $(this);
    let productId      = button.data('id');
    let productName    = button.data('name');
    let price          = button.data('price');
    let sellingPrice   = button.data('saleprice');
    let discountType   = button.data('discounttype');
    let discountAmount = button.data('discount');
    let rawTaxArr      = button.attr('data-tax-arr');
    let decodedJson    = decodeHtml(rawTaxArr); // This will replace &quot; with "
    let taxArr         = JSON.parse(decodedJson);    // Now this should work!
    let tax_price = 0;
    let tax_option = "inclusive";
    let tax_id = "";
    let tax_rate = 0;
    let taxprice = 0;
    let tax_type = 'flat';
    if (taxArr.length > 0) {
        tax_option = taxArr[0]['tax_option'];
        taxArr.forEach((tax) => {
            tax_type = tax.tax_type;
            if (tax.tax_type === "flat") {
                tax_id = tax.id;
                tax_rate = (tax.tax_rate > 0 || String(tax.tax_rate).toLowerCase() !== "no tax") ? tax.tax_rate : 0;
                if(tax_option == "inclusive"){
                    taxprice = 1 + (tax_rate / 100);
                    tax_price = tax_rate ? (sellingPrice / taxprice) : 0;
                    tax_price = (sellingPrice / taxprice) - tax_price;
                }else{
                    tax_price = tax_rate ? ((sellingPrice * tax_rate) / 100) : 0;
                }
            } else if (tax.tax_type === "floating") {
                // const taxRanges = [
                //     { from: config.Tax.taxFromOne, to: config.Tax.taxToOne },
                //     { from: config.Tax.taxFromTwo, to: config.Tax.taxToTwo },
                //     { from: config.Tax.taxFromThree, to: config.Tax.taxToThree },
                //     { from: config.Tax.taxFromFour, to: config.Tax.taxToOneFour },
                //     { from: config.Tax.taxFromFive, to: config.Tax.taxToFive },
                //     { from: config.Tax.taxFromSix, to: config.Tax.taxToSix },
                //     { from: config.Tax.taxFromSeven, to: config.Tax.taxToSeven },
                // ];
                const taxRanges = [
                    { from: 0, to: 2500 },
                    { from: 2501, to: 100000 },
                ];
                for (const range of taxRanges) {
                    if (tax.tax_from >= range.from && tax.tax_to <= range.to) {
                        tax_id = tax.id;
                        tax_rate = (tax.tax_rate > 0 || String(tax.tax_rate).toLowerCase() !== "no tax") ? tax.tax_rate : 0;
                         if(tax_option == "inclusive"){
                            taxprice = 1 + (tax_rate / 100);
                            tax_price = tax_rate > 0 ? (sellingPrice / taxprice) : 0;
                            tax_price = (sellingPrice / taxprice) - tax_price;
                            break; // exit loop once match is found
                        }else{
                            tax_price = tax_rate > 0 ? ((sellingPrice * tax_rate) / 100) : 0;
                            break; // exit loop once match is found
                        }
                    }
                }
            }
        });
    }
    
    let quantity       = 1;
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];


    let selectedVariants = getSelectedVariants(cartItems);
    if (!selectedVariants) {
        let modal = new bootstrap.Modal(document.getElementById('addtocatt'));
        modal.show();
        return;
    }

    if ($('li[data-type="Size"]').length && !selectedVariants['size']) {
        alert("Please select a size.");
        return;
    }

    let productData = {
        randomId: 'prod_' + Date.now() + '_' + Math.floor(Math.random() * 10000),
        productId,
        name: productName,
        quantity,
        price,
        sellingPrice,
        discountType,
        discountAmount,
        image: document.querySelector('.default-image').src,
        selectedVariants,
        tax_id,
        tax_rate,
        tax_price,
        tax_option,
        tax_type,
    };

    $('.addToCartText').html("Go To Cart");
    if (isLoggedIn) {
        isLoginUser(productData.productId,1,selectedVariants);
    }


    showFlashMessage("Product added to cart successfully");
    cartItems.push(productData);
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
    displayCartItems();
    let modal = new bootstrap.Modal(document.getElementById('addtocatt'));
    modal.show();
});

function displayCartItems() {
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    let productListContainer = $('.productListContainer');


    productListContainer.empty();
    $('.center-main').html(cartItems.length);

    if(cartItems.length == 0){
        $(".add-cart-footer").hide();
    }else{
         $(".add-cart-footer").show();
    }


    cartItems.forEach(function(item, index) {
        let variants = item.selectedVariants || {};
        let variantHTML = '';

        for (const [key, value] of Object.entries(variants)) {
            if (value) {
                variantHTML += `<p class="s-text">${key.charAt(0).toUpperCase() + key.slice(1)}: ${value}</p>`;
            }
        }

        let itemTotal = item.price * item.quantity;
        let price = item.price;
        let sellingPrice = item.sellingPrice;
        // let discountAmount = item.discountAmount;
        let discountType = item.discountType;

        let discountAmount = parseFloat(item.discountAmount); 
        if (Number.isInteger(discountAmount)) {
            discountAmount = discountAmount.toFixed(0); // integer show kare
        } else {
            discountAmount = discountAmount.toString(); // decimal as it is show kare
        }
        
        let discountText = '';
        if (discountType === 'flat') {
            discountText = `${Math.floor(discountAmount)} Rs Off`;
        } else if (discountType === 'percentage') {
            discountText = `${Math.floor(discountAmount)}% Off`;
        }
        
        let discountHTML = discountText ? `<span class="off-tag">${discountText}</span>` : '';
        let productHTML = `
            <div class="add-cart-list" data-index="${index}">
                <div class="ac-l">
                    <div class="ac-l-left">
                        <figure><img src="${item.image}" alt="${item.name}"/></figure>
                        <a href="javascript:void('0');" data-index="${index}" class="trash-icon close-product">Delete</a>
                    </div>
                    <div class="ac-l-right">
                        <span class="offer-tag"><i class="fa-regular fa-clock"></i> Limited time offer</span>
                        <h4>${item.name}</h4>
                        ${variantHTML}
                    </div>
                </div>
                <div class="ac-r">
                    <div class="price-tag"><span>₹${Math.floor(sellingPrice)}</span></div>
                     ${discountHTML}
                    <div class="input-increment">
                        <span class="input-number-decrement addToDecQtyBtn">–</span>
                        <input class="input-number quantityInputs" 
                            type="text" 
                            value="${item.quantity}" 
                            min="1" max="10"
                            data-randomId="${item.randomId}"
                        />
                        <span class="input-number-increment addToNewQtyBtn">+</span>
                    </div>
                     
                </div>
            </div>
        `;
        productListContainer.append(productHTML);
    });

    // localStorage.setItem('applied_coupon',[]);
    // localStorage.setItem('coupon_discount',0);
    priceCalculation();
}

function priceCalculation(){
    let couponDiscount = parseFloat(localStorage.getItem('coupon_discount')) || 0;
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    let cartData = cartItems.map(item => {
        let taxRate = (item.tax_rate > 0 || String(item.tax_rate).toLowerCase() !== "no tax") ? item.tax_rate : 0;
        let tax_price = 0;
        let tax_option = item.tax_option;  // Use item's tax_option if available
        let netPrice = 0;
        if(couponDiscount > 0){
            netPrice = item.sellingPrice - (couponDiscount / cartItems.length);
        }else{
            netPrice = item.sellingPrice;  
        }
        if (item.tax_type === "flat") {
            if (tax_option === "inclusive") {
                let divisor = 1 + (taxRate / 100);
                tax_price = taxRate ? netPrice - (netPrice / divisor) : 0;
            } else {
                tax_price = taxRate ? ((netPrice * taxRate) / 100) : 0;
            }
        } else if (item.tax_type === "floating") {
            if (tax_option === "inclusive") {
                let divisor = 1 + (taxRate / 100);
                tax_price = taxRate ? netPrice - (netPrice / divisor) : 0;
            } else {
                tax_price = taxRate ? ((netPrice * taxRate) / 100) : 0;
            }
        }
        return {
            ...item,
            tax_price: tax_price
        };
    });
    localStorage.setItem('cartItems', JSON.stringify(cartData));
    cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    let totalMrp = 0;
    let totalDiscount = 0;
    let totalTaxPrice = 0;
    let taxOption = 'inclusive';
    cartItems.forEach(function(item) {
        totalMrp += item.price * item.quantity;
        if(item.discountType == "flat") {
            totalDiscount += parseInt(item.discountAmount);
        }else if(item.discountType == "percentage"){
            totalDiscount += ((parseInt(item.discountAmount) * totalMrp) / 100);
        }
        taxOption = item.tax_option;
        totalTaxPrice += item.tax_price;
    });
    let subTotal = totalMrp-totalDiscount;
    let grandTotal = subTotal-couponDiscount;
    let taxableAmount = grandTotal;
    if (taxOption == 'inclusive') {
       taxableAmount = grandTotal - totalTaxPrice;
    }else if (taxOption == 'exclusive') {
       taxableAmount = grandTotal;
    }
    let finalAmount = grandTotal;
    console.log("------cartItems-------", cartItems);
    console.log("-----finalAmount------", finalAmount);
    console.log("-----couponDiscount------", couponDiscount);
    // Update Summary
    if (totalMrp > 0) {
        $("#totalMrp").html(`₹${Math.floor(totalMrp)}`).show();
    } else {
        $("#totalMrp").html("0"); // Or use .text('') depending on your layout
    }
    if (totalDiscount > 0) {
        $("#totalDiscount").html(`-₹${totalDiscount.toFixed(2)}`).show();
    } else {
        $("#totalDiscount").html("0"); // Or use .text('') depending on your layout
    }
    $('#subTotal').html(`₹${(subTotal).toFixed(2)}`);
    if (couponDiscount > 0) {
        $("#couponDiscount").html(`-₹${couponDiscount.toFixed(2)}`).show();
    } else {
        $("#couponDiscount").html("0"); // Or use .text('') depending on your layout
    }
    $('#grandTotal').html(`₹${(grandTotal).toFixed(2)}`);
    $('#taxableAmount').html(`₹${(taxableAmount).toFixed(2)}`);
    if (taxOption == 'inclusive') {
        $("#taxPrice").html(`+₹${totalTaxPrice.toFixed(2)}`).show();
    }else if (taxOption == 'exclusive') {
        $("#taxPrice").html(`+₹${totalTaxPrice.toFixed(2)}`).show();
    } else {
        $("#taxPrice").html("0"); // Or use .text('') depending on your layout
    }
    if (finalAmount > 0) {
        //$('.finalAmount').html(`₹${finalAmount.toFixed(2)}`);
        if (taxOption == 'inclusive') {
            $('.finalAmount').html(`₹${Math.floor(finalAmount)}`);
        }else if (taxOption == 'exclusive') {
            $('.finalAmount').html(`₹${Math.floor(finalAmount+totalTaxPrice)}`);
        }
        $('.checkoutButton').removeClass('disabled-link');
    } else {
        $('.finalAmount').html(`₹0`);
        $('.checkoutButton').addClass('disabled-link');
    }
}


$(document).on('click', '.close-product', function () {
    var index = $(this).data('index');
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    if (cartItems[index]) {
        var productId = cartItems[index].productId;
        var selectedVariants = cartItems[index].selectedVariants || {};
        isLoginUser(productId, 0, selectedVariants, 'remove');
        cartItems.splice(index, 1);
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        displayCartItems();

        // Check if current active variant is same as removed one
        let currentSelected = {};
        $('li.active[data-type]').each(function () {
            let type = $(this).data('type').toLowerCase();
            let value = $(this).data('value');
            currentSelected[type] = value;
        });

        let isSameVariant =
            Object.keys(selectedVariants).length === Object.keys(currentSelected).length &&
            Object.keys(selectedVariants).every(key => selectedVariants[key] === currentSelected[key]);

        // If same variant is removed, revert button
        if (isSameVariant) {
            $('.addToCartText').html("Add To Cart");
        }

        localStorage.setItem('applied_coupon',[]);
        localStorage.setItem('coupon_discount',0);
       showFlashMessage("Product removed from cart", "warning");

    }
});

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

$(document).off('click', '.addtoWishList').on('click', '.addtoWishList', function() {
    const productId = this.getAttribute('data-product-id');
    const heartIcon = this.querySelector('i');

    if (!isLoggedIn) {
        const loginModal = new bootstrap.Modal(document.getElementById('login'));
        loginModal.show();
        return;
    }

    fetch(addToWish, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => {
        if (!response.ok) throw new Error("Something went wrong");
        return response.json();
    })
    .then(data => {
        if (data.status === 'added') {
            showFlashMessage("Product added in wishlist");
            heartIcon.classList.remove('fa-regular');
            heartIcon.classList.add('fa-solid');
        } else if (data.status === 'removed') {
            showFlashMessage("Product remove in wishlist","warning");
            heartIcon.classList.remove('fa-solid');
            heartIcon.classList.add('fa-regular');
        }
        //showToastr(data.status, data.message);
        localStorage.setItem('wishlistCount', JSON.stringify(data.wishlistCount));
        displayWishlistItem();
    })
    .catch(error => {
        console.error("Wishlist error:", error);
    });
});
displayWishlistItem();
function displayWishlistItem() {
    //let wishlistCount = JSON.parse(localStorage.getItem('wishlistCount')) || 0;
    let wishlistCount = (localStorage.getItem('wishlistCount') && localStorage.getItem('wishlistCount') != "undefined") ? JSON.parse(localStorage.getItem('wishlistCount')) : 0;
    $(".wishlist-count").html(wishlistCount);
}

$(document).off('click', '.addToNewQtyBtn, .addToDecQtyBtn').on('click', '.addToNewQtyBtn, .addToDecQtyBtn', function(e) {
    e.preventDefault();
    var input = $(this).closest('.input-increment').find('.quantityInputs');
    var randomId = input.data('randomid');
    var cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    var existingItemIndex = cartItems.findIndex(function(item) {
        return item.randomId == randomId;
    });

    if (existingItemIndex > -1) {
        var currentQty = parseInt(cartItems[existingItemIndex].quantity);
        var minQty = parseInt(input.attr('min')) || 1;
        var maxQty = parseInt(input.attr('max')) || 10;

        if ($(this).hasClass('addToNewQtyBtn') && currentQty < maxQty) {
            currentQty++;
        } else if ($(this).hasClass('addToDecQtyBtn') && currentQty > minQty) {
            currentQty--;
        }

        localStorage.setItem('applied_coupon',[]);
        localStorage.setItem('coupon_discount',0);
        cartItems[existingItemIndex].quantity = currentQty;
        isLoginUser(cartItems[existingItemIndex].productId, currentQty, cartItems[existingItemIndex].selectedVariants);
        cartItems[existingItemIndex].total = parseFloat(cartItems[existingItemIndex].price) * currentQty;
    }

    localStorage.setItem('cartItems', JSON.stringify(cartItems));
    displayCartItems();
});

function isLoginUser(productId,quantity,selectedVariants,type=null){
    if(isLoggedIn){
         $.ajax({
            url: addToCart,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: productId,
                quantity: quantity,
                selected_variants:selectedVariants,
                addType : type
            },
            success: function (response) {
              console.log('');
            },
            error: function (xhr) {
                alert('Failed to add to cart. Please try again.');
            }
        });
    }
    
}

$(document).on('click', '.view-more-link', function () {
    getCoupon()
});

function getCoupon(){
    $.post(getCouponUrl, {
        cart_items: JSON.parse(localStorage.getItem('cartItems')) || [],
        _token: $('meta[name="csrf-token"]').attr('content'),
    }, function(response) {
        if (response.status) {
            $(".offer-available").html(response.coupons.length + " Offers Available");
            $('#all_coupons').empty();          
            $('#static_offers').empty();

            let offerCount = 0;

            response.coupons.forEach(function(coupon) {
                let disabled = coupon.is_applicable ? '' : 'apply-button-disabled';
                let reasonMsg = `<p class="text-danger small">${coupon.reason}</p>`;

                let html = 
                    `<div class="coupan-box ${!coupon.is_applicable ? 'bg-light text-muted' : ''}">
                        <span class="tag-off">${coupon.discount_type === 'percentage' ? coupon.discount_value + '% off' : 'Flat ₹' + coupon.discount_value + ' off'}</span>
                        <div class="coupan-box-space">
                            <div class="coupan-box-inner">
                                <div class="cb-left">
                                    <h5>${coupon.code}</h5>
                                    <span>Save ₹${coupon.discount_amount}</span>
                                </div>
                                <div class="cb-right">
                                    <a class="apply-btn coupon-apply ${disabled}" data-code="${coupon.code}">Apply</a>
                                </div>
                            </div>
                            <p>${coupon.description}</p>
                            ${reasonMsg}
                        </div>
                    </div>`;
                ;
                $('#all_coupons').append(html);
            });

            let staticOffers = [];

            response.coupons.slice(0, 2).forEach(coupon => {
                staticOffers.push(coupon.description);
            });

            staticOffers.forEach(function(offerText) {
                let offerHtml = 
                    `<p>
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="30" height="30" x="0" y="0" viewBox="0 0 512.003 512.003"
                            style="enable-background: new 0 0 512 512;" xml:space="preserve" class="">
                            <g>
                                <path
                                    d="M477.958 262.633a15.004 15.004 0 0 1 0-13.263l19.096-39.065c10.632-21.751 2.208-47.676-19.178-59.023l-38.41-20.38a15.005 15.005 0 0 1-7.796-10.729l-7.512-42.829c-4.183-23.846-26.241-39.87-50.208-36.479l-43.053 6.09a15.004 15.004 0 0 1-12.613-4.099l-31.251-30.232c-17.401-16.834-44.661-16.835-62.061 0L193.72 42.859a15.01 15.01 0 0 1-12.613 4.099l-43.053-6.09c-23.975-3.393-46.025 12.633-50.208 36.479l-7.512 42.827a15.008 15.008 0 0 1-7.795 10.73l-38.41 20.38c-21.386 11.346-29.81 37.273-19.178 59.024l19.095 39.064a15.004 15.004 0 0 1 0 13.263L14.95 301.699c-10.632 21.751-2.208 47.676 19.178 59.023l38.41 20.38a15.005 15.005 0 0 1 7.796 10.729l7.512 42.829c3.808 21.708 22.422 36.932 43.815 36.93 2.107 0 4.245-.148 6.394-.452l43.053-6.09a15 15 0 0 1 12.613 4.099l31.251 30.232c8.702 8.418 19.864 12.626 31.03 12.625 11.163-.001 22.332-4.209 31.03-12.625l31.252-30.232c3.372-3.261 7.968-4.751 12.613-4.099l43.053 6.09c23.978 3.392 46.025-12.633 50.208-36.479l7.513-42.827a15.008 15.008 0 0 1 7.795-10.73l38.41-20.38c21.386-11.346 29.81-37.273 19.178-59.024l-19.096-39.065zm-13.923 72.002-38.41 20.38c-12.246 6.499-20.645 18.057-23.04 31.713l-7.512 42.828a15.038 15.038 0 0 1-16.987 12.342l-43.053-6.09c-13.73-1.945-27.316 2.474-37.281 12.113L266.5 478.152a15.04 15.04 0 0 1-20.997 0l-31.251-30.232c-8.422-8.147-19.432-12.562-30.926-12.562-2.106 0-4.229.148-6.355.449l-43.053 6.09a15.042 15.042 0 0 1-16.987-12.342l-7.513-42.829c-2.396-13.656-10.794-25.215-23.041-31.712l-38.41-20.38a15.037 15.037 0 0 1-6.489-19.969L60.574 275.6c6.088-12.456 6.088-26.742 0-39.198l-19.096-39.065a15.037 15.037 0 0 1 6.489-19.969l38.41-20.38c12.246-6.499 20.645-18.057 23.04-31.713l7.512-42.828a15.038 15.038 0 0 1 16.987-12.342l43.053 6.09c13.725 1.943 27.316-2.474 37.281-12.113l31.252-30.232a15.04 15.04 0 0 1 20.997 0l31.251 30.232c9.965 9.64 23.554 14.056 37.281 12.113l43.053-6.09a15.04 15.04 0 0 1 16.987 12.342l7.512 42.829c2.396 13.656 10.794 25.215 23.041 31.712l38.41 20.38a15.037 15.037 0 0 1 6.489 19.969l-19.096 39.064c-6.088 12.455-6.088 26.743 0 39.198l19.096 39.064a15.039 15.039 0 0 1-6.488 19.972z"
                                    fill="#fc2424" opacity="1" data-original="#000000" class=""></path>
                                <path
                                    d="M363.886 148.116c-5.765-5.766-15.115-5.766-20.881 0l-194.889 194.89c-5.766 5.766-5.766 15.115 0 20.881a14.72 14.72 0 0 0 10.44 4.325c3.778 0 7.558-1.441 10.44-4.325l194.889-194.889c5.768-5.767 5.768-15.115.001-20.882zM196.941 123.116c-29.852 0-54.139 24.287-54.139 54.139s24.287 54.139 54.139 54.139 54.139-24.287 54.139-54.139-24.287-54.139-54.139-54.139zm0 78.747c-13.569 0-24.608-11.039-24.608-24.609 0-13.569 11.039-24.608 24.608-24.608s24.609 11.039 24.609 24.608c-.001 13.57-11.04 24.609-24.609 24.609zM315.061 280.61c-29.852 0-54.139 24.287-54.139 54.139s24.287 54.139 54.139 54.139c29.852 0 54.139-24.287 54.139-54.139s-24.287-54.139-54.139-54.139zm0 78.747c-13.569 0-24.609-11.039-24.609-24.608s11.039-24.608 24.609-24.608c13.569 0 24.608 11.039 24.608 24.608s-11.039 24.608-24.608 24.608z"
                                    fill="#fc2424" opacity="1" data-original="#000000" class=""></path>
                            </g>
                        </svg>
                        ${offerText}
                    </p>`
                ;
                $('#static_offers').append(offerHtml);
                offerCount++;
            });

            if(response.coupons.length > 2)
            {
                $('#total_offers').text(`+${response.coupons.length} Offers`);
            }
        } else {
           // alert(response.message);
        }
    });
}

