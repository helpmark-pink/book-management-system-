<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', '読書記録システム')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: #FFD1DC;
            min-height: 100vh;
        }
        /* モバイルナビゲーション用のパディング */
        @media (max-width: 768px) {
            main {
                padding-bottom: 80px;
            }
        }
    </style>
</head>
<body>
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-gradient-to-r from-pastel-pink-200 via-pastel-purple-100 to-pastel-yellow-100 shadow-lg backdrop-blur-sm bg-opacity-90 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16 sm:h-20">
                    <div class="flex items-center">
                        <a href="/" class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent hover:scale-105 transition-transform">
                            📚 読書記録システム
                        </a>
                    </div>
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        @auth
                            <a href="/dashboard" class="hidden sm:block text-pastel-purple-600 hover:text-pastel-pink-500 px-3 py-2 rounded-full hover:bg-white/50 transition-all">
                                ダッシュボード
                            </a>
                            <a href="{{ route('web.books.list') }}" class="hidden md:block text-pastel-purple-600 hover:text-pastel-pink-500 px-3 py-2 rounded-full hover:bg-white/50 transition-all">
                                本一覧
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-pastel-pink-600 hover:text-pastel-purple-500 px-3 py-2 rounded-full hover:bg-white/50 transition-all text-sm sm:text-base">
                                    ログアウト
                                </button>
                            </form>
                        @else
                            <a href="/login" class="text-red-600 hover:text-pastel-pink-500 px-3 py-2 rounded-full hover:bg-white/50 transition-all text-sm sm:text-base">
                                ログイン
                            </a>
                            <a href="/register" class="bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 text-red-600 px-4 py-2 rounded-full hover:shadow-lg hover:scale-105 transition-all text-sm sm:text-base">
                                登録
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow py-6 sm:py-12 px-4">
            @yield('content')
        </main>

        <!-- モバイルボトムナビゲーション (スマホサイズのみ表示) -->
        @auth
        <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-sm shadow-2xl border-t-4 border-pastel-pink-200 z-50">
            <div class="flex justify-around items-center h-20">
                <a href="/dashboard" class="flex flex-col items-center justify-center flex-1 py-2 {{ request()->is('dashboard') ? 'text-pastel-pink-600' : 'text-pastel-purple-600' }} hover:text-pastel-pink-500 transition-all">
                    <span class="text-2xl mb-1">🏠</span>
                    <span class="text-xs font-bold">ホーム</span>
                </a>

                <a href="{{ route('web.books.list') }}" class="flex flex-col items-center justify-center flex-1 py-2 {{ request()->is('books') ? 'text-pastel-pink-600' : 'text-pastel-purple-600' }} hover:text-pastel-pink-500 transition-all">
                    <span class="text-2xl mb-1">📚</span>
                    <span class="text-xs font-bold">本一覧</span>
                </a>

                <a href="/books/search" class="flex flex-col items-center justify-center flex-1 py-2 {{ request()->is('books/search*') ? 'text-pastel-pink-600' : 'text-pastel-purple-600' }} hover:text-pastel-pink-500 transition-all">
                    <span class="text-2xl mb-1">🔍</span>
                    <span class="text-xs font-bold">検索</span>
                </a>

                <a href="{{ route('web.reading-records.index') }}" class="flex flex-col items-center justify-center flex-1 py-2 {{ request()->is('reading-records*') ? 'text-pastel-pink-600' : 'text-pastel-purple-600' }} hover:text-pastel-pink-500 transition-all">
                    <span class="text-2xl mb-1">📊</span>
                    <span class="text-xs font-bold">記録</span>
                </a>

                <a href="{{ route('web.reviews.index') }}" class="flex flex-col items-center justify-center flex-1 py-2 {{ request()->is('reviews*') ? 'text-pastel-pink-600' : 'text-pastel-purple-600' }} hover:text-pastel-pink-500 transition-all">
                    <span class="text-2xl mb-1">⭐</span>
                    <span class="text-xs font-bold">レビュー</span>
                </a>
            </div>
        </nav>
        @endauth

        <!-- Footer -->
        <footer class="shadow-lg mt-auto" style="background-color: #FFF8A8;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-pastel-yellow-700 text-sm font-medium">
                    <span class="inline-block">🌸 {{ date('Y') }} 読書記録システム 🌸</span>
                    <span class="hidden sm:inline"> All rights reserved.</span>
                </p>
            </div>
        </footer>
    </div>
</body>
</html>
