var $ = require('jquery'),
    Chart = require('chart.js'),
    tinycolor = require('tinycolor2'),
    constants = require('./constants');

Chart.defaults.global.responsive = true;

/**
 * @func createBarChart
 * @param {object} canvas - The chart canvas element
 * @return {object} - Chart.js chart instance
 */
function createBarChart(canvas) {
    var data = $(canvas).data('json');

    data.datasets = [$.extend(data.datasets, constants.BAR_DEFAULTS)];

    return new Chart(canvas.getContext('2d')).Bar(data, constants.BAR_OPTIONS);
}

/**
 * @func colorPieChartSegments
 * @param {object} data - Object literal representing the chart's data
 * @return {object} - An updated version of the passed data
 * @desc Applys incremental color values to each segment of a pie chart's data
 */
function colorPieChartSegments(data) {
    $.each(data, function (index, value) {
        var color = tinycolor(constants.PIE_COLOR),
            fill = color.desaturate(index / data.length * 100);

        value = $.extend(value, { color: fill.toRgbString(), highlight: fill.lighten().toRgbString() });
    });

    return data;
}

/**
 * @func createPieChart
 * @param {object} canvas - The chart canvas element
 * @return {object} - Chart.js chart instance
 */
function createPieChart(canvas) {
    var data = $(canvas).data('json');

    chart = new Chart(canvas.getContext('2d')).Pie(colorPieChartSegments(data));
}

// Create the charts...

$.each($('.chart-type-bar .chart'), function () {
    createBarChart(this);
});

$.each($('.chart-type-pie .chart'), function () {
    createPieChart(this);
});
