<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrection;
use App\Models\Attendance;
use Illuminate\Http\Request;

class CorrectionController extends Controller
{
    // 修正申請一覧
    public function index(Request $request)
{
    // status=pending または approved。デフォルトは pending
    $status = $request->query('status', 'pending');

    $corrections = \App\Models\AttendanceCorrection::with('user', 'attendance')
        ->where('status', $status)
        ->orderBy('created_at', 'desc')
        ->paginate(20);

    return view('admin.corrections.index', compact('corrections', 'status'));
}


    // 修正申請詳細
    public function show($id)
    {
        $correction = AttendanceCorrection::with(['user', 'attendance'])
            ->findOrFail($id);

        return view('admin.corrections.show', compact('correction'));
    }

    // 承認処理
    public function approve($id)
    {
        $correction = AttendanceCorrection::findOrFail($id);

        $attendance = Attendance::findOrFail($correction->attendance_id);

        // 修正内容を勤怠データに反映
        $attendance->clock_in  = $correction->new_clock_in;
        $attendance->clock_out = $correction->new_clock_out;
        $attendance->breaks    = $correction->new_breaks;
        $attendance->note      = $correction->new_note;
        $attendance->save();

        // 修正申請ステータス更新
        $correction->status = 'approved';
        $correction->save();

        return redirect()->route('admin.corrections.index')
            ->with('message', '修正申請を承認しました。');
    }

    // 却下処理
    public function reject($id)
    {
        $correction = AttendanceCorrection::findOrFail($id);
        $correction->status = 'rejected';
        $correction->save();

        return redirect()->route('admin.corrections.index')
            ->with('message', '修正申請を却下しました。');
    }
}