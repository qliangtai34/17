@extends('layouts.admin')

@section('content')
<div class="container">

    <h2>修正申請（管理者）</h2>

    {{-- タブ切り替え --}}
    <div class="mb-3">

        <a href="{{ route('admin.attendance.requests', ['status' => 'pending']) }}"
           class="btn {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
            承認待ち
        </a>

        <a href="{{ route('admin.attendance.requests', ['status' => 'approved']) }}"
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
                <th>状態</th>
                <th>申請日</th>
                <th>操作</th>
            </tr>
        </thead>

        <tbody>
        @forelse($requests as $req)
            <tr>

                <td>{{ $req->user->name }}</td>
                <td>{{ $req->attendance->date }}</td>

                <td>
                    出勤：{{ $req->new_clock_in ? $req->new_clock_in->format('Y-m-d H:i') : '—' }}<br>
                    退勤：{{ $req->new_clock_out ? $req->new_clock_out->format('Y-m-d H:i') : '—' }}<br>
                    備考：{{ $req->new_note ?? '—' }}
                </td>

                <td>
                    @if($req->status === 'pending')
                        <span class="badge bg-warning">承認待ち</span>
                    @else
                        <span class="badge bg-success">承認済み</span>
                    @endif
                </td>

                <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>

                <td>
                @if ($req->status === 'pending')
                    <a href="{{ route('admin.attendance.approve', $req->id) }}"
                       class="btn btn-success btn-sm">承認</a>
                @endif
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">申請はありません</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{ $requests->links() }}

</div>
@endsection