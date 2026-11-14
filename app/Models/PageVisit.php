<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['visited_on', 'visitor_hash', 'referrer_host', 'device', 'locale', 'path'];

    protected function casts(): array
    {
        return ['visited_on' => 'date'];
    }
}
