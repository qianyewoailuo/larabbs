<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {
        // 只允许当前登录用户编辑自己的话题
        return $topic->user_id == $user->id;
    }

    public function destroy(User $user, Topic $topic)
    {
        // 只允许当前登录用户删除自己的话题
        return $topic->user_id == $user->id;
    }
}
