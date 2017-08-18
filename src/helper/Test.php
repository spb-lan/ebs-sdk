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

class Test
{
    public static function assertExceptionMessage(Unit $test, Exception $e, $message)
    {
        $test->assertEquals($message, $e->getMessage());
    }
}