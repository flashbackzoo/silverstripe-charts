<?php

class Chart extends DataObject {

    private static $description = 'Enter your chart data';

    private static $db = array(
        'Question' => 'Varchar',
        'ChartType' => 'Varchar',
        'Evaluation' => 'HTMLText',
    );

    private static $has_one = array(
        'ChartsPage' => 'ChartsPage',
        'UploadCsv' => 'File',
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $options = array('Pie Chart', 'Bar Chart');
        $field = DropdownField::create('ChartType', 'Type of Chart', $options)
            ->setEmptyString('(Select one)');

        $upload = new UploadField(
            $name = 'UploadCsv',
            $title = 'Upload a CSV File'
        );

        $fields->addFieldsToTab('Root.Main', array(
            $field,
            $upload,
            HTMLEditorField::create('Evaluation', 'Evaluation')
        ));

        return $fields;
    }

}