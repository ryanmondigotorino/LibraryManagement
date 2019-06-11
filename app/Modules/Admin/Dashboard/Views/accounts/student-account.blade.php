@extends ('Admin.layout.main')
@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="col-md-3 col-lg-2"></div>
        <div class="col-md-9 col-lg-10">
            <div class="card" style="width:100%">
                <div class="card-body">
                    <div class="profile_content">
                        <div class="row">
                            <div class="col-lg-10">
                                <h1 class="h2"><span class="fa fa-home"></span> Students Account</h1>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-secondary add-students-account"><span class="fa fa-plus"></span> Add Students Account</button>
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <h3 class="text-center">Password will expire in: 5 days</h3>
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover global-accounts-table" data-url="{{route('admin.dashboard.accounts.get-students-account')}}" data-type="none" data-loader="{{URL::asset("public/icons/loading.gif")}}">
                                    <thead>
                                        <tr>
                                            <th>Student No.</th>
                                            <th>Status</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Course</th>
                                            <th>Department</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add-students-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add students</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.dashboard.accounts.add-students-account')}}" class="global-landing-form">@csrf
                    <div class="form-group">
                        <label for="coursename">Course Name</label>
                        <select name="coursename" class="form-control">
                            <option selected disabled>Choose...</option>
                            @foreach($getCourse as $course)
                                <option value="{{$course->id}}">{{$course->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="departmentname">Department Name</label>
                        <select name="departmentname" class="form-control">
                            <option selected disabled>Choose...</option>
                            @foreach($getDepartment as $department)
                                <option value="{{$department->id}}">{{$department->department_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" class="form-control" name="firstname" placeholder="Enter First Name">
                    </div>
                    <div class="form-group">
                        <label for="middlename">Middle Name</label>
                        <input type="text" class="form-control" name="middlename" placeholder="Enter Middle Name">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" class="form-control" name="lastname" placeholder="Enter Last Name">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email">
                    </div>
                    <div class="form-group">
                        <label for="username">User Name</label>
                        <input type="text" class="form-control" name="username" placeholder="Enter User name">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirm Password</label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="Enter Confirm Password">
                    </div>
                    <input type="hidden" name="account_status" value="students">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-secondary global-landing-form-btn">Create Students</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection