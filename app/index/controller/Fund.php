<?php
namespace app\index\controller;

use think\facade\View;
use think\facade\Request;
use think\facade\Session;
use think\facade\Db;

use app\index\controller\Index;

use app\admin\model\IdxUserFund;
use app\admin\model\SysSetting;
use app\admin\model\AutoValue;
use app\admin\model\IdxStbcPrice;
use app\admin\model\IdxUserCount;
use app\admin\model\IdxInvest;
use app\admin\model\IdxUser;
use app\admin\model\LogUserFund;


class Fund extends Index{
    public function invest_submit(){
        $bei = 2;
        $number = Request::instance()->param('number', 0);
        $usdt_amount = Request::instance()->param('usdt_amount', 0);
        if($number != 100 && $number != 300 && $number != 500 && $number != 1000 && $number != 2000 && $number != 3000  && $number != 4000 && $number != 5000){
            return return_data(2, '', '未选择入金金额');
        }
        if($number == 4000 || $number == 5000){
            return return_data(2, '', '此入金金额暂未开放');
        }
        $invest_usdt_pct = SysSetting::where('sign', 'invest_usdt_pct')->value('value');
        if($usdt_amount < ($invest_usdt_pct * 0.01 * $number)){
            return return_data(2, '', '输入的USDT金额小于最小要求');
        }
        //判断钱够不够
        $user_fund = IdxUserFund::where('user_id', $this->user_id)->find();
        if($usdt_amount > $user_fund->money){
            return return_data(2, '', 'USDT金额不足');
        }
        $stbc_amount = ($number - $usdt_amount) * AutoValue::where('id', 3)->value('hight_number') / AutoValue::where('id', 4)->value('hight_number');
        if($stbc_amount > $user_fund->stbc){
            return return_data(2, '', 'STBC金额不足');
        }
        //入金
        $invest_maximum = SysSetting::where('sign', 'invest_maximum')->value('value');
        $progressive_total = SysSetting::where('sign', 'progressive_total')->value('value');
        $user_count = IdxUserCount::where('user_id', $this->user_id)->find();
        $has_static = 1;  //正常情况
        if($invest_maximum <= $user_count->invest_count){ // 超出
            $has_static = 0;
        }else{
            if($progressive_total >= $user_count->invest_sum){ //全部超出
                $has_static = 0;
            }elseif($progressive_total >= ($user_count->invest_sum + $number)){ //本次入金后超出
                $has_static = 2;
            }
        }
        if($has_static == 2){ //本次入金后超出, 将超出部分分离
            IdxInvest::create_data($this->user_id, $user_count->invest_sum + $number - $progressive_total, 0);
            IdxInvest::create_data($this->user_id, $number - $user_count->invest_sum + $number - $progressive_total, 1);
        }else{
            IdxInvest::create_data($this->user_id, $number, $has_static);
        }
        //资金
        $user_fund->money -= $usdt_amount;
        $user_fund->stbc -= $stbc_amount;
        $user_fund->usdt += $number * $bei;
        $user_fund->save();
        LogUserFund::create_data($this->user_id, $usdt_amount, 'money', '入金', '入金');
        if($stbc_amount > 0){
            LogUserFund::create_data($this->user_id, $stbc_amount, 'stbc', '入金', '入金');
        }
        //统计(也加自己的团队业绩)
        $user_count->invest_sum += $number;
        $user_count->invest_count += 1;
        $user_count->team_performance += $number;
        $user_count->save();
        $level = 1;
        $top_id = $this->user->top_id;
        //奖金
        $invest_earnings = SysSetting::where('sign', 'invest_earnings')->value('value'); //见点奖
        while($top_id != 0 && $level <= 8){
            //团队业绩
            $top_user_count = IdxUserCount::get($top_id);
            $top_user_count->team_performance += $number;
            $top_user_count->save();
            $top_user = IdxUser::get($top_id);
            //节点升级
            $this->level_up($top_id);
            //见点奖
            if($level <= 8 && $top_user_count->invest_sum > 0){
                $this->earnings($top_id, '见点奖', $number * $invest_earnings * 0.01);
            }
            //下次循环
            $top_id = $top_user->top_id;
            $level += 1;
        }
        return return_data(1, '', '入金成功');
    }

    //升级判断，顺带更新大小区，和业绩奖计算问题
    private function level_up($top_id){
        $user = IdxUser::get($top_id);
        //获取所有下级，并按团队业绩分为大小区
        $down_user = IdxUser::where('top_id', $top_id)->select();
        if($down_user){
            $max_zone = array('user_id'=> 0, 'team_performance'=> 0);
            $min_zone = array();
            foreach($down_user as $v){
                $v_count = IdxUserCount::get($v->user_id);
                if($v_count->team_performance > $max_zone['team_performance']){
                    if($max_zone['user_id'] != 0){
                        $min_zone[] = array('user_id'=> $max_zone['user_id'], 'team_performance'=> $max_zone['team_performance']);
                    }
                    $max_zone['user_id'] = $v->user_id;
                    $max_zone['team_performance'] = $v_count->team_performance;
                }else{
                    $min_zone[] = array('user_id'=> $v->user_id, 'team_performance'=> $v_count->team_performance);
                }
            }
        }
        //业绩奖计算问题
        $min_zone_all = 0;
        if($min_zone){
            foreach($min_zone as $v){
                $min_zone_all += $v['team_performance'];
            }
        }
        $user_count = IdxUserCount::get($top_id);
        $user_count->small_area_number = $min_zone_all;
        $user_count->big_area_number = $max_zone['team_performance'];
        if($min_zone_all <= $max_zone['team_performance']){ //谁小按谁
            //收益按小区
            $yield_zone = ',';
            foreach($min_zone as $v){
                $yield_zone .= $v['user_id'] . ',';
            }
            $user_count->yield_zone = $yield_zone;
        }else{
            //收益按大区
            $user_count->yield_zone = ',' . $max_zone['user_id'] . ',';
        }
        $user_count->save();
        //升级判断
        if($user->level == 6){
            return true;
        }
        $level_up = $user->level + 1;
        $node_condition = SysSetting::where('sign', 'node_' . $level_up)->value('value');
        if($node_condition <= $min_zone_all){
            //升级
            $user->level = $level_up;
            $user->save();
        }
    }

