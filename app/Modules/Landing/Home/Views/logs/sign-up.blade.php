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
                    <img class="img-fluid" src="{{URL::asset('storage/uploads/signup_images/crosswalk.jpg')}}" alt="Floating Books">
                    </div>
                    <div class="carousel-item active">
                    <img class="img-fluid" src="{{URL::asset('storage/uploads/signup_images/crowd.jpg')}}" alt="Books">
                    </div>
                    <div class="carousel-item">
                    <img class="img-fluid" src="{{URL::asset('storage/uploads/signup_images/people.jpg')}}" alt="Library">
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
        <div class="col-lg-6 col-xl-6 signup_form">
            <h2 class="text-center signup_title">CREATE YOUR ACCOUNT</h2><hr>
                <form class="sign-up-form" action="{{route('landing.home.sign-up-submit')}}">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" class="form-control" placeholder="Enter First name" name="firstname">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastname">Middle Name</label>
                                <input type="text" class="form-control" placeholder="(Optional)" name="middlename">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" class="form-control" placeholder="Enter Last name" name="lastname">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" placeholder="Enter Email" name="email">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-control">
                                    <option selected disabled>Choose Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="course">Course</label>
                                    <select name="course" class="form-control">
                                        <option selected disabled>Choose Course</option>
                                        @foreach($getCourses as $course)
                                            <option value="{{$course->id}}">{{$course->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <select name=department class="form-control">
                                        <option selected disabled>Choose Department</option>
                                        @foreach($getDepartments as $department)
                                            <option value="{{$department->id}}">{{$department->department_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" placeholder="Enter Username" name="username">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" id="confirmpassword">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-default pull-right" style="background-color:#800; color:#fff;" type="submit">SIGN UP</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
<script>
    $('form.sign-up-form').on('submit',function(event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function(){
                $('button[type="submit"].btn.btn-default').prop('disabled',true);
                $('button[type="submit"].btn.btn-default').html('<i class="fa fa-spinner fa-pulse"></i> Processing');
            },
            success:function(result){
                if(result['status'] == 'success'){
                    swal({
                        title: "Success",
                        text: 'Please Check your email for account verification.',
                        icon: result['status'],
                    }).then((resultStatus) => {
                        location.reload();
                    });
                }else{
                    $('button[type="submit"].btn.btn-default').prop('disabled',false);
                    swal({
                        title: "Error",
                        text: result['messages'],
                        icon: result['status'],
                    });
                }
            }
        }).done(function(){
            $('button[type="submit"].btn.btn-default').html('SIGN UP');
        });
    });
</script>
@endsection