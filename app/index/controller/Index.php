<?php
namespace app\index\controller;

use think\facade\Session;
use think\facade\View;
use think\facade\Db;

use app\admin\model\IdxUser;
use app\admin\model\IdxUserFund;
use app\admin\model\SysSetting;
use app\admin\model\CmsArticle;
use app\admin\model\IdxStbcPrice;
use app\admin\model\LogUserFund;
use app\admin\model\SysAd;
use app\admin\model\AutoValue;

use app\index\controller\Fund;


class Index extends Base{
    protected $user = null;
    protected $middleware = [
        \app\index\middleware\CheckIndex::class,
    ];

    public function __construct(){
        parent::__construct();
        $this->user_id = Session::get('user_id');
        $this->user = IdxUser::find($this->user_id);
        View::assign('user_id', $this->user_id);
        View::assign('user', $this->user);
    }

    /**
     * 首页
     *
     * @return void
     */
    public function index(){
        Fund::static_earnings($this->user_id);
        //banner
        View::assign('banner', SysAd::where('adv_id', 19)->select());
        //公告
        View::assign('notice', SysAd::where('sign', 'notice')->value('value'));
        //趋势图, 7天的历史金额
        $k_obj = Db::query("SELECT * FROM `idx_stbc_price` WHERE  UNIX_TIMESTAMP(`insert_time`) <= " . time() . " ORDER BY `id` DESC LIMIT 7");
        $today_price = 0;
        $k = array('day'=> array(), 'amount'=> array());
        foreach($k_obj as $v){
            if($today_price == 0){
                $today_price = $v['unit_price'];
            }
            $k['day'][] = intval(date("1md", strtotime($v['insert_time'])));
            $k['amount'][] = floatval($v['unit_price']);
        }
        View::assign('day', json_encode(array_reverse($k['day'])));
        View::assign('amount', json_encode(array_reverse($k['amount'])));
        View::assign('today_price', $today_price);
        //行业咨询,最新三条
        View::assign('new', CmsArticle::order('article_id desc')->limit(3)->select());
        //币种价格
        $btc = AutoValue::find(1);
        $eth = AutoValue::find(2);
        $usdt = AutoValue::find(3);
        $stbc = AutoValue::find(4);
        if(date("Y-m-d", $stbc->insert_time) != date("Y-m-d", time())){
            $stbc->hight_number = $today_price;
            $stbc->insert_time = time();
            $stbc->save();
        }
        View::assign('btc', $btc);
        View::assign('eth', $eth);
        View::assign('usdt', $usdt);
        if($btc){
            if(intval($btc->insert_time) < time() - 10){
                exec('python3 ./python/get_data.py');
            }
        }else{
            exec('python3 ./python/get_data.py');
        }
        return View::fetch();
    }

    /**
     * 收益页
     *
     * @return void
     */
    public function log(){
        $obj = LogUserFund::where('user_id', $this->user_id)->order('id desc')->select();
        View::assign('obj', $obj);
        return View::fetch();
    }

    /**
     * 入金
     *
     * @return void
     */
    public function invest(){
        $k_obj = Db::query("SELECT * FROM `idx_stbc_price` WHERE  UNIX_TIMESTAMP(`insert_time`) <= " . time() . " ORDER BY `id` DESC LIMIT 1");
        $today_price = $k_obj[0]['unit_price'];
        $b = $today_price / AutoValue::where('id', 3)->value('hight_number'); //stbc人民币价 / usdt人民币价 = 1stbc可以兑换多少usdt
        View::assign('b', $b);
        $invest_usdt_pct = SysSetting::where('sign', 'invest_usdt_pct')->value('value');
        View::assign('invest_usdt_pct', $invest_usdt_pct);
        $stbc = AutoValue::where('id', 4)->value('hight_number');
        View::assign('stbc', $stbc);
        $usdt = AutoValue::where('id', 3)->value('hight_number');
        View::assign('usdt', $usdt);
        return View::fetch();
    }

    /**
     * 资讯
     *
     * @return void
     */
    public function notice(){
        View::assign('list', CmsArticle::order('article_id desc')->limit(30)->select());
        return View::fetch();
    }

    /**
     * 资讯详情
     *
     * @return void
     */
    public function detail($id){
        View::assign('detail', CmsArticle::find($id));
        return View::fetch();
    }




    /**
     * 发放奖励
     *
     * @param int $user_id
     * @param string $type 奖励类型
     * @param integer $amount 应得的USDT金额
     * @return void
     */
    public static function earnings($user_id, $type, $amount=0){
        $stbc_amount = 0;
        $user_fund = IdxUserFund::find($user_id);
        if($user_fund->usdt > 0 && $amount > 0){
            $usdt2cny = AutoValue::where('id', 3)->value('hight_number');
            $stbc2cny = AutoValue::where('id', 4)->value('hight_number');
            if($user_fund->usdt >= $amount){
                $stbc_amount = $amount * $usdt2cny / $stbc2cny;
                $user_fund->usdt -= $amount;
            }else{
                $stbc_amount = $user_fund->usdt * $usdt2cny / $stbc2cny;
                $user_fund->usdt = 0;
            }
            $user_fund->stbc += $stbc_amount;
            $user_fund->save();
        }
        LogUserFund::create_data($user_id, $stbc_amount, 'STBC', $type, $type);
    }
}