<?php

/**
 * @package SilverStripeCharts
 *
 * @subpackage tests
 */
class ChartDatasetTest extends SapphireTest
{
    protected static $fixture_file = 'ChartDatasetTest.yml';

    public function testGetChartLabels()
    {
        $dataset = $this->objFromFixture('ChartDataset', 'Fruit');

        $labels = $dataset->getChartLabels();

        $this->assertInternalType('array', $labels);
        $this->assertEquals(4, count($labels));
        $this->assertTrue(in_array('Apple', $labels));
        $this->assertTrue(in_array('Banana', $labels));
        $this->assertTrue(in_array('Cherry', $labels));
        $this->assertTrue(in_array('Grapefruit', $labels));
    }

    public function testGetChartDataset()
    {
        $dataset = $this->objFromFixture('ChartDataset', 'Fruit');

        $data = $dataset->getChartDataset();

        $this->assertArrayHasKey('label', $data);
        $this->assertArrayHasKey('data', $data);
        $this->assertEquals($data['label'], 'Units');
        $this->assertEquals($data['data'][0], 7);
        $this->assertEquals($data['data'][1], 3);
        $this->assertEquals($data['data'][2], 5);
        $this->assertEquals($data['data'][3], 1);
    }
}
