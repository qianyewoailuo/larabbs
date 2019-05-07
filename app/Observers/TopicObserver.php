<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;
use Illuminate\Support\Facades\DB;

// 此时使用队列 这里不用引用了
// use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

// 话题模型观察监听器

// Eloquent 模型会触发许多事件（Event），我们可以对模型的生命周期内多个时间点进行监控： creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored。事件让你每当有特定的模型类在数据库保存或更新时，执行代码。当一个新模型被初次保存将会触发 creating 以及 created 事件。如果一个模型已经存在于数据库且调用了 save 方法，将会触发 updating 和 updated 事件。在这两种情况下都会触发 saving 和 saved 事件。

// Eloquent 观察器允许我们对给定模型中进行事件监控，观察者类里的方法名对应 Eloquent 想监听的事件。每种方法接收 model 作为其唯一的参数。

class TopicObserver
{

    public function saving(Topic $topic)
    {
        // 使用 HTMLPurifier for Laravel 进行 xxs 过滤
        $topic->body = clean($topic->body, 'user_topic_body');
        // 生成话题摘录
        // make_excerpt() 是自定义的辅助函数 在 helpers.php 中定义
        $topic->excerpt = make_excerpt($topic->body);
    }

    public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if (!$topic->slug) {
            // 使用app()辅助函数成 SlugTranslateHandler 实例
            // $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
            // 推送到任务队列
            dispatch(new TranslateSlug($topic));
        }
    }

    public function deleted(Topic $topic)
    {
        // 话题被删除时将回复同步删除
        // 在模型监听器中，数据库操作需避免再次触发 Eloquent 事件 必须要使用DB操作类
        DB::table('replies')->where('topic_id',$topic->id)->delete();
    }
}
