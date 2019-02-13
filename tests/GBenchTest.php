<?php
namespace GBench\Tests;

class GBenchTest extends \PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $gbench = new \GBench\GBench();
        $this->assertInstanceOf('GBench\GBench', $gbench);
    }

    public function testConstructWithHandler()
    {
        $sample = new class extends \GBench\AbstractHandler {
            public function startAfter(\GBench\GBench $gbench)
            {
                echo 'start';
            }

            public function stopAfter(\GBench\GBench $gbench, \GBench\Record $record)
            {
                echo 'stop';
            }
        };

        $gbench = new \GBench\GBench($sample);
        $gbench->start();

        $this->assertInstanceOf('GBench\AbstractHandler', $sample);
    }

    public function testStart()
    {
        $gbench = new \GBench\GBench();
        $gbench->start();

        $this->assertInstanceOf('GBench\GBench', $gbench);
    }

    public function testStop()
    {
        $gbench = new \GBench\GBench();
        $gbench->start();
        $gbench->stop('test');

        $this->assertNotEmpty($gbench->getRecords());
        $this->assertEquals('test', $gbench->getRecords()[0]->getLabel());
    }

    public function testStopExpectException()
    {
        $this->expectException(\LogicException::class);
        $gbench = new \GBench\GBench();
        $gbench->stop();
    }

    public function testReset()
    {
        $gbench = new \GBench\GBench();
        $gbench->start();
        $gbench->stop('test');
        $gbench->reset();

        $this->assertCount(0, $gbench->getRecords());
        $this->assertEquals(new \GBench\GBench, $gbench);
    }

    public function testRecord()
    {
        $gbench = new \GBench\GBench();
        $gbench->start();
        $gbench->record('test1');
        $gbench->record('test2');
        $gbench->record('test3');
        $gbench->stop('test4');

        $this->assertContainsOnlyInstancesOf(
            \GBench\Record::class,
            $gbench->getRecords()
        );

        $this->assertCount(4, $gbench->getRecords());
    }

    public function testGetRecords()
    {
        $gbench = new \GBench\GBench();
        $gbench->start();
        $gbench->record('test1');
        $gbench->record('test2');
        $gbench->record('test3');
        $gbench->stop('test4');

        $this->assertContainsOnlyInstancesOf(
            \GBench\Record::class,
            $gbench->getRecords()
        );

        $this->assertCount(4, $gbench->getRecords());
        $this->assertEquals('test1', $gbench->getRecords()[0]->getLabel());
        $this->assertEquals('test2', $gbench->getRecords()[1]->getLabel());
        $this->assertEquals('test3', $gbench->getRecords()[2]->getLabel());
        $this->assertEquals('test4', $gbench->getRecords()[3]->getLabel());
    }

    public function testRun()
    {
        $gbench = new \GBench\GBench();
        $result = $gbench->run(function () {
            return 10;
        }, 'run_test');

        $this->assertEquals('10', $result);
        $this->assertCount(1, $gbench->getRecords());
        $this->assertEquals('run_test', $gbench->getRecords()[0]->getLabel());
    }
}
