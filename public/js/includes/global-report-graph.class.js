var GlobalGraph = {

    INIT: function(){
        this.CANVASBORROWED();
        this.CANVASRETURNED();
    },
    CANVASBORROWED: function(){
        var route = $('canvas#canvasBorrowed').attr('data-url');
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
    },

    CANVASRETURNED: function(){
        var route = $('canvas#canvasReturned').attr('data-url');
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
};