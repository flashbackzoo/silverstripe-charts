var $ = require('jquery'),
    Chart = require('chart.js'),
    tinycolor = require('tinycolor2');

var constants = {
    BAR_DEFAULTS: {
        fillColor: 'rgba(151,187,205,0.5)',
        strokeColor: 'rgba(220,220,220,0.8)',
        highlightFill: 'rgba(220,220,220,0.75)',
        highlightStroke: 'rgba(220,220,220,1)'
    },
    BAR_OPTIONS: {
        scaleShowHorizontalLines: false, // Whether to show horizontal lines (except X axis)
        scaleShowVerticalLines: false // Whether to show vertical lines (except Y axis)
    },
    PIE_COLOR: 'blue'
};

// Create the charts

var charts = {
    'chart-type-bar': [],
    'chart-type-pie': []
};

$.each($('.chart-type-bar'), function (key, value) {
    var data = $(this).data('json');

    data.datasets = [$.extend(data.datasets, constants.BAR_DEFAULTS)];

    charts['chart-type-bar'].push(new Chart(this.getContext('2d')).Bar(data, constants.BAR_OPTIONS));
});

$.each($('.chart-type-pie'), function (key, value) {
    var data = $(this).data('json'),
        color = tinycolor(constants.PIE_COLOR);

    $.each(data, function (index, value) {
        var fill = color.desaturate(index / data.length * 100);
        value = $.extend(value, { color: fill.toRgbString(), highlight: fill.lighten().toRgbString() });
    });

    charts['chart-type-pie'].push(new Chart(this.getContext('2d')).Pie(data));
});
