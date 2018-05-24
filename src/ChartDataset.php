<?php

namespace flashbackzoo\SilverStripeCharts;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\ORM\DataObject;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use TractorCow\Colorpicker\Forms\ColorField;

/**
 * A set of data belonging to a {@link Chart}.
 */
class ChartDataset extends DataObject
{
    private static $table_name = 'ChartDataSet';

    private static $db = [
        'SortOrder'=>'Int',
        'Label' => 'Varchar(255)',
        'Color' => 'Color',
    ];

    private static $has_one = [
        'Chart' => Chart::class,
    ];

    private static $has_many = [
        'DataRows' => ChartData::class,
    ];

    /**
     * @config
     */
    private static $background_color = '2196f3';

    private static $summary_fields = [
        'Label',
    ];

    private static $default_sort = 'SortOrder';

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

            $config->addComponent(new GridFieldExportButton('before'));
            $config->addComponent(new GridFieldOrderableRows('SortOrder'));
            $config
                ->getComponentByType(GridFieldAddNewButton::class)
                ->setButtonName('Add Data');

            $gridField = GridField::create(
                'Data',
                'Data',
                $this->getComponents('DataRows'),
                $config
            );

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
