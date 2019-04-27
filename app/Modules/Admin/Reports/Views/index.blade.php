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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('pageJs')
<script>
    $(document).ready(function(){
        canvasBorrowed();
        canvasReturned();
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