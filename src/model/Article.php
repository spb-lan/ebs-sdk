<?php

namespace Lan\Ebs\Sdk\Model;

use Exception;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Client;

/**
 * @property mixed name
 * @property mixed description
 * @property mixed issn
 * @property mixed eissn
 * @property mixed vac
 * @property mixed year
 * @property mixed issuesPerYear
 * @property mixed editors
 * @property mixed publisher
 * @property mixed url
 */
class Article extends Model
{
    /**
     * Наименование статьи
     */
    const FIELD_NAME = 'name';

    /**
     * Авторы статьи
     */
    const FIELD_AUTHORS = 'authors';

    /**
     * Аннотация статьи
     */
    const FIELD_DESCRIPTION = 'description';

    /**
     * Ключевые слова статьи
     */
    const FIELD_KEYWORDS = 'keywords';

    /**
     * Страница начала статьи
     */
    const START_PAGE = 'startPage';

    /**
     * Страница окончания статьи
     */
    const FINISH_PAGE = 'finish_page';

    /**
     * Библиографическая запись
     */
    const FIELD_BIBLIOGRAPHIC_RECORD = 'bibliographicRecord';


    /**
     * Конструктор модели пользователя
     *
     * @param Client $client Инстанс клиента
     * @param array $fields Поля для выборки
     *
     * @throws Exception
     */
    public function __construct(Client $client, array $fields = [])
    {
        parent::__construct($client, $fields);
    }

    /**
     * Получение данных для запроса через API
     *
     * @param string $method Http-метод запроса
     * @param array $params Параметры для формирования урла
     *
     * @return array
     *
     * @throws Exception
     */
    public function getUrl($method, array $params = [])
    {
        switch ($method) {
            case 'get':
                return [
                    'url' => vsprintf('/1.0/resource/journal/article/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                ];
            case 'text':
                return [
                    'url' => vsprintf('/1.0/resource/journal/article/text/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }

    /**
     * Получение текстов статьи
     *
     * @param int $id Идентификатор модели
     *
     * @return array
     *
     * @throws Exception
     */
    public function text($id = null)
    {
        if ($id) {
            $this->setId($id);
        } else {
            $id = $this->getId();
        }

        if (empty($id)) {
            throw new Exception(Model::MESSAGE_ID_REQUIRED);
        }

        return $this->getClient()->getResponse($this->getUrl(__FUNCTION__, [$id]))['data'];
    }
}