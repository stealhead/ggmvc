<?php
header("Content-type: text/html; charset=utf-8"); 
//测试数据库
$serv = new swoole_server("0.0.0.0", 9506);

$serv->set(
	array(
		'worker_num' => 8, 
		'task_worker_num' => 100, 
		'daemonize' => false,
	));

$serv->on('Connect', function($serv, $fd){
	echo "Client:Connect.\n";
});

$serv->on('receive', function($serv, $fd, $form_id, $page){
	$start = microtime();
	echo $page . "\n";
    echo "fd : " . $fd . "  form id : " . $form_id . "\n";
	$serv->send($fd, 'i have get '. $page . "workid $serv->worker_id \n");
    for($i=1; $i <= 1000; $i++){
	    $work_id = $serv->task($page, $fd);
	    $serv->send($fd, 'workerid is:' . $work_id . "\n");
    }
	$end = microtime();
});
$serv->on('task', function($serv, $task_id, $form_id, $page){
	echo "page:" . $page . "\n";
	echo microtime() . "\n";
    $db = new model\Agent();
    $data = array(
        'name' => 'wanggang',
        'agent_id' => 336763,
        'acount' => 'moon15',
        'mobile' => 13333400827,
        'level' => '100',
        'email' => '2592030745@qq.com',
        'sale_id' => '100'
    );
    for($i=1; $i <= 50; $i++){ 
        $db->select(array('agent_id' => 33673));
        $db->insert($data);
    }
	echo microtime() . "\n";
});
$serv->on('WorkerStart', function($serv, $work_id){
	if($serv->taskworker){
        defined("APP_PATH") || define("APP_PATH", realpath(dirname(dirname(__FILE__))) . '/'); 
        require "../lib/Mapper.php";
        require "../lib/Table.php";
        require "../model/Agent.php";
		echo "taskworker my id is : $work_id\n";
	}
});

$serv->on('WorkerStop', function($serv, $work_id){});
$serv->on('finish', function($serv, $task_id, $data){
	echo "task over\n";
});
$serv->on('close', function($serv, $fd){
	echo $fd."client close\n";
});
$serv->start();
//$serv->heartbeat();
