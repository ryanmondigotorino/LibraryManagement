<div class="col-lg-1"></div>
<div class="col-lg-8">
    <div class="books-inline-container redirect-link-btn mt-4" data-url="{{route('student.explore.view-book',$books->id)}}">
        <p class="category-font-sections mt-2 ml-4">
            <b>{{$books->id. '. ' .$books->title}}</b>, by 
            ({{$books->author_name == null || $books->author_name == '' ? 'No Author Available' : $books->author_name}})
            -- {{$books->author_email}}
        </p>
    </div>
</div>
<div class="col-lg-3">
    <button class="btn btn-view-books redirect-link-btn mt-4" data-url="{{route('student.explore.view-book',$books->id)}}">View</button>
</div>