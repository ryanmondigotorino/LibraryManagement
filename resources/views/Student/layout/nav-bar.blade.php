<div class="d-flex flex-column fixed-top flex-md-row align-items-center p-3 px-md-3 mb-3 border-bottom shadow-sm" style="background-color: #A94C4C;">
    <h5 class="my-0 mr-1 font-weight-normal"><img src="{{URL::asset('storage/uploads/app_images/mainfavicon.png')}}" style="width:120px;height:50px;"></h5>
    <nav class="my-1 my-md-0 mr-md-auto">
        <a class="p-3 text-white" href="{{route('student.home.index')}}">HOME</a>
        <a class="p-3 text-white" href="{{route('student.author.index')}}">AUTHORS</a> 
        <a class="p-3 text-white" href="{{route('student.explore.index')}}">EXPLORE</a>
        <a class="p-3 text-white" href="{{route('student.contact.index')}}">CONTACT</a>
    </nav>
    <a class="fa fa-user pr-2 text-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></a>
    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{route('landing.home.sign-up')}}"><span class="fa fa-cog"></span> Account Settings</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item logout_click" href="#"><span class="fa fa-sign-out"></span> Log-out</a>
    </div>
</div>