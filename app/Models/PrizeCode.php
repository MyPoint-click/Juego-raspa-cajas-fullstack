<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrizeCode extends Model
{
    protected $fillable = [
        'campaign_id',
        'code',
        'status',
        'expires_at',
        'viewed_at',
        'redeemed_at',
        'session_id',
        'session_expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'viewed_at' => 'datetime',
        'redeemed_at' => 'datetime',
        'session_expires_at' => 'datetime',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function isUsed()
    {
        return $this->status === 'used';
    }

    public function markAsUsed()
    {
        $this->update(['status' => 'used']);
    }
}
