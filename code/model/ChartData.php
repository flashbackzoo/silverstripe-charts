<?php

/**
 * @package SilverStripeCharts
 *
 * A row of data belonging to a {@link ChartDataset}.
 */
class ChartData extends DataObject
{
    private static $db = [
        'SortOrder'=>'Int',
        'Label' => 'Varchar(255)',
        'Value' => 'Int',
        'Color' => 'Color',
    ];

    private static $has_one = [
        'Dataset' => 'ChartDataset',
    ];

    public static $summary_fields = [
        'Label',
        'Value',
    ];

    public static $default_sort = 'SortOrder';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('SortOrder');
        $fields->removeByName('DatasetID');

        $fields->addFieldToTab(
            'Root.Main',
            ColorField::create('Color', 'Color')
        );

        return $fields;
    }

    public function getCMSValidator()
    {
        return RequiredFields::create(
            'Label',
            'Value'
        );
    }

    public function getTitle()
    {
        return $this->getField('Label');
    }

    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();

        // If no color is set use the parent dataset color.
        if (!$this->getField('Color')) {
            $this->setField('Color', $this->getComponent('Dataset')->getField('Color'));
        }
    }
}
