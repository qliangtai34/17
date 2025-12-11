<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAttendanceController extends Controller
{
    public function index($date = null)
{
    // 初期表示は今日
    $targetDate = $date ? Carbon::parse($date) : Carbon::today();

    // targetDate の勤怠のみ取得
    $attendances = Attendance::with('user')
        ->whereDate('date', $targetDate)
        ->orderBy('user_id')
        ->get();

    // 前日/翌日
    $prevDate = $targetDate->copy()->subDay()->format('Y-m-d');
    $nextDate = $targetDate->copy()->addDay()->format('Y-m-d');

    return view('admin.attendances.index', compact(
        'attendances', 'targetDate', 'prevDate', 'nextDate'
    ));
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


public function show($id)
{
    $attendance = Attendance::with('user')->findOrFail($id);

    return view('admin.attendances.show', compact('attendance'));
}

public function update(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);

    

    // 修正実行（FN040）
    $attendance->update([
        'clock_in' => $request->clock_in,
        'clock_out' => $request->clock_out,
        'break_start' => $request->break_start,
        'break_end' => $request->break_end,
        'note' => $request->note,
    ]);

    return redirect()->back()->with('success', '勤怠を修正しました。');
}

}