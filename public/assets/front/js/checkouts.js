
/* Billing Address country */
function getCartItems() {
    if (isLoggedIn) {
        return window.dbCartItems || [];
    }

    return JSON.parse(localStorage.getItem('cartItems')) || [];
}
$('#country').on('change', function () {
    var countryId = $(this).val();
    var selectedStateId = null; // or set this dynamically

    if (countryId) {
        $.ajax({
            url: '/get-states/' + countryId,
            type: 'GET',
            success: function (response) {
                var options = '<option value="">Select State</option>';
                $.each(response, function (id, name) {
                    var selected = (id == selectedStateId) ? 'selected' : '';
                    options += '<option value="' + id + '" ' + selected + '>' + name + '</option>';
                });
                $('#state').html(options);
            }
        });
    } else {
        $('#state').html('<option value="">Select State</option>');
    }
});

$('#state').on('change', function () {
    var stateId = $(this).val();
    var selectedCityId = null; // or set this dynamically

    if (stateId) {
        $.ajax({
            url: '/get-cities/' + stateId,
            type: 'GET',
            success: function (response) {
                // console.log(response);
                var options = '<option value="">Select City</option>';
                $.each(response, function (id, name) {
                    var selected = (id == selectedCityId) ? 'selected' : '';
                    options += '<option value="' + id + '" ' + selected + '>' + name + '</option>';
                });
                $('#city').html(options);
            }
        });
    } else {
        $('#city').html('<option value="">Select City</option>');
    }
});

/* Shipping Addrress country */

$('#shipping_country').on('change', function () {
    var countryId = $(this).val();
    var selectedStateId = null; // or set this dynamically

    if (countryId) {
        $.ajax({
            url: '/get-states/' + countryId,
            type: 'GET',
            success: function (response) {
                var options = '<option value="">Select State</option>';
                $.each(response, function (id, name) {
                    var selected = (id == selectedStateId) ? 'selected' : '';
                    options += '<option value="' + id + '" ' + selected + '>' + name + '</option>';
                });
                $('#shipping_state').html(options);
            }
        });
    } else {
        $('#shipping_state').html('<option value="">Select State</option>');
    }
});

$('#shipping_state').on('change', function () {
    var stateId = $(this).val();
    var selectedCityId = null; // or set this dynamically

    if (stateId) {
        $.ajax({
            url: '/get-cities/' + stateId,
            type: 'GET',
            success: function (response) {
                // console.log(response);
                var options = '<option value="">Select City</option>';
                $.each(response, function (id, name) {
                    var selected = (id == selectedCityId) ? 'selected' : '';
                    options += '<option value="' + id + '" ' + selected + '>' + name + '</option>';
                });
                $('#shipping_city').html(options);
            }
        });
    } else {
        $('#shipping_city').html('<option value="">Select City</option>');
    }
});

