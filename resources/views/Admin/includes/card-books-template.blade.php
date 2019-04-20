<div class="col-12 col-md-6 col-lg-4 mt-5">
    <div class="card card_shad card-container">
        <div class="card-header">
            <p class="item-title"><b>Title: </b></p>
        </div>
        <div class="card-body">
            <div class="items">
                <img src="{{URL::asset('storage/uploads/book_images/book-('.$books->id.')/'.$books->front_image.'')}}" alt="{{$books->title}}" height="350px;" width="100%"><hr>
                <p><b>Author: </b>{{$books->author}}</p><hr>
                Description:
                <div class="item-description">
                    {{$books->description == null || $books->description == '' ? 'No Description' : $books->description}}
                </div><hr>
            </div>
            <div class="dec pull-right">
                <button class="btn btn-default" type="button"><span class="fa fa-eye"></span></button>
                <button class="btn btn-secondary" type="button"><span class="fa fa-edit"></span></button>
            </div>
        </div>
    </div>
</div>