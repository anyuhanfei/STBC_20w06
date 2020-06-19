<?php
namespace app\admin\model;

use think\Model;


class IdxInvest extends Model{
    protected $table ="idx_invest";
    protected $pk = "invest_id";

    public function getHasStaticTextAttr($value, $data){
        $res = array('可静态释放', '不可静态释放');
        return $res[$data['has_static']];
    }
}