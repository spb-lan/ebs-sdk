<?php
/**
 * Class Issue
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
 * Модель выпусков
 *
 * @property mixed name
 * @property mixed year
 * @property mixed url
 * @property mixed thumb
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Model
 */
class Issue extends Model
{
    /**
     * Номер выпуска
     */
    const FIELD_NAME = 'name';

    /**
     * Год выпуска
     */
    const FIELD_YEAR = 'year';

    /**
     * Ссылка на карточку выпуска
     */
    const FIELD_URL = 'url';

    /**
     * Ссылка на обложку выпуска
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
     * Получение данных для запроса через API
     *
     * @param string $method Http-метод запроса
     * @param array $params Параметры для формирования урла
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
                    'url' => vsprintf('/1.0/resource/journal/issue/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                );
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}