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
                        <h1 class="h2"><span class="fa fa-area-chart"></span> Librarians Account</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover get-librarians">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Status</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Date registered</th>
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
@endsection

@section('pageJs')
<script>
    $(document).ready(function(){
        $(".get-librarians").DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            ajax: {
                url: "{{route('admin.dashboard.accounts.get-admins-account')}}",
                account_type: 'librarian'
            },
            createdRow : function(row, data, dataIndex){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            },
        });
    });
    function changeStat(id,acc_stat){
        var status;
        if(acc_stat == 0){
            status = "Activate Account?"
        }else{
            status = "Deactivate Account?"
        }
        swal({
            title: "Confirmation",
            text: status,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if(result){
                $.ajax({
                    type:"POST",
                    url:"{{route('admin.dashboard.accounts.change-acc-stat')}}",
                    data:{
                        'id': id,
                        'acc_stat':acc_stat,
                        'model': 'Admin',
                        '_token': '{{csrf_token()}}'
                    },
                    success:function(getResult){
                        var text = "";
                        if(getResult == 0){
                            text = "Status Successfully Deactivate!";
                        }else{
                            text = "Status Successfully Activate!";
                        }
                        swal({
                            title: "Success",
                            text: text,
                            icon: "success",
                        }).then((resultStatus) => {
                            location.reload();
                        });
                    }
                });
            }
        });
    }
</script>
@endsection