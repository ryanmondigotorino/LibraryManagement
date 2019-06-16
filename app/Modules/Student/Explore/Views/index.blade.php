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
                                <form action="{{route('student.explore.index')}}" class="global-search-form">
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
        <div class="col-lg-2"></div>
        <div class="col-lg-10">
            <div class="suggested-container">
                <p class="suggested-box">{{$placeholderCategory}}</p>
            </div>
        </div>
        <div class="col-lg-2"></div>
        <div class="col-lg-10">
            <div class="item-cart1 mt-4 my-cart">
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-10">
                        <div id="paginate-container">
                            @include('Student.includes.paginate-line-books')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
<script src="{{URL::asset('public/js/plugins/jscroll/jquery.jscroll.js')}}"></script>
<script>
    $('body').addClass('explore_page');
    $(document).ready(function(){
        $('div#paginate-container').jscroll({
            loadingHtml: '<div class="text-center"><img width="50" src="{{URL::asset("public/icons/loading.gif")}}" alt="Loading..."/></div>',
            padding: 20,
            nextSelector: 'a.book-lists-pagination:last',
        });
    });
</script>
@endsection'