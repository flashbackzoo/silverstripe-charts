<?php

use SilverStripe\View\Parsers\ShortcodeParser;

ShortcodeParser::get('default')->register('chart', ['flashbackzoo\SilverStripeCharts\ChartExtension', 'chartShortcodeHandler']);
