<?php
namespace app\admin\model;

use think\Model;


class LogWithdraw extends Model{
    protected $table = "log_withdraw";
    protected $pk = "id";

    public function user(){
        return $this->hasOne('idx_user', 'user_id', 'user_id');
    }

    public function getStatusTextAttr($value, $data){
        $res = ['审核中', '通过', '驳回'];
        return $res[$data['status']];
    }

    public function getStatusColorAttr($value, $data){
        $res = ['red', 'green', 'blue'];
        return $res[$data['status']];
    }
}