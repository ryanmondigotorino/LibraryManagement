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
                                <img src="{{URL::asset('storage/uploads/authors/author-('.$getAuthors->id.')/'.$getAuthors->image)}}" alt="profile image" class="image_profile img-fluid">
                            </div>
                            <div class="col-lg-8">
                                <h2 style="margin-top:5%;">{{$getAuthors->name}}</h2>
                                <h5>Author</h5><hr>
                                <h6><b>Email:</b>{{$getAuthors->email}}</h6><hr>
                                <h6><b>Favorite Quote: </b>{{$getAuthors->favorite_quote}}</h6>
                            </div>
                        </div><hr>
                        <div class="row" style="margin-top:2%;">
                            <div class="col-lg-12">
                                <h2><span class="fa fa-book"></span> My Books</h2><br>
                            </div>
                        </div>
                        <div class="row">
                            @if($getBooks->count() > 0)
                                @foreach($getBooks->get() as $books)
                                    @include('Admin.includes.card-books-template')
                                @endforeach
                            @else
                                <div class="col-lg-12">
                                    <h1 class="mt-5 mb-5 text-center">No Books available.</h1>
                                </div>
                            @endif
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