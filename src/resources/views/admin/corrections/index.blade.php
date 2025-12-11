@extends('layouts.admin')

@section('content')
<div class="container">

    <h2 class="mb-3">修正申請一覧（管理者）</h2>

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

        <a href="{{ route('admin.corrections.index', ['status' => 'rejected']) }}"
           class="btn {{ $status === 'rejected' ? 'btn-primary' : 'btn-outline-secondary' }}">
            却下済み
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
                <td>{{ optional($correction->user)->name ?? '—' }}</td>

                <td>
                    {{ optional($correction->attendance)->date
                        ? \Carbon\Carbon::parse($correction->attendance->date)->format('Y-m-d')
                        : '—' }}
                </td>

                <td>
                    出勤: {{ $correction->new_clock_in  ?? '—' }}<br>
                    退勤: {{ $correction->new_clock_out ?? '—' }}<br>
                    休憩: {{ $correction->new_breaks     ?? '—' }}<br>
                    備考: {{ $correction->new_note       ?? '—' }}
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
