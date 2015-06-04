<?php

class ChartsPage extends Page {

    private static $description = 'Display your data using Chart.js';

    private static $db = array(
        'Conclusion' => 'HTMLText'
    );

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->addFieldsToTab('Root.Main', array(
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
