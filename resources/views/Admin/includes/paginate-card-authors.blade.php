<div class="row">
    @if($getAuthors->count() > 0)
        @foreach($getAuthors as $authors)
            @include('Admin.includes.card-authors-template')
        @endforeach
    @else
        <div class="col-lg-12">
            <h1 class="mt-5 mb-5 text-center">No Authors added.</h1>
        </div>
    @endif
</div>
@if($getAuthors->lastPage() > 1)
    <a href="{{$getAuthors->nextPageUrl()}}" class="author-lists-pagination" hidden>
        View More Authors
    </a>
@endif