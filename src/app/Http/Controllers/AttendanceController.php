<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceCorrection;
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

    // 休憩開始
    public function breakStart()
    {
        $attendance = $this->todayAttendance();

        if (!is_null($attendance->break_start)) {
            return back()->with('error', 'すでに休憩開始済みです');
        }

        $attendance->update([
            'break_start' => now(),
            'status'      => '休憩中',
        ]);

        return redirect()->route('attendance.index')
            ->with('message', '休憩を開始しました');
    }

    // 休憩終了
    public function breakEnd()
    {
        $attendance = $this->todayAttendance();

        if (is_null($attendance->break_start)) {
            return back()->with('error', '休憩開始していません');
        }
        if (!is_null($attendance->break_end)) {
            return back()->with('error', '休憩はすでに終了しています');
        }

        $attendance->update([
            'break_end' => now(),
            'status'    => '出勤中',
        ]);

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

    // 勤怠一覧
    public function list($year = null, $month = null)
    {
        if (!$year || !$month) {
            $year = now()->year;
            $month = now()->month;
        }

        $start = "{$year}-{$month}-01";
        $end   = date("Y-m-t", strtotime($start));

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

    // 詳細
    public function detail($date)
    {
        $attendance = Attendance::where('user_id', auth()->id())
            ->where('date', $date)
            ->firstOrFail();

        $correction = AttendanceCorrection::where('user_id', auth()->id())
            ->where('attendance_id', $attendance->id)
            ->latest()
            ->first();

        return view('attendance.detail', compact('attendance', 'correction'));
    }

    // 修正申請
    public function requestCorrection(Request $request, $id)
    {
        $attendance = Attendance::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        AttendanceCorrection::create([
            'user_id'        => auth()->id(),
            'attendance_id'  => $attendance->id,

            'original_clock_in'  => $attendance->clock_in,
            'original_clock_out' => $attendance->clock_out,
            'original_break_start' => $attendance->break_start,
            'original_break_end'   => $attendance->break_end,
            'original_note'        => $attendance->note,

            'new_clock_in'     => $request->new_clock_in,
            'new_clock_out'    => $request->new_clock_out,
            'new_break_start'  => $request->new_break_start,
            'new_break_end'    => $request->new_break_end,
            'new_note'         => $request->new_note,

            'status' => 'pending',
        ]);

        return redirect()->route('attendance.list')
            ->with('message', '修正申請を送信しました（承認待ち）');
    }

    // 修正申請一覧（管理者側）
    public function requestList(Request $request)
    {
        $status = $request->query('status', 'pending');

        $requests = AttendanceCorrection::with('user', 'attendance')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('attendance.requests', compact('requests', 'status'));
    }

    public function update(Request $request, $id)
{
    $attendance = Attendance::where('user_id', auth()->id())
                            ->findOrFail($id);

    $attendance->update([
        'date'        => $request->date,
        'clock_in'    => $request->clock_in,
        'clock_out'   => $request->clock_out,
        'break_start' => $request->break_start,
        'break_end'   => $request->break_end,
        'break_total' => $request->break_total,
        'note'        => $request->note,
        'status'      => $request->status,
    ]);

    return redirect()->back()->with('success', '更新しました');
}

}
