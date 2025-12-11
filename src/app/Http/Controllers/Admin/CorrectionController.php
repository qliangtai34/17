<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCorrection;
use App\Models\Attendance;
use Illuminate\Http\Request;

class CorrectionController extends Controller
{
    /**
     * 修正申請一覧
     */
    public function index(Request $request)
    {
        // status = pending / approved / rejected
        $status = $request->query('status', 'pending');

        $corrections = AttendanceCorrection::with(['user', 'attendance'])
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.corrections.index', compact('corrections', 'status'));
    }

    /**
     * 修正申請詳細
     */
    public function show($id)
    {
        $correction = AttendanceCorrection::with(['user', 'attendance'])
            ->findOrFail($id);

        return view('admin.corrections.show', compact('correction'));
    }

    /**
     * 承認処理
     */
    public function approve($id)
    {
        $correction = AttendanceCorrection::findOrFail($id);

        // すでに処理済みの場合は弾く
        if ($correction->status !== 'pending') {
            return back()->with('error', 'この申請はすでに処理済みです。');
        }

        $attendance = Attendance::findOrFail($correction->attendance_id);

        // 勤怠データ更新
        $attendance->update([
            'clock_in'  => $correction->new_clock_in,
            'clock_out' => $correction->new_clock_out,
            'breaks'    => $correction->new_breaks,
            'note'      => $correction->new_note,
        ]);

        // 修正申請ステータスを承認へ
        $correction->update([
            'status' => 'approved'
        ]);

        return redirect()->route('admin.corrections.index')
            ->with('message', '修正申請を承認しました。');
    }

    /**
     * 却下処理
     */
    public function reject($id)
    {
        $correction = AttendanceCorrection::findOrFail($id);

        if ($correction->status !== 'pending') {
            return back()->with('error', 'この申請はすでに処理済みです。');
        }

        $correction->update([
            'status' => 'rejected'
        ]);

        return redirect()->route('admin.corrections.index')
            ->with('message', '修正申請を却下しました。');
    }

    /**
 * 管理者が修正内容を編集する
 */
public function update(Request $request, $id)
{
    $correction = AttendanceCorrection::findOrFail($id);

    if ($correction->status !== 'pending') {
        return back()->with('error', '処理済みの申請は編集できません。');
    }

    $correction->update([
        'new_clock_in'  => $request->new_clock_in,
        'new_clock_out' => $request->new_clock_out,
        'new_breaks'    => $request->new_breaks,
        'new_note'      => $request->new_note,
    ]);

    return back()->with('message', '修正内容を更新しました。');
}

}
