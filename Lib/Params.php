<?php
namespace Lib;
class Params
{
    public static function getParam($key=null)
    {
        if(!$key) return $_GET;
        else return $_GET[$key];
    }

    protected static function request()
    {
        return $_SERVER;
    }
}
