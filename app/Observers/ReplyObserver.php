<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
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

    public function destroy(Reply $reply)
    {
        $reply->topic->reply_count = $reply->topic->replies->count();
        $reply->topic->save();
    }
}