<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {
        // 只允许当前登录用户编辑自己的话题
        // return $topic->user_id == $user->id;
        // 为了和下面相同的代码重用,增加可读性
        return $user->isAuthorOf($topic);
    }

    public function destroy(User $user, Topic $topic)
    {
        // 只允许当前登录用户删除自己的话题
        // return $user->id == $topic->user_id;
        // 为了和下面相同的代码重用,增加可读性
        return $user->isAuthorOf($topic);
    }
}
