<?php

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
        $config = GridFieldConfig_RelationEditor::create();
        $config->removeComponentsByType('GridFieldAddExistingAutocompleter');
        $config->removeComponentsByType('GridFieldSortableHeader');
        $config->removeComponentsByType('GridFieldDeleteAction');
        $config->addComponent(new GridFieldSortableRows('SortOrder'));

        $config
            ->getComponentByType('GridFieldDataColumns')
            ->setDisplayFields([
                'Title' => 'Title',
            ]);

        $fields->addFieldsToTab(
            'Root.Charts',
            [
                GridField::create(
                    'Charts',
                    'Charts',
                    $this->owner->Charts(),
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
                'ChartID' => $chart->ID,
                'ChartType' => $chart->ChartType,
                'ChartData' => $chart->getChartData(),
            ])
            ->renderWith('ChartComponent');
    }
}
