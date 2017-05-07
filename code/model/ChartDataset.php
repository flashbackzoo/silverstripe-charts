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
        'Color' => 'Color',
    ];

    private static $has_one = [
        'Chart' => 'Chart',
    ];

    private static $has_many = [
        'DataRows' => 'ChartData',
    ];

    /**
     * @config
     */
    private static $background_color = '2196f3';

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

        $fields->addFieldToTab(
            'Root.Main',
            ColorField::create('Color', 'Color')
        );

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

    protected function onBeforeWrite()
    {
        parent::onBeforeWrite();

        if (!$this->getField('Color')) {
            $this->setField('Color', $this->config()->background_color);
        }
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
            'backgroundColor' => [],
            'hoverBackgroundColor' => [],
            'label' => $this->getField('Label'),
            'data' => [],
        ];

        $rows = $this->getComponents('DataRows');

        if ($rows->count()) {
            $datasetBg = $this->obj('Color');
            $datasetBgValue = $datasetBg->getValue();

            foreach ($rows as $row) {
                $rowBg = $row->obj('Color');
                $rowBgValue = $rowBg->getValue();

                $chartDataset['backgroundColor'][] = ($rowBgValue ? "#{$rowBgValue}" : "#{$datasetBgValue}");
                $chartDataset['hoverBackgroundColor'][] = ($rowBgValue ? "#{$rowBg->Blend(0.8)}" : "#{$datasetBg->Blend(0.8)}");

                $chartDataset['data'][] = $row->getField('Value');
            }
        }

        $this->extend('updateChartDataset', $chartDataset);

        return $chartDataset;
    }
}
