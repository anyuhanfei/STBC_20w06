<?php
namespace app\BJPrExO9\controller;

class Error
{
    public function __call($method, $args)
    {
        return '网站错误!';
    }
}