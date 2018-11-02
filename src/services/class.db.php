<?php
/**
 * Created by PhpStorm.
 * User: jonathanega
 * Date: 22/10/2018
 * Time: 16:53
 */

namespace database;

use PDO;

class db extends PDO
{
    private static $PATH_INI = '/config/config.ini';
    function __construct()
    {
        if (!$settings = parse_ini_file(dirname(dirname(__FILE__)) . self::$PATH_INI, TRUE)) throw new exception('Unable to open ' . self::$PATH_INI . '.');

        $dns = $settings['DataBase']['db_driver'] . ':dbname=' . $settings['DataBase']['db_name'] . ';host=' . $settings['DataBase']['db_host'];
        parent::__construct($dns, $settings['DataBase']['db_user'], $settings['DataBase']['db_passwd']);

    }
}