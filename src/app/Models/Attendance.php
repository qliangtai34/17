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
        'breaks',   // ← 忘れず追加
        'note',
        'status',
    ];

    /**
     * キャスト設定
     */
    protected $casts = [
        'date'        => 'date',
        'clock_in'    => 'datetime',
        'clock_out'   => 'datetime',
        'breaks'      => 'array',   // ← JSON を配列として扱う
    ];

    /**
     * ユーザーとのリレーション
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function corrections()
{
    return $this->hasMany(AttendanceCorrection::class);
}

}