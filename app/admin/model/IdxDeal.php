<?php
namespace app\admin\model;

use think\Model;


class IdxDeal extends Model{
    protected $table = "idx_deal";
    protected $pk = "deal_id";

    public function getStaticTextAttr($value, $data){
        $res = ['挂卖中', '待付款', '待确认', '已完成'];
        return $res[$data['static']];
    }

    public function buyuser(){
        return $this->hasOne('idx_user', 'user_id', 'buy_user_id');
    }

    public function selluser(){
        return $this->hasOne('idx_user', 'user_id', 'sell_user_id');
    }

    public function getTextAttr($value, $data){
        return ($data['status'] == 0) ? (($data['buy_user_id'] == 0) ? '买' : '卖') : '交易';
    }

    public function getColorAttr($value, $data){
        return ($data['status'] == 0) ? (($data['buy_user_id'] == 0) ? 'red' : 'green') : 'blue';
    }
}