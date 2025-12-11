<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',   // ★ 追加
        'break_end',     // ★ 追加
        'note',
        'status',
    ];

    protected $casts = [
        'date'        => 'date',
        'clock_in'    => 'datetime',
        'clock_out'   => 'datetime',
        'break_start' => 'datetime',   // ★ 追加
        'break_end'   => 'datetime',   // ★ 追加
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function corrections()
    {
        return $this->hasMany(AttendanceCorrection::class);
    }
}
