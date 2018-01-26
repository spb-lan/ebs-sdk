<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Collection\ArticleCollection;
use Lan\Ebs\Sdk\Collection\BookCollection;
use Lan\Ebs\Sdk\Collection\IssueCollection;
use Lan\Ebs\Sdk\Collection\JournalCollection;
use Lan\Ebs\Sdk\Collection\UserCollection;
use Lan\Ebs\Sdk\Model\Article;
use Lan\Ebs\Sdk\Model\Book;
use Lan\Ebs\Sdk\Model\Issue;
use Lan\Ebs\Sdk\Model\Journal;
use Lan\Ebs\Sdk\Model\User;

class CollectionTest extends \Codeception\Test\Unit
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

    /**
     * @throws \Exception
     */
    public function testIndex()
    {
        $testCollections = [
            BookCollection::class => [
                'modelClass' => Book::class
            ],
            JournalCollection::class => [
                'modelClass' => Journal::class
            ],
            IssueCollection::class => [
                'modelClass' => Issue::class,
                'id' => 2194
            ],
            ArticleCollection::class => [
                'modelClass' => Article::class,
                'id' => 302237
            ],
            UserCollection::class => [
                'modelClass' => User::class
            ]
        ];

        /**
         * @var Collection $collectionClass
         * @var array $testData
         */
        foreach ($testCollections as $collectionClass => $testData) {
            $limit = 3;

            if ($collectionClass == BookCollection::class || $collectionClass == JournalCollection::class || $collectionClass == UserCollection::class) {
                /** @var Collection $collection */
                $collection = new $collectionClass($this->client, [], $limit);
            } else {
                /** @var Collection $collection */
                $collection = new $collectionClass($testData['id'], $this->client, [], $limit);
            }

            $collectionCount = $collection->count();

            if (!$collectionCount) {
                continue;
            }

            if ($collectionCount > 0) {
                $first = $collection->reset();
                $last = $collection->end();

                $this->assertNotNull($first);
                $this->assertNotNull($last);

                $this->assertNotEquals($first, $last);
            }

            $this->assertLessThanOrEqual($limit, $collectionCount);

            $count = 0;

            $previousModelId = 0;

            /** @var Model $model */
            foreach ($collection as $model) {
                $this->assertInstanceOf($testData['modelClass'], $model);

                $this->assertNotNull($model->getId());

//                $this->assertNotEquals((int)$previousModelId, (int)$model->getId());

                $previousModelId = $model->getId();

                $count++;
            }

            $this->assertEquals($limit, $count);

            $data = $collection->getData();

            $this->assertEquals($collectionCount, count($data));
        }
    }
}