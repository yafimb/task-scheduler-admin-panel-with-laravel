<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TaskExecutedEvent
{
    /**
     *
     */
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var Task
     */
    public $task;
    /**
     * @var
     */
    public $elapsedTime;
    /**
     * @var
     */
    public $outputFilePath;

    /**
     * Create a new event instance.
     *
     * TaskExecutedEvent constructor.
     * @param Task $task
     * @param $elapsedTime
     * @param $outputFilePath
     */
    public function __construct(Task $task, $elapsedTime, $outputFilePath)
    {
        $this->task             = $task;
        $this->elapsed_time     = $elapsedTime;
        $this->outputFilePath   = $outputFilePath;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
