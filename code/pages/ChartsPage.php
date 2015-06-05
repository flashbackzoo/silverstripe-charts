<?php

class ChartsPage extends Page {

    private static $description = 'Display your data using Chart.js';

    private static $db = array(
        'Conclusion' => 'HTMLText'
    );

    private static $has_many = array(
        'Charts' => 'Chart'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $gridField = GridField::create(
            'Charts',
            'Charts',
            $this->Charts(),
            GridFieldConfig_RelationEditor::create()
        );

        //$gridConfig = $gridField->getConfig();



        $fields->addFieldsToTab('Root.Main', array(
            $gridField,
            HTMLEditorField::create('Conclusion', 'Conclusion')
        ));

        return $fields;
    }

}

class ChartsPage_Controller extends Page_Controller {
    public function init() {
        parent::init();

        Requirements::javascript(CHARTS_DIR . "/static/js/dist/chart.js");
        Requirements::javascript(CHARTS_DIR . "/static/js/dist/main.js");
        Requirements::css(CHARTS_DIR . "/static/css/main.css");
    }
}
