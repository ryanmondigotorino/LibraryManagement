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
        <div class="col-lg-6 col-xl-6 mt-5">
            <h1><span class="fa fa-user"></span> Sign-up</h1><hr>
                <form class="sign-up-form">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" class="form-control" placeholder="Enter First name" name="firstname">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" class="form-control" placeholder="Enter Last name" name="lastname">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" placeholder="Enter Email" name="email">
                        </div>
                        <div class="col-md-6">
                            <label for="gender">Gender</label>
                            <select name="gender" class="form-control">
                                <option selected disabled>Choose...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="address">Address</label>
                            <textarea name="address" rows="2" class="form-control" placeholder="Enter Address"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="username">User Name</label>
                            <input type="text" class="form-control" placeholder="Enter User name" name="username">
                        </div>
                        <div class="col-md-6">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="confirmpassword">Confirm Password</label>
                            <input type="password" class="form-control" placeholder="Confirm Password" name="confirmpassword" id="confirmpassword">
                        </div>
                    </div><hr>
                    <div class="row">
                        <div class="col-md-10">
                            <button class="btn btn-secondary pull-right" type="submit">Create Account</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection