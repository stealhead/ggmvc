<?php
namespace Lib;
class Table
{
    protected $_name = '';
    public $mapper;
    public function __construct()
    {
        if(!($this->mapper instanceof \Lib\Mapper))
            $this->mapper = \Lib\Mapper::getInstance();
    }
    public function select ($condition=array(), $field=array())
    {
        return $this->mapper->select($this->_name, $condition, $field);
    }
    public function update ($data, $condition=array())
    {
        return $this->mapper->update($this->_name, $data, $condition);
    }
    public function insert ($data)
    {
        return $this->mapper->insert($this->_name, $data);
    }
    public function delete ($condition)
    {
        return $this->mapper->delete($this->_name, $condition);
    }
}