// Billing Address validation
document.addEventListener('DOMContentLoaded', function () {
    const billingForm = document.getElementById('addressForm');
    const shippingForm = document.getElementById('shippingAddressForm');

    function handleValidation(form, submitButton, redirectUrl) {
        if (form && submitButton) {
            submitButton.addEventListener('click', function (e) {
                e.preventDefault();
                let isValid = true;

                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                const showError = (input, message) => {
                    input.classList.add('is-invalid');
                    const error = document.createElement('div');
                    error.className = 'invalid-feedback';
                    error.style.color = 'red';
                    error.innerText = message;
                    input.parentNode.appendChild(error);
                    isValid = false;
                };

                // Scoped form fields
                const country = form.querySelector('[name="country"]');
                const firstName = form.querySelector('[name="firstname"]');
                const lastName = form.querySelector('[name="lastname"]');
                const address = form.querySelector('[name="address"]');
                const city = form.querySelector('[name="city"]');
                const state = form.querySelector('[name="state"]');
                const pinCode = form.querySelector('[name="pinCode"]');
                const phone = form.querySelector('[name="phone"]');

                // Validations
                if (!country || !country.value || country.value === 'Country/Region') {
                    showError(country, 'Please select a country');
                }
                if (!firstName || !firstName.value.trim()) {
                    showError(firstName, 'First name is required');
                }
                if (!lastName || !lastName.value.trim()) {
                    showError(lastName, 'Last name is required');
                }
                if (!address || !address.value.trim()) {
                    showError(address, 'Address is required');
                }
                if (!city || !city.value || city.value === 'City') {
                    showError(city, 'Please select a city');
                }
                if (!state || !state.value || state.value === 'State') {
                    showError(state, 'Please select a state');
                }
                if (!pinCode || !pinCode.value.trim()) {
                    showError(pinCode, 'PIN code is required');
                } else if (!/^\d{6}$/.test(pinCode.value.trim())) {
                    showError(pinCode, 'PIN code must be 6 digits');
                }
                if (!phone || !phone.value.trim()) {
                    showError(phone, 'Phone number is required');
                } else if (!/^\d{10}$/.test(phone.value.trim())) {
                    showError(phone, 'Phone number must be 10 digits');
                }

                if (isValid) {
                    const formData = new FormData(form);

                    fetch('/save-user-address', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                window.location.href = data.redirect_url;
                            } else {
                                alert('Something went wrong. Please check your input.');
                            }
                        })
                        .catch(error => {
                            console.error('AJAX Error:', error);
                            alert('Something went wrong. Please try again later.');
                        });
                }
            });
        }
    }

    // Attach validation to both forms
    handleValidation(billingForm, document.getElementById('save_billing_address'));
    handleValidation(shippingForm, document.getElementById('save_shipping_address'));
});

// Biling Addrress for close/open
$(document).ready(function () {
    $('#add_new_billing_address').on('click', function () {
        /* var btn = $(this);
        if (btn.text().trim() == "Cancel") {
            btn.text("Add New Billing Address");
        } else {
            btn.text("Cancel");
        } */
        $('#main_billing').slideToggle();
    });
});

// Shipping Addrress for close/open
$(document).ready(function () {
    $('#add_new_shipping_address').on('click', function () {
        $('#main_shipping').slideToggle();
    });
});

/*Same as billing address*/
$('.select_shipping_address').click(function(){
    if($(this).prop('checked') === true){
        $('#main_shipping').slideUp();
        $('#same_as_billing').prop('checked',false);        
    }
});

function toggleShippingAddressForm() {
    const checkbox = document.getElementById('same_as_billing');
    if (checkbox.checked) {
        $('.select_shipping_address').prop('checked', false);
    } 
}

// Payemtn method COD and wallet should not be select together
function togglewalletCod() {
    const checkbox = document.getElementById('payment1');
    if (checkbox.checked) {
        $('#payment3').prop('checked', false);
    }  
}

$('#payment3').click(function(){
    if($(this).prop('checked') === true){
        $('#payment1').prop('checked',false);        
    }
});
// Payemtn method COD and wallet should not be select together


// Initial state based on checkbox status
document.addEventListener('DOMContentLoaded', function () {
    toggleShippingAddressForm();
    $('[name="shipaddress"]').change(function () {
        if ($(this).prop('checked')) {
            $('#same_as_billing').prop('checked', false);
        }
    })
});

/*End Same as billing address*/

