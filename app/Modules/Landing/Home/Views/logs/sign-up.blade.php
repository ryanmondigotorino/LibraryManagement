@extends ('Landing.layout.main')
@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="card box_shad" style="width:100%">
            <div class="card-body">
                <div class="profile_content">
                    <h1><span class="fa fa-user"></span> Sign-up</h1><hr>
                    <form class="sign-up-form">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="firstname">First Name</label>
                                    <input type="text" class="form-control" placeholder="Enter First name" name="firstname">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" placeholder="Enter Last name" name="lastname">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" placeholder="Enter Email" name="email">
                            </div>
                            <div class="col-lg-6">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-control">
                                    <option selected disabled>Choose...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="address">Address</label>
                                <textarea name="address" rows="5" class="form-control" placeholder="Enter Address"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="username">User Name</label>
                                <input type="text" class="form-control" placeholder="Enter User name" name="username">
                            </div>
                            <div class="col-lg-6">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" placeholder="Enter Password" name="password" id="password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="confirmpassword">Confirm Password</label>
                                <input type="password" class="form-control" placeholder="Confirm Password" name="confirmpassword" id="confirmpassword">
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <button class="btn btn-secondary pull-right" type="submit">Create Account</button>
                            </div>
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