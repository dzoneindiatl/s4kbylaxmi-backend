
$(document).ready(function () {
    $('#saveAddressBtn').on('click', function (e) {
        e.preventDefault();

        // Clear previous error states
        $('#addAddressForm input, #addAddressForm select').removeClass('is-invalid');

        let isValid = true;

        // Validate required fields
        const requiredFields = [
            { name: 'country', selector: '#country', invalidValue: 'Country/Region' },
            { name: 'firstname', selector: 'input[name="firstname"]' },
            { name: 'lastname', selector: 'input[name="lastname"]' },
            { name: 'address', selector: 'input[name="address"]' },
            { name: 'state', selector: '#state', invalidValue: 'State' },
            { name: 'city', selector: '#city', invalidValue: 'City' },
            { name: 'pinCode', selector: 'input[name="pinCode"]' },
            { name: 'phone', selector: 'input[name="phone"]' },
        ];

        requiredFields.forEach(field => {
            const $el = $(field.selector);
            const value = $el.val().trim();
            if (!value || (field.invalidValue && value === field.invalidValue)) {
                $el.addClass('is-invalid');
                isValid = false;
            }
        });

        // Additional validation for PIN and phone
        const pin = $('input[name="pinCode"]').val().trim();
        const phone = $('input[name="phone"]').val().trim();

        if (!/^\d{5,6}$/.test(pin)) {
            $('input[name="pinCode"]').addClass('is-invalid');
            isValid = false;
        }

        if (!/^\d{10}$/.test(phone)) {
            $('input[name="phone"]').addClass('is-invalid');
            isValid = false;
        }

        if (isValid) {
            const formData = new FormData(document.getElementById('addAddressForm'));
        
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
                console.log(data);
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
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

    $('#cancelAddressBtn').on('click', function () {
        $('#add-address').modal('hide');
    });
});


  /* Billing Address country */
    
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
                    $('.city').html(options);
                }
            });
        } else {
            $('#city').html('<option value="">Select City</option>');
        }
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

    
     $(document).ready(function () {
        $('.edit-address-btn').on('click', function () {
            const addressId = $(this).data('id');
    
            $.ajax({
                url: '/get-user-address/' + addressId,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    
                    //console.log(response);
                    // Populate fields
                    $('#editAddressForm select[name="country"]').val(response.country);
                    $('#editAddressForm input[name="firstname"]').val(response.firstname);
                    $('#editAddressForm input[name="lastname"]').val(response.lastname);
                    $('#editAddressForm input[name="address"]').val(response.address);
                    $('#editAddressForm input[name="addressSecond"]').val(response.addressSecond);
                    $('#editAddressForm select[name="state"]').val(response.state);
                    $('#editAddressForm select[name="city"]').html(`<option value="${response.city}" selected>${response.city_name}</option>`);
                    $('#editAddressForm input[name="pinCode"]').val(response.pinCode);
                    $('#editAddressForm input[name="phone"]').val(response.phone);
                    $('#editAddressForm input[name="addressId"]').val(response.address_id);
    
                    $('#editAddressForm input[name="address_place_type"][value="' + response.address_place_type + '"]').prop('checked', true);
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                    alert('Something went wrong while fetching address.');
                }
            });
        });
    });

    // Already script working in script.blade file 
    // $(document).ready(function () {
    //     $('#updateAddressBtn').on('click', function (e) {
    //         e.preventDefault();
    
    //         // Clear previous errors
    //         $('#editAddressForm input, #editAddressForm select').removeClass('is-invalid');
    //         $('.error-msg').remove();
    
    //         // let isValid = true;
    
    //         // const requiredFields = [
    //         //     { name: 'country', selector: '#country', invalidValue: 'Country/Region' },
    //         //     { name: 'firstname', selector: 'input[name="firstname"]' },
    //         //     { name: 'lastname', selector: 'input[name="lastname"]' },
    //         //     { name: 'address', selector: 'input[name="address"]' },
    //         //     { name: 'state', selector: '#state', invalidValue: 'State' },
    //         //     { name: 'city', selector: '#city', invalidValue: 'City' },
    //         //     { name: 'pinCode', selector: 'input[name="pinCode"]' },
    //         //     { name: 'phone', selector: 'input[name="phone"]' }
    //         // ];
    
    //         // // Validate each field
    //         // requiredFields.forEach(field => {
    //         //     const el = $(field.selector);
    //         //     const val = el.val()?.trim();
    
    //         //     if (!val || (field.invalidValue && val === field.invalidValue)) {
    //         //         el.addClass('is-invalid');
    //         //         el.after('<span class="error-msg text-danger">This field is required.</span>');
    //         //         isValid = false;
    //         //     }
    //         // });
    
    //         // // Validate radio button
    //         // if (!$('input[name="address_place_type"]:checked').val()) {
    //         //     $('input[name="address_place_type"]').last().closest('.inner_check_box')
    //         //         .append('<span class="error-msg text-danger d-block mt-2">Please select address type.</span>');
    //         //     isValid = false;
    //         // }
    
    //         // if (!isValid) return;
    
    //         // Serialize form data
    //         const formData = $('#editAddressForm').serialize();
            
    //         console.log(formData);
    
    //         // AJAX submission
    //         $.ajax({
    //             url: '/update-address', // Change to your actual route
    //             method: 'POST',
    //             data: formData,
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Required for Laravel
    //             },
    //             success: function (response) {
    //                 if (response.success) {
    //                     alert('Address updated successfully!');
    //                     $('#edit-address').modal('hide');
    //                     // Optionally reload address list
    //                     location.reload();
    //                 } else {
    //                     alert(response.message || 'Failed to update address.');
    //                 }
    //             },
    //             error: function (xhr) {
    //                 console.log(xhr.responseText);
    //                 alert('Something went wrong. Please try again.');
    //             }
    //         });
    //     });
    
    //     // Cancel button action
    //     $('#cancelAddressBtn').on('click', function () {
    //         $('#edit-address').modal('hide');
    //     });
    // });
    
    // Copy to clipboard
   document.getElementById('copyBtn').addEventListener('click', function () {
    const btn = this;
    const textToCopy = btn.textContent;
    
        // Copy to clipboard
        navigator.clipboard.writeText(textToCopy).then(function () {
            // Change background and title
            btn.style.backgroundColor = 'green';
            btn.title = 'Copied!';
    
            // Optionally reset after 2 seconds
            setTimeout(() => {
                btn.style.backgroundColor = ''; // Revert to original
                btn.title = 'Click to copy';
            }, 2000);
        }).catch(function (err) {
            console.error('Copy failed', err);
        });
    });

