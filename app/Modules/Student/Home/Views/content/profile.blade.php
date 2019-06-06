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
                        <div class="row" style="margin-bottom:2%;">
                            <div class="col-lg-4">
                                <img src="{{$base_data->image == null || $base_data->image == '' ? URL::asset('public/css/assets/profile_image/noimage.png') : URL::asset('storage/uploads/profile_image/user('.$base_data->id.')/'.$base_data->image)}}" alt="profile image" class="image_profile img-fluid">
                            </div>
                            <div class="col-lg-8">
                                <h2 style="margin-top:5%;">{{$base_data->firstname.$middlename.$base_data->lastname}}</h2>
                                <h5>{{$base_data->family_member}}</h5><hr>
                                <h6><b>Email:</b> {{$base_data->email}}</h6><hr>
                                <h6><b>Address: </b> {{$base_data->address == null || $base_data->address == '' ? 'Address not set.' : $base_data->address}}</h6>
                                <h6><b>Contact Number: </b> {{$base_data->contact_num != null || isset($base_data->contact_num) ? '(+63)-'.$base_data->contact_num : 'No Contact number'}}</h6>
                                <h6><b>Username: </b> {{$base_data->username}}</h6>
                                <button type="button" class="btn btn-success" style="margin-top:1%;" data-toggle="modal" data-target="#imgupload">Change Display Photo</button>
                            </div>
                        </div><hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include("Student.includes.profile-dp-modal-template")
@endsection

@section('pageJs')
@endsection