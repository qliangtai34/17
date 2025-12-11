@extends('layouts.app')

@section('content')
<div class="container">

    <h2>勤怠詳細（{{ $attendance->date }}）</h2>

    <table class="table table-bordered">
        <tr>
            <th>出勤</th>
            <td>{{ $attendance->clock_in ?? '—' }}</td>
        </tr>

        <tr>
            <th>退勤</th>
            <td>{{ $attendance->clock_out ?? '—' }}</td>
        </tr>

        <tr>
            <th>休憩開始</th>
            <td>{{ $attendance->break_start ?? '—' }}</td>
        </tr>

        <tr>
            <th>休憩終了</th>
            <td>{{ $attendance->break_end ?? '—' }}</td>
        </tr>

        <tr>
            <th>備考</th>
            <td>{{ $attendance->note ?? '—' }}</td>
        </tr>
    </table>

    <h3>修正申請</h3>

    {{-- ⭐ 承認待ちなら編集不可 --}}
    @if ($correction && $correction->status === 'pending')
        <div class="alert alert-warning">
            承認待ちのため修正はできません。
        </div>
    @else

        {{-- ✔ 修正可能な場合のみフォーム表示 --}}
        <form action="{{ route('attendance.requestCorrection', $attendance->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>出勤（修正後）</label>
                <input type="datetime-local" name="new_clock_in" class="form-control">
            </div>

            <div class="mb-3">
                <label>退勤（修正後）</label>
                <input type="datetime-local" name="new_clock_out" class="form-control">
            </div>

            <div class="mb-3">
                <label>休憩開始（修正後）</label>
                <input type="datetime-local" name="new_break_start" class="form-control">
            </div>

            <div class="mb-3">
                <label>休憩終了（修正後）</label>
                <input type="datetime-local" name="new_break_end" class="form-control">
            </div>

            <div class="mb-3">
                <label>備考（修正後）</label>
                <textarea name="new_note" class="form-control"></textarea>
            </div>

            <button class="btn btn-primary">修正申請</button>
        </form>

    @endif
</div>
@endsection
