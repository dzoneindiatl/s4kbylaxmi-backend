var notRequiredQtyAjaxClickonQtyBtn = true;
function getSelectedVariants(cartItems = [], checkDuplicate = true) {

    let selected = {};
    const productId = $('#product_id').val();

    $('.activeVarientLi.active').each(function () {

        let $variant = $(this);
        let type = ($variant.attr('data-type') || '').toLowerCase();
        let value = $variant.attr('data-value') || '';

        if (type && value) {
            selected[type] = value;
        }
    });

    console.log('Selected Variants:', selected);

    if (!checkDuplicate) {
        return selected;
    }

    let exists = cartItems.some(function (item) {
        if (item.productId != productId) {
            return false;
        }
        let variant = item.selectedVariants || {};
        let keys1 = Object.keys(selected);
        let keys2 = Object.keys(variant);
        if (keys1.length !== keys2.length) {
            return false;
        }
        return keys1.every(function (key) {
            return variant[key] === selected[key];
        });
    });

    return exists ? null : selected;
}

$(document).on('click', '.addToCartBtn', function() {

    let button = $(this);
    if (button.text() === 'Go To Cart') {
        window.location.href = goTocartUrl;
        return;
    }
    let productId = button.data('id');
    let productName = button.data('name');
    let productType = button.data('producttype');
    let sku = button.data('sku');
    let price = button.data('price');
    let sellingPrice = button.data('saleprice');
    let discountType = button.data('discounttype');
    let discountAmount = button.data('discount');
    let rawTaxArr = button.attr('data-tax-arr');
    let quantity = parseInt($('#quantity').val()) || 1;

    // =========================
    // LOGGED-IN USER
    // =========================
    if (isLoggedIn) {
        let selectedVariants = getSelectedVariants([], false);
        console.log('Logged-in selectedVariants:', selectedVariants);
        isLoginUser(productId,quantity,selectedVariants);
        return;
    }

    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    let selectedVariants = getSelectedVariants(cartItems, true);

    console.log('Guest selectedVariants:', selectedVariants);

    // Same product + same variant already exists
    if (selectedVariants === null) {
        showFlashMessage(
            "This product with selected options is already in your cart."
        );

        openCartDropdown();

        return;
    }

    let productData = {
        randomId: 'prod_' + Date.now() + '_' + Math.floor(Math.random() * 10000),
        productId: productId,
        name: productName,
        productType: productType,
        sku: sku,
        quantity: quantity,
        price: price,
        sellingPrice: sellingPrice,
        discountType: discountType,
        discountAmount: discountAmount,
        image: document.querySelector('.default-image')?.src || '',
        selectedVariants: selectedVariants,
        rawTaxArr: rawTaxArr
    };

    // Duplicate check
    let isExist = cartItems.some(function(item) {
        if (item.productId != productId) {
            return false;
        }

        let oldVariants = item.selectedVariants || {};
        let newVariants = selectedVariants || {};

        let oldKeys = Object.keys(oldVariants);
        let newKeys = Object.keys(newVariants);

        if (oldKeys.length !== newKeys.length) {
            return false;
        }

        return oldKeys.every(function(key) {
            return oldVariants[key] === newVariants[key];
        });
    });

    if (isExist) {
        showFlashMessage("This product with selected options is already in your cart.");
        openCartDropdown();
        return;
    }

    cartItems.push(productData);
    localStorage.setItem('cartItems',JSON.stringify(cartItems));
    console.log('Guest cart saved:',JSON.parse(localStorage.getItem('cartItems')));

    updateHeaderCart();
    openCartDropdown();
    $('.addToCartText').html("Go To Cart");
    showFlashMessage("Product added to cart successfully");
});

