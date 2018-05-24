<?php

namespace flashbackzoo\SilverStripeCharts;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\ORM\DataExtension;
use SilverStripe\View\ArrayData;
use SilverStripe\View\Requirements;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;

class ChartExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $casting = [
        'chartShortcodeHandler' => 'HTMLText',
    ];

    private static $has_many = [
        'Charts' => Chart::class,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $config = GridFieldConfig_RecordEditor::create();
        $config->removeComponentsByType('GridFieldDeleteAction');
        $config->addComponent(new GridFieldOrderableRows('SortOrder'));

        $fields->addFieldsToTab(
            'Root.Charts',
            [
                GridField::create(
                    'Charts',
                    'Charts',
                    $this->owner->getComponents('Charts'),
                    $config
                ),
            ]
        );
    }

    /**
     * Handle the `[chart]` shortcode.
     *
     * @return string
     */
    public static function chartShortcodeHandler($arguments, $content = null, $parser = null)
    {
        if (!array_key_exists('id', $arguments) || !$chart = Chart::get()->byId($arguments['id'])) {
            return '';
        }

        Requirements::javascript('flashbackzoo/silverstripe-charts:client/js/dist/main.js');

        return ArrayData::create([
                'ChartID' => $chart->getField('ID'),
                'ChartType' => $chart->getField('ChartType'),
                'ChartData' => $chart->getChartData(),
            ])
            ->renderWith('flashbackzoo/SilverStripeCharts/ChartComponent');
    }
}
