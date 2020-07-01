<?php
use think\facade\Route;

Route::rule('log/recharge', 'user/recharge');
Route::rule('log/recharge/submit/:id', 'user/recharge_submit');
Route::rule('log/withdraw', 'user/withdraw');
Route::rule('log/withdraw/submit/:id', 'user/withdraw_submit');
Route::rule('log/shop/apply', 'other/shop_apply');
Route::rule('log/shop/apply/submit/:id', 'other/shop_apply_submit');

Route::rule('deal', 'other/deal');
Route::rule('invest', 'other/invest');
Route::rule('invest/freeze', 'other/invest_freeze');
Route::rule('price', 'other/price');
Route::rule('price/add', 'other/price_add');
