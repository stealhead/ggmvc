<?php
namespace Lib;
class RedisTable extends \Lib\GRedis{
    public function __construct($config = null)
    {
        if(!$config){
            $confObj = new \Lib\Config();
            $config = $confObj->getConf('redis');
        }
        parent::__construct();
        $this->setConfig($config);
        return $this->connect();
    }
}
