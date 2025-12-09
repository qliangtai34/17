@extends('layouts.app')

@section('content')
<div class="container">

    <h2>勤怠詳細（{{ $attendance->date }}）</h2>

    <table class="table table-bordered">
        <tr>
            <th>出勤</th>
            <td>{{ $attendance->clock_in }}</td>
        </tr>
        <tr>
            <th>退勤</th>
            <td>{{ $attendance->clock_out }}</td>
        </tr>

        {{-- 休憩一覧 --}}
        <tr>
    <th>休憩</th>
    <td>
        @php
            // null の場合は空配列に変換
            $breaks = $attendance->breaks ?? [];
        @endphp

        @forelse ($breaks as $break)
            {{ $break['break_start'] }} 〜 {{ $break['break_end'] ?? '' }}<br>
        @empty
            —
        @endforelse
    </td>
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

        {{-- ✔ 修正可能な場合のみフォームを表示 --}}
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
                <label>休憩（修正後）</label>
                <textarea name="new_breaks" class="form-control" rows="3"
                    placeholder="09:00-09:10 のように入力"></textarea>
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