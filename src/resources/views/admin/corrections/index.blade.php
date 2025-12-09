@extends('layouts.admin')

@section('content')
<div class="container">

    <h2>修正申請一覧（管理者）</h2>

    {{-- タブ切り替え --}}
    <div class="mb-3">
        <a href="{{ route('admin.corrections.index', ['status' => 'pending']) }}"
           class="btn {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
            承認待ち
        </a>

        <a href="{{ route('admin.corrections.index', ['status' => 'approved']) }}"
           class="btn {{ $status === 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">
            承認済み
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>申請者</th>
                <th>日付</th>
                <th>内容</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>

        @forelse($corrections as $correction)
            <tr>
                <td>{{ $correction->user->name }}</td>
                <td>{{ $correction->attendance->date }}</td>
                <td>
                    出勤: {{ $correction->new_clock_in ?? '—' }}<br>
                    退勤: {{ $correction->new_clock_out ?? '—' }}<br>
                    備考: {{ $correction->new_note ?? '—' }}
                </td>
                <td>
                    <a href="{{ route('admin.corrections.show', $correction->id) }}"
                       class="btn btn-sm btn-info">
                        詳細
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">申請がありません</td>
            </tr>
        @endforelse

        </tbody>
    </table>

    {{ $corrections->links() }}

</div>
@endsection