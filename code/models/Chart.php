<?php

class Chart extends DataObject {

    private static $description = 'Enter your chart data';

    private static $db = array(
        'Question' => 'Varchar',
        'ChartType' => 'Varchar',
        'Description' => 'HTMLText',
    );

    private static $has_one = array(
        'ChartsPage' => 'ChartsPage',
        'UploadCsv' => 'File',
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $chartTypes = array(
            'bar' => 'Bar Chart',
            'pie' => 'Pie Chart'
        );
        $chartTypeDropdown = DropdownField::create(
            'ChartType',
            'Type of Chart',
            $chartTypes
        )->setEmptyString('(Select one)');

        $dataUpload = new UploadField(
            $name = 'UploadCsv',
            $title = 'Upload a CSV File'
        );

        $description = HTMLEditorField::create('Description', 'Description');

        $fields->addFieldsToTab('Root.Main', array($chartTypeDropdown, $dataUpload, $description));

        return $fields;
    }

}