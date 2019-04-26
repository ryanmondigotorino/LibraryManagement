@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.nav-bar")
@endsection

@section('pageCss')
<style>
    body{
        background-image: url('/public/css/assets/landing_bg.png');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 585px;
    }
    .content-container{
        margin-top: 6%;
    }
    .outer-bg{

    }
</style>
@endsection

@section('content')
<div class="content-container">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 search-bar">
            
        </div>
        <div class="col-lg-4"></div>    
    </div>  
</div>
@endsection

@section('pageJs')
@endsection