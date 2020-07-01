<?php
namespace app\BJPrExO9\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Cookie;

use app\admin\model\SysCatalog;
use app\admin\model\AdmAdmin;
use app\admin\model\AdmRole;
use app\admin\model\SysModuleAction;

use app\BJPrExO9\controller\Base;

class Admin extends Base{

    public function __construct(){
        //引用父类初始化方法
        parent::__construct();
        //系统设置
        View::assign('developer_model', $this->developer_model);
        //后台目录
        $catalog = SysCatalog::order('sort asc')->select();
        View::assign('adm_catalog', $catalog);
        //后台目录高亮
        $controller = Request::instance()->controller();
        $action = Request::instance()->action();
        $current_url = strtolower($controller . '/' . $action);
        $current = SysCatalog::where('path', $current_url)->find();
        if($current){
            Cookie::set('current_id', $current->catalog_id);
            View::assign('current_id', $current->catalog_id);
        }else{
            View::assign('current_id', Cookie::get('current_id'));
        }
    }
}