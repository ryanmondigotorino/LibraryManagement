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
                                <h1 class="h2"><span class="fa fa-home"></span> Librarians Account</h1>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-secondary add-librarian-account"><span class="fa fa-plus"></span> Add Librarian Account</button>
                            </div>
                        </div><hr>
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
<div class="modal fade" id="add-librarian-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add librarian</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.dashboard.accounts.add-admins-account')}}" class="add-librarian-form">@csrf
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
                    <input type="hidden" name="account_status" value="librarian">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-secondary create-librarian">Create Librarian</button>
                    </div>
                </form>
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
                data: {
                    account_type: 'librarian'
                }
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
    $('.add-librarian-account').on('click',function(){
        $('#add-librarian-modal').modal();
    });
    $('.add-librarian-form').on('submit',function(event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url : $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function(){
                $('button[type="submit"].create-librarian').prop('disabled',true);
                $('button[type="submit"].create-librarian').html('<i class="fa fa-spinner fa-pulse"></i> Processing');
            },
            success: function(result){
                if(result['status'] == 'error'){
                    $('button[type="submit"].create-librarian').prop('disabled',false);
                    $('button[type="submit"].create-librarian').html('Create Librarian');
                    swal({
                        title: "Error!",
                        text: result['messages'],
                        icon: result['status'],
                    });
                }else{
                    $('button[type="submit"].create-librarian').html('Create Librarian');
                    swal({
                        title: "Success",
                        text: result['messages'],
                        icon: result['status'],
                    }).then((resultStatus) => {
                        location.reload();
                    });
                }
            }
        });
    });
</script>
@endsection