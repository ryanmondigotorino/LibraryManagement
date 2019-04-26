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
                        <h1 class="h2"><span class="fa fa-plus"></span> Add books</h1><hr>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <form action="{{route('admin.books.add-books-save')}}" class="add-books">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="frontimage">Front Book Image</label>
                                                <div class="form-group img-group">
                                                    <img src="{{URL::asset('storage/uploads/profile_image/noimage.png')}}" id="book-front-picture" class="item_image btn_book-front-picture" alt="Book Front Image">
                                                    <input type="file" class="d-none" name="frontImage"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="frontimage">Back Book Image</label>
                                            <div class="form-group">
                                                <div class="form-group img-group">
                                                    <img src="{{URL::asset('storage/uploads/profile_image/noimage.png')}}" id="book-back-picture" class="item_image btn_book-back-picture" alt="Book Back Image">
                                                    <input type="file" class="d-none" name="backImage"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Author</label>
                                        <select name="book_author" class="form-control">
                                            <option selected disabled>Choose...</option>
                                            @foreach($getAuthors as $authors)
                                                <option value="{{$authors->id}}">{{$authors->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Title</label>
                                        <input type="text" class="form-control" name="book_title" placeholder="Enter Book Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Genre</label>
                                        <select name="book_genre" class="form-control">
                                            <option selected disabled>Choose...</option>
                                            @foreach(config('books.book_category') as $genre)
                                                <option value="{{$genre}}">{{$genre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Description</label>
                                        <textarea name="book_description" rows="5" class="form-control" placeholder="Enter Book Description (Optional)"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Date Published</label>
                                        <input type="date" class="form-control" max="{{date('Y-m-d',time())}}" name="book_published">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-secondary add-books pull-right" type="submit"><span class="fa fa-plus"></span> Add</button>
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
    var globalFrontImage;
    var globalBackImage;
    $('.btn_book-front-picture').on('click',function(){
        $("input[type='file'][name='frontImage']").click();
        $("input[type='file'][name='frontImage']").on('change', function(){
            $('.item_image').css('opacity','1');
            var reader = new FileReader();
            reader.onload = function(e){
                $('#book-front-picture').attr('src',e.target.result);
            }
            if($('input[type="file"]')[0]['files'][0]){
                reader.readAsDataURL($("input[type='file'][name='frontImage']")[0]['files'][0]);
                globalFrontImage = $("input[type='file'][name='frontImage']")[0]['files'][0];
            }
        });
    });
    $('.btn_book-back-picture').on('click',function(){
        $("input[type='file'][name='backImage']").click();
        $("input[type='file'][name='backImage']").on('change', function(){
            $('.item_image').css('opacity','1');
            var reader = new FileReader();
            reader.onload = function(e){
                $('#book-back-picture').attr('src',e.target.result);
            }
            if($('input[type="file"]')[0]['files'][0]){
                reader.readAsDataURL($("input[type='file'][name='backImage']")[0]['files'][0]);
                globalBackImage = $("input[type='file'][name='backImage']")[0]['files'][0];
            }
        });
    });
    $('form.add-books').on('submit',function(event){
        event.preventDefault();
        var frontImage = globalFrontImage;
        var backImage = globalBackImage;
        var formData = new FormData();
        formData.append('book_front',frontImage);
        formData.append('book_back',backImage);
        formData.append('book_author',$('select[name="book_author"]').val());
        formData.append('book_title',$('input[name="book_title"]').val());
        formData.append('book_genre',$('select[name="book_genre"]').val());
        formData.append('book_description',$('textarea[name="book_description"]').val());
        formData.append('book_published',$('input[name="book_published"]').val());
        formData.append('_token','{{csrf_token()}}');
        $.ajax({
            type:'POST',
            url: $(this).attr('action'),
            data: formData,
            contentType: false,
            processData: false,
            beforeSend:function(){
                $('button[type="submit"].add-books').prop('disabled',true);
                $('button[type="submit"].add-books').html('<i class="fa fa-spinner fa-pulse"></i>');
            },
            success: function(result){
                if(result['status'] == 'success'){
                    swal({
                        title: 'Success!',
                        text: result['messages'],
                        icon: result['status']
                    }).then((result) => {
                        location.href="{{route('admin.books.index')}}";
                    });
                }else{
                    $('button[type="submit"].add-books').prop('disabled',false);
                    swal({
                        title: 'Error!',
                        text: result['messages'],
                        icon: result['status']
                    })
                }
            }
        }).done(function(){
            $('button[type="submit"].add-books').html('<span class="fa fa-plus"></span> Add');
        });
    });
</script>
@endsection