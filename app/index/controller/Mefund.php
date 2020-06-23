<?php
namespace app\index\controller;

use think\facade\Session;
use think\facade\View;
use think\facade\Request;
use think\facade\Db;

use app\index\controller\Index;

use app\admin\model\IdxUserData;
use app\admin\model\IdxUserFund;
use app\admin\model\LogUserFund;
use app\admin\model\LogWithdraw;
use app\admin\model\SysSetting;
use app\admin\model\IdxUser;
use app\admin\model\SysAd;
use app\admin\model\LogRecharge;
use PDO;

class Mefund extends Index{
    /**
     * 钱包
     *
     * @return void
     */
    public function wallet(){
        return View::fetch();
    }

    public function wallet_usdt(){
        return View::fetch();
    }

    public function wallet_stbc(){
        return View::fetch();
    }

    public function wallet_alipay(){
        return View::fetch();
    }

    public function wallet_wx(){
        return View::fetch();
    }

    public function wallet_bank(){
        return View::fetch();
    }

    /**
     * 修改钱包
     *
     * @param [type] $type
     * @return void
     */
    public function wallet_update_submit($type){
        $data = Request::instance()->param('');
        switch ($type) {
            case 'alipay':
                $alipay_qrcode_temp = Request::instance()->file('alipay_qrcode');
                if($data['alipay_account'] == '' || $data['alipay_username'] == ''){
                    return return_data(2, '', '请完善信息', 'json');
                }
                if(!$alipay_qrcode_temp){
                    return return_data(2, '', '请上传收款二维码', 'json');
                }
                $alipay_qrcode_res = file_upload($alipay_qrcode_temp, 'wallet');
                if($alipay_qrcode_res['status'] == 2){
                    return return_data(2, '', $alipay_qrcode_res['error']);
                }
                $alipay_qrcode = $alipay_qrcode_res['file_path'];
                $user_data = IdxUserData::find($this->user_id);
                $user_data->alipay_account = $data['alipay_account'];
                $user_data->alipay_username = $data['alipay_username'];
                $user_data->alipay_qrcode = $alipay_qrcode;
                break;
            case 'wx':
                $wx_qrcode_temp = Request::instance()->file('wx_qrcode');
                if($data['wx_account'] == '' || $data['wx_nickname'] == ''){
                    return return_data(2, '', '请完善信息', 'json');
                }
                if(!$wx_qrcode_temp){
                    return return_data(2, '', '请上传收款二维码', 'json');
                }
                $wx_qrcode_res = file_upload($wx_qrcode_temp, 'wallet');
                if($wx_qrcode_res['status'] == 2){
                    return return_data(2, '', $wx_qrcode_res['error']);
                }
                $wx_qrcode = $wx_qrcode_res['file_path'];
                $user_data = IdxUserData::find($this->user_id);
                $user_data->wx_account = $data['wx_account'];
                $user_data->wx_nickname = $data['wx_nickname'];
                $user_data->wx_qrcode = $wx_qrcode;
                break;
            case 'bank':
                if($data['bank_name'] == '' || $data['bank_code'] == '' || $data['bank_username'] == ''){
                    return return_data(2, '', '请完善信息', 'json');
                }
                $user_data = IdxUserData::find($this->user_id);
                $user_data->bank_username = $data['bank_username'];
                $user_data->bank_code = $data['bank_code'];
                $user_data->bank_name = $data['bank_name'];
                break;
            case 'usdt':
                if($data['usdt'] == ''){
                    return return_data(2, '', '请完善信息', 'json');
                }
                $user_data = IdxUserData::find($this->user_id);
                $user_data->usdt = $data['usdt'];
                break;
            case 'stbc':
                if($data['stbc'] == ''){
                    return return_data(2, '', '请完善信息', 'json');
                }
                $user_data = IdxUserData::find($this->user_id);
                $user_data->stbc = $data['stbc'];
                break;
            default:
                return return_data(2, '', '非法操作', 'json');
                break;
        }
        $res = $user_data->save();
        return $res ? return_data(1, '', '上传成功', 'json') : return_data(2, '', '上传失败', 'json');
    }

    /**
     * 充值
     *
     * @return void
     */
    public function recharge(){
        View::assign('usdt_address', SysAd::where('sign', 'usdt_address')->value('value'));
        return View::fetch();
    }

