<?php
namespace app\admin\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Request;

use app\admin\controller\Admin;

use app\admin\model\IdxDeal;
use app\admin\model\IdxUser;
use app\admin\model\IdxInvest;
use app\admin\model\IdxStbcPrice;
use app\admin\model\LogAdminOperation;


class Other extends Admin{
    public function deal(){
        $buy_phone = Request::instance()->param('buy_phone', '');
        $sell_phone = Request::instance()->param('sell_phone', '');
        $deal_id = Request::instance()->param('deal_id', '');
        $obj = new IdxDeal;
        $buy_user_id = IdxUser::where('phone', $buy_phone)->value('user_id');
        $sell_user_id = IdxUser::where('phone', $sell_phone)->value('user_id');
        $obj = $deal_id == '' ? $obj : $obj->where('deal_id', $deal_id);
        $obj = $buy_user_id ? $obj->where('buy_user_id', $buy_user_id) : $obj;
        $obj = $sell_user_id ? $obj->where('sell_user_id', $sell_user_id) : $obj;
        $list = $obj->order('status asc, deal_id desc')->paginate($this->page_number, false,['query'=>request()->param()]);
        $this->many_assign(['list'=> $list, 'buy_phone'=> $buy_phone, 'sell_phone'=> $sell_phone, 'deal_id'=> $deal_id]);
        return View::fetch();
    }

    /**
     * 入金
     *
     * @return void
     */
    public function invest(){
        $user_account = Request::instance()->param('user_account', '');
        $obj = new IdxInvest;
        $user_id = IdxUser::where('phone', $user_account)->value('user_id');
        $obj = $user_id ? $obj->where('user_id', $user_id) : $obj;
        $list = $obj->order('invest_id desc')->paginate($this->page_number, false,['query'=>request()->param()]);
        $this->many_assign(['list'=> $list, 'user_account'=> $user_account]);
        return View::fetch();
    }

    /**
     * 价格
     *
     * @return void
     */
    public function price(){
        $list = IdxStbcPrice::order('id desc')->paginate($this->page_number, false,['query'=>request()->param()]);
        View::assign('list', $list);
        return View::fetch();
    }

    public function price_add(){
        $type = Request::instance()->param('type', '');
        $amount = Request::instance()->param('amount', 0);
        $last_obj = IdxStbcPrice::order('id desc')->find();
        if($last_obj){
            $time = date("Y-m-d", strtotime($last_obj->insert_time) + (60 * 60 * 24 + 1));
            $amount = $last_obj->unit_price + floatval($type . $amount);
        }else{
            $time = date("Y-m-d", time());
            $amount = 1 + intval($type . $amount);
        }
        $res = IdxStbcPrice::create([
            'unit_price'=> $amount,
            'insert_time'=> $time,
        ]);
        if($res){
            LogAdminOperation::create_data('添加' . $time . 'STBC价格', 'operation');
            return return_data(1, array($amount, $time), '添加成功');
        }else{
            return return_data(3, '', '添加失败，请联系管理员');
        }
    }
}