<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理者ログイン</title>
</head>
<body>
    <h1>管理者ログイン</h1>

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div>
            <label>メールアドレス：</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>パスワード：</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">ログイン</button>
    </form>
</body>
</html>