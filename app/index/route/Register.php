<?php
namespace app\index\validate;

use think\Validate;
use think\facade\Session;

use app\admin\model\IdxUser;


class Register extends Validate{
    protected $rule = [
        'phone'=> 'require|checkPhone',
        'password'=> 'require|confirm:password_affirm|checkPassword',
        'password_affirm'=> 'require',
        'smscode'=> 'require|checkSmsCode',
    ];

    protected $message = [
        'phone.require'=> '请填写手机号',
        'password.require'=> '请填写密码',
        'password.confirm'=> '确认密码与密码不一致',
        'password_affirm.require'=> '请填写确认密码',
        'smscode.require'=> '请填写短信验证码'
    ];

    public function checkPhone($value, $rule, $data){
        $rule = "/^1[3456789]\d{9}$/";
        if(!preg_match($rule, $value)){
            return '手机号码无效！';
        }
        $user = IdxUser::where('phone', $value)->find();
        if($user){
            return '此手机号码已注册';
        }
        return true;
    }

    public function checkPassword($value, $rule, $data){
        $rule = "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$/";
        if(preg_match($rule, $value)){
            return true;
        }else{
            return '密码最少6位，并且需要有数字和字母';
        }
    }

    public function checkSmsCode($value, $rule, $data){
        $register_sms_code = Session::get('sms_code');
        $register_sms_phone = Session::get('sms_phone');
        if($data['phone'] != $register_sms_phone){
            return "非法操作";
        }
        if($value != $register_sms_code){
            return "短信验证码输入错误";
        }
        return true;
    }
}