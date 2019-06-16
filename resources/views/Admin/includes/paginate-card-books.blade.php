<div class="row">
    @if($getBooks->count() > 0)
        @foreach($getBooks as $books)
            @include('Admin.includes.card-books-template')
        @endforeach
    @else
        <div class="col-lg-12">
            <h1 class="mt-5 mb-5 text-center">No Books available.</h1>
        </div>
    @endif
</div>
@if($getBooks->lastPage() > 1)
    <a href="{{$getBooks->nextPageUrl()}}" class="book-lists-pagination" hidden>
        View More Books
    </a>
@endif