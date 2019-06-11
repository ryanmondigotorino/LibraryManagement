@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.nav-bar")
@endsection

@section('pageCss')
<style>
    body{
        background-image: url('/public/css/assets/student-landing-bg-img.png');
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-size: 100% 100%;

    }
    .content-container{
        margin-top:10%;
        margin-bottom:2%;
    }
    .search-bar{
        margin-top: 8%;
        color: #fff;
    }
    .card-container{
        background-color: #7FC4FD;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="content-container">
        <div class="row">
            <div class="card box_shad card-profile-container" style="width:100%">
                <div class="card-body">
                    <div class="profile_content">
                        <h1><span class="fa fa-edit"></span> Edit my account</h1><hr>
                        <form action="{{route('student.home.edit-settings-save',$base_data->username)}}" class="global-landing-form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="firstname">First Name</label>
                                        <input type="text" class="form-control" value="{{$base_data->firstname}}" placeholder="Enter First name" name="firstname">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="middlename">Middle Name</label>
                                        <input type="text" class="form-control" value="{{$base_data->middlename}}" placeholder="Enter Middle Name (Optional)" name="middlename">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="lastname">Last Name</label>
                                        <input type="text" class="form-control" value="{{$base_data->lastname}}" placeholder="Enter Last name" name="lastname">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" placeholder="Enter username" value="{{$base_data->username}}" name="username">
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="contactnumber">Contact Number</label>
                                        <input type="text" class="form-control" value="{{isset($base_data->contact_num) ? '0'.$base_data->contact_num : ''}}" placeholder="Enter Contact Number" name="contact_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="address">Address</label>
                                    <textarea name="address" rows="5" class="form-control" placeholder="Enter Address">{{$base_data->address}}</textarea>
                                </div>
                            </div><hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-success pull-right global-landing-form-btn" type="submit"><span class="fa fa-edit"></span> Edit account</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="content-container">
        <div class="row">
            <div class="card box_shad card-profile-container" style="width:100%">
                <div class="card-body">
                    <div class="profile_content">
                        <h1><span class="fa fa-edit"></span> Change password</h1><hr>
                        <form action="{{route('student.home.edit-password-save',$base_data->username)}}" class="global-landing-form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="oldpassword">Old password</label>
                                        <input type="password" class="form-control" placeholder="Enter Old password" name="old_password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="newpassword">New password</label>
                                        <input type="password" class="form-control" placeholder="Enter New password" name="new_password">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="confirmpassword">Confirm password</label>
                                        <input type="password" class="form-control" placeholder="Enter Confirm password" name="confirm_password">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-success pull-right global-landing-form-btn" type="submit"><span class="fa fa-edit"></span> Change password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection