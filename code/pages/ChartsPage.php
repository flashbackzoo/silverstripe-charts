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

        $config = GridFieldConfig_RelationEditor::create();
        $config->removeComponentsByType('GridFieldAddExistingAutocompleter');
        $config->removeComponentsByType('GridFieldSortableHeader');
        $config->removeComponentsByType('GridFieldDeleteAction');
        $config->addComponent(new GridFieldSortableRows('SortOrder'));

        $gridField = GridField::create(
            'Charts',
            'Charts',
            $this->Charts(),
            $config
        );

        $dataColumns = $config->getComponentByType('GridFieldDataColumns');

        $dataColumns->setDisplayFields(array(
            'Title' => 'Title'
        ));

        $fields->addFieldsToTab('Root.Main', array(
            HTMLEditorField::create('Content', 'Intro'),
            $gridField,
            HTMLEditorField::create('Conclusion', 'Conclusion')
        ), 'Metadata');

        return $fields;
    }

}

class ChartsPage_Controller extends Page_Controller {
    public function init() {
        parent::init();

        Requirements::javascript(CHARTS_DIR . "/static/js/dist/main.js");
        Requirements::css(CHARTS_DIR . "/static/css/main.css");
    }
}
