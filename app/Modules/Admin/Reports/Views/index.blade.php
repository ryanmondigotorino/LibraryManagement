@extends ('Admin.layout.main')
@section('pageCss')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="col-md-3 col-lg-2"></div>
        <div class="col-md-9 col-lg-10">
            <div class="card" style="width:100%">
                <div class="card-body">
                    <div class="profile_content">
                        <h1 class="h2"><span class="fa fa-table"></span> Monthly Borrowed Books</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <canvas id="canvasBorrowed" data-url="{{route('admin.dashboard.get-borrowed-books-chart')}}"></canvas>
                            </div>
                        </div>
                        <h1 class="h2"><span class="fa fa-table"></span> Monthly Borrowed Returned Books</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <canvas id="canvasReturned" data-url="{{route('admin.reports.get-returned-books-chart')}}"></canvas>
                            </div>
                        </div><hr>
                        <h1 class="h2"><span class="fa fa-table"></span> Returned Books</h1><hr>
                        <div class="row mt-5">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="w-50 pr-1">
                                        <input type="text" class="dates form-control" name="dates">
                                    </div>
                                    <div>
                                        <button class="btn btn-secondary runSearch" type="button">Run</button>
                                        <button type="button" class="btn btn-secondary download-reports"><span class="fa fa-download"></span> Download Logs</button>
                                    </div>
                                </div>
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover global-audit-table" data-url="{{route('admin.reports.get-returned-books')}}" data-loader="{{URL::asset("public/icons/loading.gif")}}">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Student Number</th>
                                            <th>Student Name</th>
                                            <th>Books</th>
                                            <th>Date Return</th>
                                            <th>Penalty</th>
                                            <th>Status</th>
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
@endsection

@section('pageJs')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    GlobalTable.AUDITS();
    GlobalGraph.INIT();
</script>
@endsection