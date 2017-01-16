<?php
defined('APP_PATH') || define('APP_PATH', realpath(dirname(dirname(__FILE__))) . '/');
require('../lib/Mapper.php');
require('../lib/Table.php');
require('../model/Agent.php');
$db = new model\Agent();
//$mapper = $db->mapper;
//var_dump($mapper);exit;
$result = $db->insert(array('agent_id' => '12312'));
echo $result . "\n";
//$db = lib\Mapper::getInstance();
var_dump($db);
