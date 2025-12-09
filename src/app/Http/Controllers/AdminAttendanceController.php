<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;

class AdminAttendanceController extends Controller
{
    public function index()
    {
        // 全ユーザーの勤怠を取得
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.attendances.index', compact('attendances'));
    }

    public function showUserMonthly($userId, $year, $month)
{
    $attendances = Attendance::where('user_id', $userId)
        ->whereYear('date', $year)
        ->whereMonth('date', $month)
        ->orderBy('date')
        ->get();

    $user = User::findOrFail($userId);

    return view('admin.attendances.monthly', compact(
        'attendances', 'user', 'year', 'month'
    ));
}

}