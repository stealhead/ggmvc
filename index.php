<?php
defined('APP_PATH') || define('APP_PATH', realpath(dirname(__FILE__)) . '/');
require_once(APP_PATH . "Lib/func/Autoload.php");
$default = include_once(APP_PATH . 'Conf/conf.php');
$module = $default['module'];
$controller = $default['controller'];
$action = $default['action'];
$request_uri = $_SERVER['REQUEST_URI'];
$script_name = $_SERVER['SCRIPT_NAME'];
$path_info = str_replace($script_name, '', $request_uri);
$request = trim($path_info, '/') ? explode('/', trim($path_info, '/')) : NULL;
if(!empty($request)){
    if(array_key_exists(0, $request) && $request[0]) $module = $request[0];
    if(array_key_exists(1, $request) && $request[1]) $controller = $request[1];
    if(array_key_exists(2, $request) && $request[2]) $action = $request[2];
} 
$className = 'Module\\' . ucfirst(strtolower($module)) . '\\' . ucfirst($controller);
$viewName = APP_PATH . 'View/' . ucfirst(strtolower($module)) . '/' . ucfirst(strtolower($controller)) . '/' . strtolower($action) . '.html'; 
try {
    $obj = new $className();
    $obj->$action();
} catch (Exception $e) {
    echo 'caught exception:' . $e->getMessage() . "\n";
}
