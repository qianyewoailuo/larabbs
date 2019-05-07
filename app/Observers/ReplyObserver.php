<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        // 与下面的代码重用了
        // $reply->topic->reply_count = $reply->topic->replies->count();
        // $reply->topic->save();
        $reply->topic->updateReplyCount();

        // 通知话题作者有新的评论
        $reply->topic->user->notify(new TopicReplied($reply));
        // 默认的 User 模型中使用了 trait —— Notifiable，它包含用来发通知的方法notify()
        // 之后我们还要自动将 users 表里的 notification_count +1 ，这样我们就能跟踪用户未读通知了

    }

    public function creating(Reply $reply)
    {
        // HTMLPurifier 提供的clean过滤方法
        // 参数1是过滤内容 参数2是过滤的规则 在config里设置
        // $reply->content = clean($reply->content,'user_topic_body');
        // if (empty($reply->content)){
        //     return redirect()->back()->with('danger','请不要做出一些危险操作');
        // }
    }

    public function deleted(Reply $reply)
    {
        // 和上述代码重复
        // $reply->topic->reply_count = $reply->topic->replies->count();
        // $reply->topic->save();
        $reply->topic->updateReplyCount();
    }
}