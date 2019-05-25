<div class="modal fade" id="imgupload" tabindex="-1" role="dialog" aria-labelledby="imageModalCenterTitle" aria-hidden="true" 
     data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="genderModalCenterTitle">Change your profile pic</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container imgup">
                    <img class="image_profile" id="profile-picture" width="100%" height="300px" style="border-radius: 50%;" src="{{$base_data->image == null || $base_data->image == '' ? URL::asset('public/css/assets/noimage.png') : URL::asset('storage/uploads/profile_image/user('.$base_data->id.')/'.$base_data->image)}}">
                    <button class="btn btn-success dp_btn" data-url="{{route("student.home.profile-image-upload",$base_data->username)}}" style="margin-top: 5%;margin-bottom: 5%;" type="button">Change Profile pic</button>
                    <input type="file" data-id="{{ isset($base_data->id) ? $base_data->id : '' }}" data-token="{{ csrf_token() }}" class="d-none uploadImage" name="profilePicture"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> 
                        <button type="submit" class="btn btn-success dp_save d-none" name="sbmt">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>