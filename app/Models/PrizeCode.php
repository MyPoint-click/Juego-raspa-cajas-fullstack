<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrizeCode extends Model
{
    protected $fillable = [
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

    public function isUsed()
    {
        return $this->status === 'used';
    }

    public function markAsUsed()
    {
        $this->update(['status' => 'used']);
    }
}
