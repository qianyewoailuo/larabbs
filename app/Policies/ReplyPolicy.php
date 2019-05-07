<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{

    public function destroy(User $user, Reply $reply)
    {
        // 回复删除策略授权 >>> 仅当回复用户为当前用户或 当前话题作者为当前用户时 可删除
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
