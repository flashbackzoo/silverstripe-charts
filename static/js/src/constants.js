module.exports = {
  default: {
    dataset: {
      backgroundColor: 'rgba(33,150,243,1)',
    },
  },
  bar: {
    options: {
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
    },
  },
  pie: {
    options: {},
  },
};
