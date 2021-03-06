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
                        <h1 class="h2"><span class="fa fa-plus"></span> Add books</h1><hr>
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-8">
                                <form action="{{route('admin.books.add-books-save')}}" class="global-books">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="frontimage">Front Book Image</label>
                                                <div class="form-group img-group">
                                                    <img src="{{URL::asset('public/css/assets/profile_image/noimage.png')}}" id="book-front-picture" class="item_image btn_book-front-picture" alt="Book Front Image">
                                                    <input type="file" class="d-none" name="frontImage"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="frontimage">Back Book Image</label>
                                            <div class="form-group">
                                                <div class="form-group img-group">
                                                    <img src="{{URL::asset('public/css/assets/profile_image/noimage.png')}}" id="book-back-picture" class="item_image btn_book-back-picture" alt="Book Back Image">
                                                    <input type="file" class="d-none" name="backImage"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Author</label>
                                        <select name="book_author" class="form-control">
                                            <option selected disabled>Choose...</option>
                                            @foreach($getAuthors as $authors)
                                                <option value="{{$authors->id}}">{{$authors->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Title</label>
                                        <input type="text" class="form-control" name="book_title" placeholder="Enter Book Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Genre</label>
                                        <select name="book_genre" class="form-control">
                                            <option selected disabled>Choose...</option>
                                            @foreach(config('books.book_category') as $genre)
                                                <option value="{{$genre}}">{{$genre}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Book Description</label>
                                        <textarea name="book_description" rows="5" class="form-control" placeholder="Enter Book Description (Optional)"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="booktitle">Date Published</label>
                                        <input type="date" class="form-control" max="{{date('Y-m-d',time())}}" name="book_published">
                                    </div>
                                    <div class="form-group">
                                        <label for="Quantity">Quantity</label>
                                        <input type="text" class="form-control" name="book_quantity" placeholder="Enter quantity (Qty..)">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-secondary global-books pull-right" type="submit"><span class="fa fa-plus"></span> Add</button>
                                    </div>
                                </form>
                            </div>
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