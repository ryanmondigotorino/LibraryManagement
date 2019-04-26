@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.straight-nav-bar")
@endsection

@section('pageCss')
<style>
    .content-container{
        margin-top: 30px;
    }
    .inner-box{
        background-color: rgba(255, 255, 255);
        width: 100%;
        height: 250px;
        border-color: #000000;
        border: solid;
    }
    .outer-box{
        background-color: #A94C4C;
        width: 95%;
        height: inherit;
        position: absolute;
        left: 5%;
        border-color: #000000;
        border: solid;
    }
    .desc{
        color: #A94C4C;
        margin-top: 4%;
    }
</style>
@endsection

@section('content')
<div class="content-container ml-4">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-6">
            <div class="inner-box"><br>
                <div class="outer-box">
                    <div class="mt-2 mr-2">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <p class="category-font-header">Book Category</p>
                            </div>
                            <div class="col-lg-4">
                                <p class="category-font-header text-center">Search</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Fantasy">Fantasy</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="History">History</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Poetry">Poetry</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <form action="{{route('student.explore.index')}}" class="search-explore">
                                    <input type="text" class="form-control mr-5" name="search" autocomplete="off" placeholder="Search.." value="{{$placeholder}}">
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Adventure">Adventure</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Sci-Fi">Sci-Fi</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Romance">Romance</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Short Story">Short Story</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Humor">Humor</p>
                                    </div>
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Mystery">Mystery</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <p class="category-font-sections btn-category" data-category="Horror">Horror</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><br>
    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-8">
            <div class="item-cart1 mt-4 my-cart">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="suggested-container">
                                <p class="suggested-box">{{$placeholderCategory}}</p>
                            </div>
                        </div>
                    </div>
                    @if($getBooks->count() > 0)
                        @php $cntr = 0; @endphp
                        @foreach($getBooks->get() as $key => $books)
                            <div class="row mt-4">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-8">
                                    <div class="books-inline-container" data-id="{{$books->id}}">
                                        <p class="category-font-sections mt-2 ml-4">
                                            <b>{{($key + 1). '. ' .$books->title}}</b>, by 
                                            ({{$books->author_name == null || $books->author_name == '' ? 'No Author Available' : $books->author_name}})
                                            -- {{$books->author_email}}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <button class="btn btn-view-books mt-1" data-id="{{$books->id}}">View</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="row mt-4">
                            <div class="col-lg-1"></div>
                            <div class="col-lg-9">
                                <div class="books-inline-container">
                                    <p class="category-font-sections mt-2 ml-4 text-center">No Books Available</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
<script>
    $('body').addClass('explore_page');
    $('div.books-inline-container').on('click',function(){
        var id = $(this).attr('data-id');
        var linkid = "{{route('student.explore.view-book','id')}}";
        var url = linkid.replace('id',id);
        location.href=url;
    });
    $('button.btn-view-books').on('click',function(){
        var id = $(this).attr('data-id');
        var linkid = "{{route('student.explore.view-book','id')}}";
        var url = linkid.replace('id',id);
        location.href=url;
    });
    $('.search-explore').on('submit',function(event){
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
    $('p.btn-category').on('click',function(event){
        var thisTarget = $(event.target);
        var dataCategory = thisTarget.attr('data-category');
        var categorySerialize = 'search_category=' + dataCategory;
        history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + categorySerialize);
        location.reload();
    });
</script>
@endsection'