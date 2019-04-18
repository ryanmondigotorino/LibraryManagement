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
                        <h1 class="h2"><span class="fa fa-table"></span> Administrator Logs</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover get-admin-logs">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Type</th>
                                            <th>Username</th>
                                            <th>Action</th>
                                            <th>IP Address</th>
                                            <th>Device Used</th>
                                            <th>Browser Used</th>
                                            <th>OS Used</th>
                                            <th>Created</th>
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
<script>
    $(document).ready(function(){
        $(".get-admin-logs").DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            ajax: {
                url: "{{route('admin.dashboard.accounts.get-admin-logs')}}",
            },
            createdRow : function(row, data, dataIndex){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            },
        });
    });
</script>
@endsection