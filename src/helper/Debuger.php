<?php
/**
 * Class Debuger
 *
 * @author       Denis Shestakov <das@landev.ru>
 * @copyright    Copyright (c) 2017, Lan Publishing
 * @license      MIT
 */

namespace Lan\Ebs\Sdk\Helper;

/**
 * Хелпер для дебага тестов
 *
 * @package      Lan\Ebs
 * @subpackage   Sdk
 * @category     Helper
 */
class Debuger
{
    /**
     * Дамп через дебагер
     *
     * @param $var
     */
    public static function dump($var)
    {
        if (class_exists('\Codeception\Util\Debug', false)) {
            \Codeception\Util\Debug::debug($var);
        } else {
            if (class_exists('\Ice\Core\Debuger', false)) {
                \Ice\Core\Debuger::dump($var);
            }
        }
    }
}