<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShorturlUrl extends Model
{
    use HasFactory;

    protected $dates = [
        'expires_at',
    ];

    protected $perPage = 10;

    public function couldExpire(): bool
    {
        return $this->expires_at !== null;
    }

    public function hasExpired(): bool
    {
        if (!$this->couldExpire()) {
            return false;
        }

        $expiresAt = new Carbon($this->expires_at);

        return !$expiresAt->isFuture();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
