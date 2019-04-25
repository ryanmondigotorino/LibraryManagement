<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta-details')
    <link rel="stylesheet" href="{{ URL::asset('public/css/bootstrap/bootstrap.min.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('public/css/pageloader.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('public/css/profile.css') }} ">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{URL::asset('storage/uploads/app_images/mainfavicon.png')}}" type="image/png">
    <title>Home</title>

    @yield('pageCss')
</head>
<body onload="myFunction()">
    <div class="content1">
        @include("Landing.layout.nav-bar")
    </div>
    <div id="loader"></div>
    <div style="display:none;" id="myDiv" class="animate-bottom">
        <div class="content-container">
            @yield('content')
        </div>
    </div>
</body>

<script src="{{ URL::asset('public/js/bootstrap/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('public/js/bootstrap/popper.min.js') }}"></script>
<script src="{{ URL::asset('public/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/js/holder.min.js') }}"></script>
<script src="{{ URL::asset('public/js/sweetalert.js') }}"></script>
<script src="{{ URL::asset('public/js/jquery-validator.js') }}"></script>

<script>
    $(document).ready(function(){
        var path = window.location.pathname;
        if(path.includes('login') || path.includes('sign-up')){
            $('div.content1').addClass('d-none');
        }else{
            $('div.content1').removeClass('d-none');
            $('body').addClass('landing_page');
        }
    });
    var myVar;
    function myFunction() {
        myVar = setTimeout(showPage, 100);
    }
    function showPage() {
        document.getElementById("loader").style.display = "none";
        document.getElementById("myDiv").style.display = "block";
    }
</script>
@yield('pageJs')
</html>