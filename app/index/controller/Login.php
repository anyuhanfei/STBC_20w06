<?php
namespace app\index\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Validate;

use app\index\controller\Base;

use think\exception\ValidateException;

use app\admin\model\IdxUser;

use app\index\validate\Register;
use app\index\validate\Forget;


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

    /**
     * 注册
     *
     * @return void
     */
    public function register($code = ''){
        View::assign('code', $code);
        return View::fetch();
    }

    public function register_submit(){
        $phone = Request::instance()->param('phone', '');
        $password = Request::instance()->param('password', '');
        $password_affirm = Request::instance()->param('password_affirm', '');
        $smscode = Request::instance()->param('smscode', '');
        $invitecode = Request::instance()->param('invitecode', '');
        try{
            validate(Register::class)->check([
                'phone'  => $phone,
                'password' => $password,
                'password_affirm' => $password_affirm,
                'smscode' => $smscode,
            ]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return return_data(2, '', $e->getError(), 'json');
        }
        if($invitecode != ''){
            $top_user = IdxUser::where('invite_code', $invitecode)->find();
            if(!$top_user){
                return return_data(2, '', '邀请码错误', 'json');
            }
            $top_id = $top_user->user_id;
        }else{
            $top_id = 0;
        }
        $res = IdxUser::create_data($phone, $password, $top_id);
        if($res){
            Session::delete('sms_code');
            Session::delete('sms_phone');
            return return_data(1, '', '注册成功', 'json');
        }else{
            return return_data(2, '', '注册失败', 'json');
        }
    }

    /**
     * 忘记密码
     *
     * @return void
     */
    public function forget(){
        return View::fetch();
    }

    public function forget_submit(){
        $phone = Request::instance()->param('phone', '');
        $password = Request::instance()->param('password', '');
        $password_affirm = Request::instance()->param('password_affirm', '');
        $smscode = Request::instance()->param('smscode', '');
        try{
            validate(Forget::class)->check([
                'phone'  => $phone,
                'password' => $password,
                'password_affirm' => $password_affirm,
                'smscode' => $smscode,
            ]);
        } catch (ValidateException $e) {
            // 验证失败 输出错误信息
            return return_data(2, '', $e->getError(), 'json');
        }
        $user = IdxUser::where('phone', $phone)->find();
        $user->password = md5($password . $user->password_salt);
        $res = $user->save();
        if($res){
            return return_data(1, '', '密码修改成功', 'json');
        }else{
            return return_data(2, '', '密码修改失败', 'json');
        }
    }

    public function loginout(){
        Session::delete('user_id');
        return redirect('/index/login');
    }
}