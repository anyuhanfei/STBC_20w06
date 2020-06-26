<?php
use think\facade\Route;

// 首页
Route::get('/', 'index/index/index');
Route::get('invest', 'index/index/invest');
Route::post('invest/submit', 'index/fund/invest_submit');
Route::get('log', 'index/index/log');
Route::get('notice', 'index/index/notice');
Route::get('notice/detail/:id', 'index/index/detail');


// 登录
Route::get('login', 'index/login/login');
Route::post('login/submit', 'index/login/login_submit');
Route::get('forget', 'index/login/forget');
Route::post('forget/submit', 'index/login/forget_submit');
Route::get('register/[:code]', 'index/login/register');
Route::post('register/submit', 'index/login/register_submit');
Route::get('login/out', 'index/login/loginout');

Route::rule('sms/:type', 'index/base/sms');

//C2C
Route::get('deal', 'index/deal/deal');
Route::post('deal/shop/q', 'index/deal/shop_q');
Route::get('deal/create', 'index/deal/create');
Route::post('deal/create/submit', 'index/deal/create_submit');
Route::post('deal/ing', 'index/deal/dealing');
Route::get('deal/order', 'index/deal/order');
Route::get('deal/order/detail/:deal_id', 'index/deal/order_detail');
Route::post('deal/order/submit/1', 'index/deal/order_1');
Route::post('deal/order/submit/2', 'index/deal/order_2');

//我的
Route::get('me', 'index/me/index');
Route::get('me/invite', 'index/me/invite');
Route::get('me/service', 'index/me/service');
Route::get('me/set', 'index/me/set');
Route::get('me/team', 'index/me/team');
Route::get('me/update/password', 'index/me/updatepassword');
Route::post('me/set/update/password/submit', 'index/me/updatepassword_submit');

Route::get('me/recharge', 'index/mefund/recharge');
Route::post('me/recharge/submit', 'index/mefund/recharge_submit');
Route::get('me/recharge/log', 'index/mefund/recharge_log');
Route::get('me/transfer', 'index/mefund/transfer');
Route::post('me/transfer/submit', 'index/mefund/transfer_submit');
Route::get('me/transfer/log', 'index/mefund/transfer_log');
Route::get('me/withdraw', 'index/mefund/withdraw');
Route::post('me/withdraw/submit', 'index/mefund/withdraw_submit');
Route::get('me/withdraw/log', 'index/mefund/withdraw_log');
Route::get('me/wallet', 'index/mefund/wallet');
Route::get('me/wallet/usdt', 'index/mefund/wallet_usdt');
Route::get('me/wallet/stbc', 'index/mefund/wallet_stbc');
Route::get('me/wallet/wx', 'index/mefund/wallet_wx');
Route::get('me/wallet/alipay', 'index/mefund/wallet_alipay');
Route::get('me/wallet/bank', 'index/mefund/wallet_bank');
Route::post('me/wallet/update/submit/:type', 'index/mefund/wallet_update_submit');
