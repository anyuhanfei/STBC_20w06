<?php
namespace app\index\controller;

use think\facade\Session;
use think\facade\View;
use think\facade\Request;
use think\facade\Db;

use app\index\controller\Index;

use app\admin\model\IdxDeal;
use app\admin\model\SysSetting;
use app\admin\model\IdxUserFund;
use app\admin\model\LogUserFund;
use app\admin\model\AutoValue;


class Deal extends Index{
    public function deal(){
        $deal = IdxDeal::where('status', 0)->order('insert_time desc')->select();
        View::assign('deal', $deal);
        View::assign('shop_usdt', SysSetting::where('sign', 'shop_usdt')->value('value'));
        return View::fetch();
    }

    public function shop_q(){
        $shop_usdt = SysSetting::where('sign', 'shop_usdt')->value('value');
        $user_fund = IdxUserFund::find($this->user_id);
        if($user_fund->money < $shop_usdt){
            return return_data(2, '', 'USDT余额不足');
        }
        Db::startTrans();
        $user_fund->money -= $shop_usdt;
        $res = $user_fund->save();
        $this->user->is_shop = 1;
        $this->user->save();
        if($res){
            Db::commit();
            LogUserFund::create_data($this->user_id, '-'.$shop_usdt, 'money', '申请商家', '提交商家保证金');
            return return_data(1, '', '申请商家成功');
        }else{
            Db::rollback();
            return return_data(2, '', '申请商家失败');
        }
    }

    public function create(){
        View::assign('stbc_amount', AutoValue::where('id', 4)->value('hight_number'));
        return View::fetch();
    }

    public function create_submit(){
        $type = Request::instance()->param('type', '');
        $amount = Request::instance()->param('amount', 0); //stbc
        $number = Request::instance()->param('number', 0); //单价
        if($this->user->is_shop != 1){
            return return_data(2, '', '没有权限');
        }
        if($type != 'buy' && $type != 'sell'){
            return return_data(2, '', '非法操作');
        }
        if($amount <= 0 || $number <= 0){
            return return_data(2, '', '请正确填写金额或单价');
        }
        Db::startTrans();
        $res_two = true;
        if($type == 'sell'){
            $user_fund = IdxUserFund::find($this->user_id);
            if($user_fund->stbc <= $amount){
                return return_data(2, '', 'STBC余额不足');
            }
            $user_fund->stbc -= $amount;
            $res_two = $user_fund->save();
            LogUserFund::create_data($this->user_id, '-'.$amount, 'stbc', 'C2C', '挂单出售STBC');
        }
        $res_one = IdxDeal::create([
            'deal_id'=> 'sn' . create_captcha(14, 'array_figure'),
            $type . '_user_id'=> $this->user_id,
            'amount'=> $amount,
            'unit_price'=> $number,
            'insert_time'=> date("Y-m-d H:i:s", time())
        ]);
        if($res_one && $res_two){
            Db::commit();
            return return_data(1, '', '挂单成功');
        }else{
            Db::rollback();
            return return_data(2, '', '挂单失败');
        }
    }

    public function dealing(){
        $deal_id = Request::instance()->param('deal_id', '');
        $deal = IdxDeal::find($deal_id);
        if(!$deal){
            return return_data(2, '', '非法操作');
        }
        if($deal->status != 0){
            return return_data(2, '', '非法操作');
        }
        if($deal->buy_user_id == $this->user_id || $deal->sell_user_id == $this->user_id){
            return return_data(2, '', '不能与自己交易');
        }
        Db::startTrans();
        if($deal->buy_user_id == 0){
            //我买
            $deal->buy_user_id = $this->user_id;
            $deal->status = 1;
            $deal->operation_time = date("Y-m-d H:i:s", time());
            $res_one = $deal->save();
            $res_two = true;
        }else{
            //我卖
            $user_fund = IdxUserFund::find($this->user_id);
            if($user_fund->stbc < $deal->amount){
                return return_data(2, '', 'STBC余额不足');
            }
            $user_fund->stbc -= $deal->amount;
            $res_two = $user_fund->save();
            LogUserFund::create_data($this->user_id, '-'.$deal->amount, 'stbc', 'C2C', '与收购STBC的订单交易');
            $deal->sell_user_id = $this->user_id;
            $deal->status = 1;
            $deal->operation_time = date("Y-m-d H:i:s", time());
            $res_one = $deal->save();
        }
        if($res_one && $res_two){
            Db::commit();
            return return_data(1, '', '交易开始');
        }else{
            Db::rollback();
            return return_data(2, '', '交易提交失败');
        }
    }

    public function order(){
        $obj = IdxDeal::where('buy_user_id', $this->user_id)->whereOr('sell_user_id', $this->user_id)->order('insert_time desc')->select();
        View::assign('list', $obj);
        return View::fetch();
    }

