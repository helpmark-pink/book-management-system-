# 本番環境デプロイメントガイド

## ⚠️ 重要: 本番環境で必須の対応事項

現在の開発環境設定のまま本番環境にデプロイすると**セキュリティリスク**があります。
以下の4つの重大な問題を必ず対応してください。

### 🔴 重大な問題1: デバッグモードが有効

**現在の設定（開発環境）:**
```env
APP_DEBUG=true
```

**問題点:**
- エラー画面でデータベース接続情報、APIキー、ファイルパス等の機密情報が表示される
- 攻撃者にシステム構成が漏洩する重大なセキュリティリスク

**本番環境での設定:**
```env
APP_ENV=production
APP_DEBUG=false  # 🔴 絶対にfalseに！
APP_URL=https://your-actual-domain.com  # 実際のドメインに変更
```

### 🔴 重大な問題2: SQLiteデータベースの使用

**現在の設定（開発環境）:**
```env
DB_CONNECTION=sqlite
```

**問題点:**
- SQLiteは単一ファイルベースのため、複数ユーザーの同時アクセスに弱い
- バックアップやスケーリングが困難
- パフォーマンスが低い

**本番環境での設定（MySQL）:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=reading_management_db
DB_USERNAME=reading_user
DB_PASSWORD=strong_password_here  # 20文字以上の強固なパスワード
```

**データベース作成コマンド:**
```sql
CREATE DATABASE reading_management_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'reading_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON reading_management_db.* TO 'reading_user'@'localhost';
FLUSH PRIVILEGES;
```

### 🔴 重大な問題3: HTTPS未対応

**現在の設定（開発環境）:**
```env
SESSION_SECURE_COOKIE=false
```

**問題点:**
- HTTPで通信するとセッションCookieが平文で送信される
- セッションハイジャック（なりすまし）のリスク
- 中間者攻撃（MITM）に脆弱

**本番環境での設定:**
```env
SESSION_SECURE_COOKIE=true  # 🔴 HTTPS環境では必ずtrue
SESSION_DRIVER=database  # 複数サーバー対応
SESSION_LIFETIME=10080  # 7日間（開発時の525600分は長すぎる）
```

### 🔴 重大な問題4: ログレベルとセッション有効期限

**現在の設定（開発環境）:**
```env
LOG_LEVEL=debug
SESSION_LIFETIME=525600  # 1年間
```

**問題点:**
- debugログは詳細すぎてディスク容量を圧迫
- 1年間のセッション有効期限はセキュリティリスク

**本番環境での設定:**
```env
LOG_LEVEL=error  # errorまたはwarningを推奨
SESSION_LIFETIME=10080  # 7日間（分単位）
```

---

## 実装済みのセキュリティ機能

### 1. レート制限（ブルートフォース攻撃対策）
- **ログイン**: 5分間に5回まで試行可能
- **新規登録**: 1時間に3回まで試行可能
- IPアドレスとユーザー名でトラッキング

### 2. セキュリティヘッダー
以下のセキュリティヘッダーを自動的に追加：
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: SAMEORIGIN`
- `X-XSS-Protection: 1; mode=block`
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Permissions-Policy: geolocation=(), microphone=(), camera=()`
- `Strict-Transport-Security` (本番環境のみ)

### 3. HTTPS強制
本番環境では自動的にHTTPSにリダイレクトされます。

### 4. セッションセキュリティ
- セッション暗号化
- Secure Cookie（HTTPS使用時）
- HttpOnly Cookie
- SameSite Cookie設定

### 5. CSRF保護
Laravelのデフォルト機能により全フォームで有効。

### 6. エラーハンドリング
本番環境ではエラー詳細を非表示にし、ログに記録。

## デプロイ前のチェックリスト

### 必須設定

1. **環境変数の設定**
   ```bash
   # .env.production.exampleをコピーして.envを作成
   cp .env.production.example .env
   ```

2. **.envファイルの編集**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   # アプリケーションキーの生成
   php artisan key:generate

   # データベース設定（本番用）
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_secure_password

   # セッション設定
   SESSION_ENCRYPT=true
   SESSION_SECURE_COOKIE=true
   SESSION_DOMAIN=yourdomain.com
   ```

3. **データベースのマイグレーション**
   ```bash
   php artisan migrate --force
   ```

4. **キャッシュの最適化**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **アセットのビルド**
   ```bash
   npm install
   npm run build
   ```

### Webサーバー設定

#### Nginxの例
```nginx
server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    root /path/to/Book-Management-System/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # セキュリティヘッダー（アプリケーションでも設定されていますが念のため）
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
}

# HTTPからHTTPSへのリダイレクト
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

### ファイルパーミッション
```bash
# ストレージとキャッシュディレクトリの書き込み権限
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## デプロイ後の確認事項

### セキュリティチェック

1. **HTTPS動作確認**
   - HTTPでアクセスしてHTTPSにリダイレクトされることを確認
   - SSL証明書が有効であることを確認

2. **セキュリティヘッダー確認**
   ```bash
   curl -I https://yourdomain.com
   ```
   必要なヘッダーが含まれているか確認

3. **レート制限テスト**
   - ログインを5回失敗させてブロックされることを確認
   - 待機時間後に再試行できることを確認

4. **エラーページ確認**
   - 存在しないページにアクセスして、詳細なエラーが表示されないことを確認

5. **セッション確認**
   - ログイン/ログアウトが正常に動作することを確認
   - Cookieがsecure属性を持っていることを確認

### パフォーマンスチェック

1. **キャッシュ確認**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Opcache有効化**
   php.iniで以下を確認：
   ```ini
   opcache.enable=1
   opcache.memory_consumption=128
   opcache.interned_strings_buffer=8
   opcache.max_accelerated_files=4000
   opcache.revalidate_freq=60
   ```

## 運用・メンテナンス

### ログの確認
```bash
tail -f storage/logs/laravel.log
```

### キャッシュのクリア（設定変更後）
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### データベースバックアップ
定期的にバックアップを取得してください：
```bash
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

## トラブルシューティング

### 500エラーが発生する場合
1. ストレージディレクトリの権限を確認
2. .envファイルの設定を確認
3. `php artisan config:clear`を実行
4. storage/logs/laravel.logでエラー詳細を確認

### セッションが保持されない場合
1. SESSION_DOMAIN設定を確認
2. SESSION_SECURE_COOKIEがHTTPS環境と一致しているか確認
3. データベースのsessionsテーブルが存在するか確認

### レート制限が動作しない場合
1. キャッシュドライバーが正しく設定されているか確認
2. Redisまたはデータベースキャッシュが利用可能か確認

## 追加推奨事項

### セキュリティ強化（オプション）
1. **パスワード最小文字数の再設定**
   - 現在は制限なしですが、セキュリティ上は最低8文字を推奨
   - `RegisterController.php`と`RegisterRequest.php`で`'min:8'`を追加

2. **2要素認証（2FA）の実装**
   - Laravel Fortifyまたはカスタム実装

3. **IPホワイトリスト**
   - 管理者機能がある場合、特定IPからのみアクセス可能に

4. **定期的なセキュリティアップデート**
   ```bash
   composer update
   npm update
   ```

5. **監視サービスの導入**
   - Sentry（エラー監視）
   - New Relic（パフォーマンス監視）
   - Uptime Robot（稼働監視）

### パフォーマンス最適化
1. **CDN使用**
   - 静的ファイル（CSS、JS、画像）をCDN経由で配信

2. **Redis使用**
   - セッションとキャッシュにRedisを使用

3. **データベースインデックス**
   - 頻繁に検索されるカラムにインデックスを追加

4. **画像最適化**
   - 書籍カバー画像の最適化・リサイズ

## サポート

問題が発生した場合は、以下を確認してください：
- Laravel公式ドキュメント: https://laravel.com/docs
- ログファイル: storage/logs/laravel.log
- Webサーバーログ（Nginx/Apache）
