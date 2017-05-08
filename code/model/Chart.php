<?php

/**
 * @package SilverStripeCharts
 */
class Chart extends DataObject
{
    private static $description = 'Enter your chart data';

    private static $db = [
        'SortOrder'=>'Int',
        'Title' => 'Varchar(255)',
        'ChartType' => 'Varchar',
    ];

    private static $has_one = [
        'Page' => 'Page',
    ];

    private static $has_many = [
        'Datasets' => 'ChartDataset',
    ];

    /**
     * The available chart types.
     *
     * @var array
     */
    private static $chartTypes = [
        'bar' => 'Bar Chart',
        'pie' => 'Pie Chart',
    ];

    public static $default_sort = 'SortOrder';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('SortOrder');
        $fields->removeByName('PageID');
        $fields->removeByName('Datasets');

        $chartTypeDropdown = DropdownField::create(
            'ChartType',
            'Type',
            self::$chartTypes
        )
        ->setEmptyString('Select...');

        $fields->addFieldsToTab(
            'Root.Main',
            [
                $chartTypeDropdown,
            ]
        );

        if ($this->ID) {
            $fields->addFieldToTab(
                'Root.Main',
                ReadonlyField::create(
                    'Shortcode',
                    'Shortcode',
                    "[chart,id='{$this->getField('ID')}']"
                ),
                'Title'
            );

            $config = GridFieldConfig_RecordEditor::create();
            $config->removeComponentsByType('GridFieldFilterHeader');
            $config->removeComponentsByType('GridFieldSortableHeader');
            $config->removeComponentsByType('GridFieldDeleteAction');
            $config->addComponent(new GridFieldSortableRows('SortOrder'));
            $config
                ->getComponentByType('GridFieldAddNewButton')
                ->setButtonName('Add Dataset');

            $fields->addFieldToTab(
                'Root.Main',
                GridField::create(
                    'Datasets',
                    'Datasets',
                    $this->getComponents('Datasets'),
                    $config
                )
            );
        }

        return $fields;
    }

    public function getCMSValidator()
    {
        return RequiredFields::create(
            'Title',
            'ChartType'
        );
    }

    protected function onAfterDelete()
    {
        parent::onAfterDelete();

        foreach ($this->getComponents('Datasets') as $dataset) {
            $dataset->delete();
        }
    }

    /**
     * Get a JSON encoded string representing the chart's CSV data.
     *
     * @return string
     */
    public function getChartData()
    {
        $chartData = [
            'type' => $this->getField('ChartType'),
            'data' => [
                'labels' => [],
                'datasets' => [],
            ],
        ];

        $datasets = $this->getComponents('Datasets');

        // Populate the data.
        if ($datasets->count()) {
            $chartData['data']['labels'] = $datasets->first()->getChartLabels();

            foreach ($datasets as $dataset) {
                $chartData['data']['datasets'][] = $dataset->getChartDataset();
            }
        }

        // Set some default options.
        switch ($chartData['type']) {
            case 'bar':
                $chartData['options'] = [
                    'responsive' => true,
                    'scales' => [
                        'yAxes' => [
                            [
                                'ticks' => [
                                    'beginAtZero' => true,
                                    'min' => 0,
                                ],
                            ],
                        ],
                    ],
                ];
                break;

            case 'pie':
                $chartData['options'] = [
                    'responsive' => true,
                ];
                break;

            default:
                $chartData['options'] = [
                    'responsive' => true,
                ];
                break;
        }

        $this->extend('updateChartData', $chartData);

        return Convert::raw2xml(json_encode($chartData));
    }
}
