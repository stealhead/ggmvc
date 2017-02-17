<?php
namespace Lib;
class GRedis {

	//数据库序列ID
	protected $_db = 0;

	//数据前缀名称
	protected $_name = 'def_';

	//主机地址
	protected $_host = '127.0.0.1';

	//主机端口
	protected $_port = 6379;

	//权限验证
	protected $_passwd = NULL;

	//超时时间
	protected $_timeout = 0;

	protected $_pconnect = false;

	protected $_redis;

	protected $_key = null;

	/**
	 * 构造器-初始化Redis配置
	 *
	 * */
	public function __construct () {
		$this->_redis = new \Redis();
	}

	public function setConfig (array $config) {
		if (!isset($config['host'])) throw new Exception('Invalid Hostname');
		$this->_host = $config['host'];
		if (isset($config['port']) && intval($config['port'])) $this->_port = $config['port'];
		if (isset($config['timeout']) && intval($config['timeout'])) $this->_timeout = $config['timeout'];
		if (isset($config['name']) && $config['name']) $this->_name = $config['name'];
		if (isset($config['pconnect']) && $config['pconnect']) $this->_pconnect = $config['pconnect'];
		if (isset($config['passwd']) && $config['passwd']) $this->_passwd = $config['passwd'];
		return $this;
	}

	public function getRedis () {
		if (!($this->_redis instanceof Redis)) $this->connect();
		return $this->_redis;
	}

	/**
	 * redis 连接实例
	 * */
	public function connect () {
		if ($this->_pconnect) 
			$this->_redis->pconnect($this->_host, $this->_port, intval($this->_timeout));
		else $this->_redis->connect($this->_host, $this->_port, intval($this->_timeout));
		$this->_redis->connect($this->_host, $this->_port);
		if ($this->_passwd) $this->_redis->auth($this->_passwd);
		return $this;
	}

	/**
	 * 关闭redis连接
	 * */
	public function close () {
		return $this->_redis->close();
	}

	/**
	 * 设置一个键值对
	 * */
	public function set ($key, $value) {
		$key = $this->_name . $key;
		$this->_redis->set($key, $value);
		$this->_key = $key;
		return $this;
	}

	/**
	 * 获取一个键值对
	 * */
	public function get ($key) {
		$key = $this->_name . $key;
		return $this->_redis->get($key);
	}

	/**
	 * 设置值自增
	 * @param string $key 自增字段键
	 * @param int		 $by  增量参数
	 * */
	public function incr ($key, $by = null) {
		$key = $this->_name . $key;
		if (0 < intval($by)) return $this->_redis->incrby($key, intval($by));
		return $this->_redis->incr($key);
	}

	/**
	 * 设置自减
	 * @param string $key 自减字段键
	 * @param int		 $by  减量参数
	 * */
	public function decr ($key, $by) {
		$key = $this->_name . $key;
		if (0 < detval($by)) return $this->_redis->decrby($key, detval($by));
		return $this->_redis->decr($key);
	}

	/**
	 * 重置值
	 * @return 重置前的旧值
	 * */
	public function getset ($key, $value) {
		$key = $this->_name . $key;
		return $this->_redis->getset($key, $value);
	}

	/**
	 * 判断键是否存在
	 * */
	public function exists ($key) {
		$key = $this->_name . $key;
		return $this->_redis->exists($key);
	}

	/**
	 * Delete Key
	 * @param string $key 删除值键
	 * */
	public function remove ($key) {
		$key = $this->_name . $key;
		return $this->_redis->del($key);
	}

	/**
	 * SETEX
	 * */
	public function setex ($key, $value, $ex) {
		$key = $this->_name . $key;
		$this->_key = $key;
		return $this->_redis->setex($key, $ex, $value);
	}

	/**
	 * 多值存储
	 * @param array $data 多键值对数据
	 * */
	public function mset (array $data) {
		$rows = array();
		foreach ($data as $key => $value)  {
			$key = $this->_name . $key;
			$rows[$key] = $value;
		}
		$this->_redis->mset($rows);
		return $this;
	}

	/**
	 * 多值提取
	 * @param array $keys 多值键列表
	 * */
	public function mget (array $keys) {
		foreach ($keys as &$key)  $key = $this->_name . $key;
		return $this->_redis->get($keys);
	}

	/**
	 * 多值删除
	 * @param array $keys 多值健列表
	 * */
	public function mdel (array $keys) {
		foreach ($keys as &$key)  $key = $this->_name . $key;
		$this->_redis->del($keys);
		return $this;
	}

