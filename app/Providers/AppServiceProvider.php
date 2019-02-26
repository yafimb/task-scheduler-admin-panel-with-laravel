<?php

namespace App\Providers;

use App\Models\Task;
use App\Observers\TaskOserver;
use App\Events\TaskExecutedEvent;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Task::observe(TaskOserver::class);

        $this->app->resolving(Schedule::class, function ($schedule){
            $this->schedule($schedule);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Passport::ignoreMigrations();
    }

    /**
     * @param $schedule
     */
    private function schedule(Schedule $schedule)
    {
        /**
         * Fetch all active tasks
         */
        $tasks = app('App\Models\Task')->getActive();

        /**
         * Schedule the task
         */
        foreach ($tasks as $task)
        {
            $event          = $schedule->exec($task->command);
            $outputFilePath = 'task-'.sha1($task->command.$task->expression);

            $event
                ->cron($task->expression)
                ->before(function() use ($event, $task){

                    $event->start = microtime(true);

                })
                ->after(function () use ($event, $task, $outputFilePath){

                    $elapsedTime    = microtime(true) - $event->start;
                    event(new TaskExecutedEvent($task, $elapsedTime, $outputFilePath));
                })
                ->sendOutPutTo(storage_path($outputFilePath));

            if($task->dont_overlap)
            {
                $event->withoutOverlapping();
            }

            if($task->run_in_maintenance)
            {
                $event->evenInMaintenanceMode();
            }
        }
    }

}
