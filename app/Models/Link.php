<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    // 定义填充字段
    protected $fillable  = ['title', 'link'];
}
