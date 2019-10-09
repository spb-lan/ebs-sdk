<?php

namespace Lan\Ebs\Sdk\Test\Unit;

use Exception;
use Lan\Ebs\Sdk\Classes\Collection;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Collection\ArticleCollection;
use Lan\Ebs\Sdk\Collection\BookCollection;
use Lan\Ebs\Sdk\Collection\IssueCollection;
use Lan\Ebs\Sdk\Collection\JournalCollection;
use Lan\Ebs\Sdk\Collection\UserCollection;
use Lan\Ebs\Sdk\Helper\Test;
use Lan\Ebs\Sdk\Model\Article;
use Lan\Ebs\Sdk\Model\Book;
use Lan\Ebs\Sdk\Model\Issue;
use Lan\Ebs\Sdk\Model\Journal;
use Lan\Ebs\Sdk\Model\User;

class ModelTest extends \Codeception\Test\Unit
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
     * @throws Exception
     */
    public function testGet()
    {
        $testModels = [
            Book::class => [
                'collectionClass' => BookCollection::class
            ],
            Journal::class => [
                'collectionClass' => JournalCollection::class
            ],
            Issue::class => [
                'collectionClass' => IssueCollection::class,
                'id' => 2194
            ],
            Article::class => [
                'collectionClass' => ArticleCollection::class,
                'id' => 302237
            ],
            User::class => [
                'collectionClass' => UserCollection::class
            ],
        ];

        foreach ($testModels as $modelClass => $testData) {
            /** @var Model $model */
            $model = new $modelClass($this->client);

            try {
                $model->get();
            } catch (Exception $e) {
                Test::assertExceptionMessage($this, $e, Model::MESSAGE_ID_REQUIRED);
            }

            $collectionClass = $testData['collectionClass'];

            $limit = 3;

            if ($collectionClass === BookCollection::class || $collectionClass === JournalCollection::class || $collectionClass === UserCollection::class) {
                /** @var Collection $collection */
                $collection = new $collectionClass($this->client, [], $limit);
            } else {
                /** @var Collection $collection */
                $collection = new $collectionClass($testData['id'], $this->client, [], $limit);
            }

            /** @var Book $model */
            $model = $collection->reset();

            $data = $model->get();

            $this->assertNotNull($model->getId());
            $this->assertNotNull($model->id);
            $this->assertNotNull($data['id']);

//            $this->assertNotNull($model->name);
//
            /** @var Model $model1 */
            $model1 = new $modelClass($this->client);

            $modelData = $model1->get($model->getId());

            $this->assertEquals($modelData['id'], $model->getId());

//            $this->assertEquals($model->name, $modelData['name']);
        }
    }
}