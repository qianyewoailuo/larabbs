<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    // 全局中间件
    protected $middleware = [
        // 检测应用时候进入 维护模式
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        // 检测表单请求数据是否过大
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        // 对提交的请求参数进行 PHP 函数 trim() 处理
        \App\Http\Middleware\TrimStrings::class,
        // 对提交请求参数中空字符串转换为null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // 修正代理服务器后的服务器参数
        \App\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        // web 中间件组 应用于 routes/web.php 路由文件
        // 在 RouteServiceProvider 中设定
        'web' => [
            // cookie 加密解密
            \App\Http\Middleware\EncryptCookies::class,
            // 将 cookie 添加到响应中
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            // 开启会话
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            // 将系统错误数据注入到视图变量 $errors 中
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            // CSRF 检测防止跨站请求伪造的安全威胁
            \App\Http\Middleware\VerifyCsrfToken::class,
            // 路由绑定处理
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // 注册自定义的邮箱激活认证中间件(强制用户邮箱认证)
            \App\Http\Middleware\EnsureEmailIsVerified::class,
            // 记录用户最后活跃时间
            \App\Http\Middleware\RecordLastActivedTime::class,
        ],
        // API 中间件组 应用于 routes/api.php 路由文件
        // 在 RouteServiceProvider 中设定
        'api' => [
            // 使用别名来调用中间件
            // 请见：https://learnku.com/docs/laravel/5.7/middleware#为路由分配中间件
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    // 中间件别名设置，允许使用别名调用中间件，与上述api中间件组一样
    protected $routeMiddleware = [
        // 登录用户认证
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        // 用户策略授权
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        // 访客授权
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        // 签名认证
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        // 访问节流 可设定限制每分钟请求次数
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        // 强制用户邮箱认证
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    // 除全局中间件外的其他中间件优先级设定，执行正序顺序
    // 例如 StartSession 会话中间件是首先被执行的，
    // 因为后续中间件例如 Auth 必须确保 session 能使用才能进行后续认证操作
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
