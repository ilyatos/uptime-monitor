<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Response
 * @package App\Models
 *
 * @property int $id
 * @property int $service_id
 * @property int $response_size
 * @property float $response_time
 * @property bool $availability
 * @property int $reason_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Response extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'service_id',
        'response_size',
        'response_time',
        'availability',
        'reason_id'
    ];

    public function reason()
    {
        return $this->hasOne('App\Models\Reason', 'id', 'reason_id');
    }

}