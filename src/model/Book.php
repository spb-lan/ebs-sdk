<?php
/**
 * Class Book
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Model;

use Exception;
use Lan\Ebs\Sdk\Classes\Model;
use Lan\Ebs\Sdk\Client;

/**
 * Модель книг
 *
 * @property mixed name
 * @property mixed description
 * @property mixed isbn
 * @property mixed year
 * @property mixed edition
 * @property mixed pages
 * @property mixed specialMarks
 * @property mixed classification
 * @property mixed authors
 * @property mixed authorAdditions
 * @property mixed bibliographicRecord
 * @property mixed contentQuality
 * @property mixed publisher
 * @property mixed url
 * @property mixed thumb
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Model
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
    const FIELD_SPECIAL_MARKS = 'specialMarks';

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
    const FIELD_AUTHOR_ADDITIONS = 'authorAdditions';

    /**
     * Библиографическая запись
     */
    const FIELD_BIBLIOGRAPHIC_RECORD = 'bibliographicRecord';

    /**
     * Качество текста книг (процент)
     */
    const FIELD_CONTENT_QUALITY = 'contentQuality';

    /**
     * Издательство
     */
    const FIELD_PUBLISHER = 'publisher';

    /**
     * Языл
     */
    const FIELD_LANG = 'lang';

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
    public function __construct(Client $client, array $fields = array())
    {
        parent::__construct($client, $fields);
    }

    /**
     * Получение текстов книги
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

        return $this->getClient()->getResponse($this->getUrl(__FUNCTION__, array($id)))['data'];
    }

    /**
     * Получение данных для запроса через API
     *
     * @param string $method Http-метод запроса
     * @param array $params Параметры для формирования урла
     *
     * ```php
     *
     *  $url = $this->getUrl('get');
     * ```
     *
     * @return array
     *
     * @throws Exception
     */
    public function getUrl($method, array $params = array())
    {
        switch ($method) {
            case 'get':
                return array(
                    'url' => vsprintf('/1.0/resource/book/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                );
            case 'text':
                return array(
                    'url' => vsprintf('/1.0/resource/book/text/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}