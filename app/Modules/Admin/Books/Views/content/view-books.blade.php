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
                        <div class="row" style="margin-bottom:2%;">
                            <div class="col-lg-4">
                                <div id="slider" class="carousel slide" data-ride="carousel">
                                    <ul class="carousel-indicators">
                                        <li data-target="#slider" data-slide-to="0" class="active"></li>
                                        <li data-target="#slider" data-slide-to="1"></li>
                                    </ul>
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img src="{{$getBooks->front_image == null ? URL::asset('public/css/assets/noimage.png') : URL::asset('storage/uploads/book_images/book-('.$getBooks->id.')/'.$getBooks->front_image)}}" alt="Front image" class="img-fluid" style="width:100%;height:500px;">
                                        </div>
                                        <div class="carousel-item">
                                            <img src="{{$getBooks->back_image == null ? URL::asset('public/css/assets/noimage.png') : URL::asset('storage/uploads/book_images/book-('.$getBooks->id.')/'.$getBooks->back_image)}}" alt="Back image" class="img-fluid" style="width:100%;height:500px;">
                                        </div>
                                    </div>

                                    <a class="carousel-control-prev" href="#slider" data-slide="prev">
                                        <span class="carousel-control-prev-icon"></span>
                                    </a>
                                    <a class="carousel-control-next" href="#slider" data-slide="next">
                                        <span class="carousel-control-next-icon"></span>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <h2 style="margin-top:5%;">{{$getBooks->title}}</h2>
                                <h5>Books</h5><hr>
                                <h6><b>Genre:</b>{{$getBooks->genre}}</h6><hr>
                                <h6><b>Description: </b>{{$getBooks->description}}</h6>
                                <button type="button" class="btn btn-secondary redirect-link-btn" data-url="{{route('admin.books.edit-books',$getBooks->id)}}" title="Edit Book" style="margin-top:1%;"><span class="fa fa-edit"></span> Edit Book</button>
                            </div>
                        </div><hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection