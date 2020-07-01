<?php
namespace app\BJPrExO9\controller;

use think\facade\View;

use app\BJPrExO9\controller\Admin;

class Index extends Admin{

    public function index(){
        return View::fetch();
    }
}