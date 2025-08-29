<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AttendanceNonce extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'token',
        'expires_at',
        'used_at',
    ];

    // ✅ Cast datetime fields to Carbon automatically
    protected $casts = [
        'expires_at' => 'datetime',
        'used_at'    => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: generate a nonce
    public static function generateForUser($userId)
    {
        return self::create([
            'user_id'   => $userId,
            'token'     => Str::uuid()->toString(),
            'expires_at'=> Carbon::now()->addSeconds(60), // 60 sec validity
        ]);
    }

    // ✅ Helper: check validity (only once)
    public function isValid(): bool
    {
        return !$this->used_at && $this->expires_at->isFuture();
    }
}
