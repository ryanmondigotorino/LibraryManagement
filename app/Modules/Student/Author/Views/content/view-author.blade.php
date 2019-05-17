@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.straight-nav-bar")
@endsection

@section('pageCss')
<style>
    body{
        background-color: #534D4D;
    }
    .back_btn{
        margin: 2% 0 0 25%;
        width: 75px;
        background-color: #FC0101;
        color: #fff;
        border-radius: 15px;
    }
    .desc__box{
        width: 560px;
        background-color: #A94C4C;
        margin: -5% 0 0 35%;
        padding: 2%;
        display: inline-block;
    }
    .author_img{
        height: 100px;
        border-radius: 50px;
        float:left;
    }
    .author_bio{
        padding-left:23%;
        color: #fff;
    }
    .author_bio > p {
        font-style: italic;
    }
    .author_desc{
        width: 900px;
        margin: 3% 0 0 35%;
        color: #fff;
    }
    .author_works{
        width: 900px;
        margin: 3% 0 0 35%;
        color: #fff;
        display: inline-block;
    }

    .author_works > a {
        color: #2699FB !important;
    }
    .card-container{
        background-color: #7FC4FD;
    }
</style>
@endsection

@section('content')
<div class="content-container">
    <div class="row">
        <div class="col-lg-9">
            <button type="button" class="btn btn-default back_btn redirect-link-btn" data-url="{{route('student.author.index')}}"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            <div class="desc__box">
                <img class="author_img" src="{{URL::asset('storage/uploads/authors/author-('.$getAuthors->id.')/'.$getAuthors->image)}}" alt="Image of {{$getAuthors->name}}" >
                <div class="author_bio">
                    <h3>{{$getAuthors->name}}</h3>
                    <p style="font-family: 'Quicksand', sans-serif;">
                        {{$getAuthors->favorite_quote}}
                    </p>
                </div>
            </div>
            <div class="author_desc">
                <h2>Books</h2>
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
@endsection

@section('pageJs')
@endsection