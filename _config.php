<?php

define('CHARTS_DIR', basename(__DIR__));

ShortcodeParser::get('default')->register('chart', ['ChartExtension', 'chartShortcodeHandler']);
