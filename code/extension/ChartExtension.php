<?php

/**
 * @package SilverStripeCharts
 */
class ChartExtension extends DataExtension
{
    /**
     * @var array
     */
    private static $casting = [
        'chartShortcodeHandler' => 'HTMLText',
    ];

    private static $has_many = [
        'Charts' => 'Chart',
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $config = GridFieldConfig_RecordEditor::create();
        $config->removeComponentsByType('GridFieldDeleteAction');
        $config->addComponent(new GridFieldSortableRows('SortOrder'));

        $fields->addFieldsToTab(
            'Root.Charts',
            [
                GridField::create(
                    'Charts',
                    'Charts',
                    $this->getComponents('Charts'),
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

        Requirements::javascript(CHARTS_DIR . '/static/js/dist/main.js');

        return ArrayData::create([
                'ChartID' => $chart->getField('ID'),
                'ChartType' => $chart->getField('ChartType'),
                'ChartData' => $chart->getChartData(),
            ])
            ->renderWith('ChartComponent');
    }
}
