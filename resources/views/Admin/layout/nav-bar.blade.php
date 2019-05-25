<nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{route('admin.dashboard.index')}}">Welcome! {{$base_data->account_type == 'librarian' ? 'Librarian' : 'Admin'}} {{$base_data->firstname}}</a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link logout_click" href="#" data-url="{{route("landing.home.logout")}}" data-id="{{$base_data->id}}" data-guard="admin" data-model="Admin" data-token="{{ csrf_token() }}">Sign out</a>
        </li>
    </ul>
</nav>
<div class="container profile_below">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar">
            <div class="sidebar-sticky">
                @foreach(config('nav-bars.'.$base_data->account_type) as $nav_key => $nav_value)
                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span class="navHead">{{$nav_key}}</span>
                        <a class="d-flex align-items-center text-muted" href="#">
                            <span data-feather="plus-circle"></span>
                        </a>
                    </h6>
                    @foreach(config('nav-bars.'.$base_data->account_type.'.'.$nav_key) as $key_content => $content_nav)
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                @php $route = isset($content_nav['slug']) ? route($content_nav['route'],$content_nav['slug']) : route($content_nav['route']) @endphp
                                <a class="nav-link" href="{{$route}}">
                                    <span class="{{$content_nav['icon']}}"></span>
                                    {{$key_content}} 
                                </a>
                            </li>
                        </ul>
                    @endforeach
                @endforeach
            </div>
        </nav>
    </div>
</div>