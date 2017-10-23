<?php

namespace Lan\Ebs\Sdk\Helper;

class Debuger
{
    /**
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