	/**
	 * 构建集合
	 * */
	public function sadds ($key, array $values) {
		$key = $this->_name . $key;
		foreach ($values as $v) $this->_redis->sadd($key, $v); 
		$this->_key = $key;
		return $this;
	}

	/**
	 * 添加集合元素
	 * */
	public function sadd ($key, $value) {
		$key = $this->_name . $key;
		$this->_redis->sadd($key, $value); 
		$this->_key = $key;
		return $this;
	}

	/**
	 * 构建有序集合
	 * */
	public function zadds ($key, array $values) {
		$key = $this->_name . $key;
		$values = array_values($values);
		foreach ($values as $k => $v) $this->_redis->zadd($key, $k, $v); 
		$this->_key = $key;
		return $this;
	}

	/**
	 * 添加有序集元素
	 * */
	public function zadd ($key, $value, $order) {
		$key = $this->_name . $key;
		$this->_redis->zadd($key, intval($order), $value); 
		$this->_key = $key;
		return $this;
	}

	/**
	 * 获取有序集合
	 * */
	public function zrange ($key, $start = 0, $end = -1) {
		$key = $this->_name . $key;
		$range = $this->_redis->zrange($key, intval($start), intval($end)); 
		$this->_key = $key;
		return $range;
	}

	/**
	 * 获取有序集合总数
	 * */
	public function zSize ($key) {
		$key = $this->_name . $key;
		$range = $this->_redis->zSize($key);
		return $range;
	}

	/**
	 * 移除有序集合属性-移除集合Key中的属性值value
	 * */
	public function zdel ($key, $value) {
		$key = $this->_name . $key;
		return $this->_redis->zrem($key, $value);
	}

	/**
	 * 构建列表
	 * @param string $key		列表键
	 * @param string $value 列表值
	 * @param int		 $ins   插入类型 (0 压入-插入至头; 1 追加-在列表尾插入)
	 * */
	public function push ($key, $value, $ins = 0) {
		$key = $this->_name . $key;
		if (1 == $ins) $this->_redis->rpush($key, $value);
		else $this->_redis->lpush($key, $value);
		$this->_key = $key;
		return $this;
	}

	/**
	 * 输出列表数据
	 * @param string $key 获取的列表键
	 * @param int		 $ord 0：先进后出；1: 先进先出
	 * */
	public function pop ($key, $ord = 0) {
		$key = $this->_name . $key;
		if (1 == $ord) return $this->_redis->rpop($key);
		else return $this->_redis->lpop($key);
	}

	public function bpop ($key, $ord = 0, $timeout = 0) {
		$key = $this->_name . $key;
		if (1 == $ord) return $this->_redis->BRPOP($key, intval($timeout));
		else return $this->_redis->blpop($key, intval($timeout));
	}

	/**
	 * 发布消息
	 * */
	public function publish ($channel, $msg) {
		return $this->_redis->publish('chan_' . $channel, $msg);
	}

	/**
	 * 订阅者消费
	 * */
	public function subscribe (array $channel, $callback) {
		return $this->_redis->subscribe('chan_' . $channel, $callback);
	}

	static function readyMsg ($redis, $channel, $msg) {
		return array($channel => $msg);
	}

	/**
	 * 构建hash
	 * @param string $hash 哈希表名
	 * @param array  $key  列表数据
	 * */
	public function hash ($hash, array $data) {
		$hash = $this->_name . $hash;
		foreach ($data as $k => $v) $this->_redis->hset($hash, $k, $v);
		$this->_key = $hash;
		return $this;
	}

	/**
	 * 写入hash
	 * */
	public function hset ($hash, $key, $value) {
		$hash = $this->_name . $hash;
		$this->_redis->hset($hash, $key, $value);
		return $this;
	}

