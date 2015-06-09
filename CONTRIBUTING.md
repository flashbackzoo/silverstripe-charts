# Contributing

The easiest way to get a development version is with `composer require flashbackzoo/silverstripe-charts --prefer-source`

Don't forget to run the tests `./vendor/bin/phpunit silverstripe-charts/test/`

## Front-end

To update the front-end (JavaScript, CSS) you first need to install the dev dependencies with `npm install`

Once you have installed the dependencies, run `npm run build` to compile the JavaScript and CSS.

### JavaScript

The main JavaScript for the module is `static/js/src/main.js`

Compile the JavaScript with `npm run build:js`

Watch for changes with `npm run watch:js`

### CSS

The CSS uses the [Stylus preporcessor](https://learnboost.github.io/stylus/) and is located at `static/styl/main.styl`

Compile the CSS with `npm run build:css`

Watch for changes with `npm run watch:css`