    public function recharge_submit(){
        $photo = Request::instance()->file('photo');
        $amount = Request::instance()->param('amount', '');
        if($amount == ''){
            return return_data(2, '', '请完善信息', 'json');
        }
        if(!$photo){
            return return_data(2, '', '请上传上传凭证', 'json');
        }
        $qrcode = file_upload($photo, 'wallet');
        $prove = $qrcode['file_path'];
        $res = LogRecharge::create([
            'user_id'=> $this->user_id,
            'amount'=> $amount,
            'prove'=> $prove,
            'insert_time'=> date("Y-m-d H:i:s", time())
        ]);
        if($res){
            return return_data(1, '', '充值申请成功');
        }else{
            return return_data(2, '', '充值申请失败');
        }
    }

    public function recharge_log(){
        View::assign('list', LogRecharge::where('user_id', $this->user_id)->order('id desc')->select());
        return View::fetch();
    }

    /**
     * 转账
     *
     * @return void
     */
    public function transfer(){
        return View::fetch();
    }

    public function transfer_submit(){
        $phone = Request::instance()->param('phone', '');
        $amount = Request::instance()->param('amount', 0);
        if($phone == '' || $amount <= 0){
            return return_data(2, '', '请正确填写转账信息');
        }
        $to_user = IdxUser::where('phone', $phone)->find();
        if(!$to_user){
            return return_data(2, '', '输入的手机号不存在');
        }
        $user_fund = IdxUserFund::find($this->user_id);
        if($user_fund->stbc < $amount){
            return return_data(2, '', 'STBC余额不足');
        }
        $is_down_user = false;
        $top_id = $to_user->top_id;
        while($top_id != 0){
            if($top_id == $this->user_id){
                $is_down_user = true;
                break;
            }
            $top_user = IdxUser::where('user_id', $top_id)->find();
            $top_id = $top_user->top_id;
        }
        if($is_down_user == false){
            return return_data(2, '', '此会员不是团队下会员');
        }
        Db::startTrans();
        $to_user_fund = IdxUserFund::find($to_user->user_id);
        $to_user_fund->stbc += $amount;
        $res_one = $to_user_fund->save();
        $user_fund->stbc -= $amount;
        $res_two = $user_fund->save();
        if($res_one && $res_two){
            LogUserFund::create_data($to_user->user_id, $amount, 'stbc', '转账', $this->user->phone . '向我转账');
            LogUserFund::create_data($this->user_id, '-'.$amount, 'stbc', '转账', '转账给' . $to_user->phone);
            Db::commit();
            return return_data(1, '', '转账成功');
        }else{
            Db::rollback();
            return return_data(2, '', '转账失败');
        }

    }

    public function transfer_log(){
        View::assign('list', LogUserFund::where('user_id', $this->user_id)->where('fund_type', '转账')->order('id desc')->select());
        return View::fetch();
    }

    /**
     * 提现
     *
     * @return void
     */
    public function withdraw(){
        return View::fetch();
    }

    public function withdraw_submit(){
        $amount = Request::instance()->param('amount', 0);
        if($amount <= 0){
            return return_data(2, '', '请填写正确的提现金额');
        }
        $user_fund = IdxUserFund::find($this->user_id);
        if($user_fund->stbc < $amount){
            return return_data(2, '', 'STBC余额不足');
        }
        Db::startTrans();
        $user_fund->stbc -= $amount;
        $res_one = $user_fund->save();
        $res_two = LogWithdraw::create([
            'user_id'=> $this->user_id,
            'amount'=> $amount - ($amount * SysSetting::where('sign', 'withdraw_fee')->value('value') * 0.01),
            'fee'=> $amount * SysSetting::where('sign', 'withdraw_fee')->value('value') * 0.01,
            'insert_time'=> date("Y-m-d H:i:s", time())
        ]);
        LogUserFund::create_data($this->user_id, $amount, 'stbc', '提现', '提现申请');
        if($res_one && $res_two){
            Db::commit();
            return return_data(1, '', '提现成功');
        }else{
            Db::rollback();
            return return_data(2, '', '提现失败');
        }
    }

    public function withdraw_log(){
        $list = LogWithdraw::where('user_id', $this->user_id)->order('id desc')->select();
        View::assign('list', $list);
        return View::fetch();
    }
}