var $ = require('jquery'),
    Chart = require('chart.js');

var constants = {
    BAR_FILL_COLOR: 'rgba(151,187,205,0.5)',
    BAR_STROKE_COLOR: 'rgba(220,220,220,0.8)',
    BAR_HIGHLIGHT_FILL: 'rgba(220,220,220,0.75)',
    BAR_HIGHLIGHT_STROKE: 'rgba(220,220,220,1)'
};

var barChartOptions = {
    scaleShowHorizontalLines: false, // Whether to show horizontal lines (except X axis)
    scaleShowVerticalLines: false // Whether to show vertical lines (except Y axis)
};

var pieChartOptions = {};

var barData = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [
        {
            label: 'My First dataset',
            fillColor: constants.BAR_FILL_COLOR,
            strokeColor: constants.BAR_STROKE_COLOR,
            highlightFill: constants.BAR_HIGHLIGHT_FILL,
            highlightStroke: constants.BAR_HIGHLIGHT_STROKE,
            data: [65, 59, 80, 81, 56, 55, 40]
        }
    ]
};

var pieData = [
    {
        value: 300,
        color:"#F7464A",
        highlight: "#FF5A5E",
        label: "Red"
    },
    {
        value: 50,
        color: "#46BFBD",
        highlight: "#5AD3D1",
        label: "Green"
    },
    {
        value: 100,
        color: "#FDB45C",
        highlight: "#FFC870",
        label: "Yellow"
    }
];

// Create the charts

var charts = {
    'chart-type-bar': [],
    'chart-type-pie': []
};

$.each($('.chart-type-bar'), function (key, value) {
    charts['chart-type-bar'].push(new Chart(this.getContext('2d')).Bar(barData, barChartOptions));
});

$.each($('.chart-type-pie'), function (key, value) {
    charts['chart-type-pie'].push(new Chart(this.getContext('2d')).Pie(pieData, pieChartOptions));
});
