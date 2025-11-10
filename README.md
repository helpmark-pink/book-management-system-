# 📚 読書記録システム (Book Management System)

Laravel 12とVue.js 3で構築された、書籍管理・読書記録システムです。Google Books APIと連携し、書籍の検索・登録、読書進捗の管理、レビューの投稿などが可能です。

## ✨ 主な機能

### 📖 書籍管理
- **Google Books API連携**: 書籍を簡単に検索・登録
- **本一覧表示**: 登録済みの全書籍を表紙付きで一覧表示
- **書籍情報**: タイトル、著者、出版社、ページ数、説明文など

### 📊 読書記録
- **読書進捗管理**: 現在のページ数を記録し、進捗率を自動計算
- **ステータス管理**: 未読/読中/完読のステータス変更
- **統計情報**: 総読書記録数、完読数などを表示

### ⭐ レビュー機能
- **星評価**: 1〜5段階の評価システム
- **レビュー投稿**: 読了した書籍の感想を記録
- **平均評価**: 書籍ごとの平均評価を自動計算

### 📱 レスポンシブデザイン
- **モバイル最適化**: スマートフォンでも快適に利用可能
- **ボトムナビゲーション**: モバイル専用の固定ナビゲーション
- **パステルカラーUI**: 優しい色合いのデザイン

### 🔐 セキュリティ
- **ユーザー認証**: セッションベースの認証システム
- **レート制限**: ブルートフォース攻撃対策
- **セキュリティヘッダー**: HSTS、XSS保護など
- **CSRF保護**: フォームの改ざん防止

## 🚀 技術スタック

### バックエンド
- **Laravel 12**: 最新版PHPフレームワーク
- **PHP 8.4**: 最新のPHP
- **MySQL/SQLite**: データベース（本番/開発環境）

### フロントエンド
- **Vue.js 3**: リアクティブUIフレームワーク
- **Tailwind CSS v4**: ユーティリティファーストCSS
- **Vite**: 高速ビルドツール

### 外部API
- **Google Books API v1**: 書籍情報の取得

## 📋 環境要件

- PHP >= 8.2
- Composer
- Node.js >= 18
- MySQL >= 8.0 (本番環境)
- SQLite (開発環境)

## 🛠️ 開発環境セットアップ

### 1. リポジトリのクローン
```bash
git clone <repository-url>
cd Book-Management-System
```

### 2. 依存パッケージのインストール
```bash
# PHP依存パッケージ
composer install

# Node.js依存パッケージ
npm install
```

### 3. 環境設定ファイルの作成
```bash
cp .env.example .env
php artisan key:generate
```

### 4. データベースの準備
```bash
# SQLiteデータベースファイルを作成
touch database/database.sqlite

# マイグレーション実行
php artisan migrate
```

### 5. 開発サーバーの起動
```bash
# Laravelサーバー（ターミナル1）
php artisan serve

# Vite開発サーバー（ターミナル2）
npm run dev
```

ブラウザで http://127.0.0.1:8000 にアクセスしてください。

## 🌐 本番環境デプロイ

⚠️ **重要**: 開発環境の設定のまま本番環境にデプロイすると、セキュリティリスクがあります。

### クイックスタート

1. **本番環境用設定ファイルを使用**
   ```bash
   cp .env.production .env
   ```

2. **必須設定項目を変更**（詳細は `.env.production` 内のコメント参照）
   - `APP_DEBUG=false`
   - `APP_URL=https://your-domain.com`
   - データベース設定（MySQL）
   - `SESSION_SECURE_COOKIE=true`

3. **完全なデプロイ手順**

   詳細なチェックリストは以下を参照してください：
   - **[PRODUCTION_CHECKLIST.md](./PRODUCTION_CHECKLIST.md)**: ステップバイステップのチェックリスト
   - **[DEPLOYMENT.md](./DEPLOYMENT.md)**: 技術的な詳細とトラブルシューティング

### 本番環境で必須の対応（4つの重大な問題）

本番環境にデプロイする前に、以下の4つの重大な問題を必ず修正してください：

1. **🔴 デバッグモード**: `APP_DEBUG=false` に設定（trueは危険）
2. **🔴 データベース**: SQLite → MySQL/PostgreSQLに変更
3. **🔴 HTTPS設定**: `SESSION_SECURE_COOKIE=true` に設定
4. **🔴 セキュリティ設定**: ログレベル、セッション有効期限の調整

