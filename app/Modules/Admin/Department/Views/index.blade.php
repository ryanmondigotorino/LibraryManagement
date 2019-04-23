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
                                <h1 class="h2"><span class="fa fa-table"></span> Department</h1>
                            </div>
                            <div class="col-lg-2">
                                <button type="button" class="btn btn-secondary add-department"><span class="fa fa-plus"></span> Add Department</button>
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover get-department">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Department name</th>
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
<div class="modal fade" id="add-department" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Department</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.department.add-department')}}" class="add-department-form">@csrf
                    <div class="form-group">
                        <label for="departmentname">Department Name</label>
                        <input type="text" class="form-control" name="departmentname" placeholder="Enter Department Name">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-secondary add-department">Add Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@foreach($getDepartment as $department)
    <div class="modal fade" id="edit-department-{{$department->id}}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                    <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('admin.department.edit-department')}}" class="edit-department-form">@csrf
                        <div class="form-group">
                            <label for="departmentname">Department Name</label>
                            <input type="text" class="form-control" name="departmentname" value="{{$department->department_name}}" placeholder="Enter Department Name">
                        </div>
                        <input type="hidden" name="departmentid" value="{{$department->id}}">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                            <button type="submit" class="btn btn-secondary edit-department">Edit Department</button>
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
        $(".get-department").DataTable({
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
                url: "{{route('admin.department.get-department')}}",
            },
            createdRow : function(row, data, dataIndex){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            }
        });
    });
    $('.edit-department-form').on('submit',function(event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function(){
                $('button[type="submit"].edit-department').prop('disabled',true);
                $('button[type="submit"].edit-department').html('<i class="fa fa-spinner fa-pulse"></i>');
            },
            success: function(result){
                if(result['status'] == 'success'){
                    swal({
                        title: 'Success!',
                        text: result['messages'],
                        icon: result['status']
                    }).then((result) => {
                        location.reload();
                    });
                }else{
                    $('button[type="submit"].edit-department').prop('disabled',false);
                    swal({
                        title: 'Error!',
                        text: result['messages'],
                        icon: result['status']
                    })
                }
            }
        }).done(function(){
            $('button[type="submit"].edit-department').html('Edit Department');
        });
    });
    function editdepartment(id,name){
        $('#edit-department-'+id).modal();
    }
    function deletedepartment(id,name){
        swal({
            title: "Confirmation!",
            text: "Delete this "+name+" department?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((result) => {
            if(result){
                $.ajax({
                    type: 'POST',
                    url: "{{route('admin.department.delete-department')}}",
                    data: {
                        id: id,
                        _token: '{{csrf_token()}}'
                    },
                    beforeSend:function(){
                        $('button.department-'+id).prop('disabled',true);
                        $('button.department-'+id).html('<i class="fa fa-spinner fa-pulse"></i>');
                    },
                    success: function(result){
                        if(result['status'] == 'success'){
                            swal({
                                title: 'Success!',
                                text: result['messages'],
                                icon: result['status']
                            }).then((result) => {
                                location.reload();
                            });
                        }else{
                            $('button.department-'+id).prop('disabled',false);
                            swal({
                                title: 'Error!',
                                text: result['messages'],
                                icon: result['status']
                            });
                        }
                    },
                }).done(function(){
                    $('button.department-'+id).html('<span class="fa fa-trash"></span>');
                });
            }
        });
    }
    $('.add-department').on('click',function(){
        $('#add-department').modal();
    });
    $('.add-department-form').on('submit',function(event){
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function(){
                $('button[type="submit"].add-department').prop('disabled',true);
                $('button[type="submit"].add-department').html('<i class="fa fa-spinner fa-pulse"></i>');
            },
            success: function(result){
                if(result['status'] == 'success'){
                    swal({
                        title: 'Success!',
                        text: result['messages'],
                        icon: result['status']
                    }).then((result) => {
                        location.reload();
                    });
                }else{
                    $('button[type="submit"].add-department').prop('disabled',false);
                    swal({
                        title: 'Error!',
                        text: result['message'],
                        icon: result['status']
                    })
                }
            }
        }).done(function(){
            $('button[type="submit"].add-department').html('Add Depertment');
        });
    })
</script>
@endsection