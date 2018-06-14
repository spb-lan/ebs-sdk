<?php
/**
 * Interface Common
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk;

use Exception;

/**
 * Общий интерфейс для объектов SDK
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 */
interface Common
{
    /**
     * Получение данных для запроса через API
     *
     * Метод возвращает данные по запросу к апи (урл, метод запроса, код ответа успешного ответа)
     *
     * @param string $method Http-метод запроса
     * @param array $params Параметры для формирования урла
     *
     * @return array
     *
     * @throws Exception
     */
    function getUrl($method, array $params = array());
}