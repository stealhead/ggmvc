<?php
namespace Module\Index;
class Curl{
    public function index()
    {
        $mapper = new \Lib\Mapper();
        var_dump(get_class_methods($mapper));
    }
}
