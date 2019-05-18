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
                            <div class="col-lg-8">
                                <h1 class="h2"><span class="fa fa-table"></span> Books</h1>
                            </div>
                            <div class="col-lg-4">
                                <a href="{{route('admin.books.add-books')}}" class="btn btn-secondary"><span class="fa fa-plus"></span> Add Books</a>
                                <a href="{{route('admin.books.inventory')}}" class="btn btn-secondary"><span class="fa fa-eye"></span> View Lists of Inventory</a>
                            </div>
                        </div><hr>
                        <div class="row">
                            @if($getBooks->count() > 0)
                                @foreach($getBooks->get() as $books)
                                    @include('Admin.includes.card-books-template')
                                @endforeach
                            @else
                                <div class="col-lg-12">
                                    <h1 class="mt-5 mb-5 text-center">No Books available.</h1>
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