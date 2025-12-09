@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>{{ $user->name }} さんの {{ $year }}年{{ $month }}月 の勤怠</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩開始</th>
                <th>休憩終了</th>
                <th>合計勤務時間</th>
            </tr>
        </thead>

        <tbody>
        @forelse($attendances as $atd)
            <tr>
                <td>{{ $atd->date }}</td>
                <td>{{ optional($atd->clock_in)->format('H:i') }}</td>
                <td>{{ optional($atd->clock_out)->format('H:i') }}</td>
                <td>{{ optional($atd->break_start)->format('H:i') }}</td>
                <td>{{ optional($atd->break_end)->format('H:i') }}</td>
                <td>
                    {{ $atd->total_work_hours ?? '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">データがありません</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <a href="{{ route('admin.attendances') }}" class="btn btn-secondary">戻る</a>
</div>
@endsection
