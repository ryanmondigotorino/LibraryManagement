var GlobalImages = {
    
    INIT: function(){
        this.GLOBALAUTHOR();
        this.GLOBALBOOK();
    },

    GLOBALAUTHOR: function(){
        var globalImage;
        $('.btn-global-author-image').on('click',function(){
            $("input[type='file'][name='authorImage']").click();
            $("input[type='file'][name='authorImage']").on('change', function(){
                $('.item_image').css('opacity','1');
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#author-picture').attr('src',e.target.result);
                }
                if($('input[type="file"][name="authorImage"]')[0]['files'][0]){
                    reader.readAsDataURL($("input[type='file'][name='authorImage']")[0]['files'][0]);
                    globalImage = $("input[type='file'][name='authorImage']")[0]['files'][0];
                }
            });
        });
        $('form.global-author').on('submit',function(event){
            event.preventDefault();
            var image = globalImage;
            var author_name = $('input[name="author_name"]'),
                author_email = $('input[name="author_email"]'),
                quote = $('textarea[name="quote"]'),
                token = $('input[name="_token"]'),
                url = $(this).attr('action');
            var formData = new FormData();
            formData.append('authorImage',image);
            formData.append('author_name',author_name.val());
            formData.append('author_email',author_email.val());
            formData.append('quote',quote.val());
            formData.append('_token',token.val());
            if(url.includes('edit-author-save')){
                formData.append('author_id',$('input[name="author_id"]').val());
            }
            $.ajax({
                type:'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $('button[type="submit"].global-author').prop('disabled',true);
                    $('button[type="submit"].global-author').html('<i class="fa fa-spinner fa-pulse"></i>');
                },
                success: function(result){
                    var url_result = result['url'];
                    if(result['status'] == 'success'){
                        swal({
                            title: 'Success!',
                            text: result['messages'],
                            icon: result['status']
                        }).then((result) => {
                            location.href = url_result;
                        });
                    }else{
                        $('button[type="submit"].global-author').prop('disabled',false);
                        swal({
                            title: 'Error!',
                            text: result['messages'],
                            icon: result['status']
                        })
                    }
                }
            }).done(function(){
                $('button[type="submit"].global-author').html('<span class="fa fa-plus"></span> Add');
            });
        });
    },

    GLOBALBOOK: function(){
        var globalFrontImage;
        var globalBackImage;
        $('.btn_book-front-picture').on('click',function(){
            $("input[type='file'][name='frontImage']").click();
            $("input[type='file'][name='frontImage']").on('change', function(){
                $('.item_image').css('opacity','1');
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#book-front-picture').attr('src',e.target.result);
                }
                if($('input[type="file"][name="frontImage"]')[0]['files'][0]){
                    reader.readAsDataURL($("input[type='file'][name='frontImage']")[0]['files'][0]);
                    globalFrontImage = $("input[type='file'][name='frontImage']")[0]['files'][0];
                }
            });
        });
        $('.btn_book-back-picture').on('click',function(){
            $("input[type='file'][name='backImage']").click();
            $("input[type='file'][name='backImage']").on('change', function(){
                $('.item_image').css('opacity','1');
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#book-back-picture').attr('src',e.target.result);
                }
                if($('input[type="file"][name="backImage"]')[0]['files'][0]){
                    reader.readAsDataURL($("input[type='file'][name='backImage']")[0]['files'][0]);
                    globalBackImage = $("input[type='file'][name='backImage']")[0]['files'][0];
                }
            });
        });
        $('form.global-books').on('submit',function(event){
            event.preventDefault();
            var frontImage = globalFrontImage;
            var backImage = globalBackImage;
            var formData = new FormData();
            var targetBtn = $('button[type="submit"].global-books'),
                url = $(this).attr('action');
            formData.append('book_front',frontImage);
            formData.append('book_back',backImage);
            formData.append('book_author',$('select[name="book_author"]').val());
            formData.append('book_title',$('input[name="book_title"]').val());
            formData.append('book_genre',$('select[name="book_genre"]').val());
            formData.append('book_description',$('textarea[name="book_description"]').val());
            formData.append('book_published',$('input[name="book_published"]').val());
            formData.append('book_quantity',$('input[name="book_quantity"]').val());
            formData.append('_token',$('input[name="_token"]').val());
            if(url.includes('edit-books-save')){
                formData.append('book_id',$('input[name="book_id"]').val());
            }
            $.ajax({
                type:'POST',
                url: url,
                data: formData,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    targetBtn.prop('disabled',true);
                    targetBtn.html('<i class="fa fa-spinner fa-pulse"></i>');
                },
                success: function(result){
                    var url_result = result['url'];
                    if(result['status'] == 'success'){
                        swal({
                            title: 'Success!',
                            text: result['messages'],
                            icon: result['status']
                        }).then((result) => {
                            location.href = url_result;
                        });
                    }else{
                        targetBtn.prop('disabled',false);
                        swal({
                            title: 'Error!',
                            text: result['messages'],
                            icon: result['status']
                        })
                    }
                }
            }).done(function(){
                targetBtn.html('<span class="fa fa-plus"></span> Success');
            });
        });
    }
};
GlobalImages.INIT();