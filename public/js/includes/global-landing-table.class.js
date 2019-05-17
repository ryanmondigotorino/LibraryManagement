var GlobalTable = {

    INIT: function(){
        this.EVENTS();
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
            createdRow : function(row){
                var thisRow = $(row);
                thisRow.addClass('cntr');
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
            }
        });
    }
};
GlobalTable.INIT();