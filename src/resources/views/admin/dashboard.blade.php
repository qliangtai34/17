@extends('layouts.app')

@section('content')
<div class="container">
    <h1>管理者ダッシュボード</h1>

    <a href="{{ route('admin.attendances') }}" class="btn btn-primary mt-3">
        全ユーザー勤怠一覧へ
    </a>
</div>
@endsection