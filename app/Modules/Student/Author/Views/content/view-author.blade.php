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
</style>
@endsection

@section('content')
<div class="content-container">
    <div class="row">
        <div class="col-lg-9">
            <button class="btn btn-default back_btn"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            <div class="desc__box">
                <img class="author_img" src="{{URL::asset('public/css/assets/author_image.jpg')}}" alt="" align="">
                <div class="author_bio">
                    <h3>John Green</h3>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor 
                        incididunt ut ero labore et dolore magna aliqua. Ut enim ad minim veniam.
                    </p>
                </div>
            </div>
            <div class="author_desc">
                <h2>Excepteur sint occaeuiecat cupidatat.</h2>
                <p class="mt-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut ero labore et dolore magna aliqua. 
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco poriti laboris nisi ut aliquip ex ea commodo consequat. 
                    Duis aute irure dolor in reprehenderit in uienply voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                    Excepteur sint occaecat cupidatat norin proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
            <div class="author_works">
                <p>LOREM IPSUM DOLOR AMET CONSECTETUER <a href="#" class="pull-right" style="color: #2699FB !important;"><i class="fa fa-globe" aria-hidden="true"> webpage </i></a> </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection