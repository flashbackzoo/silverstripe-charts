<?php

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
        'UploadCsv' => 'File',
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

        $chartTypeDropdown = DropdownField::create(
            'ChartType',
            'Chart type',
            self::$chartTypes
        )
        ->setEmptyString('(Select one)');

        $dataUpload = UploadField::create(
            'UploadCsv',
            'Chart data'
        )
        ->setDescription('CSV data for the chart');

        $dataUpload->allowedExtensions = ['csv'];

        $fields->addFieldsToTab(
            'Root.Main',
            [
                $chartTypeDropdown,
                $dataUpload,
            ]
        );

        if ($this->ID) {
            $fields->addFieldToTab(
                'Root.Main',
                ReadonlyField::create(
                    'Shortcode',
                    'Shortcode',
                    "[chart,id='{$this->ID}']"
                ),
                'Title'
            );
        }

        return $fields;
    }

    public function getCMSValidator()
    {
        return RequiredFields::create(
            'Title',
            'ChartType',
            'UploadCsv'
        );
    }

    /**
     * Generates a Bar Chart formatted array from the chart's CSV data.
     *
     * @param CSVParser
     *
     * @return array
     */
    private function getBarChartData(CSVParser $parser)
    {
        $data = [
            'labels' => [],
            'datasets' => [
                [
                    'label' => $this->Title,
                    'backgroundColor' => [], // Populated on the client-side.
                    'hoverBackgroundColor' => [], // Populated on the client-side.
                    'borderWidth' => 0,
                    'data' => [],
                ]
            ],
        ];

        foreach ($parser as $row) {
            $data['labels'][] = (array_key_exists('Label', $row) ? $row['Label'] : '');
            $data['datasets'][0]['data'][] = (array_key_exists('Value', $row) ? $row['Value'] : '');
        }

        return $data;
    }

    /**
     * Generates a Pie Chart formatted array from the chart's CSV data.
     *
     * @param CSVParser
     *
     * @return array
     */
    private function getPieChartData(CSVParser $parser)
    {
        $data = [
            'labels' => [],
            'datasets' => [
                [
                    'backgroundColor' => [], // Populated on the client-side.
                    'hoverBackgroundColor' => [], // Populated on the client-side.
                    'data' => [],
                ],
            ],
        ];

        foreach ($parser as $row) {
            $data['labels'][] = (array_key_exists('Label', $row) ? $row['Label'] : '');
            $data['datasets'][0]['data'][] = (array_key_exists('Value', $row) ? $row['Value'] : '');
        }

        return $data;
    }

    /**
     * Get a JSON encoded string representing the chart's CSV data.
     *
     * @return string
     */
    public function getChartData()
    {
        if (!$this->UploadCsv()->ID) {
            return '';
        }

        $parser = new CSVParser($this->UploadCsv()->getFullPath());

        if ($this->ChartType == 'bar') {
            $data = $this->getBarChartData($parser);
        } else {
            $data = $this->getPieChartData($parser);
        }

        return Convert::raw2xml(json_encode($data));
    }
}
