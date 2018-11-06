<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reason
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $reason
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Reason extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reason'
    ];
}
