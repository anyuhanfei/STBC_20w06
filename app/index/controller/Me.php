<?php
namespace app\index\controller;

use think\facade\Session;
use think\facade\View;
use think\facade\Request;

use app\index\controller\Index;

use app\admin\model\AutoValue;
use app\admin\model\SysAd;
use app\admin\model\IdxUser;


class Me extends Index{
    /**
     * 个人中心
     *
     * @return void
     */
    public function index(){
        $stbc = AutoValue::where('id', 4)->value('hight_number');
        View::assign('stbc', $stbc);
        return View::fetch();
    }

    /**
     * 推广
     *
     * @return void
     */
    public function invite(){
        if($this->user->invite_code == ''){
            $this->user->invite_code = create_captcha(12, 'lowercase+uppercase+figure');
            $this->user->save();
        }
        if($this->user->invite_qrcode == ''){
            $this->user->invite_qrcode = png_erwei('http://' . $_SERVER['HTTP_HOST'] . '/index/register/' . $this->user->invite_code, $this->user->phone);
            $this->user->save();
        }
        return View::fetch();
    }

    /**
     * 客服
     *
     * @return void
     */
    public function service(){
        View::assign('service', SysAd::where('sign', 'service_qrcode')->value('image'));
        return View::fetch();
    }

    /**
     * 设置
     *
     * @return void
     */
    public function set(){
        return View::fetch();
    }

    /**
     * 团队
     *
     * @return void
     */
    public function team(){
        $down_user = IdxUser::where('top_id', $this->user_id)->order('register_time desc')->select();
        foreach($down_user as &$v){
            $v->phone = substr($v->phone,0,3) . "****" . substr($v->phone,-4,4);
        }
        View::assign('down_user', $down_user);
        return View::fetch();
    }

    /**
     * 修改密码
     *
     * @return void
     */
    public function updatepassword(){
        return View::fetch();
    }

    public function updatepassword_submit(){
        $old_password = Request::instance()->param('old_password', '');
        $password = Request::instance()->param('password', '');
        $repeat_password = Request::instance()->param('repeat_password', '');
        if($old_password == '' || $password == '' || $repeat_password == ''){
            return return_data(2, '', '有未填写信息');
        }
        if($password != $repeat_password){
            return return_data(2, '', '确认密码与新密码不同');
        }
        if(md5($old_password . $this->user->password_salt) != $this->user->password){
            return return_data(2, '', '旧密码输入错误');
        }
        $md5password = md5($password . $this->user->password_salt);
        if($md5password == $this->user->password){
            return return_data(2, '', '新密码不能与旧密码相同');
        }
        $rule = "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/";
        if(preg_match($rule, $password)){
            $this->user->password = $md5password;
            $res = $this->user->save();
            return $res ? return_data(1, '', '修改成功') : return_data(2, '', '修改失败');
        }else{
            return return_data(2, '', '输入密码不符合要求，6到20位数字和字母的组合');
        }
    }
}