/* for payment process */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var totalShippingCharge = 0;
$('#pay_now').on('click', function (e) { 
    $('.error-msg').html("");

    var cartItems = getCartItems();
    console.log(' cart items ', cartItems.length);
    if (parseInt(cartItems.length) === 0) { 
        $('#pay_now').remove();
        $('#shoopping_continue').show();
        $('.error-msg').html("Your cart is empty, Please add some product in cart.");
        return false;
    }

    // Adddress relevant validation
    let that = $(this);
    let selectedBilling = $('.select_billing_address:checked').val();
    console.log("selectedBilling--------",selectedBilling); 
    let isSameAsBillingChecked = $('#same_as_billing').is(':checked');
    console.log("isSameAsBillingChecked------------",isSameAsBillingChecked); 
    let selectedShipping = $('.select_shipping_address:checked').val();
    if (!selectedBilling) {
        $('.error-msg').html("Please select a billing address.");
        e.preventDefault();
        return;
    }  
    if (!isSameAsBillingChecked && !selectedShipping) {
        $('.error-msg').html("Please select a shipping address or check 'Same as billing address'.");
        e.preventDefault();
        return;
    }

    var billing_id = selectedBilling;
    var shipping_id = (isSameAsBillingChecked === true) ? billing_id : selectedShipping;
    // Adddress relevant validation

    // payment method validation
    console.log('payonline : ', $('[name="pay_online"]:checked').length);
    console.log('wallet_online  : ', $('[name="wallet_online"]:checked').length);

    if($('[name="pay_online"]:checked').length == 0 && $('[name="wallet_online"]:checked').length == 0){
        e.preventDefault();
         $('.error-msg').html("Please select any payment method.");
        return;
    }
    // payment method validation
    
    // Quantity Validation on checkout page
    var isQuantity = true;
    cartItems.forEach(function (item) {
        let variants = item.selectedVariants || {};
        let product_sku = item.name.toLowerCase();
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
            success: function (response) {
                getExactVariantComboQty = response;

                console.log('Add to cart ');
                console.log('maxSellingQty 1 : ',maxSellingQty);
                if(parseInt(maxSellingQty) > parseInt(getExactVariantComboQty) || parseInt(maxSellingQty)==0){
                    console.log('if');
                    maxSellingQtyNew = getExactVariantComboQty;
                } else {
                    maxSellingQtyNew = maxSellingQty;
                }
                console.log('getExactVariantComboQty : ',getExactVariantComboQty);
                console.log('maxSellingQty 2 : ',maxSellingQty);
                console.log('maxSellingQtyNew 3 : ',maxSellingQtyNew);

                $(".addToDecQtyBtn").attr("disabled",false).html(originalHtmlMinus);
                $(".addToNewQtyBtn").attr("disabled",false).html(originalHtmlPlus);
            },
            error: function (xhr) {
               // alert('Failed to add to cart. Please try again.');
            }
        });
        // get variant colour and size combo exact qty

        var quantity = item.quantity;
        if(parseInt(quantity) > parseInt(maxSellingQtyNew) &&  maxSellingQtyNew !=0){
            isQuantity = false;
            e.preventDefault();
            $('#paymentError').show();
            $(".error-msg").html("You can't order above max quantity of product, Your product quantity is exceed : " + quantity);
            setTimeout(function(){  $(".error-msg").html("");  }, 5000);
        } 
    });
    if(!isQuantity){
        return false;
    }
    // Quantity Validation on checkout page
    console.log( 'calculate cart : ', cartItems);
    var totals = calculateCartTotals(cartItems);
    var subTotal = totals.finalAmount;
    console.log('subTotal  : ', subTotal);

    // COD order should be less than 500 validation
    if($('[name="pay_online"]:checked').val() =='cod' && subTotal > codMaxLimit){
        e.preventDefault();
        $('#paymentError').show();
        $(".error-msg").html("Cash on Delivery available for orders up to ₹"+codMaxLimit);
        setTimeout(function(){  $(".error-msg").html("");  }, 5000);
        return false;
    } else {
        $(".error-msg").html("");
    }
    
    console.log('subTotal : pay now'. subTotal);
    if(parseInt(subTotal) == 0){ //  order amount should be greater than 0 rs validation
        e.preventDefault();
        $('#paymentError').show();
        $(".error-msg").html("You cant process order with zero amount.");
        setTimeout(function(){  $(".error-msg").html("");  }, 5000);
        return false;
    } else {
        $(".error-msg").html("");
    }
    
    if(parseInt(subTotal) < 0){ //  order amount should be greater than 0 rs validation
         e.preventDefault();
        $('#paymentError').show();
        $(".error-msg").html("You cant process order with minus amount.");
        setTimeout(function(){  $(".error-msg").html("");  }, 5000);
        return false;
    } else {
        $(".error-msg").html("");
    }
    // COD order should be less than 12000 validation

    var payment_type = document.querySelector('input[name="pay_online"]:checked')?.value;
    var walletCheckbox = document.getElementById('payment1');
    var wallet_type = walletCheckbox?.checked ? "wallet" : null;
    if (wallet_type && $('[name="pay_online"]:checked').val() !='cod') {
        // Get real available waller balance 
        var walletBalance = 0;
        $.ajax({
            url: '/get-user-wallet/' + USERID,
            type: 'GET',
            async: false,
            cache: false,
            success: function (response) {
                console.log('response  : ', response);
                walletBalance = parseFloat(response) || 0;
            }
        });
        // Get real available waller balance 
        console.log('user_id  : ', USERID);
        console.log('walletBalance  : ', walletBalance);
       
        // var subTotal = parseFloat($('.finalAmount').text().replace(/[^\d.]/g, ''));
        var wallet_amount = 0;
        if (walletCheckbox?.checked) {
            if (parseFloat(walletBalance) >= (subTotal)) {
                wallet_amount = subTotal;
                payment_type = "wallet";
                subTotal = 0;
            } else if (payment_type === "razorpay") {
                wallet_amount = walletBalance;
                subTotal = subTotal - walletBalance;
            }
        }
    } else {
        var subTotal = parseFloat($('.finalAmount').text().replace(/[^\d.]/g, ''));
    }
    shippingCalculation(shipping_id, cartItems, subTotal);
    var postData = {
        cartItems: cartItems,
        sub_total: subTotal,
        payment_mode: payment_type,
        coupon_id: localStorage.getItem('coupon_id'),
        coupon_discount: parseFloat(localStorage.getItem('coupon_discount')) || 0,
        wallet_amount: wallet_amount,
        billing_id: billing_id,
        shipping_id: shipping_id,
        shippingcharge: totalShippingCharge,
    };
    var url = window.location.origin + "/place-order";
    console.log("===========postData=============",postData); 
    console.log("place order url------",url); 
    that.prop('disabled', true).text('Please wait...');
    $.ajax({
        type: "POST",
        url: url,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(postData),

        success: function (data) {
            if (data.success==false) {
                console.log(' final order response ', data);
                $(".error-msg").html(data.message);
                setTimeout(function(){  $(".error-msg").html("");  }, 5000);
            } else {
                if (payment_type === "wallet" && data.success) {
                    localStorage.removeItem('coupon_id');
                    localStorage.removeItem('coupon_discount');
                    window.location.href = data.url;
                }
                if (payment_type == 'razorpay') {
                    if (data.success) {
                        if (data.type == 'wallet') {
                            location.href = window.location.origin + "/dashboard";
                        } else {
                            var options = {
                                "key": RAZORPAYKEY,
                                "amount": data.data.amount,
                                "currency": "INR",
                                "name": "FurnishWorlds",
                                "description": "Transaction",
                                "image": "https://furnishworlds.com/uploads/settings/JUL2026/1783656397-settings.jpg",
                                "order_id": data.data.id,
                                "callback_url": window.location.origin + "/checkout-callback",
                                "prefill": {
                                    "name": USERNAME,
                                    "email": USEREMAIL,
                                    "contact": USERPHONE
                                },
                                "notes": {
                                    "coupon_id": postData.coupon_id,
                                    "coupon_discount": postData.coupon_discount,
                                    "shippingcharge": postData.shippingcharge,
                                    "billing_id": postData.billing_id,
                                    "shipping_id": postData.shipping_id,
                                },
                                "theme": {
                                    "color": "#3399cc"
                                },
                                "handler": function(response) {
                                    localStorage.removeItem('coupon_id');
                                    localStorage.removeItem('coupon_discount');
                                    // Razorpay response ko backend par bhejna
                                    console.log("Payment Success:", response);

                                },
                                "modal": {
                                    "ondismiss": function() {
                                        console.log("Razorpay closed");
                                    }
                                }
                            };
                            var rzp1 = new Razorpay(options);
                            rzp1.on('payment.failed', function(response) {
                                console.log("Payment Failed:", response.error);
                            });
                            rzp1.open();
                        }
                    }
                } else {
                    if (data.success) {
                                 // COD successful
                        localStorage.removeItem('coupon_id');
                        localStorage.removeItem('coupon_discount');
                        window.location.href = data.url;
                    }
                }
            }
            that.prop('disabled', false).text('Pay Now');
        },
        error: function (err) {
            that.prop('disabled', false).text('Pay Now');
            $('.error-msg').remove();
            if (err.status == 422) {
                $("#add-new-billing-address").addClass('d-none');
                $('#new-billing-address').toggleClass('show');
                $.each(err.responseJSON.errors, function (i, error) {
                    var el = $(document).find('[name="' + i + '"]');
                    el.after($('<span class="error-msg" style="color: red;">' + error[0] + '</span>'));
                });
            }
        }
    });
});



