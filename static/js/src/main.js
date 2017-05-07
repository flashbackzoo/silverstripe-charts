var $ = require('jquery');
var Chart = require('chart.js');
var tinycolor = require('tinycolor2');
var constants = require('./constants');

Chart.defaults.global.responsive = true;

/**
 * Generate fill colors for the chart.
 *
 * @param {Object} dataset
 *
 * @return {Object}
 */
function generateBackgroundColor(dataset) {
  var generated = {
    backgroundColor: [],
    hoverBackgroundColor: [],
  };

  $.each(dataset.data, function (index, data) {
    var color = tinycolor(constants.default.dataset.backgroundColor);

    generated.backgroundColor.push(color.toRgbString());
    generated.hoverBackgroundColor.push(color.lighten().toRgbString());
  });

  return $.extend({}, dataset, generated);
}

/**
 * Create a bar chart.
 *
 * @param {Object} canvas - The chart canvas element.
 *
 * @return {Object} - Chart.js chart instance.
 */
function createBarChart(canvas) {
  var data = $(canvas).data('json');

  // Generate background colors.
  $.each(data.datasets, function (index, dataset) {
    data.datasets[index] = generateBackgroundColor(dataset);
  });

  return new Chart(
    canvas.getContext('2d'),
    {
      type: 'bar',
      data: data,
      options: constants.bar.options,
    }
  );
}

/**
 * Create a pie chart.
 *
 * @param {Object} canvas - The chart canvas element.
 *
 * @return {Object} - Chart.js chart instance.
 */
function createPieChart(canvas) {
  var data = $(canvas).data('json');

  // Generate background colors.
  $.each(data.datasets, function (index, dataset) {
    data.datasets[index] = generateBackgroundColor(dataset);
  });

  return new Chart(
    canvas.getContext('2d'),
    {
      type: 'pie',
      data: data,
      options: constants.pie.options,
    }
  );
}

// Create the charts...

$.each($('.chart--bar .chart__canvas'), function () {
    createBarChart(this);
});

$.each($('.chart--pie .chart__canvas'), function () {
    createPieChart(this);
});
