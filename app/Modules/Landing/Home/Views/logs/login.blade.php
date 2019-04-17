@extends ('Landing.layout.main')
@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="card box_shad" style="width:100%">
            <div class="card-body">
                <div class="profile_content">
                    <h1><span class="fa fa-user"></span> Login</h1><hr>
                    <div class="row" style="margin-bottom:2%;">
                        <div class="col-lg-3">
                            <h5>Get connected with us!</h5>
                        </div>
                        <div class="col-lg-7">
                            <form class="loginClick">
                                {{ csrf_field() }} 
                                <div class="form-group">
                                    <label for="email_username">Email/User Name</label>
                                    <input type="text" class="form-control" name="email_username" placeholder="Enter here">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="Enter password">
                                </div>
                                <div class="form-group pull-right">
                                    <button type="submit" class="btn btn-secondary " name="sbmt">Login</button>
                                </div>
                            </form>
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
    $('.loginClick').on('submit',function(event){
        event.preventDefault();
        $.ajax({
            type:"POST",
            url:"{{route('landing.home.login-submit')}}",
            data: $(this).serialize(),
            beforeSend: function(){
                $("button[type='submit']").html('<i class="fa fa-spinner fa-pulse"></i> Processing');
            },
            success: function(result){
                if(result['status'] == 'success'){
                    location.reload();
                }else if(result['status'] == 'warning'){
                    swal({
                        title: "Warning",
                        text: result['msg'],
                        icon: result['status'],
                        button: "Ok",
                    });
                }else{
                    swal({
                        title: "Error",
                        text: result['msg'],
                        icon: result['status'],
                        button: "Ok",
                    });
                }
            }
        }).done(function(){
            $("button[type='submit']").html('Login')
        });
    });
</script>
@endsection