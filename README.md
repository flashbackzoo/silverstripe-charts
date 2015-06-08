# SilverStripe Charts

This module provides a Charts Page pagetype, which can be used to present CSV data, via the [Chart.js](http://www.chartjs.org/) library.

There are currently two supported Chart types, Bar chart, and Pie chart.

NOTE: This module is still in development - so don't use it in production just yet :)

## Requirements

```
"php": ">=5.3.2",
"silverstripe/framework": "~3.1",
"silverstripe/cms": "~3.1",
"undefinedoffset/sortablegridfield": "0.4.3"
```

## Usage

1. Create a Charts Page in the CMS
2. Add a Chart via the GridField
3. Publish your page

Charts get their data from the CVS file you upload at Step 2. The CSV requires the following column headings:

- Option: Used for labels
- Count: Used for totals

If your CSV includes other columns, that's fine, they will just be ignored.

Here is an example of a valid CSV file:

```
"Option","Count"
"Apple",13
"Banana",3
"Cherry",6
"Grapefruit",3
```

## Contributing

See the [contributing](CONTRIBUTING.md) docs.
