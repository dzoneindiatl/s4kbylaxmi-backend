<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@php
    $siteLogo = \App\Models\Setting::where('key','Site.logo')->first();
    $favIcon = \App\Models\Setting::where('key','Site.fav_icon')->first();
@endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furnish world</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ env('WEBSITE_URL').'uploads/settings/'.@$favIcon->value }}" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ env('WEBSITE_URL').'assets/front/css/style_home.css' }}">
    <link rel="stylesheet" type="text/css" href="{{ env('WEBSITE_URL').'assets/front/tejap/css/style.css' }}">
</head>
<body> 
  
@csrf 
<section class="site-content mt-5">
      <div class="content-wrapper">
        <div class="container">
            <div class="my-5 d-flex justify-content-center">
                <a href="{{ env('WEBSITE_URL') }}">
                    <img src="{{ env('WEBSITE_URL') .'uploads/settings/'.@$siteLogo->value }}" alt="logo"
                        class="desktop-logo" style="height: 12rem;line-height: 4rem;">  
                </a>
            </div>
          
          <div class="content-area">
            
            <form action="{{ route('admin-verify-login') }}" method="post" autocomplete="off"> 
                @csrf
                <div class="col-lg-4 col-md-6 col-sm-10 mx-auto">
                    <div class="loginregister-area">
                        <div class="page-header text-center">
                            <h1 class="page-title">Sign In</h1>
                        </div>
                        <div class="loginregister-header">
                        <h3>Welcome</h3>
                        <p>Enter your email address to sign in.</p>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Whoops!</strong> There were some problems with your input.
                                <ul class="mt-2 mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="form-focus row">
                            <div class="form-group col-12">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email*" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group col-12">
                            <div class="password-group">
                                <input type="password" class="form-control password-input" name="password" id="password" placeholder="Password*" required>
                                {{-- <span class="password-icon">SHOW</span> --}}
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>                                           
                            </div>
                            <input type="hidden" name="cartItems" id="loginCartItems">
                            {{-- <div class="form-group col-12">
                                <a href="" class="forgot-pass">Forgot Password?</a>
                            </div>                     --}}
                        </div>
                        <div class="form-submit">
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                        {{-- <div class="login-social">
                            <p>Login with social account</p>
                            <ul class="login-social-icon">
                            <li class="facebook"><a target="_blank" href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li class="facebook"><a target="_blank" href="#"><i class="fa-brands fa-google"></i></a></li>                         
                            </ul>
                        </div>          
                        <div class="loginregister-footer">
                        <p>Don't have an account? <a href="" class="ms-2 text-decoration-underline">Register</a></p>
                        </div> --}}
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ env('WEBSITE_URL').'assets/js/custom.js' }}"></script> 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>