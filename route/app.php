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
use think\facade\Route;


Route::group("/user", function () {

    Route::post("/getcode", "/user/getCode");

    Route::post("/register", "user/register");

    Route::post("/login", "user/login");

    Route::post("/resetpwd", "user/resetpwd");
});

Route::group("/user",function(){

    Route::delete("/delete/:id","/user/deleteById");

    Route::get("/disabled/:id","/user/disabled");

    Route::get("/page","user/page");

})->middleware(app\middleware\JwtMiddleware::class);