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
class Journal extends Model
{
    /**
     * Наименование журнала
     */
    const FIELD_NAME = 'name';

    /**
     * Описание журнала
     */
    const FIELD_DESCRIPTION = 'description';

    /**
     * ISSN журнала
     */
    const FIELD_ISSN = 'issn';

    /**
     * EISSN журнала
     */
    const FIELD_EISSN = 'eissn';

    /**
     * Входит в перечень ВАК
     */
    const FIELD_VAC = 'vac';

    /**
     * Год основания
     */
    const FIELD_YEAR = 'year';

    /**
     * Выпусков в грд
     */
    const FIELD_ISSUES_PER_YEAR = 'issuesPerYear';

    /**
     * Редакторы
     */
    const FIELD_EDITORS = 'editors';

    /**
     * Издательство
     */
    const FIELD_PUBLISHER = 'publisher';

    /**
     * Ссылка на карточку журнала
     */
    const FIELD_URL = 'url';

    /**
     * Конструктор модели журнала
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
                    'url' => vsprintf('/1.0/resource/journal/get/%d', $params),
                    'method' => 'GET',
                    'code' => 200
                ];
            default:
                throw new Exception('Route for ' . $method . ' not found');
        }
    }
}