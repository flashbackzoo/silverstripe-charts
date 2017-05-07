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
        $this->assertEquals($data['label'], 'Units');

        $this->assertArrayHasKey('data', $data);
        $this->assertEquals(4, count($data['data']));
        $this->assertEquals($data['data'][0], 7);
        $this->assertEquals($data['data'][1], 3);
        $this->assertEquals($data['data'][2], 5);
        $this->assertEquals($data['data'][3], 1);

        $this->assertArrayHasKey('backgroundColor', $data);
        $this->assertEquals(4, count($data['backgroundColor']));
        $this->assertEquals($data['backgroundColor'][0], '#2196f3');
        $this->assertEquals($data['backgroundColor'][1], '#2196f3');
        $this->assertEquals($data['backgroundColor'][2], '#FF5722');
        $this->assertEquals($data['backgroundColor'][3], '#2196f3');

        $this->assertArrayHasKey('hoverBackgroundColor', $data);
        $this->assertEquals(4, count($data['hoverBackgroundColor']));
    }
}
