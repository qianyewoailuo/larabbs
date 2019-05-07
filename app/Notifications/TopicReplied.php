<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;
    // 用以存储reply模型实例的的属性
    public $reply;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // 开启自定义通知的频道,对应的方法是to+频道名
        return ['database', 'mail'];
        // 默认是邮件频道
        // return ['mail'];
    }

    public function toMail($notifiable)
    {
        // 跳转到$this->reply->id的锚点
        $url = $this->reply->topic->link(['#reply' . $this->reply->id]);

        return (new MailMessage)
            ->line('你的话题有了新回复！')
            ->action('查看回复', $url);
    }

    public function toDatabase($notifiable)
    {
        // 获取回复对应的话题实例
        $topic = $this->reply->topic;
        $link = $topic->link(['#reply' . $this->reply->id]);

        // 存入Notifications数据表里字段data的数据
        return [
            'reply_id'      =>  $this->reply->id,
            'reply_content' =>  $this->reply->content,
            'user_id'       =>  $this->reply->user->id,
            'user_name'     =>  $this->reply->user->name,
            'user_avatar'   =>  $this->reply->user->avatar,
            'topic_link'    =>  $link,
            'topic_id'      =>  $topic->id,
            'topic_title'   =>  $topic->title,
        ];
    }
}