詳細は [DEPLOYMENT.md](./DEPLOYMENT.md) の冒頭セクションを参照してください。

## 📁 プロジェクト構成

```
Book-Management-System/
├── app/
│   ├── Http/Controllers/          # コントローラー
│   │   ├── Auth/                  # 認証関連
│   │   ├── BookSearchController.php
│   │   ├── ReadingRecordController.php
│   │   └── ReviewController.php
│   ├── Models/                    # Eloquentモデル
│   │   ├── User.php
│   │   ├── Book.php
│   │   ├── ReadingRecord.php
│   │   └── Review.php
│   └── Http/Middleware/           # ミドルウェア
├── database/
│   └── migrations/                # データベースマイグレーション
├── resources/
│   ├── views/                     # Bladeテンプレート
│   │   ├── auth/                  # 認証ページ
│   │   ├── books/                 # 書籍関連ページ
│   │   ├── reading-records/       # 読書記録ページ
│   │   └── reviews/               # レビューページ
│   ├── css/                       # スタイルシート
│   └── js/                        # JavaScript
├── routes/
│   ├── web.php                    # Webルート
│   └── api.php                    # APIルート
├── .env.production.example        # 本番環境設定テンプレート
├── .env.production                # 本番環境設定ファイル
├── DEPLOYMENT.md                  # デプロイメントガイド
└── PRODUCTION_CHECKLIST.md        # 本番デプロイチェックリスト
```

## 🎨 主要な画面

- **ホーム**: システムの紹介ページ
- **ダッシュボード**: 読書統計とクイックアクション
- **本検索**: Google Books APIで書籍を検索・追加
- **本一覧**: 登録済み書籍の一覧表示
- **読書記録**: 読書進捗の管理
- **レビュー**: 書籍レビューの投稿・閲覧

## 🔒 セキュリティ機能

### 実装済み
- レート制限（ログイン: 5分/5回、登録: 1時間/3回）
- セキュリティヘッダー（HSTS、XSS保護、CSP等）
- HTTPS強制（本番環境）
- セッション暗号化
- CSRF保護

### 推奨事項（オプション）
- 2要素認証（2FA）の実装
- パスワード最小文字数の設定（現在は制限なし）
- IPホワイトリスト
- エラー監視サービス（Sentry等）

## 📊 データベース構造

### テーブル一覧
- `users`: ユーザー情報
- `books`: 書籍情報
- `reading_records`: 読書記録
- `reviews`: レビュー
- `sessions`: セッション管理
- `cache`: キャッシュデータ
- `jobs`: ジョブキュー

## 🔧 開発用コマンド

### キャッシュクリア
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### マイグレーション
```bash
php artisan migrate                # 実行
php artisan migrate:rollback       # ロールバック
php artisan migrate:fresh          # リセット
```

### テスト
```bash
php artisan test
```

## 📝 Google Books API設定（オプション）

レート制限対策として、APIキーの設定を推奨します。

1. [Google Cloud Console](https://console.cloud.google.com/) でプロジェクトを作成
2. Google Books API を有効化
3. 認証情報を作成してAPIキーを取得
4. `.env` に設定:
   ```env
   GOOGLE_BOOKS_API_KEY=your_api_key_here
   ```

## 🐛 トラブルシューティング

### よくある問題

**セッションが保持されない**
- `SESSION_SECURE_COOKIE` とHTTPS環境の設定を確認
- ストレージディレクトリの権限を確認

**500エラー**
- `storage/logs/laravel.log` でエラー詳細を確認
- ストレージディレクトリの権限: `chmod -R 775 storage`

**読書記録更新時にログアウトされる**
- ルート名の競合を確認（webルートとAPIルートで重複していないか）

詳細は [DEPLOYMENT.md](./DEPLOYMENT.md) のトラブルシューティングセクションを参照してください。

## 📄 ライセンス

このプロジェクトはMITライセンスの下で公開されています。

## 🙏 謝辞

- [Laravel](https://laravel.com) - PHPフレームワーク
- [Vue.js](https://vuejs.org) - JavaScriptフレームワーク
- [Tailwind CSS](https://tailwindcss.com) - CSSフレームワーク
- [Google Books API](https://developers.google.com/books) - 書籍情報API