$(document).ready(function () {
    if (!isLoggedIn) {
        updateHeaderCart();
    }
});
function openCartDropdown() {
    let $wrapper = $('.cart-wrapper');
    let $dropdown = $('.cart-dropdown');

    if (!$wrapper.length || !$dropdown.length) {
        console.log('Cart wrapper/dropdown not found');
        return;
    }

    $wrapper.addClass('open active');
    $dropdown.addClass('open active');

    // Agar CSS display se hidden hai to force visible
    $dropdown.css({
        'display': 'block',
        'visibility': 'visible',
        'opacity': '1'
    });

    console.log('Cart dropdown opened');
}
function updateHeaderCart() {
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    let $container = $('#headerCartItems');

    if (!$container.length) {
        console.log('#headerCartItems not found');
        return;
    }

    $container.empty();

    if (cartItems.length === 0) {
        $container.html(`
            <div class="empty-cart">
                Your cart is empty.
            </div>
        `);

        $('#cartCount').text('0');
        $('#cartTotal').text('₹0');

        return;
    }

    let total = 0;

    cartItems.slice(0, 2).forEach(function(item, index) {
        let price = parseFloat(item.sellingPrice || 0);
        let quantity = parseInt(item.quantity || 1);

        total += price * quantity;

        let productSlug = (item.name || '')
            .toString()
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

        let productUrl =
            "{{ url('product/product') }}/" +
            productSlug +
            ".html/" +
            item.sku;

        let html = `
            <div class="cart-item"
                data-randomid="${item.randomId}"
                data-index="${index}">

                <a href="${productUrl}">
                    <img src="${item.image}" alt="${item.name}">
                </a>

                <div class="cart-info">
                    <h5>${item.name}</h5>
                    <span>Qty: ${quantity}</span>
                    <strong>₹${price}</strong>
                </div>

                <button type="button"
                    class="remove-item close-product"
                    data-randomid="${item.randomId}"
                    data-index="${index}">

                    <span class="material-symbols-outlined">
                        close
                    </span>
                </button>
            </div>
        `;

        $container.append(html);
    });

    $('#cartCount').text(cartItems.length);
    $('#cartTotal').text('₹' + total.toFixed(0));

    console.log('Header cart updated:', cartItems);
}

