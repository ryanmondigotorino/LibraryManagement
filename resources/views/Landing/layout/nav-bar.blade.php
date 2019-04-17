<div class="d-flex flex-column fixed-top flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal"><img src="{{URL::asset('storage/uploads/app_images/mainfavicon.png')}}" style="width:50px;height:50px;"></h5>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="{{route('landing.home.index')}}">Home</a>
    </nav>
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Login/Sign-up
    </button>
    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{route('landing.home.sign-up')}}"><span class="fa fa-user-plus"></span> Sign-up</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{route('landing.home.login')}}"><span class="fa fa-user"></span> Login</a>
    </div>
</div>