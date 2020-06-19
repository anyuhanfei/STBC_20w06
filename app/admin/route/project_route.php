<?php
use think\facade\Route;

Route::rule('log/recharge', 'user/recharge');
Route::rule('log/recharge/submit/:id', 'user/recharge_submit');
Route::rule('log/withdraw', 'user/withdraw');
Route::rule('log/withdraw/submit/:id', 'user/withdraw_submit');

Route::rule('deal', 'other/deal');
Route::rule('invest', 'other/invest');
Route::rule('price', 'other/price');
Route::rule('price/add', 'other/price_add');
