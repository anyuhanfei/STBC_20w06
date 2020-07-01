<?php
use think\facade\Route;

// 首页模块
Route::get('/', 'index/index');

// 广告模块
Route::get('ad', 'ad/ad');

// 日志模块
Route::rule('admin/operation/log', 'log/admin_operation_log');
Route::rule('admin/login/log', 'log/admin_login_log');

//个人模块
Route::get('me/detail', 'me/detail');
Route::post('me/detail/submit', 'me/detail_submit');
Route::get('me/update/password', 'me/update_password');
Route::post('me/update/password/submit', 'me/update_password_submit');

//网站设置
Route::get('setting/[:cgory]', 'webset/setting');

//文章管理
Route::get('cms/category', 'cms/category');
Route::rule('cms/article', 'cms/article');

// 会员管理
Route::rule('user', 'user/user');
Route::get('user/team/:id', 'user/user_team');
Route::get('user/detail/:id', 'user/user_detail');

Route::rule('user/fund/log', 'log/user_fund_log');
Route::rule('user/operation/log', 'log/user_operation_log');