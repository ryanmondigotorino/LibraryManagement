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
                        <h1 class="h2"><span class="fa fa-table"></span> Borrowed Books</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover get-borrowed-books">
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
@foreach($getBorrowedDetails as $borrowed)
    <div class="modal fade" id="add-penalty-{{$borrowed->id}}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Penalty if not returned</h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.books.approved-borrowed')}}" class="add-penalty-form">@csrf
                        <div class="form-group">
                            <label for="penalty">Penalty</label>
                            <input type="text" class="form-control" name="penalty" placeholder="Enter Penalty">
                        </div>
                        <input type="hidden" value="{{$borrowed->id}}" name="borrow_id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-secondary add-penalty">Add Penalty</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@section('pageJs')
<script>
    $(document).ready(function(){
        $(".get-borrowed-books").DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            processing: true,
            language: {
                processing: '<img src="{{URL::asset("public/icons/loading.gif")}}" style="width:10%; margin-bottom:10px;">'
            },
            ajax: {
                url: "{{route('admin.books.get-borrowed')}}",
            },
            createdRow : function(row, data, dataIndex){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            }
        });
    });
    $('form.add-penalty-form').on('submit',function(event){
        event.preventDefault();
        swal({
            title: "Confirmation",
            text: "Approve this borrowed data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    beforeSend:function(){
                        $('button[type="submit"].add-penalty').prop('disabled',true);
                        $('button[type="submit"].add-penalty').html('<i class="fa fa-spinner fa-pulse"></i>');
                    },
                    success:function(result){
                        if(result['status'] == 'success'){
                            swal({
                                title: "Success",
                                text:  result['messages'],
                                icon: result['status'],
                            }).then((resultStatus) => {
                                location.reload();
                            });
                        }else{
                            $('button[type="submit"].add-penalty').prop('disabled',false);
                            swal({
                                title: "Error",
                                text: result['messages'],
                                icon: result['status'],
                            });
                        }
                    }
                }).done(function(){
                    $('button[type="submit"].add-penalty').html('Add Penalty');
                });
            }
        });
    });
    function returnBorrow(id){
        swal({
            title: "Confirmation",
            text: "Mark this as returned?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: '{{route("admin.books.return-borrowed")}}',
                    data: {
                        id: id,
                        _token : "{{ csrf_token() }}"
                    },
                    beforeSend:function(){
                        $('button.btn.btn-success.return-'+id).prop('disabled',true);
                        $('button.btn.btn-success.return-'+id).html('<i class="fa fa-spinner fa-pulse"></i>');
                    },
                    success:function(result){
                        if(result['status'] == 'success'){
                            swal({
                                title: "Success",
                                text:  result['messages'],
                                icon: result['status'],
                            }).then((resultStatus) => {
                                location.reload();
                            });
                        }else{
                            $('button.btn.btn-success.return-'+id).prop('disabled',false);
                            swal({
                                title: "Error",
                                text: result['messages'],
                                icon: result['status'],
                            });
                        }
                    }
                }).done(function(){
                    $('button.btn.btn-success.return-'+id).html('<span class="fa fa-sign-out"></span> Mark Return');
                });
            }
        });
    }
    function approveBorrow(id){
        $('#add-penalty-'+id).modal();
    }
    function deleteBorrow(id){
        swal({
            title: "Confirmation",
            text: "Delete this borrowed data?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: '{{route("admin.books.delete-borrowed")}}',
                    data: {
                        id: id,
                        _token : "{{ csrf_token() }}"
                    },
                    beforeSend:function(){
                        $('button.btn.btn-danger.borrow-'+id).prop('disabled',true);
                        $('button.btn.btn-danger.borrow-'+id).html('<i class="fa fa-spinner fa-pulse"></i>');
                    },
                    success:function(result){
                        if(result['status'] == 'success'){
                            swal({
                                title: "Success",
                                text:  result['messages'],
                                icon: result['status'],
                            }).then((resultStatus) => {
                                location.reload();
                            });
                        }else{
                            $('button.btn.btn-danger.borrow-'+id).prop('disabled',false);
                            swal({
                                title: "Error",
                                text: result['messages'],
                                icon: result['status'],
                            });
                        }
                    }
                }).done(function(){
                    $('button.btn.btn-danger.borrow-'+id).html('<span class="fa fa-trash"></span>');
                });
            }
        });
    }
</script>
@endsection