<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at';
    protected $field_prefix = 'user_';

    // User模型的last_actived_at属性访问器
    public function getLastActivedAtAttribute($value)
    {
        // 获取今天的日期
        $date = Carbon::now()->toDateString();

        // 获取哈希表以及哈希字段名
        // $hash = $this->hash_prefix . $date;
        // $field = $this->field_prefix . $this->id;
        // 多次使用便于维护使用自定义方法
        $hash = $this->getHashFromDateString($date);
        $field = $this->getHashField();

        // 优先选择Redis的数据 否则使用数据库的数据
        $datetime = Redis::hGet($hash, $field) ?: $value;

        // 如果存在的话返回时间对应的Carbon实体
        if ($datetime) {
            return new Carbon($datetime);
        } else {
            // 否则使用用户注册时间
            return $this->created_at;
        }
    }

    // 记录上次登录时间
    public function recordLastActivedAt()
    {
        // 获取今天的日期 格式转换为 2019-5-10 样式
        $date = Carbon::now()->toDateString();

        // redis 哈希表的命名 如 larabbs_last_actived_at_2019-5-10
        // $hash = $this->hash_prefix . $date;
        // 多次使用到,为了便于维护使用自定义方法获取
        $hash = $this->getHashFromDateString($date);

        // 字段名称命名 如 user_1
        // $field = $this->field_prefix . $this->id;
        // 多次使用到,为了便于维护使用自定义方法获取
        $field = $this->getHashField();

        // 测试数据是否捕获
        // dd(Redis::hGetAll($hash));

        // 当前时间，如：2019-5-10 08:35:15
        $now = Carbon::now()->toDateTimeString();

        // 数据写入 Redis ，字段已存在会被更新
        Redis::hSet($hash, $field, $now);
    }
    // 同步上次登录时间
    public function syncUserActivedAt()
    {
        // 获取昨天的日期 格式如：2019-05-09
        $yesterday_date = Carbon::yesterday()->toDateString();
        // $yesterday_date = Carbon::now()->toDateString();

        // Redis 哈希表的命名 如：larabbs_last_actived_at_2019_05_09
        // $hash = $this->hash_prefix . $yesterday_date;
        $hash = $this->getHashFromDateString($yesterday_date);

        // 从 Redis 中获取所有哈希表里的数据
        $dates = Redis::hGetAll($hash);
        // 遍历并同步到数据库
        foreach ($dates as $user_id => $actived_at) {
            // hash表中的数据键名为$this->hash_prefix . $this->id
            // 将类似的 user_1 转换为 1
            $user_id = str_replace($this->field_prefix, ' ', $user_id);
            // 只有当用户存在时才更新到数据库中
            if ($user = $this->find($user_id)) {
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }
        // 同步成功即将 Redis 里的数据清除
        Redis::del($hash);
    }

    // 获取哈希表的命名
    public function getHashFromDateString($date)
    {
        // Redis 哈希表命名 如 larabbs_last_actived_at_2019-5-10
        return $this->hash_prefix . $date;
    }
    // 获取哈希表键名
    public function getHashField()
    {
        // 字段名称 如 user_1
        return $this->field_prefix . $this->id;
    }
}