function displayCartItems(notRequiredQtyAjaxClickonQtyBtn) {
   
     let cartItems = [];

    if (isLoggedIn) {
        cartItems = window.dbCartItems || [];
    } else {
        cartItems = JSON.parse(localStorage.getItem('cartItems') || '[]');
    }
    let productListContainer = $('.productListContainer');
    let productListCartPageContainer = $('.productListCartPageContainer');

    console.log("cart ITem ----",cartItems); 
    productListContainer.empty();
    
    $('.center-main').html(cartItems.length);

    if (cartItems.length == 0) {
        $(".add-cart-footer").hide();
        productListContainer.append(emptyCartImg);
        productListCartPageContainer.append(emptyCartImg);
        $('.cart-collaterals').hide();
   } else {
        $(".add-cart-footer").show();
        $('.cart-collaterals').show();
    }

    var notRequiredQtyAjaxClickonQtyBtnNew = notRequiredQtyAjaxClickonQtyBtn;
    console.log(' before foreach notRequiredQtyAjaxClickonQtyBtn : ',notRequiredQtyAjaxClickonQtyBtn);
    console.log('cartItems : ',cartItems);
    var cartTotal = 0;
    productListCartPageContainer.empty();

    
    cartItems.forEach(function (item, index) {
        let variants = item.selectedVariants || {};
        let product_sku = item.sku.toLowerCase();
        console.log("-----variants-------",variants); 
        let variantHTML = '';
        for (const [key, value] of Object.entries(variants)) {
            if (value) {
                variantHTML += `<p class="s-text">${key.charAt(0).toUpperCase() + key.slice(1)}: ${value}</p>`;
            }
        }
        //  sku accoring to variant && product type 
        let variant_sku = product_sku.toLowerCase();
        if(item.productType==2){
            // variant_sku = product_sku + '_' + variants.colour.toLowerCase() + '_' + variants.size.toLowerCase();
            variant_sku = product_sku + '_' + variants.color.toLowerCase();
        }
        //  sku accoring to variant && product type 
        
        var is_out_stock = '';
        $.ajax({
            url: isOutOfStock,
            type: 'POST',
            async: false,
            cache: false,
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: item.productId,
                variant_sku: variant_sku,
            },
            success: function (response) {
                console.log(' is out od stock response :  ', response);
                if(response){
                    is_out_stock = 'Out Of Stock';
                }
                console.log(' is_out_stock :  ', is_out_stock);
            },
            error: function () {
            // alert('Failed to add to cart. Please try again.');
            }
        });
        //  product is out of stock 

        // get variant colour and size combo exact qty
        console.log('after notRequiredQtyAjaxClickonQtyBtnNew : ',notRequiredQtyAjaxClickonQtyBtnNew);
        if(notRequiredQtyAjaxClickonQtyBtnNew==true){
            let variant_sku = product_sku.toLowerCase();
            if(item.productType==2){
                // variant_sku = product_sku + '_' + variants.colour.toLowerCase() + '_' + variants.size.toLowerCase();
                variant_sku = product_sku + '_' + variants.color.toLowerCase();
            }
            //  sku accoring to variant && product type 

            var getExactVariantComboQty = 0;
            const originalHtmlMinus = $(".addToDecQtyBtn").html();
            const originalHtmlPlus = $(".addToNewQtyBtn").html();
            $.ajax({
                url: getVarientReaminingQty,
                type: 'POST',
                async: false,
                cache: false,
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    product_id: item.productId,
                    sku: variant_sku,
                },
                beforeSend: function(){
                    // $(".addToDecQtyBtn").attr("disableed",true).html(`
                    // <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    // Processing...
                    // `);
                    //     $(".addToNewQtyBtn").attr("disableed",true).html(`
                    //     <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    //     Processing...
                    // `);
                    
                // $(".addToDecQtyBtn").attr("disabled",true);
                // $(".addToNewQtyBtn").attr("disabled",true);
                $(".addToDecQtyBtn").css("pointer-events", "none");
                $(".addToNewQtyBtn").css("pointer-events", "none");
                },
                success: function (response) {
                    getExactVariantComboQty = response;
                    console.log('Add to cart ');
                    console.log('maxSellingQty 1 : ',maxSellingQty);
                    if(parseInt(maxSellingQty) > parseInt(getExactVariantComboQty) || parseInt(maxSellingQty)==0){
                        maxSellingQtyNew = getExactVariantComboQty;
                    } else {
                        maxSellingQtyNew = maxSellingQty;
                    }
                    console.log('getExactVariantComboQty : ',getExactVariantComboQty);
                    console.log('maxSellingQty 2 : ',maxSellingQty);
                    console.log('maxSellingQtyNew 3 : ',maxSellingQtyNew);

                    $(".addToDecQtyBtn").css("pointer-events", "auto");
                $(".addToNewQtyBtn").css("pointer-events", "auto");
                    $(".addToDecQtyBtn").html(originalHtmlMinus);
                    $(".addToNewQtyBtn").html(originalHtmlPlus);
                },
                error: function () {
                // alert('Failed to add to cart. Please try again.');
                }
            });
        }  else {
        }
        // get variant colour and size combo exact qty

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


        if(is_out_stock !=''){
            var offer_tag = `<span class="offer-tag">Out Of Stock, Please remove it.</span>`;
        } else {
           var offer_tag =  `<span class="offer-tag"><i class="fa-regular fa-clock"></i> Limited time offer</span>`;
        }

        let productHTML = `<li class="mini-cart-item" data-index="${index}">  
            <div class="mini-cart-image">
                <a href="javascript:void(0)"><img src="${item.image}" alt="${item.name}"></a>
            </div>    
            <div class="mini-cart-summery"> 
                <div class="mini-cart-summerydata">
                    <a href="javascript:void(0)" class="mini-cart-title">${item.name}</a>
                    <p>Quantity : ${item.quantity}</p>
                </div> 
                <div class="mini-cart-summeryprice">
                    <span class="mini-cart-price">
                        <del>₹ ${item.price}</del>
                        <ins>₹ ${item.sellingPrice}</ins>
                    </span>
                    <a href="javascript:void('0');" data-index="${index}" class="remove remove_from_cart_button trash-icon close-product">Remove</a>
                </div>
            </div>
        </li>`;

        productListContainer.html(productHTML);

        let productCartPageHTML =`<div class="cart-item">
                        <div class="cart-image">
                          <a href="javascript:void(0)"><img src="${item.image}" alt="${item.name}"></a>
                        </div>
                        <div class="cart-summery">
                          <div class="cart-summerydata">
                            <a href="javascript:void(0)" class="cart-title">${item.name}</a>                               
                            <div class="cart-quantity">
                              <div class="quantity-group">
                                <a href="javascript:void(0)" class="dec qty-btn"></a>
                                <input type="text" id="quantity" class="input-text qty" name="quantity" value="${item.quantity}"
                                  maxlength="50">
                                <a href="javascript:void(0)" class="inc qty-btn"></a>
                              </div>
                            </div>
                          </div>
                          <div class="cart-summeryprice">
                            <span class="cart-price">
                                <del>₹ ${item.price}</del>
                                <ins>₹ ${item.sellingPrice}</ins>
                            </span>
                            <a href="javascript:void('0');" data-index="${index}" class="remove remove_from_cart_button trash-icon close-product">Remove</a>
                          </div>                              
                        </div>
                        </div>`;
        productListCartPageContainer.append(productCartPageHTML);

        cartTotal += item.sellingPrice;
    });

    $('.cartTotalPopup').html(cartTotal);

    // localStorage.setItem('applied_coupon',[]);
    // localStorage.setItem('coupon_discount',0);
    priceCalculation();
}

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

