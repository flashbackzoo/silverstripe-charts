# Developer Documentation

## Installation

`composer require flashbackzoo/silverstripe-charts`

## Configure

Extend any `Page` with `ChartExtension`

```yml
Page:
  extensions:
    - ChartExtension
```

This provides a "Charts" tab on pages, where CMS users can create either bar or pie charts.

See the [user guide](user-guide.md) for more details.

### Colors

CMS users can optionally assign a color to datasets. You can set the default hex value via config.

```yml
ChartDataset:
  background_color: '2196f3'
```
