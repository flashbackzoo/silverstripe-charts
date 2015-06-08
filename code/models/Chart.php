<?php

class Chart extends DataObject {

    private static $description = 'Enter your chart data';

    private static $db = array(
        'SortOrder'=>'Int',
        'Title' => 'Varchar',
        'ChartType' => 'Varchar',
        'Description' => 'HTMLText',
    );

    private static $has_one = array(
        'ChartsPage' => 'ChartsPage',
        'UploadCsv' => 'File',
    );

    /**
     * The available chart types.
     * @var Array
     */
    private static $chartTypes = array(
        'bar' => 'Bar Chart',
        'pie' => 'Pie Chart'
    );

    public static $default_sort = 'SortOrder';

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeByName('SortOrder');
        $fields->removeByName('ChartsPageID');

        $chartTypeDropdown = DropdownField::create(
            'ChartType',
            'Chart type',
            self::$chartTypes
        )->setEmptyString('(Select one)');

        $dataUpload = UploadField::create(
            'UploadCsv',
            'Chart data')
            ->setDescription('CSV data for the chart');
        $dataUpload->allowedExtensions = array('csv');

        $description = HTMLEditorField::create('Description', 'Description');

        $fields->addFieldsToTab('Root.Main', array(
            $chartTypeDropdown,
            $description
        ));

        $fields->addFieldToTab('Root.Main', $dataUpload, 'Description');

        return $fields;
    }

    public function getCMSValidator() {
        return new RequiredFields('Title', 'ChartType', 'Description', 'UploadCsv');
    }

    /**
     * Get the cache key for the chart, used for Partial Caching in the template.
     * The cache should invalidate when the chart's type changes or the data is updated.
     * @return String - The cache key.
     */
    public function getChartCacheKey() {
        return implode('_', array(
            'chart',
            $this->ID,
            $this->ChartType,
            strtotime($this->UploadCsv()->LastEdited)
        ));
    }

    /**
     * Generates a Bar Chart formatted array from the chart's CSV data.
     * @param CSVParser
     * @return Array
     */
    private function getBarChartData(CSVParser $parser) {
        $data = array(
            'labels' => array(),
            'datasets' => array(
                'data' => array()
            )
        );

        foreach ($parser as $row) {
            $data['labels'][] = (array_key_exists('Option', $row) ? $row['Option'] : '');
            $data['datasets']['data'][] = (array_key_exists('Count', $row) ? $row['Count'] : '');
        }

        return $data;
    }

    /**
     * Generates a Pie Chart formatted array from the chart's CSV data.
     * @param CSVParser
     * @return Array
     */
    private function getPieChartData(CSVParser $parser) {
        $data = array();

        foreach ($parser as $row) {
            $data[] = array(
                'label' => (array_key_exists('Option', $row) ? $row['Option'] : ''),
                'value' => (array_key_exists('Count', $row) ? $row['Count'] : '')
            );
        }

        return $data;
    }

    /**
     * Get a JSON encoded string representing the chart's CSV data.
     * @return String
     */
    public function getChartData() {
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