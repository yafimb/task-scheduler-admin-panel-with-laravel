<?php

namespace App\Models;

use Cron\CronExpression;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * Class Task
 * @package App
 */
class Task extends Model
{
    use Notifiable;
    /**
     * @var array
     */
    protected $fillable = [
        'command',
        'expression',
        'description',
        'dont_overlap',
        'run_in_maintenance',
        'notification_email',
    ];

    /**
     * @return String
     */
    public function getLastRunAttribute() : String
    {
        $last_run = 'N/A';

        if($last = $this->results()->orderBy('id', 'DESC')->first())
        {
            $last_run = $last->run_at->setTimezone('Asia/Jerusalem')->format('Y-m-d h:i A');
        }

        return $last_run;
    }

    /**
     * @return string
     */
    public function getAverageRuntimeAttribute()
    {
        return number_format($this->results()->avg('duration')/1000,2);
    }

    /**
     * @return string
     */
    public function getNextRunAttribute()
    {
        return CronExpression::factory($this->getCronExpression())->getNextRunDate('now',0,false,'Asia/Jerusalem')->format('Y-m-d h:i A');
    }

    /**
     * @return string
     */
    private function getCronExpression() : String
    {
        return $this->expression ?: '* * * * *';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * @return mixed
     */
    public function getActive()
    {
        return Cache::rememberForever('tasks.active', function (){
            return $this->getAll()->filter(function($task){
                return $task->is_active;
            });
        });
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return Cache::rememberForever('tasks.all', function (){
            return $this->all();
        });
    }

    /**
     * @return mixed
     */
    public function routeNotificationForMail()
    {
        return $this->notification_email;
    }

}
