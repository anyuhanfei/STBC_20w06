<?php
namespace app\BJPrExO9\controller;

use think\facade\Session;
use think\facade\Request;
use think\facade\View;

use app\BJPrExO9\controller\Admin;

use app\admin\model\SysBasic;
use app\admin\model\SysSet;
use app\admin\model\SysSetCategory;
use app\admin\model\LogAdminOperation;
use app\admin\model\SysSetting;


class Webset extends Admin{

    /**
     * 网站设置
     *
     * @return void
     */
    public function setting($cgory=''){
        $setting_category = SysSetting::where('type', 'cgory')->select();
        $category = SysSetting::where('type', 'cgory')->where('category_name', $cgory)->find();
        if($category){
            $cgory = $category->category_name;
        }else{
            $category = SysSetting::where('type', 'cgory')->order('id asc')->find();
            if($category){
                $cgory = $category->category_name;
            }else{
                $cgory = '';
            }
        }
        $setting = SysSetting::where('type', 'value')->where('category_name', $cgory)->order('sort asc')->select();
        View::assign('setting_category', $setting_category);
        View::assign('setting', $setting);
        View::assign('cgory', $cgory);
        return View::fetch();
    }
}