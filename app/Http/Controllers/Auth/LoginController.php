<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // 访客中间件限制登录用户只能访问logou方法
        $this->middleware('guest')->except('logout');
    }

    // 重写trait 提示登录成功闪存
    protected function sendLoginResponse(Request $request)
    {
        // 重新生成用户相关会话ID
        $request->session()->regenerate();
        // 增加闪存会话信息
        session()->flash('success','登录成功,欢迎您的回来!');
        // 清除指定用户在缓存数据库中的登录次数记录,包括Lock记录
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    // 重新trait 提示登出成功闪存
    public function logout(Request $request)
    {
        $this->guard()->logout();
        // 清除用户相关会话信息
        $request->session()->invalidate();
        // 增加闪存
        session()->flash('success','您已成功退出');
        // 如果有设置loggeOut逻辑就重定向到自定义页面(例如退出到登录页面)
        // 否则默认自定义到首页
        return $this->loggedOut($request) ?: redirect('/');
    }
}