// Edit address case.
$(document).on('change', '.editState', function () {
    var stateId = $(this).val();
    var selectedCityId = null; // dynamically set if needed

    if (stateId) {
        $.ajax({
            url: '/get-cities/' + stateId,
            type: 'GET',
            success: function (response) {
                var options = '<option value="">Select City</option>';
                $.each(response, function (id, name) {
                    var selected = (id == selectedCityId) ? 'selected' : '';
                    options += '<option value="' + id + '" ' + selected + '>' + name + '</option>';
                });
                $('.editCity').html(options);
            }
        });
    } else {
        $('.editCity').html('<option value="">Select City</option>');
    }
});



renderCart();

function renderCart() {
    var cartItems = [];
    var cartOutOfStock = [];
    var cartAllItems = getCartItems();

    console.log("--------renderCart-----in renderCartMethod--",cartAllItems); 
    cartAllItems.forEach(function (item, index) {
        let variants = item.selectedVariants || {};
        //  product is out of stock

        let product_sku = item.name.toLowerCase();
        let variant_sku = product_sku.toLowerCase();
        if(item.productType==2){
            // variant_sku = product_sku + '_' + variants.colour.toLowerCase() + '_' + variants.size.toLowerCase();
            variant_sku = product_sku + '_' + variants.color.toLowerCase();
        }
        //  sku accoring to variant && product type 

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
                if(!response){
                    cartItems.push(item);
                } else {
                    cartOutOfStock.push(item);
                }
            },
            error: function (xhr) {
            // alert('Failed to add to cart. Please try again.');
            }
        });
        //  product is out of stock 
    });
    if(cartOutOfStock!=''){
        document.addEventListener("DOMContentLoaded", function () {
            var myModal = new bootstrap.Modal(document.getElementById('out-of-stock-product'));
            myModal.show();
        });
        // confirm('You have some out of stock product in cart, You can buy these product when will be in stock.');
        console.log(' cartItems new ', cartItems);
    }
    
    console.log(' render cart ', cartItems);
    var $container = $('#checkout-cart-container');
    let couponDiscount = parseFloat(localStorage.getItem('coupon_discount')) || 0;
    if (cartItems.length === 0) {
        $container.html('<p>Your cart is empty.</p>');
        $('#totalMrp, #delivery, #totalDiscount, #subTotal, #couponDiscount, #total-items, #grandTotal, #taxableAmount, #taxPrice, .finalAmount').text('₹0');
        $('#pay_now').remove();
        $('#shoopping_continue').show();
        $('.Delivery-sec').html('<h3>Your Cart is empty !!!</h3>' + emptyCartImg +'</br><button class="w-100 full-btn" id="shoopping_continue"><a href="https://vasvi.in/" style="color:#fff">Add to Cart Any Item</a></button>');
        return;
    }

    let output = '<ul class="check-product-list t1">';
    let delivery = 0;
    let totalMrp = 0;
    let totalDiscount = 0;
    let totalTaxPrice = 0;
    let taxOption = 'inclusive';
    let qty = 0;
    let taxRate = 0; 
    console.log(' before loop ', cartItems);
    cartItems.forEach(function (item) {
        qty = item.quantity;
        
        totalMrp += item.price * item.quantity;
        // if (item.discountType == "flat") {
        //     totalDiscount += parseInt(item.discountAmount);
        // } else if (item.discountType == "percentage") {
        //     totalDiscount += ((parseInt(item.discountAmount) * totalMrp) / 100);
        // }
        totalDiscount += item.price - item.sellingPrice ;  
        taxOption = item.tax_option;
        taxRate = item.tax_rate; 

        totalTaxPrice += ((item.sellingPrice * taxRate)/100) ; 

        output += `
                <li>
                    <div class="cp-list-l">
                        <figure><img src="${item.image}" alt="${item.name}"></figure>
                        <span class="no-product">${item.quantity}</span>
                    </div>
                    <div class="cp-list-r">
                        <figcaption>
                            <h4>${item.name}</h4>
                            <!-- <p>Bundles</p> -->
                            <span>₹${item.sellingPrice}</span>
                            </br><span>Qty : ${item.quantity}</span>
                            </br><span>Color : ${item.selectedVariants.colour}</span> / <span>Size : ${item.selectedVariants.size}</span>
                        </figcaption>
                    </div>
                </li>`;
    });
    output += '</ul>';
    
    $container.html(output);
    let subTotal = totalMrp - totalDiscount;
    let grandTotal = subTotal - couponDiscount;
    let taxableAmount = grandTotal;
    if (taxOption == 'inclusive') {
        taxableAmount = grandTotal - totalTaxPrice;
    } else if (taxOption == 'exclusive') {
        taxableAmount = grandTotal;
    }
    console.log(totalShippingCharge);
    let finalAmount = grandTotal + totalShippingCharge;
    // Update Summary
    
    // Maanage COD checkbox text value 
    if (totalMrp > 12000) {
        $(".cod_value").html('Not Available');
    } else {
        $(".cod_value").html(`₹${Math.floor(totalMrp)}`);
    }
    // Maanage COD checkbox text value 

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
    } else if (taxOption == 'exclusive') {
        $("#taxPrice").html(`+₹${totalTaxPrice.toFixed(2)}`).show();
    } else {
        $("#taxPrice").html("0"); // Or use .text('') depending on your layout
    }
    
    if (finalAmount > 0) {
        //$('.finalAmount').html(`₹${finalAmount.toFixed(2)}`);
        if (taxOption == 'inclusive') {
            $('.finalAmount').html(`₹${Math.floor(finalAmount)}`);
        } else if (taxOption == 'exclusive') {
            $('.finalAmount').html(`₹${Math.floor(finalAmount + totalTaxPrice)}`);
        }
        $('.checkoutButton').removeClass('disabled-link');
    } else {
        $('.finalAmount').html(`₹0`);
        $('.checkoutButton').addClass('disabled-link');
    }
    
    $('#delivery').text(`₹${delivery.toLocaleString()}`);
    // $('#total-items').text(cartItems.length);
    $('#total-items').html(qty); 
}


