<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskOverdue extends Notification implements ShouldQueue
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
                    ->subject('Задача просрочена!')
                    ->line('Внимание! Срок выполнения задачи истек: ' . $this->task->title)
                    ->line('Дата дедлайна: ' . $this->task->due_date->format('d.m.Y'))
                    ->action('Перейти к задаче', url('/api/tasks/' . $this->task->id));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
