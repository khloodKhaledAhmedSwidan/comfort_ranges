<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
class replay extends Notification
{
    use Queueable;
    protected $user;
    public function __construct($msg)
    {
//        $this->user = $msg;
    }
    public function via($notifiable)
    {
        return ['mail'];
    }
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('السلام عليكم ورحمة الله وبركاتة ')
//            ->line($this->user->name .'السلام عليكم ورحمة الله وبركاتة ')
            ->action($this->my_notification ."  ",null)
            ->line('شكرا لاستخدامكم تطبيق كشوف');
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