// sandeep
function calculateCartTotals(cartItems) {

    let totalMrp = 0;
    let totalDiscount = 0;
    let totalTaxPrice = 0;
    let taxOption = 'inclusive';

    cartItems.forEach(function (item) {

        totalMrp += item.price * item.quantity;

        if (item.discountType == "flat") {
            totalDiscount += parseInt(item.discountAmount);
        } 
        else if (item.discountType == "percentage") {
            totalDiscount += ((parseInt(item.discountAmount) * totalMrp) / 100);
        }

        taxOption = item.tax_option;
        totalTaxPrice += item.tax_price;
    });

    let subTotal = totalMrp - totalDiscount;

    let couponDiscount = parseFloat(localStorage.getItem('coupon_discount')) || 0;

    let grandTotal = subTotal - couponDiscount;

    let finalAmount;

    if (taxOption == 'inclusive') {
        finalAmount = grandTotal + totalShippingCharge;
    } else {
        finalAmount = grandTotal + totalTaxPrice + totalShippingCharge;
    }

    return {
        totalMrp,
        totalDiscount,
        subTotal,
        couponDiscount,
        grandTotal,
        totalTaxPrice,
        taxOption,
        finalAmount
    };
}

//


$('.shipping_cancel').on('click', function () {
    $('#main_shipping').slideUp();
});

