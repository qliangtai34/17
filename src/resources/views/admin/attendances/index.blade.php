@extends('layouts.app')

@section('content')
<div class="container">
    <h2>全ユーザー勤怠一覧</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ユーザー名</th>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>ステータス</th>
            </tr>
        </thead>

        <tbody>
        @foreach ($attendances as $att)
            <tr>
                <td>{{ $att->user->name }}</td>
                <td>{{ $att->date }}</td>
                <td>{{ $att->clock_in }}</td>
                <td>{{ $att->clock_out }}</td>
                <td>{{ $att->status }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection