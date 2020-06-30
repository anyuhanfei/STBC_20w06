<?php
namespace app\admin\model;

use think\Model;


class IdxInvest extends Model{
    protected $table ="idx_invest";
    protected $pk = "invest_id";

    public function user(){
        return $this->hasOne('idx_user', 'user_id', 'user_id');
    }

    public function getHasStaticTextAttr($value, $data){
        $res = array('不可静态释放', '可静态释放');
        return $res[$data['has_static']];
    }

    public static function create_data($user_id, $number, $has_static){
        self::create([
            'user_id'=> $user_id,
            'amount'=> $number,
            'has_static'=> $has_static,
            'insert_time'=> date('Y-m-d H:i:s', time()),
            'operation_time'=> date('Y-m-d H:i:s', time())
        ]);
    }
}