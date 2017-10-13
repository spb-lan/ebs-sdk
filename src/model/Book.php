<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 26.07.17
 * Time: 11:57
 */

namespace Lan\Ebs\Sdk\Model;

use Exception;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Client;

/**
 * @property mixed name
 * @property mixed description
 * @property mixed isbn
 * @property mixed year
 * @property mixed edition
 * @property mixed pages
 * @property mixed special_marks
 * @property mixed classification
 * @property mixed authors
 * @property mixed author_additions
 * @property mixed bibliographic_record
 * @property mixed content_quality
 * @property mixed publisher
 * @property mixed url
 * @property mixed thumb
 */
class Book extends Model
{
    /**
     * Наименование книги
     */
    const FIELD_NAME = 'name';

    /**
     * Описание книги
     */
    const FIELD_DESCRIPTION = 'description';

    /**
     * ISBN книги
     */
    const FIELD_ISBN = 'isbn';

    /**
     * Год издания книги
     */
    const FIELD_YEAR = 'year';

    /**
     * Издание
     */
    const FIELD_EDITION = 'edition';

    /**
     * Объем книги
     */
    const FIELD_PAGES = 'pages';

    /**
     * Специальные отметки
     */
    const FIELD_SPECIAL_MARKS = 'special_marks';

    /**
     * Гриф
     */
    const FIELD_CLASSIFICATION = 'classification';

    /**
     * Авторы
     */
    const FIELD_AUTHORS = 'authors';

    /**
     * Дополнительные авторы
     */
    const FIELD_AUTHOR_ADDITIONS = 'author_additions';

    /**
     * Библиографическая запись
     */
    const FIELD_BIBLIOGRAPHIC_RECORD = 'bibliographic_record';

    /**
     * Качество текста книг (процент)
     */
    const FIELD_CONTENT_QUALITY = 'content_quality';

    /**
     * Издательство
     */
    const FIELD_PUBLISHER = 'publisher';

    /**
     * Ссылка на карточку книги
     */
    const FIELD_URL = 'url';

    /**
     * Ссылка на обложку книги
     */
    const FIELD_THUMB = 'thumb';

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
                    'url' => vsprintf('/1.0/resource/book/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}