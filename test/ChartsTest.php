<?php

class ChartTest extends SapphireTest {

    protected static $fixture_file = 'ChartsTest.yml';

    public function setUp() {
        parent::setUp();

        if(!file_exists(ASSETS_PATH)) mkdir(ASSETS_PATH);

        // Create a test folder for each of the fixture references
        $folderIDs = $this->allFixtureIDs('Folder');
        foreach($folderIDs as $folderID) {
            $folder = DataObject::get_by_id('Folder', $folderID);
            if(!file_exists(BASE_PATH."/$folder->Filename")) mkdir(BASE_PATH."/$folder->Filename");
        }

        // Create a test file for each of the fixture references
        $fileIDs = $this->allFixtureIDs('File');
        foreach($fileIDs as $fileID) {
            $file = DataObject::get_by_id('File', $fileID);
            $fh = fopen(BASE_PATH."/$file->Filename", "w");
            fwrite($fh, '"Option","Count"' . PHP_EOL);
            fwrite($fh, '"Apple",13' . PHP_EOL);
            fwrite($fh, '"Banana",3' . PHP_EOL);
            fwrite($fh, '"Cherry",6' . PHP_EOL);
            fwrite($fh, '"Grapefruit",3' . PHP_EOL);
            fclose($fh);
        }
    }

    public function tearDown() {
        parent::tearDown();

        // Remove the test files that we've created
        $fileIDs = $this->allFixtureIDs('File');
        foreach($fileIDs as $fileID) {
            $file = DataObject::get_by_id('File', $fileID);
            if($file && file_exists(BASE_PATH."/$file->Filename")) unlink(BASE_PATH."/$file->Filename");
        }

        // Remove the test folders that we've crated
        $folderIDs = $this->allFixtureIDs('Folder');
        foreach($folderIDs as $folderID) {
            $folder = DataObject::get_by_id('Folder', $folderID);
            if($folder && file_exists(BASE_PATH."/$folder->Filename")) {
                Filesystem::removeFolder(BASE_PATH."/$folder->Filename");
            }
        }

        // Remove left over folders and any files that may exist
        if(file_exists(ASSETS_PATH.'/ChartTest')) {
            Filesystem::removeFolder(ASSETS_PATH.'/ChartTest');
        }
    }

    public function testGetChartData() {
        $pieChart = $this->objFromFixture('Chart', 'chart1');
        $barChart = $this->objFromFixture('Chart', 'chart2');

        $expectedPieData = '[{&quot;label&quot;:&quot;Apple&quot;,&quot;value&quot;:&quot;13&quot;},{&quot;label&quot;:&quot;Banana&quot;,&quot;value&quot;:&quot;3&quot;},{&quot;label&quot;:&quot;Cherry&quot;,&quot;value&quot;:&quot;6&quot;},{&quot;label&quot;:&quot;Grapefruit&quot;,&quot;value&quot;:&quot;3&quot;}]';
        $expectedBarChartData = '{&quot;labels&quot;:[&quot;Apple&quot;,&quot;Banana&quot;,&quot;Cherry&quot;,&quot;Grapefruit&quot;],&quot;datasets&quot;:{&quot;data&quot;:[&quot;13&quot;,&quot;3&quot;,&quot;6&quot;,&quot;3&quot;]}}';

        $this->assertEquals($expectedPieData, $pieChart->getChartData());
        $this->assertEquals($expectedBarChartData, $barChart->getChartData());
    }
}
