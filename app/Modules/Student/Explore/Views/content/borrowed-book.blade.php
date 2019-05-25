@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.nav-bar")
@endsection

@section('pageCss')
<style>
    body{
        background-color:#534D4D;
    }
</style>
@endsection

@section('content')
<div class="profile_container ml-4">
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <div class="card" style="width:100%;margin-top:80px;">
                <div class="card-body">
                    <div class="profile_content">
                        <h1 class="h2"><span class="fa fa-table"></span> Borrowed Books</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover global-landing-table" data-url="{{route('student.explore.get-borrowed-books')}}" data-loader="{{URL::asset("public/icons/loading.gif")}}">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Student Number</th>
                                            <th>Student Name</th>
                                            <th>Books</th>
                                            <th>Date Return</th>
                                            <th>Penalty</th>
                                            <th>Status</th>
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
@foreach($getBorrowedDetails as $borrow)
<div class="modal fade" id="renewal-books-{{$borrow->id}}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Renewal of borrowing</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route("student.explore.borrowed-books-renew")}}" class="global-landing-form">@csrf
                    <div class="form-group">
                        <label for="days">Number of days extended</label>
                        <input type="number" class="form-control" name="days_extend" placeholder="Enter Number of days">
                    </div>
                    <input type="hidden" name="id" value="{{$borrow->id}}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-secondary global-landing-form-btn renew-books">Renew now</button>
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