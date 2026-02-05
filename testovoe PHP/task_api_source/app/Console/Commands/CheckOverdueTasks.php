<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskOverdue;
use Illuminate\Console\Command;

class CheckOverdueTasks extends Command
{
    protected $signature = 'tasks:check-overdue';

    protected $description = 'Check for overdue tasks and notify users';

    public function handle()
    {
        // Use startOfDay to be safe with date comparisons if due_date is just Y-m-d
        $tasks = Task::where('due_date', '<', now()->startOfDay())
                     ->where('status', '!=', 'completed')
                     ->with('user')
                     ->chunk(100, function ($tasks) {
                         foreach ($tasks as $task) {
                             $this->info("Sending notification for task: {$task->id}");
                             try {
                                 $task->user->notify(new TaskOverdue($task));
                             } catch (\Exception $e) {
                                 $this->error("Failed to notify user {$task->user->id}: " . $e->getMessage());
                             }
                         }
                     });

        $this->info("Check completed.");
    }
}
