<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 27.07.17
 * Time: 13:17
 */

namespace Lan\Ebs\Sdk\Helper;

use Codeception\Test\Unit;
use Exception;

/**
 * Хелпер для тестов
 *
 * @package Lan\Ebs\Sdk\Helper
 */
class Test
{
    /**
     * Ассерт для эксепшинов
     *
     * @param Unit $test Юнит-тест
     * @param Exception $e Эксешин
     * @param string $message Проверяемое сообщение
     */
    public static function assertExceptionMessage(Unit $test, Exception $e, $message)
    {
        $test->assertEquals($message, $e->getMessage());
    }
}