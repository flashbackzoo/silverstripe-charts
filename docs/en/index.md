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