function priceCalculation() {
    let couponDiscount = parseFloat(localStorage.getItem('coupon_discount')) || 0;
    let cartItems;

    if (window.buyNowData) {
        cartItems = [{
            product_id: window.buyNowData.product_id,
            name: window.buyNowData.product_name || '',
            sku: window.buyNowData.sku || '',
            price: parseFloat(window.buyNowData.price) || 0,
            sellingPrice: parseFloat(window.buyNowData.selling_price) || 0,
            quantity: parseInt(window.buyNowData.quantity) || 1,
            image: window.buyNowData.image || '',
            rawTaxArr: window.buyNowData.tax_arr || ''
        }];
    } else if (isLoggedIn) {
        cartItems = (window.dbCartItems || []).map(item => ({
            ...item,
            product_id: item.product_id,
            name: item.name || '',
            sku: item.sku || '',
            productType: item.productType || '',
            selectedVariants: item.selectedVariants || {},
            price: parseFloat(item.price) || 0,
            sellingPrice: parseFloat(item.sellingPrice) || 0,
            discountAmount: parseFloat(item.discountAmount) || 0,
            discountType: item.discountType || '',
            quantity: parseInt(item.quantity) || 1,
            image: item.image || item.product?.image || '',
            rawTaxArr: item.rawTaxArr || item.product?.tax_arr || ''
        }));
    } else {
        cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    }

    const totalQuantity = cartItems.reduce((sum, item) => sum + (parseInt(item.quantity) || 0), 0);

    let cartData = cartItems.map(item => {
        let rawTaxArr = item.rawTaxArr || '';
        let taxArr = [];

        try {
            if (rawTaxArr) {
                if (Array.isArray(rawTaxArr)) {
                    taxArr = rawTaxArr;
                } else if (typeof rawTaxArr === 'string') {
                    let decodedJson = decodeHtml(rawTaxArr);
                    taxArr = JSON.parse(decodedJson) || [];
                }
            }
        } catch (error) {
            console.error("Tax JSON Parse Error:", error);
            taxArr = [];
        }

        let tax_price_total = 0;
        let tax_option = "inclusive";
        let tax_id = "";
        let tax_rate = 0;
        let tax_type = "flat";
        let sellingPrice = parseFloat(item.sellingPrice) || 0;
        let netPrice = sellingPrice;

        if (couponDiscount > 0 && totalQuantity > 0) {
            netPrice = sellingPrice - (couponDiscount / totalQuantity);
        }

        if (netPrice < 0) {
            netPrice = 0;
        }
        console.log("--------------taxArr--------------",taxArr); 
        if (taxArr.length > 0) {
           taxArr.forEach((tax) => {
                let taxprice = 0;
                let tax_price = 0;
                tax_type = tax.tax_type;

                if (tax.tax_type === "flat") {
                    tax_option = tax.tax_option;

                    tax_id = tax.id;
                    tax_rate = (tax.tax_rate > 0 || String(tax.tax_rate).toLowerCase() !== "no tax") ? tax.tax_rate : 0;
                    console.log("------------tax_rate-----------------",tax_rate); 
                    if (tax_option == "inclusive") {
                        // taxprice = 1 + (tax_rate / 100);
                        // console.log("----------taxprice------------",taxprice); 
                        // tax_price = tax_rate ? (netPrice / taxprice) : 0;
                        // console.log("==========tax--price=========",tax_price); 
                        // tax_price_total = netPrice - tax_price;
                        // console.log("===========tax_price_total------------",tax_price_total); 
                        tax_price_total = ((netPrice * tax_rate) / 100);

                        console.log("-------tax_price_total-------if-----",tax_price_total); 
                    } else {
                        tax_price_total = tax_rate ? ((netPrice * tax_rate) / 100) : 0;
                        console.log("-------tax_price_total-------else-----",tax_price_total); 
                    }

                } else if (tax.tax_type === "floating") {
                    tax_option = tax.tax_option;

                    tax_id = tax.id;

                    if ((parseInt(netPrice) >= parseInt(tax.tax_from)) && (parseInt(netPrice) <= parseInt(tax.tax_to))) {
                        tax_rate = (tax.tax_rate > 0 || String(tax.tax_rate).toLowerCase() !== "no tax") ? tax.tax_rate : 0;
                        if (tax_option == "inclusive") {
                            taxprice = 1 + (tax_rate / 100);

                            tax_price = tax_rate > 0 ? (netPrice / taxprice) : 0;

                            tax_price_total = netPrice - tax_price;

                        } else {
                            tax_price_total = tax_rate > 0 ? ((netPrice * tax_rate) / 100) : 0;
                        }
                    }
                }
            });
        }

        let finalTax = item.quantity * tax_price_total;

        return {
            ...item,
            tax_id: tax_id,
            tax_price: finalTax,
            tax_rate: tax_rate,
            tax_option: tax_option,
            tax_type: tax_type,
            rawTaxArr: rawTaxArr
        };
    });
   
    if (window.buyNowData) {
        cartItems = cartData;
    } else if (isLoggedIn) {
        cartItems = cartData;
    } else {
        localStorage.setItem('cartItems', JSON.stringify(cartData));
        cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    }
    console.log("---------cartItem logs---------------",cartItems); 
    let totalMrp = 0;
    let totalDiscount = 0;
    let totalTaxPrice = 0;
    let totalSelling = 0;
    let taxOption = 'inclusive';

    cartItems.forEach(function(item) {
        let quantity = parseInt(item.quantity) || 1;
        let price = parseFloat(item.price) || 0;
        console.log("----price------",price); 

        let sellingPrice = parseFloat(item.sellingPrice) || 0;
        console.log("-----sellingPrice----------",sellingPrice); 
        let taxPrice = parseFloat(item.tax_price) || 0;
        console.log("----------taxPrice--------------",taxPrice); 
        totalMrp += price * quantity;
        totalSelling += sellingPrice * quantity;
        totalTaxPrice += taxPrice;
      
        if (item.tax_option) {
            taxOption = item.tax_option;
        }
    });
    console.log("----------totalMrp----------",totalMrp); 
    console.log("----------total selling-----------",totalSelling);
    console.log("========totalTaxPRice==========",totalTaxPrice); 
    totalDiscount = totalMrp - totalSelling;
    console.log("------------totalDiscount---------",totalDiscount); 
    let subTotal = totalMrp - totalDiscount;
    console.log("-------------subTotal----------",subTotal); 
    let grandTotal = subTotal - couponDiscount;
    console.log("===========grandTotal========",grandTotal); 
    if (grandTotal < 0) {
        grandTotal = 0;
    }

    let taxableAmount = grandTotal;

    if (taxOption === 'inclusive') {
        taxableAmount = grandTotal - totalTaxPrice;
        console.log("===========taxableAmount==========",taxableAmount); 
    } else if (taxOption === 'exclusive') {
        taxableAmount = grandTotal;
    }

    let finalAmount = grandTotal;

    if (totalMrp > 0) {
        $("#totalMrp").html(`₹${Math.floor(totalMrp)}`).show();
    } else {
        $("#totalMrp").html("0");
    }

    if (totalDiscount > 0) {
        $("#totalDiscount").html(`-₹${totalDiscount.toFixed(2)}`).show();
    } else {
        $("#totalDiscount").html("0");
    }

    $('#subTotal').html(`₹${subTotal.toFixed(2)}`);

    if (couponDiscount > 0) {
        $("#couponDiscount").html(`-₹${couponDiscount.toFixed(2)}`).show();
    } else {
        $("#couponDiscount").html("0");
    }

    $('#grandTotal').html(`₹${grandTotal.toFixed(2)}`);
    $('#taxableAmount').html(`₹${taxableAmount.toFixed(2)}`);

    if (taxOption === 'inclusive' || taxOption === 'exclusive') {
        $("#taxPrice").html(`+₹${totalTaxPrice.toFixed(2)}`).show();
    } else {
        $("#taxPrice").html("0");
    }
    console.log("-------------finalAmount------------",finalAmount); 
    if (finalAmount > 0) {
        console.log("-----------comes under if----------",finalAmount); 
        if (taxOption === 'inclusive') {
            $('.finalAmount').html(`₹${Math.floor(finalAmount)}`);
        } else if (taxOption === 'exclusive') {
            $('.finalAmount').html(`₹${Math.floor(finalAmount + totalTaxPrice)}`);
        }
        $('.checkoutButton').removeClass('disabled-link');
    } else {
        console.log("----------comes under else------------",finalAmount); 
        $('.finalAmount').html(`₹0`);
        $('.checkoutButton').addClass('disabled-link');
    }
}

