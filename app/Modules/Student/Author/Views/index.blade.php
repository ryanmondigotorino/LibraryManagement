@extends ('Student.layout.main')

@section('student-nav-bar')
    @include("Student.layout.straight-nav-bar")
@endsection

@section('pageCss')
<style>
    body{
        background-color:#534D4D;
    }
</style>
@endsection

@section('content')
<div class="profile_container ml-4">
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="row">
                <div class="col-lg-6">
                    <p style="font-size: 50px;color:#D63333;">Authors</p>
                </div>
                <div class="col-lg-6">
                    <form action="{{route('student.author.index')}}" class="global-search-form">
                        <input type="text" class="form-control mt-4" autocomplete="off" placeholder="Search.." name="search" value="{{$placeholder}}">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div id="paginate-container">
                    @include('Admin.includes.paginate-card-authors')
            </div>
        </div>
        <div class="col-lg-2">
            <img src="{{URL::asset('public/css/assets/books-pile.png')}}" alt="book-img" class="img-fluid" style="margin-top:300px;">
        </div>
    </div>
</div>
@endsection

@section('pageJs')
    <script src="{{URL::asset('public/js/plugins/jscroll/jquery.jscroll.js')}}"></script>
    <script>
        $(document).ready(function(){
            $('div#paginate-container').jscroll({
                loadingHtml: '<div class="text-center"><img width="50" src="{{URL::asset("public/icons/loading.gif")}}" alt="Loading..."/></div>',
                padding: 20,
                nextSelector: 'a.author-lists-pagination:last',
            });
        });
    </script>
@endsection