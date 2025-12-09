@extends('layouts.admin')

@section('content')
<h2>修正申請 詳細</h2>

<h4>申請者：{{ $correction->user->name }}</h4>
<h4>日付：{{ $correction->attendance->date }}</h4>

<table class="table">
    <tr>
        <th>項目</th>
        <th>元の値</th>
        <th>修正後</th>
    </tr>

    <tr>
        <td>出勤</td>
        <td>{{ $correction->original_clock_in }}</td>
        <td>{{ $correction->new_clock_in }}</td>
    </tr>

    <tr>
        <td>退勤</td>
        <td>{{ $correction->original_clock_out }}</td>
        <td>{{ $correction->new_clock_out }}</td>
    </tr>

    <tr>
        <td>休憩</td>
        <td>{{ $correction->original_breaks }}</td>
        <td>{{ $correction->new_breaks }}</td>
    </tr>

    <tr>
        <td>備考</td>
        <td>{{ $correction->original_note }}</td>
        <td>{{ $correction->new_note }}</td>
    </tr>
</table>

<form action="{{ route('admin.corrections.approve', $correction->id) }}" method="POST">
    @csrf
    <button class="btn btn-success">承認</button>
</form>

<form action="{{ route('admin.corrections.reject', $correction->id) }}" method="POST" class="mt-2">
    @csrf
    <button class="btn btn-danger">却下</button>
</form>

@endsection