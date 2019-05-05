<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // 可填充字段定义
    protected $fillable = [
        'name','description',
    ];
}
