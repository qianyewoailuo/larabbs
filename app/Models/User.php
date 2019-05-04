<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\MustVerifyEmail as IlluminateMustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable ,IlluminateMustVerifyEmail;

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
}
