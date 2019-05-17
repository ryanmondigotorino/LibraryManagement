var GlobalForm = {

    INIT: function(){
        this.EVENTS();
        this.REDIRECT();
        this.LANDINGPAGELOADER();
        this.BORROWBOOK();
        this.LOGOUT();
    },

    EVENTS: function(){
        $('form.global-landing-form').on('submit',function(event){
            event.preventDefault();
            var url = $(this).attr('action');
            var targetBtn = $('button[type="submit"].global-landing-form-btn')
            $.ajax({
                type:'post',
                url: url,
                data: $(this).serialize(),
                beforeSend: function(){
                    $(targetBtn).prop('disabled',true);
                    $(targetBtn).html('<i class="fa fa-spinner fa-pulse"></i> Processing..');
                },
                success:function(result){
                    // console.log(result);
                    // return false;
                    if(result['status'] == 'success'){
                        if(result['url'] == 'none'){
                            location.reload();
                        }else{
                            swal({
                                title: "Success",
                                text: result['message'],
                                icon: result['status'],
                            }).then((confirm) => {
                                if(confirm){
                                    location.href = result['url'];
                                }
                            });
                        }
                    }else if(result['status'] == 'warning'){
                        swal({
                            title: "Warning",
                            text: result['messages'],
                            icon: result['status'],
                            button: "Ok",
                        });
                    }else{
                        swal({
                            title: "Error",
                            text: result['messages'],
                            icon: result['status'],
                            button: "Ok",
                        });
                    }
                }
            }).done(function(result){
                if(result['status'] == 'success'){
                    targetBtn.html('<span class="fa fa-check"></span> Success! Please wait <i class="fa fa-spinner fa-pulse"></i>');
                }else{
                    targetBtn.prop('disabled',false);
                    targetBtn.html('<span class="fa fa-edit"></span> Re-submit');
                }
            });
        });
    },

    REDIRECT: function(){
        $('.redirect-link-btn').on('click',function(){
            var url = $(this).attr('data-url');
            location.href= url;
        });
    },

    LANDINGPAGELOADER: function(){
        $(document).ready(function(){
            var path = window.location.pathname;
            if(path.includes('login') || path.includes('sign-up')){
                $('div.content1').addClass('d-none');
            }else{
                $('div.content1').removeClass('d-none');
                $('body').addClass('landing_page');
            }
        });
    },

    BORROWBOOK: function(){
        $('button[type="button"].borrow_btn').on('click',function(){
            var book_id = $(this).attr('data-id'),
                url = $(this).attr('data-url'),
                token = $(this).attr('data-token');
            swal({
                title: "Confirmation",
                text: "Borrow This Book?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((result) => {
                if(result){
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            book_id: book_id,
                            _token: token,
                        },
                        beforeSend: function(){
                            $('button[type="button"].borrow_btn').html('<i class="fa fa-spinner fa-pulse"></i> Wait a moment.');
                        },
                        success:function(result){
                            var result_url = result['url'];
                            if(result['status'] == 'success'){
                                swal({
                                    title: 'Success!',
                                    text: result['messages'],
                                    icon: result['status']
                                }).then((result) => {
                                    location.href = result_url;
                                });
                            }else{
                                $('button[type="button"].borrow_btn').prop('disabled',false);
                                swal({
                                    title: 'Error!',
                                    text: result['messages'],
                                    icon: result['status']
                                })
                            }
                        }
                    }).done(function(){
                        $('button[type="button"].borrow_btn').html('BORROW THIS BOOK');
                    });
                }
            });
        });
    },

    LOGOUT: function(){
        $('a.logout_click').on('click',function(){
            var id = $(this).attr('data-id'),
                url = $(this).attr('data-url'),
                token = $(this).attr('data-token');
            swal({
                title: "Confirmation",
                text: "Logout now?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((result) => {
                if(result){
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            guard: 'student',
                            model: 'Student',
                            id: id,
                            _token : token
                        },
                        success:function(result){
                            if(result == 'success'){
                                location.reload();
                            }
                        }
                    });
                }
            });
        });
    }
};
GlobalForm.INIT();