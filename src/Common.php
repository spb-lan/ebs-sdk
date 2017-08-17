<?php
/**
 * Created by PhpStorm.
 * User: dp
 * Date: 17.08.17
 * Time: 13:00
 */

namespace Lan\Ebs\Sdk;

interface Common
{
    function getUrl($method, array $params = []);
}