<?php
use think\facade\Route;

Route::rule('log/recharge', 'user/recharge');
Route::rule('log/withdraw', 'user/withdraw');
Route::rule('log/shop/apply', 'other/shop_apply');

Route::rule('deal', 'other/deal');
Route::rule('invest', 'other/invest');
Route::rule('price', 'other/price');
