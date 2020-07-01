<?php
namespace app\BJPrExO9\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Request;

use app\BJPrExO9\controller\Admin;

use app\admin\model\SysAd;
use app\admin\model\SysAdv;
use app\admin\model\LogAdminOperation;


class Ad extends Admin{
    /**
     * 广告管理-列表
     *
     * @return void
     */
    public function ad(){
        $ad = SysAd::order('ad_id desc')->select();
        $adv = SysAdv::order('adv_id desc')->select();
        View::assign('ad', $ad);
        View::assign('adv', $adv);
        //删除未被上传的图片
        $ad_images = Session::get('ad_content_images');
        if($ad_images){
            foreach($ad_images as $k => $v){
                delete_image($v);
                unset($ad_images[$k]);
            }
        }
        Session::set('ad_content_images', array());
        return View::fetch();
    }
}