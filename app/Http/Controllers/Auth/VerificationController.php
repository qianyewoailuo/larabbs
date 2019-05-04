<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        // 访问频率控制中间件 这里是限制1分钟内只能6次访问
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * 显示认证邮件提醒页面 + 未认证闪存提示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // email_verified_at字段是否为空,空即未验证邮箱跳转到auth.verif页面
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/');
        } else {
            session()->flash('warning','您的账号尚未激活,请检查或重新发送注册邮件进行激活');
            return view('auth.verify');
        }
    }

}
