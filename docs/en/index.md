# Developer Documentation

## Installation

`composer require flashbackzoo/silverstripe-charts`

## Setup

Extend any `Page` with `ChartExtension`

```yml
Page:
  extensions:
    - ChartExtension
```

This provides a "Charts" tab on pages, where CMS users can create either bar or pie charts.

See the [user guide](user-guide.md) for more details.

## Configure

CMS users can optionally assign a color to datasets. You can set the default hex value via config.

```yml
ChartDataset:
  background_color: '2196f3'
```

## Extend

You can pass custom options to your charts by creating an extension.

```yml
Chart:
  extensions:
    - MyChartExtension
```

```php
class MyChartExtension extends DataExtension
{
    public function updateChartData(&$chartData)
    {
        // Check for the chart type then set some options.
        if ($chartData['type'] === 'bar') {
            $chartData['options'] = [
                // Your options.
            ];
        }
    }
}
```

The [Chart.js docs](http://www.chartjs.org/docs/) provide a list of options you can pass to each chart type.
