<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Result
 * @package App
 */
class Result extends Model
{
    /**
     * @var string
     */
    protected $table = 'task_results';
    /**
     * @var array
     */
    protected $fillable = [
        'result',
        'duration',
    ];

    protected $dates = [
        'run_at',
    ];
}
