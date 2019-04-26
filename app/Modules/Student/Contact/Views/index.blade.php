@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.nav-bar")
@endsection

@section('pageCss')
<style>
    body{
        background-color:#534D4D;
    }
    .content-container{
        margin-top: 9%;
    }
    .about_img{
        width: 300px;
        height: 500px;
        margin: 0 3% 0 20%;
    }
    .inner-box{
        background-color: rgba(255, 255, 255, 0.5);
        width: 100%;
        height: 430px;
    }
    .outer-box{
        background-color: rgba(0, 0, 0, 0.8);
        width: 100%;
        height: 440px;
        position: absolute;
        left: 5%;

    }
    .img-first, .img-second, .img-third{
        border-radius: 50%;
        width: 125px;
        height: 125px;
        margin: 2% 15% 2px 5%;
    }
    .desc{
        color: #A94C4C;
        margin-top: 4%;
    }
    .footer{
        background-color: rgba(12, 3, 3, 0.7);
        position: absolute;
        left: 0;
        bottom: 0;
        height: 30px;
        width: 100%;
    }
</style>
@endsection

@section('content')
<div class="content-container">
    <div class="row">
        <div class="col-lg-4">
            <img class="about_img" src="{{URL::asset('public/css/assets/contact_images/about-us-bg-img.png')}}">
        </div>
        <div class="col-lg-7">
            <div class="inner-box">
                <h3 style="font-weight:bold; color: #A94C4C; padding: 5px 0 0 5px; letter-spacing: 3px;">ABOUT US</h3>
                <div class="outer-box">
                    <div class="row">
                        <img class="img-first" src="{{URL::asset('public/css/assets/contact_images/Ryan.jpg')}}" alt="Ryan">
                        <div class="desc">
                            <h4>Ryan Torino</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit<p>
                        </div>
                    </div>
                    <div class="row">    
                        <img class="img-second" src="{{URL::asset('public/css/assets/contact_images/Henrie.jpg')}}" alt="Henrie">
                        <div class="desc">
                            <h4>Henrieta Avelino</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit<p>
                        </div>
                    </div>
                    <div class="row">
                        <img class="img-third" src="{{URL::asset('public/css/assets/contact_images/Ellis.jpg')}}" alt="Ellis">
                        <div class="desc">
                            <h4>Ellis Hilao</h4>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit<p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection