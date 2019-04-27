@extends ('Admin.layout.main')
@section('pageCss')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
<div class="profile_container">
    <div class="row">
        <div class="col-md-3 col-lg-2"></div>
        <div class="col-md-9 col-lg-10">
            <div class="card" style="width:100%">
                <div class="card-body">
                    <div class="profile_content">
                        <h1 class="h2"><span class="fa fa-table"></span> Monthly Borrowed Books</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <canvas id="canvasBorrowed"></canvas>
                            </div>
                        </div>
                        <h1 class="h2"><span class="fa fa-table"></span> Monthly Borrowed Returned Books</h1><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <canvas id="canvasReturned"></canvas>
                            </div>
                        </div><hr>
                        <h1 class="h2"><span class="fa fa-table"></span> Returned Books</h1><hr>
                        <div class="row mt-5">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-6">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="w-50 pr-1">
                                        <input type="text" class="dates form-control" name="dates">
                                    </div>
                                    <div>
                                        <button class="btn btn-secondary runSearch" type="button">Run</button>
                                        <button type="button" class="btn btn-secondary download-reports"><span class="fa fa-download"></span> Download Logs</button>
                                    </div>
                                </div>
                            </div>
                        </div><hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-striped table_shad table-bordered table-hover get-borrowed-books">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Student Number</th>
                                            <th>Student Name</th>
                                            <th>Books</th>
                                            <th>Date Return</th>
                                            <th>Penalty</th>
                                            <th>Status</th>
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
@endsection

@section('pageJs')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
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
        $(".get-borrowed-books").DataTable({
            responsive: true,
            serverSide: true,
            bPaginate: true,
            searching: true,
            autoWidth : false,
            order: [[ 0, "desc" ]],
            processing: true,
            language: {
                processing: '<img src="{{URL::asset("public/icons/loading.gif")}}" style="width:10%; margin-bottom:10px;">'
            },
            ajax: {
                url: "{{route('admin.reports.get-returned-books')}}",
                data:{
                    datePicker: $("input[name='dates']").val(),
                }
            },
            createdRow : function(row, data, dataIndex){
                var thisRow = $(row);
                thisRow.addClass('cntr');
            }
        });
        canvasBorrowed();
        canvasReturned();
    });
    $('.runSearch').on('click',function(event){
        var dateSerialize = 'date=' + $("input[name='dates']").val();
        history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + dateSerialize);
        location.reload();
    });
    $('.download-reports').on('click',function(){
        window.open("/reports/download-xlsx"+window.location.search);
    });
    function canvasBorrowed(){
        var route = "{{route('admin.dashboard.get-borrowed-books-chart')}}";
        $.ajax({
            type: 'GET',
            url: route
        }).done(function(data){
            var array_months = [];
            var borrow_monthly = [], backgroundBorrowed = [];
            Object.entries(data['statusMonthly']).forEach(function(value,key){
                var months = value[0];
                var borrow = value[1]['borrow'];
                array_months.push(months);
                borrow_monthly.push(borrow);
                backgroundBorrowed.push('#8dc342');
            });
            const CHART = $('#canvasBorrowed');
            Chart.defaults.global.animation.duration = 500;
            let barChart = new Chart(CHART,{
                type: 'bar',
                data: {
                    labels: array_months,
                    datasets: [
                        {
                            label: 'Borrowed',
                            backgroundColor: backgroundBorrowed,
                            borderColor: backgroundBorrowed,
                            data: borrow_monthly,
                        },
                    ]
                },
                option: {
                    scales: {
                        yAxes: [{
                            stacked: true,
                            ticks: {
                            beginAtZero: true,
                            fontSize: 18,
                            fontStyle: 100,
                            fontColor: '#eeeee'
                            }
                        }],
                        xAxes: [{
                            stacked: true,
                            ticks: {
                            beginAtZero: true,
                            fontSize: 18,
                            fontStyle: 600,
                            fontColor: '#54575b'
                            },
                            categoryPercentage: .7
                        }]
                    },
                }
            });
        });
    }
    function canvasReturned(){
        var route = "{{route('admin.reports.get-returned-books-chart')}}";
        $.ajax({
            type: 'GET',
            url: route
        }).done(function(data){
            var array_months = [];
            var borrow_monthly = [], backgroundBorrowed = [];
            Object.entries(data['statusMonthly']).forEach(function(value,key){
                var months = value[0];
                var borrow = value[1]['borrow'];
                array_months.push(months);
                borrow_monthly.push(borrow);
                backgroundBorrowed.push('#534D4D');
            });
            const CHART = $('#canvasReturned');
            Chart.defaults.global.animation.duration = 500;
            let barChart = new Chart(CHART,{
                type: 'line',
                data: {
                    labels: array_months,
                    datasets: [
                        {
                            label: 'Returned Books',
                            backgroundColor: backgroundBorrowed,
                            borderColor: backgroundBorrowed,
                            data: borrow_monthly,
                        },
                    ]
                },
                option: {
                    scales: {
                        yAxes: [{
                            stacked: true,
                            ticks: {
                            beginAtZero: true,
                            fontSize: 18,
                            fontStyle: 100,
                            fontColor: '#eeeee'
                            }
                        }],
                        xAxes: [{
                            stacked: true,
                            ticks: {
                            beginAtZero: true,
                            fontSize: 18,
                            fontStyle: 600,
                            fontColor: '#54575b'
                            },
                            categoryPercentage: .7
                        }]
                    },
                }
            });
        });
    }
</script>
@endsection