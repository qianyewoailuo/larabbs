<?php

namespace App\Http\Controllers;

// 直接引用Carbon即可完成使用设置
use Carbon\Carbon;
// 中文本地化设置
// 在 App\Providers\AppServiceProvider 类中的boot方法添加 Carbon::setLocale('zh');
class TestsController extends Controller
{
    // carbon常用时间函数
    public function carbonTest()
    {
        // 获取当前时间对象 2019-05-11 05:30:10
        $now               = Carbon::now();
        // 获取今天初始时间 2019-05-11 00:00:00
        $today             = Carbon::today()->toDateTimeString();
        // 获取昨天初始时间 2019-05-10 00:00:00
        $yesterday         = Carbon::yesterday()->toDateTimeString();
        // 获取明天初始时间 2019-05-12 00:00:00
        $tomorrom          = Carbon::tomorrow()->toDateTimeString();
        // 格式化时间对象成字符串 格式为 2019-05-11 19:22:35
        $toDateTimeString  = Carbon::now()->toDateTimeString();
        // 格式化时间对象成字符串 格式为 2019-05-11
        $toDateString      = Carbon::now()->toDateString();
        // 格式化时间对象成字符串 格式为 19:22:35
        $toTimeString      = Carbon::now()->toTimeString();
        // 时间对象转换为数组
        $toArray           = Carbon::now()->toArray();

        # 操作日期/时间的函数
        // 格式为前缀 add|sub + Years/Months/Days/Weeks/Hours/Minutes/Seconds 例如
        // 增加30days 2019-06-10 05:30:10
        $addDays           = Carbon::now()->addDays(30)->toDateTimeString();
        // 减少30days 2019-04-11 05:30:10
        $subDays           = Carbon::now()->subDays(30)->toDateTimeString();
        // 增加2years 2021-04-11 05:30:10
        $addYears          = Carbon::now()->addYears(2)->toDateTimeString();
        // 其余不再举例

        # 相对时间
        // 通过 diff() 方法可以很容易的显示相对时间
        // 例如，我们有一篇博客，并且我们想显示它是在 三小时 前发布的。可以利用这些方法
        // 方法名格式类似于add|sub, 这里的是 diffIn + Years/Months/Days/Hours/Minutes/Seconds等
        // 获取今天的初始时间 2019-05-11 00:00:00
        $today = Carbon::today();
        // 创建一个的时间 2019-04-11 00:00:00
        $pass  = Carbon::create(2019, 4, 11, 0);
        // 创建一个的时间 2019-06-10 00:00:00
        $future = Carbon::create(2019, 6, 10, 0);
        // 30
        echo $result = $pass->diffInDays($today);
        echo '<br>';
        // 30
        echo $result = $future->diffInDays($today);
        echo '<br>';
        // 其余的相对时间不再测试

        # 易于人类阅读的时间差
        // 4小时前
        echo Carbon::now()->subHours(4)->diffForHumans();
        echo '<br>';
        // 距现在7小时后
        echo Carbon::now()->addHours(7)->diffForHumans();
        echo '<br>';
        echo $pass->diffForHumans();
        echo '<br>';
        // 距现在4周后
        echo $future->diffForHumans();
        echo '<br>';
        // 4周前
        echo Carbon::now()->diffForHumans($future);
        echo '<br>';
        // 一个月后
        echo Carbon::now()->diffForHumans($pass);

        // 结果打包成数组
        $carbon = compact('now', 'today', 'yesterday', 'tomorrom', 'toDateTimeString', 'toDateString', 'toTimeString', 'toArray', 'addDays', 'subDays', 'addYears');
        // 输出
        dd($carbon);
    }

    // helper functions
    public function helperTest()
    {
        $array = [
            'user' => ['username' => 'something'],
            'app' => ['creator' => ['name' => 'someone'], 'created' => 'today']
        ];
        $dot_array = array_dot($array);
        /*
        array:3 [▼
        "user.username" => "something"
        "app.creator.name" => "someone"
        "app.created" => "today"
        ]
        */
        $name = array_get($array,'app.creator.name');
        /* "someone" */
        $path = public_path('js/app.js');
        $path = public_path();
        // dd($path);
        echo config_path(),'<br>',app_path(),'<br>',base_path(),'<br>',database_path();
    }
}
