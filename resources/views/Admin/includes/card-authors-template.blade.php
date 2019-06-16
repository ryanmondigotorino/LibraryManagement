<div class="col-12 col-md-6 col-lg-6 mt-5">
    <div class="card card_shad card-container">
        <div class="card-body">
            <div class="profile_content">
                <div class="row">
                    <div class="col-lg-4">
                        <img src="{{URL::asset('storage/uploads/authors/author-('.$authors->id.')/'.$authors->image.'')}}" alt="profile image" class="image_profile img-fluid">
                    </div>
                    <div class="col-lg-8">
                        <h2 style="margin-top:5%;"></h2>
                        <h5>{{$authors->name}}</h5><hr>
                        <h6><b>Email:</b> {{$authors->email == null || $authors->email == '' ? 'No email available' : $authors->email}}</h6>
                        <h6><b>Quote: </b> {{$authors->favorite_quote}}</h6><hr>
                        @php $viewAuthor = Auth::guard('admin')->check() ? 'admin.author.view-author' : 'student.author.view-author' @endphp
                        <div class="pull-right">
                            <a href="{{route('admin.author.edit-author',$authors->id)}}" class="btn btn-secondary {{Auth::guard('admin')->check() ? '' : 'd-none' }}" title="Edit"><span class="fa fa-edit"></span></a>
                            <a href="{{route($viewAuthor,[$authors->id,$authors->name])}}" class="btn btn-secondary" title="View"><span class="fa fa-eye"></span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>