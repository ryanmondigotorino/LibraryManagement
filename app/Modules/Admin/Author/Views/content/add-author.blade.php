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
                                <form action="{{route('admin.author.add-author-save')}}" class="global-author">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="authorImage">Author Image</label>
                                        <div class="form-group img-group">
                                            <img src="{{URL::asset('storage/uploads/profile_image/noimage.png')}}" id="author-picture" class="item_image btn-global-author-image" alt="Author Image">
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
                                        <textarea name="quote" class="form-control" rows="5" placeholder="Enter quote (optional)"></textarea>
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
@endsection