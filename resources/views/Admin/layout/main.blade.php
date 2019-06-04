<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ URL::asset('public/css/bootstrap/bootstrap.min.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('public/css/pageloader.css') }} ">
    <link rel="stylesheet" href="{{ URL::asset('public/css/adminprofile.css') }} ">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{URL::asset('public/css/assets/mainfavicon.png')}}" type="image/png">
    <link rel="stylesheet" href="{{URL::asset('public/css/colors.css')}}">
    <title>Dashboard</title>
    @yield('pageCss')
</head>
<body onload="myFunction()" style="margin:0;background-color:lightgray;">
    @include("Admin.layout.nav-bar")
    <div id="loader"></div>
    <div style="display:none;" id="myDiv" class="animate-bottom">
        @yield('content')
    </div>
</body>
<script src="{{ URL::asset('public/js/bootstrap/jquery-3.3.1.min.js') }}"></script>
<script src="{{ URL::asset('public/js/bootstrap/popper.min.js') }}"></script>
<script src="{{ URL::asset('public/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/js/holder.min.js') }}"></script>
<script src="{{ URL::asset('public/js/sweetalert.js') }}"></script>
<script src="{{ URL::asset('public/js/jquery-validator.js') }}"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
<script src="{{ URL::asset('public/js/jspdf/jspdf.min.js') }}"></script>
<script src="{{ URL::asset('public/js/jspdf/jspdf.plugin.addhtml.js') }}"></script>
<script src="{{ URL::asset('public/js/jspdf/jspdf.plugin.split_text_to_size.js') }}"></script>
<script src="{{ URL::asset('public/js/jspdf/jspdf.plugin.standard_fonts_metrics.js') }}"></script>
<script src="{{ URL::asset('public/js/includes/global-landing-form.class.js') }}"></script>
<script src="{{ URL::asset('public/js/includes/global-landing-search.class.js') }}"></script>
<script src="{{ URL::asset('public/js/includes/global-landing-table.class.js') }}"></script>
<script src="{{ URL::asset('public/js/includes/global-image-form.class.js') }}"></script>
<script src="{{ URL::asset('public/js/includes/global-report-graph.class.js') }}"></script>
<script>
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