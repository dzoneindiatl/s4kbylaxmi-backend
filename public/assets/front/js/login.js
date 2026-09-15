
document.getElementById('customerSignin').addEventListener('submit', async function(e) {
    e.preventDefault(); // Stop default form submission
    let form = e.target;
    let isValid = true;

    // Clear previous errors
    document.getElementById('login-error').style.display = 'none';
    document.getElementById('login-error').innerHTML = '';
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    const showError = (input, message) => {
        input.classList.add('is-invalid');
        let error = document.createElement('div');
        error.className = 'invalid-feedback';
        error.innerText = message;
        input.parentNode.appendChild(error);
        isValid = false;
    };

    let email = form.querySelector('[name="email"]');
    let password = form.querySelector('[name="password"]');

    // Frontend validation
    if (email.value.trim() === '') {
        showError(email, 'Email is required');
    } else {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) showError(email, 'Enter a valid email');
    }

    if (password.value.trim() === '') {
        showError(password, 'Password is required');
    } else if (password.value.length < 6) {
        showError(password, 'Password must be at least 6 characters');
    }

    if (!isValid) return;
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    // Prepare form data
    const formData = new FormData(form);
    formData.append('cartItems', JSON.stringify(cartItems));

    const $btn = document.querySelector('#submit-btn'); // your button
    const originalHtml = $btn.innerHTML;
    $btn.disabled = true;
    $btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
        Processing...
    `;
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();

        $btn.disabled = false;
        $btn.innerHTML = originalHtml;
        if (response.ok) {
            location.reload();

        } else {
            // Handle validation or auth error
            if (result.errors) {
                // Laravel validation errors
                Object.keys(result.errors).forEach(key => {
                    let input = form.querySelector(`[name="${key}"]`);
                    if (input) showError(input, result.errors[key][0]);
                });
                console.log(' login error ', result.errors);
                document.getElementById('login-error').innerText = result.errors;
                document.getElementById('login-error').style.display = 'block';
                $btn.innerHTML = originalHtml;
                $btn.disabled = false;
            } else if (result.message) {
                // Custom error message from backend
                document.getElementById('login-error').innerText = result.message;
                document.getElementById('login-error').style.display = 'block';
                $btn.innerHTML = originalHtml;
                $btn.disabled = false;
            }
        }
    } catch (err) {
        location.reload();
        
        document.getElementById('login-error').innerText = err;
        document.getElementById('login-error').style.display = 'block';
        $btn.innerHTML = originalHtml;
        $btn.disabled = false;
    }
});


document.getElementById('customerSignup').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    let isValid = true;

    // Clear previous errors
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    const errorBox = document.getElementById('signup-error');
    errorBox.style.display = 'none';
    errorBox.innerText = '';

    const showError = (input, message) => {
        input.classList.add('is-invalid');
        const error = document.createElement('div');
        error.className = 'invalid-feedback';
        error.innerText = message;
        input.parentNode.appendChild(error);
        isValid = false;
    };

    const name = form.querySelector('[name="name"]');
    const email = form.querySelector('[name="email"]');
    const phone = form.querySelector('[name="phone_number"]');
    const password = form.querySelector('[name="password"]');
    const confirmPassword = form.querySelector('[name="confirm_password"]');

    // Frontend validation
    if (name.value.trim() === '') showError(name, 'Name is required');
  
    if (email.value.trim() === '') {
        showError(email, 'Email is required');
    } else {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value.trim())) showError(email, 'Enter a valid email');
    }

    if (phone.value.trim() === '') {
        showError(phone, 'Phone number is required');
    } else {
        const phonePattern = /^[0-9]{10}$/;
        if (!phonePattern.test(phone.value.trim())) showError(phone, 'Enter a valid 10-digit phone number');
    }

    if (password.value.trim() === '') {
        showError(password, 'Password is required');
    } else if (password.value.length < 6) {
        showError(password, 'Password must be at least 6 characters');
    }

    if (confirmPassword.value.trim() === '') {
        showError(confirmPassword, 'Confirm password is required');
    } else if (confirmPassword.value !== password.value) {
        showError(confirmPassword, 'Passwords do not match');
    }

    if (!isValid) return;

    // Submit via fetch
    const formData = new FormData(form);
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    formData.append('cartItems', JSON.stringify(cartItems));

    const $btn = document.querySelector('#submit-btn'); // your button
    const originalHtml = $btn.innerHTML;

    $btn.disabled = true;
    $btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
        Processing...
    `;
    
    try {
        $('.otp_message').html('We have sent a OTP on your email, please enter OTP for email verification.');
        // setTimeout(function(){  $('.otp_message').html('');  }, 5000);
        $('.register-otp').show();
        $('#customerSignup').hide();
        $('.sign-in-sec').hide();

        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });
        const result = await response.json();
        $btn.disabled = false;
        $btn.innerHTML = originalHtml;
        console.log('result : ', result.errors);
        if (result.errors) {  console.log('if');
            // Show validation or general errors
            $('.otp_message').html('');
            $('.register-otp').hide();
            $('#customerSignup').show();
            // $('.sign-in-sec').show();
            if (result.errors) {
                Object.keys(result.errors).forEach(key => {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) showError(input, result.errors[key][0]);
                });
            } else if (result.message) {
                errorBox.innerText = result.message;
                errorBox.style.display = 'block';
            }
            
        } else {  console.log('else');

            // Redirect on success
            $('.user_id').val(result.user_id);
            setTimeout(function(){  $('.login_sucesss_message').html(result.message)  }, 5000);
            
            //$('.otp_message').html(result.message);
            //setTimeout(function(){  $('.otp_message').html('');  }, 5000);
           // $('.register-otp').show();
           // $('#customerSignup').hide();
           // $('.sign-in-sec').hide();
        }
    } catch (err) {
        errorBox.innerText = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    }
});