$('.billing_cancel').on('click', function () {
    $('#main_billing').slideUp();
});
// document.addEventListener("DOMContentLoaded", () => {
//     const wallet = document.getElementById("payment1");
//     const paymentRadios = document.querySelectorAll("input[name='pay_online']");
//     if (wallet) {
//         const toggleWallet = () => {
//             wallet.disabled = document.getElementById("payment3").checked;
//             if (wallet.disabled) wallet.checked = false;
//         };

//         paymentRadios.forEach(r => r.addEventListener("change", toggleWallet));
//         toggleWallet();
//     }
// });
function checkCOD() {
    // let limit = +$('#cash_on').val(),
    //     total = +$('.finalAmount').text().replace(/[^\d.]/g, '');
    // $('#payment3').prop('disabled', total >= limit).prop('checked', total >= limit ? false : $('#payment3').prop('checked'));
    // $('.limit').css('color', total >= limit ? 'red' : 'green')
    //     .html(`<i class="fa fa-info-circle" aria-hidden="true"></i>   Cash on Delivery available for orders up to ₹${limit}`);
}

$(document).ready(function () {
    checkCOD();
    new MutationObserver(checkCOD).observe(document.querySelector('.finalAmount'), { childList: true, subtree: true });
});
document.addEventListener('DOMContentLoaded', function () {
    let walletInput = document.getElementById('payment1');
    let walletAmount = parseFloat(document.getElementById('wallet_amount').textContent.replace(/[₹,]/g, '') || 0);

    if (walletAmount <= 0) {
        walletInput.disabled = true;
    }
});

