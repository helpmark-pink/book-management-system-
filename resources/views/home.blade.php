@extends('layouts.app')

@section('title', 'ホーム - 読書記録システム')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="text-center mb-12">
        <div class="text-6xl sm:text-8xl mb-6 animate-bounce">📚</div>
        <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold mb-4">
            <span class="bg-gradient-to-r from-pastel-pink-500 via-pastel-purple-500 to-pastel-yellow-500 bg-clip-text text-transparent">
                読書記録システム            </span>
        </h1>
        <p class="text-lg sm:text-2xl text-pastel-purple-500 mb-8">
            あなたの読書ライフを可愛く記録しましょう 🌸
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mt-12">
            <div class="bg-gradient-to-br from-pastel-pink-100 to-white p-6 sm:p-8 rounded-3xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all border-4 border-pastel-pink-200">
                <div class="text-4xl sm:text-5xl mb-4">📖</div>
                <h3 class="text-xl sm:text-2xl font-bold text-pastel-pink-600 mb-3">本の管理</h3>
                <p class="text-pastel-purple-600 text-sm sm:text-base">
                    Google Books APIで書籍を検索して、簡単に本を追加できます
                </p>
            </div>

            <div class="bg-gradient-to-br from-pastel-purple-100 to-white p-6 sm:p-8 rounded-3xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all border-4 border-pastel-purple-200">
                <div class="text-4xl sm:text-5xl mb-4">📊</div>
                <h3 class="text-xl sm:text-2xl font-bold text-pastel-purple-600 mb-3">進捗管理</h3>
                <p class="text-pastel-purple-600 text-sm sm:text-base">
                    現在のページ数を記録して、読書の進捗を視覚的に確認できます
                </p>
            </div>

            <div class="bg-gradient-to-br from-pastel-yellow-100 to-white p-6 sm:p-8 rounded-3xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all border-4 border-pastel-yellow-200 sm:col-span-2 lg:col-span-1">
                <div class="text-4xl sm:text-5xl mb-4">⭐</div>
                <h3 class="text-xl sm:text-2xl font-bold text-pastel-yellow-600 mb-3">レビュー・評価</h3>
                <p class="text-pastel-purple-600 text-sm sm:text-base">
                    読んだ本の感想や評価を記録して、後で振り返ることができます
                </p>
            </div>
        </div>

        @guest
        <div class="mt-12">
            <a href="/register" class="inline-block bg-gradient-to-r from-pastel-pink-400 via-pastel-purple-400 to-pastel-yellow-400 text-white px-8 sm:px-12 py-4 sm:py-5 rounded-full text-lg sm:text-xl font-bold hover:shadow-2xl hover:scale-110 transition-all">
                今すぐ始める ✨
            </a>
        </div>
        @endguest
    </div>
</div>
@endsection