    public static function static_earnings($user_id){
        $user_invest = IdxInvest::where('user_id', $user_id)->where('has_static', 1)->select();
        if($user_invest){
            //准备工作
            $user = IdxUser::find($user_id);
            $user_fund = IdxUserFund::find($user_id);
            $user_count = IdxUserCount::find($user_id);
            $static_earnings = SysSetting::where('sign', 'static_day_pct')->value('value'); //静态收益
            foreach($user_invest as $v){
                if($v->period >= 100){
                    continue;
                }
                $invest_time = $v->operation_time;
                $now_time = date("Y-m-d", time());
                while($invest_time != $now_time){ //如果最后一次释放时间不是今天，那么就循环
                    if($v->period >= 100){
                        break;
                    }
                	if($user->is_earnings == 0){ //如果收益冻结，仅仅更新时间
                        $v->operation_time = date('Y-m-d', strtotime($v->operation_time) + (25 * 60 * 60));
                        $v->period += 1;
                        $v->save();
                        $invest_time = $v->operation_time; //更新循环条件
                        continue;
                    }
                    //释放
                    $v->period += 1;
                    $v->operation_time = date('Y-m-d', strtotime($v->operation_time) + (25 * 60 * 60));
                    $v->save();
                    $earnings = $v->amount * $static_earnings * 0.01; //今日返的钱usdt
                    if($user_fund->usdt >= $earnings){
                        $user_fund->usdt -= $earnings;
                    }else{
                        $earnings = $user_fund->usdt;
                        $user_fund->usdt = 0;
                    }
                    $earnings = $earnings * AutoValue::where('id', 3)->value('hight_number') / AutoValue::where('id', 4)->value('hight_number');  //今日返的钱stbc
                    $user_fund->stbc += $earnings;
                    $user_fund->save();
                    LogUserFund::create_data($user_id, $earnings, 'stbc', '静态收益', '静态收益');
                    $invest_time = $v->operation_time; //更新循环条件

                    $top_id = $user->top_id; //上级id，下面的循环条件
                    $i = 1; //层级
                    $fan_level = 0;  //返佣最高等级
                    $down_user_id = $user_id; //下层id
                    $once_level_four = true;
                    while($top_id != 0){
                        //代数奖
                        $top = IdxUser::get($top_id);
                        if($top->is_earnings == 0){ //如果上级收益冻结，不获取收益
                            $top_id = $top->top_id;
                            $i += 1;
                            $down_user_id = $top->user_id;
                            continue;
                        }
                        $top_fund = IdxUserFund::get($top_id);
                        $top_count = IdxUserCount::get($top_id);
                        $sql = "SELECT COUNT(*) as c FROM (SELECT COUNT(user_id) FROM idx_invest WHERE user_id IN (SELECT user_id FROM idx_user WHERE top_id=" . $top_id . ") GROUP BY user_id) AS a";
                        $down_user_count = Db::query($sql);
                        if($i <= 8 && $down_user_count[0]['c'] >= $i && $top_count->invest_sum > 0){
                        // if($i <= 20 && $top_count->down_team_number >= $i && $top_count->invest_sum > 0){
                            $aa = SysSetting::where('sign', 'aa_'.$i)->value('value');
                            self::earnings($top_id, '代数奖', $earnings * $aa * 0.01);
                        }
                        //总业绩奖
                        if($i <= 30){
                            if(strpos($top_count->yield_zone, ',' . $down_user_id . ',') !== false && $top_count->invest_sum > 0){ //如果下层在小区内，就发奖
                                //发奖
                                $fan_level_j = $fan_level == 0 ? 0 : SysSetting::where('sign', 'node_'.$fan_level.'_pct')->value('value');
                                $top_level_j = $top->level == 0 ? 0 : SysSetting::where('sign', 'node_'.$top->level.'_pct')->value('value');
                                $j = (($top_level_j - $fan_level_j) < 0) ? 0 : $top_level_j - $fan_level_j;
                                if($top->level == 4 && $fan_level == 4 && $once_level_four == true){
                                    $j = 1;
                                    $once_level_four = false;
                                }
                                $yield_earnings = $earnings * $j * 0.01;
                                $top_fund->money += $yield_earnings;
                                //返佣最高等级
                                if($top->level > $fan_level){
                                    $fan_level = $top->level;
                                }
                                if($yield_earnings > 0){
                                    LogUserFund::create_data($user_id, $yield_earnings, 'stbc', '总业绩奖', '静态收益');
                                }
                            }
                        }else{
                            break;
                        }
                        $top_fund->save();
                        $top_id = $top->top_id;
                        $i += 1;
                        $down_user_id = $top->user_id;
                    }
                }
            }
        }
    }
}


