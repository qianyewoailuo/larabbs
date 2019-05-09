<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Link extends Model
{
    // 定义填充字段
    protected $fillable  = ['title', 'link'];

    public $cache_key = 'larabbs_links';
    protected $cahce_expire_in_minutes = 1440;

    public function getAllCached()
    {
        // 尝试从缓存中取出 cache_key 对应的数据
        // 否则运行匿名函数中的代码来取出 Links 表的数据
        return Cache::remember($this->cache_key,$this->cahce_expire_in_minutes,function(){
            return $this->all();
        });
    }
}
