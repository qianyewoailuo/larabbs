<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    // 回复发布
	public function store(ReplyRequest $request,Reply $reply)
	{
        // $reply = Reply::create($request->all());
        // XXS注入警告
        $content = clean($request->content,'user_topic_body');
        if (empty($content)){
            return redirect()->back()->with('danger','请不要做出一些危险操作');
        }

        $reply->content = $content;
        $reply->user_id = Auth::id();
        $reply->topic_id = $request->topic_id;
        $reply->save();

		return redirect()->to($reply->topic->link())->with('success', '回复成功');
	}
    // 删除回复
	public function destroy(Reply $reply)
	{
		$this->authorize('destroy', $reply);
		$reply->delete();

		return redirect()->route('replies.index')->with('success', '回复删除成功');
	}
}