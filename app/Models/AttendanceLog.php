<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'work_date',
        'clock_in_at',
        'clock_in_photo',
        'clock_out_at',
        'clock_out_photo',
        'ip_address',
        'device_info',
        'location',
    ];

    protected $dates = [
        'clock_in_at',
        'clock_out_at',
        'work_date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
