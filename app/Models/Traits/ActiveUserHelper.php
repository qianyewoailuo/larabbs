<?php

namespace App\Models\Traits;

use App\Models\Topic;
use App\Models\Reply;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait ActiveUserHelper
{
    // 用于存储临时用户数据的属性
    protected  $users = [];

    // 配置信息
    protected $topic_weight = 4;    // 话题权重
    protected $reply_weight = 1;    // 回复权重
    protected $pass_days    = 7;    // 默认统计7天内发表的内容
    protected $user_number  = 6;    // 取出的活跃用户数

    // 缓存配置
    protected $cache_key               = 'larabbs_active_users';
    protected $cache_expire_in_minutes = 65;

    // 返回活跃用户数据的方法
    public function getActiveUsers()
    {
        // 尝试从缓存中取出 cache_key 对应的活跃用户数据。如果能获取到就直接返回
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function () {
            // 当缓存没有相应数据时执行匿名函数中的方法进行取出活跃用户数据
            return $this->calculateActiveUsers();
        });
    }

    // 将活跃用户列表进行缓存的方法
    public function calculateAndCacheActiveUsers()
    {
        // 取得活跃用户列表
        $active_users = $this->calculateActiveUsers();
        // 并加以缓存
        $this->cacheActiveUsers($active_users);
    }

    // 获取活跃用户数据的方法
    private function calculateActiveUsers()
    {
        $this->calculateTopicsScore();
        $this->calculateReplyScore();

        // 数组按照得分排序
        $users = array_sort($this->users, function ($user) {
            return $user['score'];
        });

        // 我们需要的是倒序，高分靠前，第二个参数为保持数组的 KEY 不变
        $users = array_reverse($users, true);

        // 只获取我们想要的数量
        $users = array_slice($users, 0, $this->user_number, true);

        // 新建一个空集合
        $active_users = collect();

        foreach ($users as $user_id => $user) {
            // 找寻下是否可以找到用户
            $user = $this->find($user_id);

            // 如果数据库里有该用户的话
            if ($user) {
                // 将此用户实体放入集合的末尾
                $active_users->push($user);
            }
        }
        // 返回数据
        return $active_users;
    }

    // 话题得分
    private function calculateTopicsScore()
    {
        // 从话题数据表中取出限定时间范围内发表过话题的用户
        // 并且同时取出用户此段时间类发布话题的数量
        $topic_users = Topic::query()->select(DB::raw('user_id,count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // 根据话题数量计算得分
        foreach ($topic_users as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    // 回复得分
    private function calculateReplyScore()
    {
        // 从回复数据表取出限定时间范围内有发表过回复的用户
        // 并且同时取出 用户此段时间内发不回复的数量
        $reply_users = Reply::query()->select(DB::raw('user_id,count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // 根据回复数量计算得分
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }
    // 数据缓存
    private function cacheActiveUsers($active_users)
    {
        // 将数据放入缓存中
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }
}