    public function order_detail($deal_id){
        $deal = IdxDeal::find($deal_id);
        if($deal->buy_user_id == $this->user_id){
            // 我是买家, 显示卖家信息
            $deal_user = $deal->selluser;
        }else{
            $deal_user = $deal->buyuser;
        }
        View::assign('deal', $deal);
        View::assign('deal_user', $deal_user);
        View::assign('deal_id', $deal_id);
        View::assign('usdt_price', AutoValue::where('id', 3)->value('hight_number'));
        return View::fetch();
    }

    public function order_1(){
        $type = Request::instance()->param('type', '');
        $deal_id = Request::instance()->param('deal_id', '');
        if($type != 'usdt' && $type != 'cny'){
            return return_data(2, '', '非法操作');
        }
        $deal = IdxDeal::find($deal_id);
        if(!$deal){
            return return_data(2, '', '非法操作');
        }
        if($deal->status != 1){
            return return_data(2, '', '非法操作');
        }
        Db::startTrans();
        if($type == 'usdt'){
            //直接扣usdt
            $buy_user_fund = IdxUserFund::find($deal->buy_user_id);
            $sell_user_fund = IdxUserFund::find($deal->sell_user_id);
            $usdt2cny = AutoValue::where('id', 3)->value('hight_number');
            $usdt = round($deal->amount * $deal->unit_price / $usdt2cny, 2);
            if($buy_user_fund->money < $usdt){
                Db::rollback();
                return return_data(2, '', 'USDT余额不足, 请线下交易或充值');
            }
            $buy_user_fund->money -= $usdt;
            $buy_user_fund->stbc += $deal->amount;
            $res_two = $buy_user_fund->save();
            $sell_user_fund->money += $usdt;
            $res_three = $sell_user_fund->save();
            LogUserFund::create_data($deal->buy_user_id, '-'.$usdt, 'money', 'C2C', '选择USDT交易, 扣除USDT');
            LogUserFund::create_data($deal->buy_user_id, $deal->amount, 'stbc', 'C2C', '交易完成');
            LogUserFund::create_data($this->sell_user_id, $usdt, 'money', 'C2C', '对方选择USDT交易, 得到USDT');
            $deal->status = 3;
            $deal->pay_type = 'USDT';
        }else{
            //上传支付凭证
            $image = Request::instance()->file('image');
            $image_res = file_upload($image, 'deal');
            if($image_res == null || $image_res['status'] == 2){
                Db::rollback();
                return return_data(2, '', '图片上传审核未通过');
            }
            $deal->pay_prove = $image_res['file_path'];
            $deal->pay_type = 'CNY';
            $deal->status = 2;
            $res_two = true;
            $res_three = true;
        }
        $deal->operation_time = date("Y-m-d H:i:s", time());
        $res_one = $deal->save();
        if($res_one && $res_two && $res_three){
            Db::commit();
            return return_data(1, '', '操作成功');
        }else{
            Db::rollback();
            return return_data(2, '', '操作失败');
        }
    }

    public function order_2(){
        $deal_id = Request::instance()->param('deal_id', '');
        $deal = IdxDeal::find($deal_id);
        if(!$deal){
            return return_data(2, '', '非法操作');
        }
        if($deal->status != 2){
            return return_data(2, '', '非法操作');
        }
        Db::startTrans();
        $deal->status = 3;
        $deal->operation_time = date("Y-m-d H:i:s", time());
        $res_one = $deal->save();
        //stbc给买家
        $buy_user_fund = IdxUserFund::find($deal->buy_user_id);
        $buy_user_fund->stbc += $deal->amount;
        $res_two = $buy_user_fund->save();
        LogUserFund::create_data($deal->buy_user_id, $deal->amount, 'stbc', 'C2C', '交易完成');
        if($res_one && $res_two){
            Db::commit();
            return return_data(1, '', '操作成功');
        }else{
            Db::rollback();
            return return_data(2, '', '操作失败');
        }
    }

    public function order_down($deal_id){
        $deal_id = Request::instance()->param('deal_id', '');
        $deal = IdxDeal::find($deal_id);
        if(!$deal){
            return return_data(2, '', '非法操作');
        }
        if($deal->buy_user_id != $this->user_id && $deal->sell_user_id != $this->user_id){
            return return_data(2, '', '非法操作');
        }
        if($deal->status != 0){
            return return_data(2, '', '非法操作');
        }
        Db::startTrans();
        $res_one = true;
        if($deal->sell_user_id == $this->user_id){
            //退钱
            $sell_user_fund = IdxUserFund::find($this->user_id);
            $sell_user_fund->stbc += $deal->amount;
            $res_one = $sell_user_fund->save();
            LogUserFund::create_data($this->user_id, $deal->amount, 'stbc', 'C2C', '挂单下架, 返还STBC');
        }
        $res_two = $deal->delete();
        if($res_one && $res_two){
            Db::commit();
            return return_data(1, '', '下架成功');
        }else{
            Db::rollback();
            return return_data(2, '', '下架失败');
        }
    }
}