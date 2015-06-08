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

    public static $default_sort = 'SortOrder';

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeByName('SortOrder');
        $fields->removeByName('ChartsPageID');

        $chartTypeDropdown = DropdownField::create(
            'ChartType',
            'Chart type',
            array(
                'bar' => 'Bar Chart',
                'pie' => 'Pie Chart'
            ))
            ->setEmptyString('(Select one)');

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

    private function getBarChartData() {
        if (!$this->UploadCsv()->ID) {
            return null;
        }

        $parser = new CSVParser($this->UploadCsv()->getFullPath());

        $data = array(
            'labels' => array(),
            'datasets' => array(
                'data' => array()
            )
        );

        foreach ($parser as $row) {
            $data['labels'][] = $row['Option'];
            $data['datasets']['data'][] = $row['Count'];
        }

        return Convert::raw2xml(json_encode($data));
    }

    private function getPieChartData() {
        $parser = new CSVParser($this->UploadCsv()->getFullPath());

        $data = array();

        foreach ($parser as $row) {
            $data[] = array(
                'label' => $row['Option'],
                'value' => $row['Count']
            );
        }

        return Convert::raw2xml(json_encode($data));
    }

    public function getChartData() {
        if ($this->ChartType == 'bar') {
            return $this->getBarChartData();
        } else {
            return $this->getPieChartData();
        }
    }

}