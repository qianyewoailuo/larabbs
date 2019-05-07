<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as IlluminateMustVerifyEmail;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable implements MustVerifyEmail
{
    use IlluminateMustVerifyEmail;

    use Notifiable {
        // 将当前user的 notify 方法改为自定义重写的 laravelNotify
        // 其实不会这样的话还可以直接写一个新的topicNotify()方法 最后调用notify
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户就不必通知了
        if ($this->id == Auth::id()) {
            return;
        }

        // 只有数据库类型通知才需要提醒 直接发送Email或其他都pass
        if (method_exists($instance,'toDatabase')) {
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        // 将notifications表中的read_at由null标志为已读时间
        $this->unreadNotifications->markAsRead();
    }

    /**
     * 使用 IlluminateMustVerifyEmail 后可以使用三个方法
     * hasVerifiedEmail() 检测用户 Email 是否已认证
     * markEmailAsVerified() 将用户标示为已认证
     * sendEmailVerificationNotification() 发送 Email 认证的消息通知，触发邮件的发送
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 一个用户可以拥有或曾发布多个话题
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
    // 一个用户可以发布多个评论
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    // 代码重用,判断是否是当前用户
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
}
