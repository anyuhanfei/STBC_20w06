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
                    return return_data(2, '', '此手机号已被注册');
                }
                break;
            case 'forget':
                if(!$user){
                    return return_data(2, '', '此手机号未被注册');
                }
                break;
            default:
                return return_data(2, '', '非法操作');
        }
        $code = create_captcha(6);
        // 发短信
        $this->send_sms($phone, $code);
        // 保存信息
        Session::set('sms_phone', $phone);
        Session::set('sms_code', $code);
        return return_data(1, '', '发送成功');
    }

    public function send_sms($phone, $code=''){
        $post_data = array();
        $post_data['userid'] = 3865;
        $post_data['account'] = 'qx4877';
        $post_data['password'] = '20200525';
        if($code != ''){
            $post_data['content'] = '【STBC】您的短信验证码为：' . $code;
        }else{
            $post_data['content'] = '【STBC】您有新的订单，请及时查看';
        }
        $post_data['mobile'] = $phone;
        $post_data['sendtime'] = date("Y-m-d H:i:s", time());
        $url='http://120.25.105.164:8888/sms.aspx?action=send';
        $o='';
        foreach ($post_data as $k=>$v)
        {
        $o.="$k=".urlencode($v).'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
    }
}