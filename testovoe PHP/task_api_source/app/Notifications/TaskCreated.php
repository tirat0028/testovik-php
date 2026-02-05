<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Новая задача создана')
                    ->line('В вашем списке появилась новая задача: ' . $this->task->title)
                    ->action('Просмотреть задачу', url('/api/tasks/' . $this->task->id));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
