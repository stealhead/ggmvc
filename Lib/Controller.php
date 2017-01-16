<?php
namespace Lib;
class Controller
{
    public $view = array(); //页面变量
    public function display()
    {
        global $viewName;
        ob_start();
        if(file_exists($viewName))
        include $viewName;
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }
    public function assign($key, $value)
    {
        $this->view[$key] = $value;
    }
}