	/**
	 * 获取hash值
	 * @param string $hash 哈希表名
	 * @param mixed  $key  获取一个或多个键
	 * @return mixed ( NULL | String | Array)
	 * */
	public function hget ($hash, $key = NULL) {
		$hash = $this->_name . $hash;
		if (NULL == $key) return $this->_redis->hgetAll($hash);
		$data = NULL;
		if (is_array($key)) foreach ($key as $k) $data[$k] = $this->_redis->hget($hash, $k);
		else $data = $this->_redis->hget($hash, $key);
		return $data;
	}
	/**
	 * 删除Hash中的值-单键或批量
	 * */
	public function hdel ($hash, $key) {
		$hash = $this->_name . $hash;
		if (is_array($key)) foreach ($key as $k) $this->_redis->hdel($hash, $k);
		else $this->_redis->hdel($hash, $key);
		return $this;
	}
	/**
	 * 设置hash值自增序列
	 * */
	public function hIncrBy ($hash, $key, $incrby = 1) {
		$hash = $this->_name . $hash;
		return $this->hIncrBy($hash, $key, $incrby);
	}
	/**
	 * 获取hash键值列表
	 * */
	public function hgetAll ($hash) {
		$hash = $this->_name . $hash;
		return $this->_redis->hgetAll($hash);
	}
	/**
	 * 获取hash值列表 
	 * */
	public function hgetVals ($hash) {
		$hash = $this->_name . $hash;
		return $this->_redis->hvals($hash);
	}


	/**
	 * Redis KEYS pattern
	 * */
	public function keys ($key = '*') {
		$key = $this->_name . $key;
		return $this->_redis->keys($key);
	}

	/**
	 * fetchROWS
	 * 有序获取队列数据-不删除
	 * */
	public function fetchAll ($key, array $options) {
		$key = $this->_name . $key;
		if ($options) return $this->_redis->sort($key, $options);
		return $this->_redis->sort($key);
	}

	/**
	 * return Random Key
	 * */
	public function randkey () {
		return $this->_redis->randomkey();
	}

	/**
	 * TTL set (Time to live)
	 * @param string  $key		 设置指定键生存时间 (秒)
	 * @param int			$timeout expire time
	 * */
	public function setTTL ($timeout, $key = null) {
		if (NULL == $key) $key = $this->_key;
		else $key = $this->_name . $key;
		return $this->_redis->expire($key, $timeout);
	}

	/**
	 * 设置到期时间
	 * @param string  $key		 设置指定键生存时间 (Unix 时间戳)
	 * @param int			$timeout expire time
	 * */
	public function setTimeout ($timeout, $key = null) {
		if (NULL == $key) $key = $this->_key;
		else $key = $this->_name . $key;
		return $this->_redis->expireat($key, $timeout);
	}

	/**
	 * get TTL
	 * @param string $key 获取键生存时间
	 * */
	public function getTTL ($key = NULL) {
		if (NULL == $key) $key = $this->_key;
		else $key = $this->_name . $key;
		return $this->_redis->ttl($key);
	}

	/**
	 * 移除生存时间
	 * */
	public function removeTime ($key) {
		if (NULL == $key) $key = $this->_key;
		else $key = $this->_name . $key;
		return $this->_redis->PERSIST($key);
	}

	/**
	 * switch Db
	 * @param int $db 切换数据库序号
	 * */
	public function switchDB ($db) {
		return $this->_redis->select(intval($db));
	}

	/**
	 * move 实例
	 * @param string $key 要移动的键
	 * @param int		 $db  目标库序号
	 * @notice 移动的key需要在当前库中存在且在目标库中不存在，否则失败
	 * */
	public function move ($key, $db) {
		$key = $this->_name . $key;
		return $this->_redis->move($key, intval($db));
	}

	/**
	 * rename the key
	 * @param string $key1 原key
	 * @param string $key2 新key
	 * @notice $model == 1 时 key2存在时会被覆盖
	 * @notice $model == 2 时 当且仅当key2不存在时操作成功
	 * */
	public function rename ($key1, $key2, $model = 1) {
		$key1 = $this->_name . $key1;
		$key2 = $this->_name . $key2;
		if (1 == $model) return $this->_redis->rename($key1, $key2);
		return $this->_redis->renamenx($key1, $key2);
	}

	/**
	 * get value Type
	 * @return none | string | list | set | zset | hash
	 * */
	public function getType ($key = NULL) {
		if (NULL == $key) $key = $this->_key;
		else $key = $this->_name . $key;
		$type = $this->_redis->type($key);
		switch ($type) {
			case 0: return 'none';		//key不存在
			case 1: return 'string';	//字符串
			case 2: return 'set';			//集合
			case 3: return 'list';		//列表
			case 4: return 'zset';		//有序集合
			case 5: return 'hash';		//哈希表
			default: return $type;
		}
	}

	public function __set ($key, $value) {
		return $this->set($key, $value);
	}

	public function __get ($key) {
		return $this->get($key);
	}

	public function __isset ($key) {
		return $this->exists($key);
	}

	public function __unset ($key) {
		return $this->remove($key);
	}

}

