# Contributing

The easiest way to get a development version is with `composer require flashbackzoo/silverstripe-charts --prefer-source`

Don't forget to run the tests `./vendor/bin/phpunit silverstripe-charts/tests/`

## Front-end

To update the front-end (JavaScript, CSS) you first need to install the dependencies with `npm install`

Once you've don that, run `npm run build` to compile the JavaScript.

### JavaScript

The main JavaScript for the module is `static/js/src/main.js`

You can watch for changes with `npm run build:watch`
