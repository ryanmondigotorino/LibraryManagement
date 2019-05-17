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
                                <h1 class="h2"><span class="fa fa-table"></span> Course</h1>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-secondary add-course"><span class="fa fa-plus"></span> Add Course</button>
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover global-landing-table" data-url="{{route('admin.course.get-courses')}}" data-loader="{{URL::asset("public/icons/loading.gif")}}">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Course name</th>
                                            <th>Date created</th>
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
<div class="modal fade" id="add-course" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Course</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.course.add-courses')}}" class="global-landing-form">@csrf
                    <div class="form-group">
                        <label for="coursename">Course Name</label>
                        <input type="text" class="form-control" name="coursename" placeholder="Enter Course Name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-secondary global-landing-form-btn">Add Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@foreach($getCourse as $course)
    <div class="modal fade" id="edit-course-{{$course->id}}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Course</h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.course.edit-courses')}}" class="global-landing-form">@csrf
                        <div class="form-group">
                            <label for="coursename">Course Name</label>
                            <input type="text" class="form-control" name="coursename" value="{{$course->name}}" placeholder="Enter Course Name">
                        </div>
                        <input type="hidden" name="courseid" value="{{$course->id}}">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-secondary global-landing-form-btn">Edit Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('pageJs')
@endsection