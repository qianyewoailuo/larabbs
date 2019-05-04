<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    // 当前show方法的参数使用了 [隐性路由绑定] 是是『约定优于配置』设计范式的体现
    // 具体解析参考 https://learnku.com/courses/laravel-intermediate-training/5.7/personal-page/2610#889027 md文档中的 创建控制器 部分
    public function show(User $user)
    {
        return view('users.show',compact('user'));
    }
}
