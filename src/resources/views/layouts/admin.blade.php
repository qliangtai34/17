<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者画面</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 読み込み（必要なら） -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }
        .admin-header {
            background: #343a40;
            color: #fff;
            padding: 15px 20px;
        }
        .admin-header a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
        }
        .sidebar {
            width: 220px;
            background: #212529;
            color: #fff;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ddd;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #495057;
            color: #fff;
        }
        .content {
            margin-left: 240px;
            padding: 30px;
        }
    </style>

</head>
<body>

    <!-- ヘッダー -->
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <strong>管理者画面</strong>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.corrections.index') }}">修正申請一覧</a>
            <a href="{{ route('attendance.index') }}">一般画面へ戻る</a>
            <a href="#" 
   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    ログアウト
</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

        </div>
    </div>

    <!-- サイドバー -->
    <div class="sidebar">
        <a href="{{ route('admin.dashboard') }}">📊 ダッシュボード</a>
        <a href="{{ route('admin.corrections.index') }}">📝 修正申請一覧</a>
        <a href="{{ route('admin.attendances') }}">📅 勤怠一覧</a>
    </div>

    <!-- メインコンテンツ -->
    <div class="content">
        @yield('content')
    </div>

</body>
</html>