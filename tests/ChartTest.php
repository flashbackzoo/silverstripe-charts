<?php

/**
 * @package SilverStripeCharts
 *
 * @subpackage tests
 */
class ChartTest extends SapphireTest
{
    protected static $fixture_file = 'ChartTest.yml';

    public function testGetChartData()
    {
        $chart = $this->objFromFixture('Chart', 'Bar');

        $this->assertInternalType('string', $chart->getChartData());
    }
}
