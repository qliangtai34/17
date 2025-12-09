@extends('layouts.app')

@section('content')
<div class="container">

    <h2>修正申請一覧</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>対象日</th>
                <th>申請日時</th>
                <th>ステータス</th>
                <th>内容</th>
            </tr>
        </thead>
        <tbody>
            @forelse($corrections as $correction)
                <tr>
                    <td>{{ $correction->id }}</td>
                    <td>{{ $correction->attendance->date }}</td>
                    <td>{{ $correction->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($correction->status === 'pending')
                            <span class="badge bg-warning">承認待ち</span>
                        @elseif($correction->status === 'approved')
                            <span class="badge bg-success">承認済み</span>
                        @else
                            <span class="badge bg-danger">却下</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="collapse"
                            data-bs-target="#detail-{{ $correction->id }}">
                            詳細
                        </button>
                    </td>
                </tr>

                <tr class="collapse bg-light" id="detail-{{ $correction->id }}">
                    <td colspan="5">
                        <strong>元の出勤:</strong> {{ $correction->original_clock_in }}<br>
                        <strong>元の退勤:</strong> {{ $correction->original_clock_out }}<br>
                        <strong>元の休憩:</strong> {{ $correction->original_breaks }}<br>
                        <strong>元の備考:</strong> {{ $correction->original_note }}<br>
                        <hr>
                        <strong>変更後の出勤:</strong> {{ $correction->new_clock_in }}<br>
                        <strong>変更後の退勤:</strong> {{ $correction->new_clock_out }}<br>
                        <strong>変更後の休憩:</strong> {{ $correction->new_breaks }}<br>
                        <strong>変更後の備考:</strong> {{ $correction->new_note }}
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5">現在申請はありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection