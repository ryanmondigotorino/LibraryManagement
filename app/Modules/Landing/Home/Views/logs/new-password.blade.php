@extends ('Landing.layout.main')
@section('pageCss')
@endsection

@section('content')
<div class="signup_container">
    <div class="row">
        <div class="col-lg-6 col-xl-6">
            <div id="slider" class="carousel slide" data-ride="carousel">

                <ul class="carousel-indicators">
                    <li data-target="#slider" data-slide-to="0"></li>
                    <li data-target="#slider" data-slide-to="1"  class="active"></li>
                    <li data-target="#slider" data-slide-to="2"></li>
                </ul>

                <div class="carousel-inner">
                    <div class="carousel-item">
                    <img class="img-fluid" src="{{URL::asset('public/css/assets/signup_images/crosswalk.jpg')}}" alt="Floating Books">
                    </div>
                    <div class="carousel-item active">
                    <img class="img-fluid" src="{{URL::asset('public/css/assets/signup_images/crowd.jpg')}}" alt="Books">
                    </div>
                    <div class="carousel-item">
                    <img class="img-fluid" src="{{URL::asset('public/css/assets/signup_images/people.jpg')}}" alt="Library">
                    </div>
                </div>

                <a class="carousel-control-prev" href="#slider" data-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </a>
                <a class="carousel-control-next" href="#slider" data-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </a>

            </div>
        </div>
        <div class="col-lg-6 mt-5 text-center login_form">
            <a href="{{route('landing.home.index')}}"><img class="logo" src="{{URL::asset('public/css/assets/mainfavicon.png')}}" alt=""></a>
            <h2 class="text-center bold login-title">Set a new password</h2>
            <div class="row">
                <div class="col-sm-7 col-lg-7 col-xl-8 text-center ml-auto mr-auto mt-5">
                    <form action="{{route('landing.home.new-password-submit',[$id,$studno])}}" class="global-landing-form">
                        {{ csrf_field() }} 
                        <div class="form-group mb-4">
                            <label for="oldpassword">Old password </label>
                            <input type="password" class="form-control" name="oldpassword" placeholder="Enter your old password">
                        </div>
                        <div class="form-group mt-5">
                            <label for="newpassword">New password </label>
                            <input type="password" class="form-control" name="new_password" placeholder="Enter your new password">
                        </div>
                        <div class="form-group">
                            <label for="confirmpassword">confirm password </label>
                            <input type="password" class="form-control" name="confirm_password" placeholder="Enter confirm password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="col-md-12 btn btn-default global-landing-form-btn" style="background-color:#800; color:#fff;" name="sbmt">Change password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection