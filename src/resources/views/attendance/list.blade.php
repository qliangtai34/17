@extends('layouts.app')

@section('title', '勤怠一覧')

@section('content')

<h1>勤怠一覧 ({{ $year }}年 {{ $month }}月)</h1>

<div style="margin-bottom: 20px;">
    <a href="{{ route('attendance.list.month', ['year' => $prevYear, 'month' => $prevMonth]) }}">← 前月</a>
    |
    <a href="{{ route('attendance.list.month', ['year' => $nextYear, 'month' => $nextMonth]) }}">翌月 →</a>
</div>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>日付</th>
            <th>出勤時刻</th>
            <th>退勤時刻</th>
            <th>休憩回数</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($attendances as $attendance)
        <tr>
            <td>{{ $attendance->date }}</td>
            <td>{{ $attendance->clock_in ?? '' }}</td>
            <td>{{ $attendance->clock_out ?? '' }}</td>

            {{-- 修正： break は配列なので count($attendance->breaks ?? []) --}}
            <td>{{ is_array($attendance->breaks) ? count($attendance->breaks) : 0 }}</td>

            <td>
                <a href="{{ route('attendance.detail', ['date' => $attendance->date]) }}">詳細</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection