<?php
defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__)) . '/');
require_once(APP_PATH . "Lib/func/Autoload.php");
$default = include_once(APP_PATH . '/Conf/conf.php');
$module = $default['module'];
$controller = $default['controller'];
$action = $default['action'];
$className = 'Module\\' . ucfirst(strtolower($module)) . '\\' . ucfirst($controller);
$viewName = APP_PATH . 'View/' . ucfirst(strtolower($module)) . '/' . ucfirst(strtolower($controller)) . '/' . strtolower($action) . '.html'; 
try {
    $obj = new $className();
    $obj->$action();
} catch (Exception $e) {
    echo 'caught exception:' . $e->getMessage() . "\n";
}
