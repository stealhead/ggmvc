<?php
function autoloadSt($className) {
    $parts = explode('\\', $className);
    $path = APP_PATH . implode('/', $parts) . '.php';
    if(file_exists($path)) include_once($path);
    else{
        throw new Exception ("can not found {$path}\n");
    }
}
spl_autoload_register('autoloadSt');
