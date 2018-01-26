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
        $stat = $report->getBooksViewsStatistics('month', '2017-10-01', '2017-11-01');
        $this->assertNotNull($stat);
        $this->assertNotEmpty($stat);
    }

    public function testGetJournalsViewsStatistics()
    {
        $report = new Report($this->client);
        $stat = $report->getJournalsViewsStatistics('month', '2017-10-01', '2017-11-01');
        $this->assertNotNull($stat);
        $this->assertNotEmpty($stat);
    }

    public function testGetUsersVisitsStatistics()
    {
        $report = new Report($this->client);
        $stat = $report->getUsersVisitsStatistics('month', '2017-10-01', '2017-11-01');
        $this->assertNotNull($stat);
        $this->assertNotEmpty($stat);
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
//        $report = new Report($this->client);
//        $available = $report->getAvailableJournals();
//        $this->assertNotNull($available);
//        $this->assertNotEmpty($available);
    }

    public function testGetAvailableBooks()
    {
        $report = new Report($this->client);
        $available = $report->getAvailableBooks(720773);
        $this->assertNotNull($available);
        $this->assertNotEmpty($available);
    }
}