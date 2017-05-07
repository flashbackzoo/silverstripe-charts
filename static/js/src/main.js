var $ = require('jquery');
var Chart = require('chart.js');

Chart.defaults.global.responsive = true;

/**
 * Create a chart.
 *
 * @param {Object} canvas - HTML canvas element.
 * @param {String} type - Type of chart to create.
 * @param {Object} options - Options for the chart.
 * @return {Object} - Chart.js chart instance.
 */
function createChart(canvas, type, options) {
  return new Chart(
    canvas.getContext('2d'),
    {
      type: type,
      data: $(canvas).data('json'),
      options: options,
    }
  );
}

$.each($('.chart--bar .chart__canvas'), function () {
  createChart(
    this,
    'bar',
    {
      scales: {
        yAxes: [
          {
            ticks: {
              beginAtZero: true,
              min: 0,
            },
          },
        ],
      },
    }
  );
});

$.each($('.chart--pie .chart__canvas'), function () {
  createChart(this, 'pie', {});
});
