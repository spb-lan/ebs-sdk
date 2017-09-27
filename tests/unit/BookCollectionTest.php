<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Lan\Ebs\Sdk\Collection\BookCollection;
use Lan\Ebs\Sdk\Model\Book;
use PHPUnit_Framework_TestResult;

class BookCollectionTest extends \Codeception\Test\Unit
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

    public function testIndex()
    {
        $limit = 5;

        $collection = new BookCollection($this->client, [], $limit);

        $collectionCount = $collection->count();

        if ($collectionCount > 0) {
            $first = $collection->reset();
            $last = $collection->end();

            $this->assertNotNull($first);
            $this->assertNotNull($last);

            $this->assertNotEquals($first, $last);
        }

        $this->assertLessThanOrEqual($limit, $collectionCount);

        $count = 0;

        $previousBook = null;
        $previousBookId = 0;

        /** @var Book $book */
        foreach ($collection as $book) {
            $this->assertInstanceOf(Book::class, $book);

            $this->assertNotNull($book->getId());

            $this->assertNotEquals($previousBook, $book);
            $this->assertNotEquals($previousBookId, $book->getId());

            $previousBook = $book;
            $previousBookId = $book->getId();

            $count++;
        }

        $this->assertEquals($limit, $count);

        $data = $collection->getData();

        $this->assertEquals($collectionCount, count($data));
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