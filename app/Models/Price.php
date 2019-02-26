<?php

namespace App\Models;

use App\Traits\HasCompositePrimaryKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Price
 * @package App\Models
 */
class Price extends Model
{
    use HasCompositePrimaryKey;
    /**
     * @var array
     */
    protected $primaryKey = ['shape', 'low_size', 'high_size', 'clarity', 'color'];
    /**
     * @var array
     */
    protected $fillable = [
        'shape',
        'clarity',
        'color',
        'low_size',
        'high_size',
        'price',
        'date',
    ];
    /**
     * @return mixed
     */
    public function getAll()
    {
        return Cache::rememberForever('prices.all', function (){
            return $this->all();
        });
    }
}
