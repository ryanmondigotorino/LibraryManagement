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
                        <div class="row">
                            @if($getAuthors->count() > 0)
                                @foreach($getAuthors->get() as $authors)
                                    @include('Admin.includes.card-authors-template')
                                @endforeach
                            @else
                                <div class="col-lg-12">
                                    <h1 class="mt-5 mb-5 text-center">No Authors added.</h1>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection