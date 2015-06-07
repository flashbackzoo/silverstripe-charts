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

}