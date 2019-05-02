<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 必须邮件激活的三个判断
        // 1.如果用户已经登录  -> user()
        // 2.并且还未认证Email -> hasVerifiedEmail()
        // 3.并且访问的不是Email验证相关URL或者退出的URL
        if ($request->user() &&
            ! $request->user()->hasVerifiedEmail() &&
            ! $request->is('email/*','logout')) {

            // 根据客户端返回对应的内容
            // expectsJson() 如果是ajax请求返回true 否则返回false
            // abort 函数抛出 异常处理 程序呈现的 HTTP 异常
            return $request->expectsJson()
                        ? abort(403,'您的邮箱还未通过验证')
                        : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}
