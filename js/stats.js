$(document).ready(function(){
    var chartArray = [];
    $('.canvas canvas').each(
        function() {
            chartArray.push(this.id)
    });
    console.log(chartArray);

    var chartData = [];
    var chartLabels = [];

    $.ajax({
        url: "./inc/actionHandler.php",
        method: 'GET',
        dataType: 'json',
        data: {
            'stat': 'most-popular-recipe'
        },
        success: function(data)
        {
            data.forEach(x => chartData.push(parseInt(x.amount)));
            data.forEach(x => chartLabels.push(x.name));
            renderChart(data, '');
        },
        error: function (data)
        {
            console.log(data);
        }
    });

    function renderChart(data, labels) {
        var ctx = document.getElementById('most-popular-recipe').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor:[
                        'rgba(0, 192, 192, 0.2)',
                        'rgba(100, 92, 192, 0.2)',
                        'rgba(200, 192, 192, 0.2)'
                    ]
                }],
            }
        })
    };
})