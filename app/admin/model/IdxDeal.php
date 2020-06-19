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
        return $this->hasOne('idx_user', 'buy_user_id', 'user_id');
    }

    public function selluser(){
        return $this->hasOne('idx_user', 'sell_user_id', 'user_id');
    }
}