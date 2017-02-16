<?php
namespace Module\Index;
class Index extends \Lib\Controller {
    public function index()
    {
        //echo "hello";
        $this->assign('name', 'rongrong');
        $this->display();
    }
    public function say()
    {
        echo "i can say";
        $this->display();
    }
    public function save()
    {
        $mapper = \Lib\Mapper::getInstance();
        var_dump(get_class_methods($mapper));
    }
    public function user()
    {
        $User = new \Model\User();
        $rows = $User->select();
        var_dump($rows);
    }
    public function conf ()
    {
        $ConfObj = new \Lib\Config();
        var_dump($ConfObj->getConf('redis'));
    }
    
}

