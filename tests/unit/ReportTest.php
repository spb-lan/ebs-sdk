<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Exception;
use Lan\Ebs\Sdk\Report;

class ReportTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $client;

    protected function getServices()
    {
        return [
            'di' => new \Codeception\Lib\Di(),
            'dispatcher' => new \Codeception\Util\Maybe(),
            'modules' => \Codeception\Util\Stub::makeEmpty('Codeception\Lib\ModuleContainer')
        ];
    }

    protected function setUp()
    {
        $this->getMetadata()->setServices($this->getServices());

        parent::setUp();
    }

    protected function _before()
    {
        $this->client = ClientTest::getClient();
    }

    protected function _after()
    {
    }

    public function testGetBooksViewStatistics()
    {
        $report = new Report($this->client);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '2017-08-28');
        $this->assertNotNull($stat);
        $this->assertNotEmpty($stat);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '', '');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('', '2017-07-01', '2017-08-28');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '', '2017-08-28');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', 'YYYY-08-28');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '2015+08-28');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '2015+08-55');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '2015+12-30');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '333-12-30');
    }

    public function testGetJournalsViewsStatistics()
    {
        $report = new Report($this->client);
        $stat = $report->getJournalsViewsStatistics('month', '2017-07-01', '2017-08-28');
        $this->assertNotNull($stat);
        $this->assertNotEmpty($stat);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getJournalsViewsStatistics('month', '', '');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getJournalsViewsStatistics('', '2017-07-01', '2017-08-28');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getJournalsViewsStatistics('month', '', '2017-08-28');


        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '2015-dd-28');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '201108-28');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '2015-0830');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '20151230');

        $this->expectException(Exception::class);
        $this->expectExceptionCode(400);
        $stat = $report->getBooksViewsStatistics('month', '2017-07-01', '2015.12.30');

    }

    public function testGetUsersVisitsStatistics()
    {
        $report = new Report($this->client);
        $stat = $report->getUsersVisitsSatistics('month', '2017-07-01', '2017-08-28');
        $this->assertNotNull($stat);
    }

    public function testGetAvailablePackets()
    {
        $report = new Report($this->client);
        $available = $report->getAvailablePackets();
        $this->assertNotNull($available);
        $this->assertNotEmpty($available);
    }

    public function testGetAvailableJournals()
    {
        $report = new Report($this->client);
        $available = $report->getAvailableJournals();
        $this->assertNotNull($available);
        $this->assertNotEmpty($available);
    }


    public function testGetAvailableBooks()
    {
        $report = new Report($this->client);
        $available = $report->getAvailableBooks(814392);
        $this->assertNotNull($available);
        $this->assertNotEmpty($available);
    }

    public function testGetFormReportEBooks()
    {
        $report = new Report($this->client);
        $available = $report->getFormReportEBooks();
        $this->assertNotNull($available);
        $this->assertNotEmpty($available);
    }
}