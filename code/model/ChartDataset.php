<?php

/**
 * @package SilverStripeCharts
 *
 * A set of data belonging to a {@link Chart}.
 */
class ChartDataset extends DataObject
{
    private static $db = [
        'SortOrder'=>'Int',
        'Label' => 'Varchar(255)',
    ];

    private static $has_one = [
        'Chart' => 'Chart',
    ];

    private static $has_many = [
        'DataRows' => 'ChartData',
    ];

    public static $summary_fields = [
        'Label',
    ];

    public static $default_sort = 'SortOrder';

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('SortOrder');
        $fields->removeByName('ChartID');
        $fields->removeByName('DataRows');

        if ($this->getField('ID')) {
            $config = GridFieldConfig_RecordEditor::create();
            $config->removeComponentsByType('GridFieldFilterHeader');
            $config->removeComponentsByType('GridFieldDeleteAction');

            $importer = new GridFieldImporter('before');

            $config->addComponent($importer);
            $config->addComponent(new GridFieldExportButton('before'));
            $config->addComponent(new GridFieldSortableRows('SortOrder'));
            $config
                ->getComponentByType('GridFieldAddNewButton')
                ->setButtonName('Add Data');

            $gridField = GridField::create(
                'Data',
                'Data',
                $this->getComponents('DataRows'),
                $config
            );

            $loader = $importer->getLoader($gridField);

            $loader->mappableFields = [
                'Label' => 'Label',
                'Value' => 'Value',
            ];

            $fields->addFieldToTab('Root.Main', $gridField);
        }

        return $fields;
    }

    public function getCMSValidator()
    {
        return RequiredFields::create(
            'Label'
        );
    }

    public function getTitle()
    {
        return $this->getField('Label');
    }

    protected function onAfterDelete()
    {
        parent::onAfterDelete();

        foreach ($this->getComponents('DataRows') as $row) {
            $row->delete();
        }
    }

    /**
     * Get a list of labels which can be used by Chart.js.
     *
     * @return array
     */
    public function getChartLabels()
    {
        $chartLabels = [];

        $rows = $this->getComponents('DataRows');

        if ($rows->count()) {
            $chartLabels = $rows->Column('Label');
        }

        $this->extend('updateChartLabels', $chartLabels);

        return $chartLabels;
    }

    /**
     * Transform the dataset to an array which can be used by Chart.js.
     *
     * @return array
     */
    public function getChartDataset()
    {
        $chartDataset = [
            'label' => $this->getField('Label'),
            'data' => [],
        ];

        $rows = $this->getComponents('DataRows');

        if ($rows->count()) {
            $chartDataset['data'] = $rows->Column('Value');
        }

        $this->extend('updateChartDataset', $chartDataset);

        return $chartDataset;
    }
}
