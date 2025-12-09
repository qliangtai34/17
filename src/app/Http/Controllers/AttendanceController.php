<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
use App\Models\BreakTime;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => $today->toDateString()],
            ['status' => '勤務外']
        );

        return view('attendance.index', compact('attendance'));
    }

    // 出勤
    public function clockIn()
    {
        $attendance = $this->todayAttendance();

        if ($attendance->status !== '勤務外') {
            return back()->with('error', 'すでに出勤済みです');
        }

        $attendance->update([
            'clock_in' => Carbon::now(),
            'status'   => '出勤中',
        ]);

        return redirect()->route('attendance.list');

    }

    // 休憩入
    public function breakStart(Request $request)
{
    $attendance = Attendance::where('user_id', auth()->id())
        ->where('date', Carbon::today()->toDateString())
        ->firstOrFail();

    $breaks = $attendance->breaks ?? [];

    $breaks[] = [
        'break_start' => now()->toDateTimeString(),
        'break_end'   => null,
    ];

    // ★ 休憩中に変更
    $attendance->status = '休憩中';
    $attendance->breaks = $breaks;
    $attendance->save();

    return redirect()->route('attendance.index')
        ->with('message', '休憩を開始しました');
}



    // 休憩戻
    public function breakEnd(Request $request)
{
    $attendance = Attendance::where('user_id', auth()->id())
        ->where('date', Carbon::today()->toDateString())
        ->firstOrFail();

    $breaks = $attendance->breaks ?? [];

    // 最新の休憩を特定
    $lastIndex = count($breaks) - 1;

    if ($lastIndex >= 0 && $breaks[$lastIndex]['break_end'] === null) {
        $breaks[$lastIndex]['break_end'] = now()->toDateTimeString();
    }

    // ★ 出勤中へ戻す
    $attendance->status = '出勤中';
    $attendance->breaks = $breaks;
    $attendance->save();

    return redirect()->route('attendance.index')
        ->with('message', '休憩を終了しました');
}



    // 退勤
    public function clockOut()
    {
        $attendance = $this->todayAttendance();

        if ($attendance->status !== '出勤中') {
            return back()->with('error', '出勤中のみ退勤できます');
        }

        $attendance->update([
            'clock_out' => Carbon::now(),
            'status'    => '退勤済',
        ]);

        return redirect()->route('attendance.list')->with('message', 'お疲れ様でした。');
    }

    private function todayAttendance()
    {
        return Attendance::where('user_id', auth()->id())
            ->where('date', Carbon::today()->toDateString())
            ->firstOrFail();
    }



    public function list($year = null, $month = null)
    {
    // 初期表示：今月
    if (!$year || !$month) {
        $year = now()->year;
        $month = now()->month;
    }

    // 月初と月末
    $start = "{$year}-{$month}-01";
    $end   = date("Y-m-t", strtotime($start));  // 月末

    // ログインユーザーの勤怠データを月単位で取得
    $attendances = Attendance::where('user_id', auth()->id())
        ->whereBetween('date', [$start, $end])
        ->orderBy('date', 'asc')
        ->get();

    return view('attendance.list', [
        'attendances' => $attendances,
        'year' => $year,
        'month' => $month,
        'prevYear' => date("Y", strtotime("-1 month", strtotime($start))),
        'prevMonth' => date("m", strtotime("-1 month", strtotime($start))),
        'nextYear' => date("Y", strtotime("+1 month", strtotime($start))),
        'nextMonth' => date("m", strtotime("+1 month", strtotime($start))),
    ]);
    }

    public function detail($date)
    {
    $attendance = Attendance::where('user_id', auth()->id())
        ->where('date', $date)
        ->firstOrFail();

        // ⭐この日の自分の最新の修正申請を取得
    $correction = AttendanceCorrection::where('user_id', auth()->id())
        ->where('attendance_id', $attendance->id)
        ->latest()
        ->first();

    return view('attendance.detail', compact('attendance', 'correction'));
    }

    public function requestCorrection(Request $request, $id)
{
    $attendance = Attendance::where('id', $id)
        ->where('user_id', auth()->id())
        ->firstOrFail();

    AttendanceCorrection::create([
        'user_id' => auth()->id(),
        'attendance_id' => $attendance->id,

        'original_clock_in'  => $attendance->clock_in,
        'original_clock_out' => $attendance->clock_out,
        'original_breaks'    => json_encode($attendance->breaks),
        'original_note'      => $attendance->note,

        'new_clock_in'  => $request->new_clock_in,
        'new_clock_out' => $request->new_clock_out,
        'new_breaks'    => $request->new_breaks,
        'new_note'      => $request->new_note,

        'status' => 'pending',
    ]);

    return redirect()->route('attendance.list')
        ->with('message', '修正申請を送信しました（承認待ち）');
}

public function requestList(Request $request)
{
    // ?status=pending または ?status=approved
    $status = $request->query('status', 'pending'); // デフォルト pending

    $requests = AttendanceCorrection::with('user', 'attendance')
        ->where('status', $status)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    return view('attendance.requests', compact('requests', 'status'));
}


}