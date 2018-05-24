var Chart = require('chart.js');

/**
 * Create a chart.
 *
 * @param {Object} canvas - HTML canvas element.
 * @return {Object} - Chart.js chart instance.
 */
function createChart(canvas) {
  return new Chart(canvas.getContext('2d'), JSON.parse(canvas.dataset.json));
}

document
  .querySelectorAll('.chart__canvas')
  .forEach(function (el) {
    createChart(el);
  });
