var GlobalTable = {

    INIT: function(){
        this.EVENTS();
        this.ADMINSACCOUNT();
    },

    EVENTS: function(){
        var targetTbl = $('table.global-landing-table');
        var loader = targetTbl.attr('data-loader');
        var url = targetTbl.attr('data-url');
        targetTbl.DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            processing: true,
            language: {
                processing: '<img src="'+loader+'" style="width:10%; margin-bottom:10px;">'
            },
            ajax: {
                url: url,
            },
            createdRow : function(row,data){
                var thisRow = $(row);
                thisRow.addClass('cntr');
                if(data[5] >= 0){
                    if(data[5] > 10){
                        $(row).addClass('colorGreen');
                    }else if(data[5] > 5 && data[5] <= 10){
                        $(row).addClass('colorOrange');
                    }else if(data[5] > 0 && data[5] <= 5){
                        $(row).addClass('colorRed');
                    }
                }
                $('button[type="button"].delete-borrow',row).on('click',function(){
                    var id = $(this).attr('data-id'),
                        url = $(this).attr('data-url'),
                        token = $(this).attr('data-token');
                    var targetBtn = $('button[type="button"].delete-borrow.borrow-'+id);
                    swal({
                        title: "Confirmation",
                        text: "Cancel borrowed?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((result) => {
                        if(result){
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    id: id,
                                    _token : token
                                },
                                beforeSend:function(){
                                    $(targetBtn).prop('disabled',true);
                                    $(targetBtn).html('<i class="fa fa-spinner fa-pulse"></i>');
                                },
                                success:function(result){
                                    if(result['status'] == 'success'){
                                        swal({
                                            title: "Success",
                                            text:  result['messages'],
                                            icon: result['status'],
                                        }).then((resultStatus) => {
                                            location.reload();
                                        });
                                    }else{
                                        $(targetBtn).prop('disabled',false);
                                        swal({
                                            title: "Error",
                                            text: result['messages'],
                                            icon: result['status'],
                                        });
                                    }
                                }
                            }).done(function(){
                                $(targetBtn).html('<span class="fa fa-remove"></span> Cancel Borrow');
                            });
                        }
                    });
                });
                $('button[type="button"].renew-borrow',row).on('click',function(){
                    var id = $(this).attr('data-id');
                    $('div#renewal-books-'+id).modal();
                });
                $('button[type="button"].return-borrow',row).on('click',function(){
                    var url = $(this).attr('data-url'),
                        id = $(this).attr('data-id'),
                        token = $(this).attr('data-token');
                    swal({
                        title: "Confirmation",
                        text: "Mark this as returned?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((result) => {
                        if(result){
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    id: id,
                                    _token : token
                                },
                                beforeSend:function(){
                                    $('button.btn.btn-success.return-'+id).prop('disabled',true);
                                    $('button.btn.btn-success.return-'+id).html('<i class="fa fa-spinner fa-pulse"></i>');
                                },
                                success:function(result){
                                    if(result['status'] == 'success'){
                                        swal({
                                            title: "Success",
                                            text:  result['messages'],
                                            icon: result['status'],
                                        }).then((resultStatus) => {
                                            location.reload();
                                        });
                                    }else{
                                        $('button.btn.btn-success.return-'+id).prop('disabled',false);
                                        swal({
                                            title: "Error",
                                            text: result['messages'],
                                            icon: result['status'],
                                        });
                                    }
                                }
                            }).done(function(){
                                $('button.btn.btn-success.return-'+id).html('<span class="fa fa-sign-out"></span> Mark Return');
                            });
                        }
                    });
                });
                $('button[type="button"].approve-borrow',row).on('click',function(){
                    var id = $(this).attr('data-id');
                    $('#add-penalty-'+id).modal();
                });
                $('button[type="button"].edit-course-btn',row).on('click',function(){
                    var id = $(this).attr('data-id');
                    $('#edit-course-'+id).modal();
                });
                $('button[type="button"].delete-course-btn',row).on('click',function(){
                    var id = $(this).attr('data-id'),
                        name = $(this).attr('data-name'),
                        url = $(this).attr('data-url'),
                        token = $(this).attr('data-token');
                    swal({
                        title: "Confirmation!",
                        text: "Delete this "+name+" course?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((result) => {
                        if(result){
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    id: id,
                                    _token: token
                                },
                                beforeSend:function(){
                                    $('button.course-'+id).prop('disabled',true);
                                    $('button.course-'+id).html('<i class="fa fa-spinner fa-pulse"></i>');
                                },
                                success: function(result){
                                    if(result['status'] == 'success'){
                                        swal({
                                            title: 'Success!',
                                            text: result['messages'],
                                            icon: result['status']
                                        }).then((result) => {
                                            location.reload();
                                        });
                                    }else{
                                        $('button.course-'+id).prop('disabled',false);
                                        swal({
                                            title: 'Error!',
                                            text: result['messages'],
                                            icon: result['status']
                                        });
                                    }
                                },
                            }).done(function(){
                                $('button.course-'+id).html('<span class="fa fa-trash"></span>');
                            });
                        }
                    });
                });
                $('button[type="button"].edit-department',row).on('click',function(){
                    var id = $(this).attr('data-id');
                    $('div.modal#edit-department-'+id).modal();
                });
                $('button[type="button"].delete-department',row).on('click',function(){
                    var id = $(this).attr('data-id'),
                        department_name = $(this).attr('data-name'),
                        url = $(this).attr('data-url'),
                        token = $(this).attr('data-token');
                    swal({
                        title: "Confirmation!",
                        text: "Delete this "+department_name+" department?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((result) => {
                        if(result){
                            $.ajax({
                                type: 'POST',
                                url: url,
                                data: {
                                    id: id,
                                    _token: token
                                },
                                beforeSend:function(){
                                    $('button.department-'+id).prop('disabled',true);
                                    $('button.department-'+id).html('<i class="fa fa-spinner fa-pulse"></i>');
                                },
                                success: function(result){
                                    if(result['status'] == 'success'){
                                        swal({
                                            title: 'Success!',
                                            text: result['messages'],
                                            icon: result['status']
                                        }).then((result) => {
                                            location.reload();
                                        });
                                    }else{
                                        $('button.department-'+id).prop('disabled',false);
                                        swal({
                                            title: 'Error!',
                                            text: result['messages'],
                                            icon: result['status']
                                        });
                                    }
                                },
                            }).done(function(){
                                $('button.department-'+id).html('<span class="fa fa-trash"></span>');
                            });
                        }
                    });
                });
                $('button[type="button"].add-book-quantity',row).on('click',function(){
                    var id = $(this).attr('data-id');
                    var title = $(this).attr('data-title');
                    var quantity = $(this).attr('data-quantity');
                    swal({
                        title: "Confirmation",
                        text: "Add quantity for this book?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((result) => {
                        if(result){
                            $('span.book-title').html(title);
                            $('input[type="hidden"][name="book_id"]').val(id);
                            $('span.current_quantity').html(quantity);
                            $('div.modal#add-book-quantity').modal();
                        }
                    });
                });
                $('button[type="button"].remove-book-or-quantity',row).on('click',function(){
                    var id = $(this).attr('data-id');
                    $('input[type="hidden"][name="book_row_id"]').val(id);
                    $('div.modal#remove-book-inventory').modal();
                });
            }
        });
    },

    AUDITS: function(){
        $(document).ready(function(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            let params = new URLSearchParams(window.location.search);
            let dateFrom = '';
            let dateTo = '';
            if(params.get("date") == undefined){
                dateFrom = today
                dateTo = today
            }else{
                dateFrom = params.get("date").split(' - ')[0];
                dateTo = params.get("date").split(' - ')[1];
            }
            $('input[name="dates"]').daterangepicker({
                opens : 'left',
                applyButtonClasses : 'btn--teal',
                cancelButtonClasses : 'btn-danger',
                autoApply: true,
                locale: { format: 'DD/MM/Y' },
                startDate: dateFrom, 
                endDate: dateTo
            });
            var dateSerialize = 'date=' + $("input[name='dates']").val();
            history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + dateSerialize);
            var targetTbl = $('table.global-audit-table');
            var loader = targetTbl.attr('data-loader');
            var url = targetTbl.attr('data-url');
            targetTbl.DataTable({
                responsive: true,
                serverSide: true,
                bPaginate: true,
                searching: true,
                autoWidth : false,
                order: [[ 0, "desc" ]],
                processing: true,
                language: {
                    processing: '<img src="'+loader+'" style="width:10%; margin-bottom:10px;">'
                },
                ajax: {
                    url: url,
                    data:{
                        datePicker: $("input[name='dates']").val(),
                    }
                },
                createdRow : function(row){
                    var thisRow = $(row);
                    thisRow.addClass('cntr');
                }
            });
        });
        $('.runSearch').on('click',function(event){
            var dateSerialize = 'date=' + $("input[name='dates']").val();
            history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + dateSerialize);
            location.reload();
        });
        $('.download-reports').on('click',function(){
            if($(this).hasClass('for-admin')){
                window.open("/accounts/admin-audit/download-xlsx"+window.location.search);
            }else if($(this).hasClass('for-student')){
                window.open("/accounts/student-audit/download-xlsx"+window.location.search);
            }else{
                window.open("/reports/download-xlsx"+window.location.search);
            }
        });
    },

    ADMINSACCOUNT: function(){
        var targetTbl = $('table.global-accounts-table');
        var loader = targetTbl.attr('data-loader');
        var url = targetTbl.attr('data-url');
        var acc_type =targetTbl.attr('data-type');
        targetTbl.DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            processing: true,
            language: {
                processing: '<img src="'+loader+'" style="width:10%; margin-bottom:10px;">'
            },
            ajax: {
                url: url,
                data: {
                    account_type: acc_type
                }
            },
            createdRow : function(row){
                var thisRow = $(row);
                thisRow.addClass('cntr');
                $('button[type="button"].change-stat',row).on('click',function(){
                    var id = $(this).attr('data-id'),
                        acc_stat = $(this).attr('data-stat'),
                        url = $(this).attr('data-url'),
                        token = $(this).attr('data-token')
                        model = $(this).attr('data-model');
                    var status;
                    if(acc_stat == 0){
                        status = "Activate Account?"
                    }else{
                        status = "Deactivate Account?"
                    }
                    swal({
                        title: "Confirmation",
                        text: status,
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((result) => {
                        if(result){
                            $.ajax({
                                type:"POST",
                                url:url,
                                data:{
                                    'id': id,
                                    'acc_stat':acc_stat,
                                    'model': model,
                                    '_token': token
                                },
                                success:function(getResult){
                                    var text = "";
                                    if(getResult == 0){
                                        text = "Status Successfully Deactivate!";
                                    }else{
                                        text = "Status Successfully Activate!";
                                    }
                                    swal({
                                        title: "Success",
                                        text: text,
                                        icon: "success",
                                    }).then((resultStatus) => {
                                        location.reload();
                                    });
                                }
                            });
                        }
                    });
                });
            }
        });
    }
};
GlobalTable.INIT();