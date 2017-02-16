<?php
namespace Lib;
class Controller
{
    public $view = array(); //页面变量
    public function display()
    {
        global $viewName;
        ob_start();
        include APP_PATH . '/View/Common/header.html';
        if(file_exists($viewName))
        include $viewName;
        include APP_PATH . '/View/Common/footer.html';
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }
    public function assign($key, $value)
    {
        $this->view[$key] = $value;
    }
}

