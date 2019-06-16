@extends ('Admin.layout.main')
@section('pageCss')
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="col-md-3 col-lg-2"></div>
        <div class="col-md-9 col-lg-10">
            <div class="card" style="width:100%">
                <div class="card-body">
                    <div class="profile_content">
                        <div class="row">
                            <div class="col-lg-10">
                                <h1 class="h2"><span class="fa fa-table"></span> Authors</h1>
                            </div>
                            <div class="col-lg-2">
                                <a href="{{route('admin.author.add-author')}}" class="btn btn-secondary"><span class="fa fa-plus"></span> Add Author</a>
                            </div>
                        </div><hr>
                        <div id="paginate-container">
                            @include('Admin.includes.paginate-card-authors')
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
        $(document).ready(function(){
            $('div#paginate-container').jscroll({
                loadingHtml: '<div class="text-center"><img width="50" src="{{URL::asset("public/icons/loading.gif")}}" alt="Loading..."/></div>',
                padding: 20,
                nextSelector: 'a.author-lists-pagination:last',
            });
        });
    </script>
@endsection