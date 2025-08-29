<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;   // 👈 add

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;    // 👈 add HasApiTokens (keep other traits you already had)

    protected $fillable = ['name','email','password'];
    protected $hidden = ['password','remember_token'];

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function attendanceNonces()
    {
        return $this->hasMany(AttendanceNonce::class);
    }

}
