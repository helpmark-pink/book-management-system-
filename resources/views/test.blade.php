<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>テストページ</title>
    <style>
        body {
            font-family: sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>✅ テストページ</h1>
        <p class="success">サーバーは正常に動作しています！</p>

        <h2>システム情報</h2>
        <ul>
            <li>Laravel バージョン: {{ app()->version() }}</li>
            <li>PHP バージョン: {{ PHP_VERSION }}</li>
            <li>環境: {{ config('app.env') }}</li>
            <li>現在時刻: {{ now()->format('Y-m-d H:i:s') }}</li>
        </ul>

        <h2>データベース接続</h2>
        <p>
            @php
                try {
                    DB::connection()->getPdo();
                    echo '<span style="color: green;">✓ データベース接続: 成功</span>';
                } catch (\Exception $e) {
                    echo '<span style="color: red;">✗ データベース接続: 失敗 - ' . $e->getMessage() . '</span>';
                }
            @endphp
        </p>

        <h2>リンク</h2>
        <p>
            <a href="/" style="color: blue; text-decoration: underline;">ホームページに戻る</a>
        </p>
    </div>
</body>
</html>
