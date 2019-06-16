<div class="row mt-4 mb-4">
    @if($getBooks->count() > 0)
        @php $cntr = 0; @endphp
        @foreach($getBooks as $key => $books)
            @include('Student.includes.line-books')
        @endforeach
    @else
        <div class="col-lg-1"></div>
        <div class="col-lg-9">
            <div class="books-inline-container">
                <p class="category-font-sections mt-2 ml-4 text-center">No Books Available</p>
            </div>
        </div>
    @endif
</div>
@if($getBooks->lastPage() > 1)
    <a href="{{$getBooks->nextPageUrl()}}" class="book-lists-pagination" hidden>
        View More Books
    </a>
@endif