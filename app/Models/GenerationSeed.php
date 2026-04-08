<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerationSeed extends Model
{
    protected $fillable = [
        'system',
        'category',
        'slug',
        'scenario',
        'status',
        'last_error',
    ];
}
