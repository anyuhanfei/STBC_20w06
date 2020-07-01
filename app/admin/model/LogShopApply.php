<?php
namespace app\admin\model;

use think\Model;


class LogShopApply extends Model{
    protected $table = "log_shop_apply";
    protected $pk = 'id';

    public function getStatusTextAttr($value, $data){
        $res = ['审核中', '通过', '驳回'];
        return $res[$data['status']];
    }

    public function user(){
        return $this->hasOne('idx_user', 'user_id', 'user_id');
    }
}