document.getElementById('postSignupVerify').addEventListener('submit', async function (e) {
    e.preventDefault();
    const form = e.target;
    let isValid = true;

    // Clear previous errors
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    const errorBox = document.getElementById('signup-error');
    errorBox.style.display = 'none';
    errorBox.innerText = '';

    const showError = (input, message) => {
        input.classList.add('is-invalid');
        const error = document.createElement('div');
        error.className = 'invalid-feedback';
        error.innerText = message;
        input.parentNode.appendChild(error);
        isValid = false;
    };

    // Clear previous errors
    const email_otp = form.querySelector('[name="email_otp"]');
    
    // Frontend validation
    if (email_otp.value.trim() === '') {
        showError(email_otp, 'Phone number is required');
    } else {
        const otpPattern = /^[0-9]{6}$/;
        if (!otpPattern.test(email_otp.value.trim())) showError(email_otp, 'Enter a valid 6-digit OTP');
    }

    if (!isValid) return;

    // Submit via fetch
    const formData = new FormData(form);
    let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    formData.append('cartItems', JSON.stringify(cartItems));

    const $btn = document.querySelector('#submit-otp-btn'); // your button
    const originalHtml = $btn.innerHTML;

    $btn.disabled = true;
    $btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
        Processing...
    `;
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                'Accept': 'application/json'
            },
            body: formData
        });

        const result = await response.json();
        $btn.disabled = false;
        $btn.innerHTML = originalHtml;
        if (result.status) {
            // Redirect on success
          // location.reload();
           window.location.href = dashboardUrl;
        } else {
            // Show validation or general errors
            $('.otp_error_msg').html('OTP is not matched')
        }
    } catch (err) {
        errorBox.innerText = 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
    }
});

// Show error message
    function showError(message) {
        // Create a toast notification or alert
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            // Create a custom notification
            const notification = $(`
                <div class="alert alert-danger alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="ri-error-warning-line me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            $('body').append(notification);
            setTimeout(() => notification.remove(), 5000);
        }
    }
    
    // Show success message
    function showSuccess(message) {
        if (typeof toastr !== 'undefined') {
            toastr.success(message);
        } else {
            const notification = $(`
                <div class="alert alert-success alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    <i class="ri-check-line me-2"></i>${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `);
            $('body').append(notification);
            setTimeout(() => notification.remove(), 3000);
        }
    }


