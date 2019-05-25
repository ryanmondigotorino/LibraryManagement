var GlobalForm = {

    INIT: function(){
        this.EVENTS();
        this.REDIRECT();
        this.LANDINGPAGELOADER();
        this.BORROWBOOK();
        this.DISPERSE();
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
        $('button[type="button"].add-course').on('click',function(){
            $('#add-course').modal();
        });
        $('button[type="button"].add-department').on('click',function(){
            $('#add-department').modal();
        });
        $('button[type="button"].add-admin-account').on('click',function(){
            $('div.modal#add-admin-modal').modal();
        });
        $('button[type="button"].add-librarian-account').on('click',function(){
            $('div.modal#add-librarian-modal').modal();
        });
        $('.add-students-account').on('click',function(){
            $('div.modal#add-students-modal').modal();
        });
    },

    LANDINGPAGELOADER: function(){
        $(document).ready(function(){
            var path = window.location.pathname;
            if(path.includes('login') || path.includes('forgotpassword') || path.includes('sign-up')){
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
                token = $(this).attr('data-token'),
                guard = $(this).attr('data-guard'),
                model = $(this).attr('data-model');
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
                            guard: guard,
                            model: model,
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
    },

    DISPERSE: function(){
        $('select[name="delete_action"]').on('change',function(){
            var value = $(this).val(),
                url = $(this).attr('data-url'),
                id = $('input[type="hidden"][name="book_row_id"]').val()
                token = $(this).attr('data-token');
            if(value == 'delete_all'){
                swal({
                    title: "Confirmation",
                    text: "Are you sure do you want to remove all books?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((result) => {
                    if(result){
                        $.ajax({
                            type:"post",
                            url: url,
                            data:{
                                id:id,
                                _token: token
                            },
                            success:function(result){
                                if(result['status'] == 'success'){
                                    swal({
                                        title: 'Success!',
                                        text: result['message'],
                                        icon: result['status']
                                    }).then((result) => {
                                        location.reload();
                                    });
                                }
                            }
                        });
                    }
                });
            }else{
                var id = $('input[type="hidden"][name="book_row_id"]').val()
                $('div.modal#remove-book-inventory').modal('hide');
                $('div.modal#enter-book-quantity-remove').modal();
            }
        });
    }
};
GlobalForm.INIT();