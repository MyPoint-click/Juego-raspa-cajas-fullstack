<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'is_current',
        'starts_at',
        'ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_current' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function prizeCodes(): HasMany
    {
        return $this->hasMany(PrizeCode::class);
    }
}
