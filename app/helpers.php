<?php
// use Illuminate\Support\Facades\Route;

/*
 * @Description: 根据应用不同环境指定数据库配置信息
 * @Author: luo
 * @email: qianyewoailuo@126.com
 * @Date: 2019-05-02 14:14:03
 * @LastEditTime: 2019-05-03 04:23:07
 */

 // 获取应用运行环境配置
function get_db_config()
{
    if (getenv('IS_IN_HEROKU')) {
        $url = parse_url(getenv("DATABASE_URL"));

        return $db_config = [
            'connection' => 'pgsql',
            'host' => $url["host"],
            'database'  => substr($url["path"], 1),
            'username'  => $url["user"],
            'password'  => $url["pass"],
        ];
    } else {
        return $db_config = [
            'connection' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST', 'localhost'),
            'database'  => env('DB_DATABASE', 'forge'),
            'username'  => env('DB_USERNAME', 'forge'),
            'password'  => env('DB_PASSWORD', ''),
        ];
    }
}

// 请求的路由名称转换为 CSS 类名称
function route_class()
{
    return str_replace('.','-',Route::currentRouteName());
}