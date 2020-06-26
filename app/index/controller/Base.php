<?php
namespace app\index\controller;

use think\facade\Request;
use think\facade\Session;

use app\admin\model\IdxUser;


class Base{
    public function __construct(){

    }

    /**
     * 发送短信
     *
     * @param [type] $type
     * @return void
     */
    public function sms($type){
        $phone = Request::instance()->param('phone', '');
        $user = IdxUser::where('phone', $phone)->find();
        switch ($type) {
            case 'register':
                if($user){
                    return return_data(2, '', '此手机号已被注册', 'json');
                }
                break;
            case 'forget':
                if(!$user){
                    return return_data(2, '', '此手机号未被注册', 'json');
                }
                break;
            default:
                return return_data(2, '', '非法操作', 'json');
        }
        $code = create_captcha(6);
        // 发短信
        // $this->send_sms($phone, $code);
        // 保存信息
        Session::set('sms_phone', $phone);
        Session::set('sms_code', $code);
        return return_data(1, $code, '发送成功', 'json');
    }
}