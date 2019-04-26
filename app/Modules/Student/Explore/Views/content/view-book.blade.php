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
        width: 650px;
        background-color: rgba(255,255,255, 0.2);
        margin: -5% 0 0 35%;
        padding: 2%;
        border-radius: 20px;
        display: inline-block;
    }
    p{
        color:#fff;
    }
    .author_desc{
        width: 250px;
        height: 325px;
        background-color: rgba(255,255,255, 0.2);
        margin: 5% 0 0 20%;
        padding: 2%;
        border-radius: 20px;
        display: inline-block;
    }
    
    .author_img{
        height: 100px;
        border-radius: 50px;
        position: absolute;
        top: 10%;
        left: 48%;
    }

    .borrow_btn{
        font-family: 'Quicksand', sans-serif; 
        color:#FF3900; 
        border-color: #FF3900;
        letter-spacing: 2px;
    }
    .books_img{
        margin: 2% 0 0 35%;
    }
    .book_cover{
        margin: 2% 5% 0 5%;
        width: 200px;
        height: 250px;
    }
</style>
@endsection

@section('content')
<div class="content-container">
    <div class="row">
        <div class="col-lg-9">
            <button class="btn btn-default back_btn"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            <div class="desc__box">
                <h2 style="color:#FF3900;">TITLE OF THE BOOK</h2>
                <p>
                    Times are changing fast and whilst some of us were simply born 
                    into this meteoric times there are others who were not.
                </p>
                <p>
                    Text Default dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor 
                    invidunt ut <br> Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor 
                    invidunt ut labore et dolore magna aliquyam erat, sed dia. Hui ipsum dolor sit amet, consetetur sadipscing elitr, 
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed dia.
                    Text Default Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
                    sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed dia
                </p>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="author_desc">
                <img class="author_img" src="{{URL::asset('public/css/assets/author_image.jpg')}}" alt="">
                <p style="margin-top: 50%; font-family: 'Quicksand', sans-serif; text-align: center;">Written by:</p>
                <p style="text-align: center; font-family: 'Quicksand', sans-serif; color:#FF3900;">John Green</p>
                <p style="text-align: center; font-family: 'Quicksand', sans-serif;">@mrses | hello@my500.com</p>
                <button class="btn btn-outline-secondary borrow_btn ml-2">BORROW THIS BOOK</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8 books_img">
            <img class="book_cover" src="{{URL::asset('public/css/assets/front_cover.jpg')}}" alt="Front Cover">
            <img class="book_cover" src="{{URL::asset('public/css/assets/back_cover.jpg')}}" alt="Back Cover">
        </div>
        <div class="col-lg-2"></div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection