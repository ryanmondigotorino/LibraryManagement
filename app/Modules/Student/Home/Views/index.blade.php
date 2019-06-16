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
                <form action="{{route('student.home.index')}}" class="global-search-form">
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
        <div id="paginate-container">
            @include('Admin.includes.paginate-card-books')
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
                nextSelector: 'a.book-lists-pagination:last',
            });
        });
    </script>
@endsection