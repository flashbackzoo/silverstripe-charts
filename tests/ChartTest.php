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

        $expected = '{&quot;labels&quot;:[&quot;Apple&quot;,&quot;Banana&quot;,&quot;Cherry&quot;,&quot;Grapefruit&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;Units&quot;,&quot;data&quot;:[1,3,5,7]}]}';

        $this->assertEquals($expected, $chart->getChartData());
    }
}
