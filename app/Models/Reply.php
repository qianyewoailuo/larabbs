<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    // 一个回复对应一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // 一个回复对应一个话题
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
