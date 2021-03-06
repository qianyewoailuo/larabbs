<?php

namespace App\Models;

class Topic extends Model
{
    // 定义可填充字段
    protected $fillable = ['title', 'body',  'category_id', 'excerpt', 'slug'];

    // 一个话题属于一个分类
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // 一个话题属于一个作者
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // 一个话题有多个回复
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序使用不同的数据读取逻辑
        // 调用的时候就是withOrder方法
        switch ($order) {
            case 'recent':
                // scope是本地作用域声明 这里的recent()方法就是下面的scopeRecent方法,下面同样
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }
        // 预加载防止 N+1 问题
        return $query->with('user', 'category');
    }

    // 创建时间排序
    public function scopeRecent($query)
    {
        // 当话题有新回复时我们将编写逻辑来更新话题模型的reply_count 属性
        // 此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }
    // 最新回复排序
    public function scopeRecentReplied($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    // slug-link
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    // 代码重用解决
    public function updateReplyCount()
    {
        $this->reply_count = $this->replies->count();
        $this->save();
    }
}