//////////////Shipping Charge//////////////

$(document).ready(function () {
    const shipping_id = $('input[name="billaddress"]:checked').val() || 1;
    const subTotal = parseFloat($('#subtotal').val() || 0);
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    shippingCalculation(shipping_id, cartItems, subTotal);
});

$('[name="billaddress"]').on('change', function () {
    if ($('#same_as_billing').prop('checked')) {
        const shipping_id = $(this).val();
        const subTotal = parseFloat($('#subtotal').val());
        const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
        shippingCalculation(shipping_id, cartItems, subTotal);
    }
});
$('[name="shipaddress"]').on('change', function () {
    const shipping_id = $(this).val();
    const subTotal = parseFloat($('#subtotal').val());
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

    shippingCalculation(shipping_id, cartItems, subTotal);
});

function shippingCalculation(shipping_id, cartItems, subTotal) {
    const deliveryShippingCharge = document.getElementById('totalShippingCharge');
    $.ajax({
        url: getShippingAddress,
        method: 'POST',
        data: {
            shippingId: shipping_id,
            productData: cartItems,
            totalAmount: subTotal
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.success) {
                const data = response.data;
                totalShippingCharge = response.shippingPrice;
                if(deliveryShippingCharge){
                    deliveryShippingCharge.innerHTML = `₹${response.shippingPrice}`;
                }
            } else {
            }
            renderCart();
        },
        error: function (xhr) {
            console.error(xhr.responseText);
        }
    });
}