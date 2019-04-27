@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.nav-bar")
@endsection

@section('pageCss')
<style>
    body{
        background-image: url('/public/css/assets/student-landing-bg-img.png');
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-size: 100% 100%;

    }
    .content-container{
        margin-top: 6%;
    }
    .search-bar{
        margin-top: 8%;
        color: #fff;
    }
    .card-container{
        background-color: #7FC4FD;
    }
</style>
@endsection

@section('content')
<div class="content-container">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4 search-bar">
            <h3>
                These are the available books now inside the library.
            </h3>

            <div class="input-group mt-4">
                <form action="{{route('student.home.index')}}" class="search_book">
                    <div class="row">
                        <div class="col-lg-8">
                            <input type="text" class="form-control mr-5" autocomplete="off" placeholder="Search.." name="search" value="">
                        </div>
                        <div class="col-lg-4">
                            <button class="btn btn-default ml-5" style="background-color:#A94C4C; color:#fff;" type="submit">SEARCH</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div style="margin: 50px;">
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
@endsection

@section('pageJs')
<script>
    $('.search_book').on('submit',function(event){
        event.preventDefault();
        var thisTarget = $(event.target);
        var actionURL = thisTarget.attr('action');
        var searchbarValue = $('input[name="search"]').val();
        var serializeAllData = thisTarget.serialize();
        history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + serializeAllData);
        $.ajax({
            type: 'get',
            url: actionURL,
            data: serializeAllData,
        }).done(function(){
            location.reload();
        })
    });
</script>
@endsection