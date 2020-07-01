<?php
namespace app\BJPrExO9\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Request;
use think\facade\Db;

use app\BJPrExO9\controller\Admin;

use app\admin\model\IdxUser;
use app\admin\model\IdxUserCount;
use app\admin\model\IdxUserFund;
use app\admin\model\IdxUserData;
use app\admin\model\LogAdminOperation;
use app\admin\model\LogRecharge;
use app\admin\model\LogWithdraw;
use app\admin\model\LogUserFund;


class User extends Admin{
    public function __construct(){
        parent::__construct();
        View::assign('user_fund_type', $this->user_fund_type);
        View::assign('user_delete_onoff', $this->user_delete_onoff);
        View::assign('user_identity', $this->user_identity);
        View::assign('user_identity_text', $this->user_identity_text);
    }

    /**
     * 会员管理--列表
     *
     * @return void
     */
    public function user(){
        $user_id = Request::instance()->param('user_id', '');
        $top_user_id = Request::instance()->param('top_user_id', '');
        $nickname = Request::instance()->param('nickname', '');
        $user_identity = Request::instance()->param('user_identity', '');
        $top_user_identity = Request::instance()->param('top_user_identity', '');
        $user = new IdxUser;
        $user = ($user_id != '') ? $user->where('user_id', $user_id) : $user;
        $user = ($top_user_id != '') ? $user->where('top_id', $top_user_id) : $user;
        $user = ($nickname != '') ? $user->where('nickname', 'like', '%' . $nickname . '%') : $user;
        $user = ($user_identity != '') ? $user->where($this->user_identity, 'like', '%'. $user_identity . '%') : $user;
        if($top_user_identity != ''){
            $top_id = IdxUser::where($this->user_identity, 'like', '%' . $top_user_identity . '%')->find();
            if($top_id){
            	$user = $user->where('top_id', $top_id->user_id);
            }
        }
        $list = $user->order('user_id desc')->paginate($this->page_number, false,['query'=>request()->param()]);
        $this->many_assign(['list'=> $list, 'user_id'=> $user_id, 'nickname'=> $nickname, 'top_user_id'=> $top_user_id, 'top_user_identity'=> $top_user_identity, 'search_user_identity'=> $user_identity]);
        return View::fetch();
    }

    /**
     * 会员团队列表
     *
     * @param [type] $id
     * @return void
     */
    public function user_team($id){
        $user = IdxUser::find($id);
        $has_data = "true";
        if(!$user){
            $has_data = "false";
            $user = new IdxUser;
            $user_identity = $this->user_identity;
            $user->$user_identity = '';
        }
        $user_count = IdxUserCount::find($id);
        $team = array();
        $this->team_select($team, $id);
        View::assign('user', $user);
        View::assign('user_count', $user_count);
        View::assign('has_data', $has_data);
        View::assign('team', $team);
        return View::fetch();
    }

    private function team_select(&$team, $user_id, $level=1){
        $user = IdxUser::field('user_id, top_id, nickname, ' . $this->user_identity)->where('top_id', $user_id)->select();
        if($user){
            foreach($user as $v){
                $v->level = $level;
                $team[] = $v;
                $this->team_select($team, $v->user_id, $level + 1);
            }
        }
    }

    /**
     * 会员详情信息
     *
     * @param [type] $id
     * @return void
     */
    public function user_detail($id){
        $user = IdxUser::find($id);
        $has_data = "true";
        if(!$user){
            $has_data = "false";
        }
        View::assign('detail', $user);
        View::assign('has_data', $has_data);
        return View::fetch();
    }

    /**
     * 充值信息管理-列表
     *
     * @return void
     */
    public function recharge(){
        $user_account = Request::instance()->param('user_account', '');
        $obj = new LogRecharge;
        $user_id = IdxUser::where('phone', $user_account)->value('user_id');
        $obj = ($user_id != '') ? $obj->where('user_id', $user_id) : $obj;
        $list = $obj->order('status asc, id desc')->paginate($this->page_number, false,['query'=>request()->param()]);
        $this->many_assign(['list'=> $list, 'user_account'=> $user_account]);
        return View::fetch();
    }

    /**
     * 提现
     *
     * @return void
     */
    public function withdraw(){
        $user_account = Request::instance()->param('user_account', '');
        $obj = new LogWithdraw;
        $user_id = IdxUser::where('phone', $user_account)->value('user_id');
        $obj = ($user_id != '') ? $obj->where('user_id', $user_id) : $obj;
        $list = $obj->order('status asc, id desc')->paginate($this->page_number, false,['query'=>request()->param()]);
        $this->many_assign(['list'=> $list, 'user_account'=> $user_account]);
        return View::fetch();
    }
}