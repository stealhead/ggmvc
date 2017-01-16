<?php
namespace Lib;
class Mapper
{
    public $conn;
    public static $sql;
    public static $instance = null;
    private function __construct()
    {
        $conf = include APP_PATH.'/Conf/conf.php';
        $this->conn = mysqli_connect($conf['host'], $conf['username'], $conf['password'], $conf['database']);
        if($this->conn->connect_error) {
            die('connect error (' . $this->conn->connect_errno . ')' . $this->conn->connect_error);
        }
        mysqli_query($this->conn, 'set name utf8');

    }
    public static function getInstance () 
    {
        if(is_null(self::$instance)){
            self::$instance = new Mapper();
        }
        return self::$instance;
    }

    public function select ($table, $condition=array(), $field=array())
    {
        $where = '';
        if(!empty($condition)){
            foreach($condition as $k => $v){
                $where .= $k . "='" . $v . "' and ";
            }
            $where = 'where ' . $where . '1=1';
        }
        $fieldstr = '';
        if(!empty($field)){
            foreach($field as $k => $v){
                $fieldstr .= $v . ',';
            }
            $fieldstr = rtrim($fieldstr, ',');
        } else {
            $fieldstr = '*';
        }

        self::$sql = "select {$fieldstr} from {$table} {$where}";
        $result = mysqli_query($this->conn, self::$sql);
        $resultRow = array();
        $i = 0;
        while($row=mysqli_fetch_assoc($result)){
            foreach($row as $k => $v){
                $resultRow[$i][$k] = $v;
            }
            $i++;
        }
        return $resultRow;
    }

    public function insert($table, $data)
    {
        $values = '';
        $datas = '';
        foreach($data as $k => $v){
            $values .= $k . ',';
            $datas .= "'$v'" . ',';
        }
        $values = rtrim($values, ',');
        $datas = rtrim($datas, ',');
        self::$sql = "INSERT INTO {$table} ({$values}) values ({$datas})";
        if(mysqli_query($this->conn, self::$sql)){
            return mysqli_insert_id($this->conn);
        } else {
            return false;
        }
    }

    public function update($table, $data, $condition=array())
    {
        $where = '';
        if(!empty($condition)){
            foreach($condition as $k => $v){
                $where .= $k . "='" . $v . "' and ";
            }
            $where = "where " . $where . '1=1';
        }
        $updatastr = '';
        if(!empty($data)){
            foreach($data as $k => $v){
                $updatastr .= $k . "='" . $v . "',";
            }
            $updatastr = 'set '. rtrim($updatastr, ',');
        }
        self::$sql = "update {$table} {$updatastr} {$where}";
        return mysqli_query($this->conn, self::$sql);
    }

    public function delete($table, $condition){
        $where = '';
        if(!empty($condition)){
            foreach($condition as $k => $v){
                $where .= $k . "='". $v . "' and ";
            }
            $where = "where ". $where . " 1=1";
        }

        self::$sql = "delete from {$table} {$where}";
        return mysqli_query($this->conn, self::$sql);
    }
    
    public static function lastSql ()
    {
        return self::$sql;
    }
}
