var $ = require('jquery');
var Chart = require('chart.js');

/**
 * Create a chart.
 *
 * @param {Object} canvas - HTML canvas element.
 * @return {Object} - Chart.js chart instance.
 */
function createChart(canvas) {
  return new Chart(canvas.getContext('2d'), $(canvas).data('json'));
}

$.each($('.chart__canvas'), function () {
  createChart(this);
});
