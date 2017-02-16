<?php
namespace Lib;
class Config
{
    private $_path;
    public function __construct ($path = NULL, $filename = NULL)
    {
       if($path) $this->_path .= $path;
       else $this->_path .= APP_PATH . 'Conf';
       $this->_path = '/' . trim($this->_path, '/') . '/';
       if($filename) $this->_path .= $filename;
       else $this->_path .= 'conf.php';
    }
    public function getConf ($key = null)
    {
        $conf = include $this->_path;
        if($key){
            return $conf[$key];
        } else  return $conf;
    }
}
