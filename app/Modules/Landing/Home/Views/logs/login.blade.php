@extends ('Landing.layout.main')
@section('pageCss')
@endsection

@section('content')
<div class="login_container">
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
                    <img class="img-fluid" src="{{URL::asset('storage/uploads/login_images/books.jpg')}}" alt="Floating Books">
                    </div>
                    <div class="carousel-item active">
                    <img class="img-fluid" src="{{URL::asset('storage/uploads/login_images/shelf.jpg')}}" alt="Books">
                    </div>
                    <div class="carousel-item">
                    <img class="img-fluid" src="{{URL::asset('storage/uploads/login_images/library.jpg')}}" alt="Library">
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
        <div class="col-lg-6 col-xl-6 mt-5 login_form">
            <h2 class="text-center bold">Welcome Back!</h2>
            <div class="row" style="margin-bottom:2%;">
                <div class="col-lg-7 col-xl-8 text-center ml-auto mr-auto">
                    <hr>
                    <form class="loginClick">
                        {{ csrf_field() }} 
                        <div class="form-group">
                            <label for="email_username">Email or Username</label>
                            <input type="text" class="form-control" name="email_username" placeholder="Enter here">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="col-md-12 btn btn-default login_button" style="background-color:#800; color:#fff;" name="sbmt">Login</button>
                        </div>
                    </form>
                    <small>Don't have an account? <a href="{{route('landing.home.sign-up')}}" class="create_link"> REGISTER. </a></small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




@section('pageJs')
<script>
    $('.loginClick').on('submit',function(event){
        event.preventDefault();
        $.ajax({
            type:"POST",
            url:"{{route('landing.home.login-submit')}}",
            data: $(this).serialize(),
            beforeSend: function(){
                $("button[type='submit']").html('<i class="fa fa-spinner fa-pulse"></i> Processing');
            },
            success: function(result){
                if(result['status'] == 'success'){
                    location.reload();
                }else if(result['status'] == 'warning'){
                    swal({
                        title: "Warning",
                        text: result['msg'],
                        icon: result['status'],
                        button: "Ok",
                    });
                }else{
                    swal({
                        title: "Error",
                        text: result['msg'],
                        icon: result['status'],
                        button: "Ok",
                    });
                }
            }
        }).done(function(){
            $("button[type='submit']").html('Login')
        });
    });
</script>
@endsection