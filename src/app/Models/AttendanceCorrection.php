<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCorrection extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_id',
        'original_clock_in',
        'original_clock_out',
        'original_breaks',
        'original_note',
        'new_clock_in',
        'new_clock_out',
        'new_breaks',
        'new_note',
        'status',
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'original_clock_in' => 'datetime',
        'original_clock_out' => 'datetime',
        'new_clock_in'      => 'datetime',
        'new_clock_out'     => 'datetime',
        'original_breaks'   => 'array',
        'new_breaks'        => 'array',
    ];
}