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
                        <h1 class="h2"><span class="fa fa-table"></span> Books Inventory</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover global-landing-table" data-url="{{route('admin.books.get-inventory')}}" data-loader="{{URL::asset("public/icons/loading.gif")}}">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Author Name</th>
                                            <th>Book Title</th>
                                            <th>Genre</th>
                                            <th>Date Published</th>
                                            <th>Book Qty</th>
                                            <th>Book Disperse</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="add-book-quantity" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Quantity for <span class="book-title"></span></h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.books.add-quantity-books')}}" class="global-landing-form">@csrf
                    <div class="form-group">
                        <label for="quantity">Quantity (Current <span class="current_quantity"></span>)</label>
                        <input type="text" class="form-control" name="quantity" placeholder="Enter quantity">
                    </div>
                    <input type="hidden" name="book_id">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
                        <button type="submit" class="btn btn-secondary global-landing-form-btn">Add Penalty</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="remove-book-inventory" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose action...</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="choose_action">Choose action</label>
                                <select name="delete_action" class="form-control" data-token="{{csrf_token()}}" data-url="{{route('admin.books.delete-all-quantity')}}">
                                    <option selected disabled>Choose...</option>
                                    <option value="delete_all">Delete All</option>
                                    <option value="reduce_quantity">Reduce quantity</option>
                                </select>
                                <input type="hidden" name="book_row_id">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="enter-book-quantity-remove" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog items-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remove</h5>
                <button type="button" class="close close-modal" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form action="{{route('admin.books.disperse-quantity')}}" class="global-landing-form">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="choose_action">Enter quantity</label>
                                    <input type="text" class="form-control" placeholder="Enter Quantity to remove" name="remove_quantity">
                                    <input type="hidden" name="book_row_id">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-secondary global-landing-form-btn">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
@endsection