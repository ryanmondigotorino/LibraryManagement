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
                        <h1 class="h2"><span class="fa fa-area-chart"></span> Monthly Borrowed Books Count</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <canvas id="canvasBorrowed" data-url="{{route('admin.dashboard.get-borrowed-books-chart')}}"></canvas>
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
<script>
    GlobalGraph.CANVASBORROWED();
</script>
@endsection