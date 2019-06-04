@extends ('Landing.layout.main')
@section('pageCss')
@endsection

@section('content')
<div class="login_container">
    <div class="row">
        <div class="col-lg-6">
            <div id="slider" class="carousel slide" data-ride="carousel">
                <ul class="carousel-indicators">
                    <li data-target="#slider" data-slide-to="0"></li>
                    <li data-target="#slider" data-slide-to="1"  class="active"></li>
                    <li data-target="#slider" data-slide-to="2"></li>
                </ul>

                <div class="carousel-inner">
                    <div class="carousel-item">
                    <img class="img-fluid" src="{{URL::asset('public/css/assets/login_images/books.jpg')}}" alt="Floating Books">
                    </div>
                    <div class="carousel-item active">
                    <img class="img-fluid" src="{{URL::asset('public/css/assets/login_images/shelf.jpg')}}" alt="Books">
                    </div>
                    <div class="carousel-item">
                    <img class="img-fluid" src="{{URL::asset('public/css/assets/login_images/library.jpg')}}" alt="Library">
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
            <h2 class="text-center bold login-title">WELCOME</h2>
            <div class="row">
                <div class="col-sm-7 col-lg-7 col-xl-8 text-center ml-auto mr-auto">
                    <hr>
                    <form action="{{route('landing.home.login-submit')}}" class="global-landing-form">
                        {{ csrf_field() }} 
                        <div class="form-group">
                            <label for="email_username">Email or Username</label>
                            <input type="text" class="form-control username" name="email_username" placeholder="Username or Email">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="col-md-12 btn btn-default global-landing-form-btn" style="background-color:#800; color:#fff;" name="sbmt">LOGIN</button>
                        </div>
                    </form>
                    <small>Don't have an account? <a href="{{route('landing.home.sign-up')}}" class="create_link"> REGISTER. </a></small>
                    <br><small><a href="{{route('landing.home.forgotpassword')}}" class="create_link">Forgot passwoord? </a></small>
                </div>
            </div>
        </div>
    </div>
@endsection




@section('pageJs')
@endsection