<div class="d-flex flex-column fixed-top flex-md-row align-items-center p-3 px-md-3 mb-3 border-bottom shadow-sm" style="background-color: #A94C4C;">
    <h5 class="my-0 mr-1 font-weight-normal"><img src="{{URL::asset('public/css/assets/mainfavicon.png')}}" style="width:120px;height:50px;"></h5>
    <nav class="my-1 my-md-0 mr-md-auto">
        @php $cntr = 0; @endphp
        @foreach(config('nav-bars.student-nav-bar') as $key => $value)
            {!!count($value) == 1? '<a class="p-3 text-white" href="'.route($value[0]).'">'.$key.'</a>' : '<a class="p-3 text-white" href="'.route($value[1],$base_data->username).'">'.$key.'</a>'!!}
        @endforeach
    </nav>
    <a class="fa fa-user pr-2 text-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-hidden="true"></a>
    <div class="dropdown-menu dropdown-menu-right">
        <a class="dropdown-item" href="{{route('student.explore.borrowed-books')}}"><span class="fa fa-cog"></span> Borrowed Books</a>
        <a class="dropdown-item" href="{{route('student.home.profile-settings',$base_data->username)}}"><span class="fa fa-cog"></span> Account Settings</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item logout_click" href="#" data-url="{{route("landing.home.logout")}}" data-id="{{$base_data->id}}" data-guard="student" data-model="Student" data-token="{{ csrf_token() }}"><span class="fa fa-sign-out"></span> Log-out</a>
    </div>
</div>