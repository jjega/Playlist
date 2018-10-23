<?php
/**
 * Created by PhpStorm.
 * User: jonathanega
 * Date: 20/10/2018
 * Time: 22:52
 */

function getHeaderRule($method)
{
    $method = strtoupper($method);

    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: $method");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

}