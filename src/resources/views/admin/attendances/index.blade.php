@extends('layouts.admin')

@section('content')
<h2>日次勤怠一覧（{{ $targetDate->format('Y-m-d') }}）</h2>

<div class="mb-3">
    <a href="{{ route('admin.attendances.date', $prevDate) }}">← 前日</a>
    |
    <a href="{{ route('admin.attendances.date', $nextDate) }}">翌日 →</a>
</div>

<table border="1" cellpadding="6">
    <tr>
        <th>氏名</th>
        <th>出勤</th>
        <th>退勤</th>
        <th>休憩開始</th>
        <th>休憩終了</th>
        <th>備考</th>
        <th>詳細</th>
    </tr>

    @foreach($attendances as $at)
    <tr>
        <td>{{ $at->user->name }}</td>
        <td>{{ $at->clock_in ?? '' }}</td>
        <td>{{ $at->clock_out ?? '' }}</td>
        <td>{{ $at->break_start ?? '' }}</td>
        <td>{{ $at->break_end ?? '' }}</td>
        <td>{{ $at->note ?? '' }}</td>
        <td>
            <a href="{{ route('admin.attendance.detail', $at->id) }}">
                詳細
            </a>
        </td>
    </tr>
    @endforeach
</table>
@endsection
