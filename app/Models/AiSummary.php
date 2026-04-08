<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiSummary extends Model
{
    protected $fillable = [
        'page_type',
        'identifier',
        'summary',
    ];
}
