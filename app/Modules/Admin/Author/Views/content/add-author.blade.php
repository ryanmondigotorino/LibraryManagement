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
                        <h1 class="h2"><span class="fa fa-plus"></span> Add Author</h1><hr>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <form action="{{route('admin.author.add-author-save')}}" class="add-author">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="authorImage">Author Image</label>
                                        <div class="form-group img-group">
                                            <img src="{{URL::asset('storage/uploads/profile_image/noimage.png')}}" id="author-picture" class="item_image btn-add-author-image" alt="Author Image">
                                            <input type="file" class="d-none" name="authorImage"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="authoname">Author name</label>
                                        <input type="text" class="form-control" name="author_name" placeholder="Enter Author Name">
                                    </div>
                                    <div class="form-group">
                                        <label for="authoname">Author email</label>
                                        <input type="email" class="form-control" name="author_email" placeholder="Enter Author Email">
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Quote</label>
                                        <input type="text" class="form-control" name="quote" placeholder="Enter quote (optional)">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-secondary add-author pull-right" type="submit"><span class="fa fa-plus"></span> Add</button>
                                    </div>
                                </form>
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
    var globalImage;
    $('.btn-add-author-image').on('click',function(){
        $("input[type='file'][name='authorImage']").click();
        $("input[type='file'][name='authorImage']").on('change', function(){
            $('.item_image').css('opacity','1');
            var reader = new FileReader();
            reader.onload = function(e){
                $('#author-picture').attr('src',e.target.result);
            }
            if($('input[type="file"][name="authorImage"]')[0]['files'][0]){
                reader.readAsDataURL($("input[type='file'][name='authorImage']")[0]['files'][0]);
                globalImage = $("input[type='file'][name='authorImage']")[0]['files'][0];
            }
        });
    });
    $('form.add-author').on('submit',function(event){
        event.preventDefault();
        var image = globalImage;
        var formData = new FormData();
        formData.append('authorImage',image);
        formData.append('author_name',$('input[name="author_name"]').val());
        formData.append('author_email',$('input[name="author_email"]').val());
        formData.append('quote',$('input[name="quote"]').val());
        formData.append('_token','{{csrf_token()}}');
        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('button[type="submit"].add-author').prop('disabled',true);
                $('button[type="submit"].add-author').html('<i class="fa fa-spinner fa-pulse"></i>');
            },
            success: function(result){
                if(result['status'] == 'success'){
                    swal({
                        title: 'Success!',
                        text: result['messages'],
                        icon: result['status']
                    }).then((result) => {
                        location.href="{{route('admin.author.index')}}";
                    });
                }else{
                    $('button[type="submit"].add-author').prop('disabled',false);
                    swal({
                        title: 'Error!',
                        text: result['messages'],
                        icon: result['status']
                    })
                }
            }
        }).done(function(){
            $('button[type="submit"].add-author').html('<span class="fa fa-plus"></span> Add');
        });
    });
</script>
@endsection