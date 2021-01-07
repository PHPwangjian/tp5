<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


Route::post('login', 'index/index/login');        //登录接口
Route::rule('addTheme', 'index/index/addTheme')->middleware(['CheckToken']);  //后台首页
Route::rule('themelist', 'index/index/themelist')->middleware(['CheckToken']);  //主题列表
return [

];
