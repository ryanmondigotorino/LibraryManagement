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
                        <h1 class="h2"><span class="fa fa-table"></span> Administrator Logs</h1><hr>
                        <div class="row mt-5">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="w-50 pr-1">
                                        <input type="text" class="dates form-control" name="dates">
                                    </div>
                                    <div>
                                        <button class="btn btn-secondary runSearch" type="button">Run</button>
                                        <button type="button" class="btn btn-secondary download-reports"><span class="fa fa-download"></span> Download Reports</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(document).ready(function(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();
        today = dd + '/' + mm + '/' + yyyy;
        let params = new URLSearchParams(window.location.search);
        let dateFrom = '';
        let dateTo = '';
        if(params.get("date") == undefined){
            dateFrom = today
            dateTo = today
        }else{
            dateFrom = params.get("date").split(' - ')[0];
            dateTo = params.get("date").split(' - ')[1];
        }
        $('input[name="dates"]').daterangepicker({
            opens : 'left',
            applyButtonClasses : 'btn--teal',
            cancelButtonClasses : 'btn-danger',
            autoApply: true,
            locale: { format: 'DD/MM/Y' },
            startDate: dateFrom, 
            endDate: dateTo
        });
        var dateSerialize = 'date=' + $("input[name='dates']").val();
        history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + dateSerialize);
        $(".get-admin-logs").DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            ajax: {
                url: "{{route('admin.dashboard.accounts.get-admin-logs')}}",
                data:{
                    datePicker: $("input[name='dates']").val(),
                }
            },
            createdRow : function(row, data, dataIndex){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            },
        });
    });
    $('.runSearch').on('click',function(event){
        var dateSerialize = 'date=' + $("input[name='dates']").val();
        history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + dateSerialize);
        location.reload();
    });
    $('.download-reports').on('click',function(){
        window.open("/accounts/admin-audit/download-xlsx"+window.location.search);
    });
</script>
@endsection