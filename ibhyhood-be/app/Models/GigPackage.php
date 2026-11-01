<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GigPackage extends Model
{
    protected $guarded = [];

    public function gig(): BelongsTo
    {
        return $this->belongsTo(Gig::class);
    }
}
