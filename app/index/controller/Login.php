<?php
namespace app\index\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Request;

use app\index\controller\Base;

use app\admin\model\IdxUser;


class Login extends Base{
    /**
     * 登录
     *
     * @return void
     */
    public function login(){
        return View::fetch();
    }

    public function login_submit(){
        $account = Request::instance()->param('account', '');
        $password = Request::instance()->param('password', '');
        if($account == ''){
            return return_data(2, '', '请输入手机号');
        }
        if($password == ''){
            return return_data(2, '', '请输入密码');
        }
        $user = IdxUser::where('phone', $account)->find();
        if(!$user){
            return return_data(2, '', '账号或密码错误');
        }
        if(md5($password . $user->password_salt) !=  $user->password){
            return return_data(2, '', '账号或密码错误');
        }
        if($user->is_login == 0){
        	return return_data(2, '', '此账号已冻结');
        }
        Session::set('user_id', $user->user_id);
        return return_data(1, '', '登录成功');
    }

    public function forget(){
        return View::fetch();
    }

    public function register(){
        return View::fetch();
    }

    public function loginout(){
        Session::delete('user_id');
        return redirect('/index/login');
    }
}