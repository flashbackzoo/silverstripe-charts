var $ = require('jquery');
var Chart = require('chart.js');
var tinycolor = require('tinycolor2');
var constants = require('./constants');

Chart.defaults.global.responsive = true;

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
  $.each(data.datasets[0].data, function (index, value) {
    var color = tinycolor(constants.bar.dataset.backgroundColor);

    data.datasets[0].backgroundColor.push(color.toRgbString());
    data.datasets[0].hoverBackgroundColor.push(color.lighten().toRgbString());
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
  $.each(data.datasets[0].data, function (index, value) {
    var color = tinycolor(constants.pie.dataset.backgroundColor);
    var fill = color.desaturate(index / data.datasets[0].data.length * 100);

    data.datasets[0].backgroundColor.push(fill.toRgbString());
    data.datasets[0].hoverBackgroundColor.push(fill.lighten().toRgbString());
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
