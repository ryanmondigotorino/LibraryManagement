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
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="card" style="width:100%;margin-top:80px;">
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
                url: "{{route('student.explore.get-borrowed-books')}}",
            },
            createdRow : function(row, data, dataIndex){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            }
        });
    });
    function deleteBorrow(id){
        swal({
            title: "Confirmation",
            text: "Cancel borrowed?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: '{{route("student.explore.borrowed-books-cancel")}}',
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
</script>
@endsection