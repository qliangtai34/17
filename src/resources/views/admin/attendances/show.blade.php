@extends('layouts.admin')

@section('content')
<h2>勤怠詳細（{{ $attendance->date }}）</h2>

<p>ユーザー：{{ $attendance->user->name }}</p>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST">
    @csrf

    {{-- 出勤 --}}
    <div class="mb-3">
        <label class="form-label">出勤</label>
        <input type="datetime-local" name="clock_in" class="form-control"
            value="{{ $attendance->clock_in ? $attendance->clock_in->format('Y-m-d\TH:i') : '' }}">
        @error('clock_in') 
            <div class="text-danger">{{ $message }}</div> 
        @enderror
    </div>

    {{-- 退勤 --}}
    <div class="mb-3">
        <label class="form-label">退勤</label>
        <input type="datetime-local" name="clock_out" class="form-control"
            value="{{ $attendance->clock_out ? $attendance->clock_out->format('Y-m-d\TH:i') : '' }}">
        @error('clock_out') 
            <div class="text-danger">{{ $message }}</div> 
        @enderror
    </div>

    {{-- 休憩開始 --}}
    <div class="mb-3">
        <label class="form-label">休憩開始</label>
        <input type="datetime-local" name="break_start" class="form-control"
            value="{{ $attendance->break_start ? $attendance->break_start->format('Y-m-d\TH:i') : '' }}">
        @error('break_start') 
            <div class="text-danger">{{ $message }}</div> 
        @enderror
    </div>

    {{-- 休憩終了 --}}
    <div class="mb-3">
        <label class="form-label">休憩終了</label>
        <input type="datetime-local" name="break_end" class="form-control"
            value="{{ $attendance->break_end ? $attendance->break_end->format('Y-m-d\TH:i') : '' }}">
        @error('break_end') 
            <div class="text-danger">{{ $message }}</div> 
        @enderror
    </div>

    {{-- 備考 --}}
    <div class="mb-3">
        <label class="form-label">備考</label>
        <textarea name="note" class="form-control" rows="3">{{ $attendance->note }}</textarea>
        @error('note') 
            <div class="text-danger">{{ $message }}</div> 
        @enderror
    </div>

    <button type="submit" class="btn btn-primary mt-3">修正する</button>
</form>
@endsection
