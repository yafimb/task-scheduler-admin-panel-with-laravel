<?php

namespace App\Listeners;

use App\Events\TaskExecutedEvent;
use App\Notifications\TaskCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskExecutedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TaskExecutedEvent  $event
     * @return void
     */
    public function handle(TaskExecutedEvent $event)
    {
        if(file_exists(storage_path($event->outputFilePath)))
        {
            $output = file_get_contents(storage_path($event->outputFilePath));

            $event->task->results()->create([
                'result'    => $output,
                'duration'  => $event->elapsedTime * 1000,
            ]);

            unlink(storage_path($event->outputFilePath));
        }

        $event->task->notify(new TaskCompleted());
    }
}
