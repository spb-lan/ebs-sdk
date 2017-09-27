<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Exception;
use Lan\Ebs\Sdk\Report;
use PHPUnit_Framework_TestResult;

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

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Runs a test and collects its result in a TestResult instance.
     *
     * @param PHPUnit_Framework_TestResult $result
     *
     * @return PHPUnit_Framework_TestResult
     */
    public function run(PHPUnit_Framework_TestResult $result = null)
    {
        // TODO: Implement run() method.
    }
}