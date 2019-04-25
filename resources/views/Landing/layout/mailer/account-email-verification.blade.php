<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('css/bootstrap/bootstrap.min.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('css/pageloader.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('css/profile.css') }} ">
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <title>Lasedia Furniture - Account Verification</title>
    <style>
        body{
            font-family: 'Noto Serif', serif;
        }
    </style>
    @yield('pageCss')
</head>
<body onload="myFunction()">
    <div class="container mt-5">
        <div class="profile_container">
            <div class="row">
                <div class="card box_shad" style="width:100%;">
                    <div class="card-body">
                        <div class="profile_content">
                            <h1><span class="fa fa-check-circle-o"></span> Account Verification</h1><hr>
                            <div class="row">
                                <div class="col-lg-3">
                                    <h2>Activate your account</h2>
                                </div>
                                <div class="col-lg-7">
                                    <p>Dear {{$user['name']}}.</p>
                                    <p>Click the link below to redirect to our page.</p>
                                    <code><a href="http://{{ $_SERVER['HTTP_HOST']}}/sign-up/account-verification/{{ $user['username'] }}">Activate your account!</a></code>
                                    Use either your username or email to log in.
                                    <h4><strong>Login Details</strong></h4>
                                    <h4>Username: {{ $user['username'] }}</h4>
                                    <h4>Email: {{ $user['email'] }}</h4>
                                    <h4>Password: The one you used in during your registration</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ URL::asset('js/bootstrap/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap/popper.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('js/holder.min.js') }}"></script>
</html>