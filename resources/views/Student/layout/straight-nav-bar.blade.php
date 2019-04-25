<nav class="col-md-3 col-lg-2 d-none d-md-block sidebar" style="height:100%;background-color:#A94C4C;">
    <div class="sidebar-sticky">
        <h5 class="my-0 mr-1 font-weight-normal"><img src="{{URL::asset('storage/uploads/app_images/mainfavicon.png')}}" class="img-fluid" ></h5>
        <ul class="nav flex-column mt-5">
            <li class="nav-item">
                <a class="nav-link sidebar-font-text" href="{{route('student.home.index')}}">
                    HOME
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-font-text" href="{{route('student.author.index')}}">
                    AUTHORS
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-font-text" href="#">
                    EXPLORE
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link sidebar-font-text" href="#">
                    CONTACT
                </a>
            </li>
        </ul>        
    </div>
</nav>