document.addEventListener('click', function (e) {
    const button = e.target.closest('.close-product');
    if (!button) {
        return;
    }
    e.preventDefault();
    // Guest user → LocalStorage
    if (!window.isCustomerLoggedIn) {
        const index = parseInt(button.dataset.index);
        let cartItems = JSON.parse(
            localStorage.getItem('cartItems') || '[]'
        );
        if (cartItems[index]) {
            cartItems.splice(index, 1);

            localStorage.setItem(
                'cartItems',
                JSON.stringify(cartItems)
            );
            displayGuestCart();
            showFlashMessage("Product removed from cart", "warning");
        }
        return;
    }
    // Logged-in user → Database
    const cartId = button.dataset.cartid;

    if (cartId) {
        removeProductCartFromDB(cartId);
    }

}, true);

$(document).off('click', '.addtoWishList').on('click', '.addtoWishList', function () {
    const productId = this.getAttribute('data-product-id');
    const heartIcon = this.querySelector('i');
   
    if (!isLoggedIn) {
        //const loginModal = new bootstrap.Modal(document.getElementById('login'));
        //loginModal.show();
        window.location.href = '/login';
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
            // alert("Product added in wishlist");
            heartIcon.classList.remove('fa-regular');
            heartIcon.classList.add('fa-solid');
        } else if (data.status === 'removed') {
            showFlashMessage("Product remove in wishlist", "warning");
            // alert("Product remove in wishlist", "warning");
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
    let wishlistCount = (localStorage.getItem('wishlistCount') && localStorage.getItem('wishlistCount') != "undefined") ? JSON.parse(localStorage.getItem('wishlistCount')) : 0;
    $(".wishlist-count").html(wishlistCount);
}

$(document)
.off(
    'click',
    '.addToNewQtyBtn, .addToDecQtyBtn'
)
.on(
    'click',
    '.addToNewQtyBtn, .addToDecQtyBtn',
    function (e) {

        e.preventDefault();


        var input =
            $(this)
                .closest('.input-increment')
                .find('.quantityInputs');


        var randomId =
            input.data('randomid');


        /*
        |--------------------------------------------------------------------------
        | LOGGED-IN USER
        |--------------------------------------------------------------------------
        */

        if (isLoggedIn) {

            var productId =
                input.data('productid');

            var selectedVariants =
                input.data('variants') || {};

            var currentQty =
                parseInt(input.val()) || 1;

            var minQty =
                parseInt(input.attr('min')) || 1;

            var maxQty =
                parseInt(input.attr('max')) || 10;


            if (
                $(this).hasClass('addToNewQtyBtn') &&
                currentQty < maxQty
            ) {

                currentQty++;

            }
            else if (
                $(this).hasClass('addToDecQtyBtn') &&
                currentQty > minQty
            ) {

                currentQty--;

            }


            input.val(currentQty);


            localStorage.setItem(
                'applied_coupon',
                []
            );

            localStorage.setItem(
                'coupon_discount',
                0
            );


            isLoginUser(
                productId,
                currentQty,
                selectedVariants
            );


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | GUEST USER
        |--------------------------------------------------------------------------
        */

        var cartItems =
            JSON.parse(
                localStorage.getItem('cartItems')
            ) || [];


        var existingItemIndex =
            cartItems.findIndex(
                function (item) {

                    return item.randomId == randomId;

                }
            );


        if (existingItemIndex === -1) {
            return;
        }


        var currentQty =
            parseInt(
                cartItems[existingItemIndex].quantity
            );


        var minQty =
            parseInt(input.attr('min')) || 1;

        var maxQty =
            parseInt(input.attr('max')) || 10;


        if (
            $(this).hasClass('addToNewQtyBtn') &&
            currentQty < maxQty
        ) {

            currentQty++;

        }
        else if (
            $(this).hasClass('addToDecQtyBtn') &&
            currentQty > minQty
        ) {

            currentQty--;

        }


        localStorage.setItem(
            'applied_coupon',
            []
        );

        localStorage.setItem(
            'coupon_discount',
            0
        );


        cartItems[existingItemIndex].quantity =
            currentQty;


        cartItems[existingItemIndex].total =
            parseFloat(
                cartItems[existingItemIndex].price
            ) * currentQty;


        localStorage.setItem(
            'cartItems',
            JSON.stringify(cartItems)
        );


        console.log(
            'Guest quantity updated'
        );


        var notRequiredQtyAjaxClickonQtyBtn =
            false;


        displayCartItems(
            notRequiredQtyAjaxClickonQtyBtn
        );

    }
);

function isLoginUser(productId, quantity, selectedVariants, type = null, cartId = null) {
    if (!isLoggedIn) {
        return;
    }

    $.ajax({
        url: addToCart,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'),
            product_id: productId,
            quantity: quantity,
            selected_variants: selectedVariants,
            addType: type,
            cart_id: cartId
        },
        success: function(response) {
            console.log('Logged-in cart response:', response);

            if (!response.success) {
                showFlashMessage(
                    response.message || "Unable to update cart."
                );
                return;
            }

            // REMOVE
            if (type === 'remove') {
                $('.addToCartText').html("Add To Cart");

                showFlashMessage(
                    "Product removed from cart",
                    "warning"
                );

                return;
            }

            // ADD / UPDATE
            $('.addToCartText').html("Go To Cart");

            showFlashMessage(
                "Product added to cart successfully"
            );

            openCartDropdown();
        },
        error: function(error) {
            console.log('Logged-in cart error:', error);

            showFlashMessage(
                "Unable to update cart. Please try again."
            );
        }
    });
}

$(document).on('click', '.view-more-link', function () {
    getCoupon()
});

function getCoupon() {
    $.post(getCouponUrl, {
        cart_items: JSON.parse(localStorage.getItem('cartItems')) || [],
        _token: $('meta[name="csrf-token"]').attr('content'),
    }, function (response) {
        if (response.status) {
            $(".offer-available").html(response.coupons.length + " Offers Available");
            $('#all_coupons').empty();
            $('#static_offers').empty();

            let offerCount = 0;
            console.log(response.coupons);
            response.coupons.forEach(function (coupon) {
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

            staticOffers.forEach(function (offerText) {
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

            if (response.coupons.length > 2) {
                $('#total_offers').text(`+${response.coupons.length} Offers`);
            }
        } else {
            // alert(response.message);
        }
    });
}

