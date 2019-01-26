<?php
/**
 * Created by PhpStorm.
 * User: lotus
 * Date: 1/25/19
 * Time: 2:06 PM
 */

$driverType = 'PDO';
//$driverType = 'MySql';

function getDbConnection($driverType) {
    $className = "DB_" . $driverType;
    return new $className;
}