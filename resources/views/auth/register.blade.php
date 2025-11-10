@extends('layouts.app')

@section('title', '新規登録 - 読書記録システム')

@section('content')
<div class="max-w-md mx-auto px-4">
    <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-10 rounded-3xl shadow-2xl border-4 border-pastel-yellow-200">
        <div class="text-center mb-8">
            <div class="text-5xl sm:text-6xl mb-4">✨</div>
            <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-pastel-yellow-500 to-pastel-pink-500 bg-clip-text text-transparent">
                新規登録
            </h2>
            <p class="text-pastel-purple-400 mt-2 text-sm sm:text-base">一緒に読書を楽しみましょう！</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-bold text-pastel-purple-600 mb-2">
                    👤 名前
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full px-4 py-3 border-3 border-pastel-yellow-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-pink-200 focus:border-pastel-pink-300 transition-all @error('name') border-red-400 @enderror"
                    placeholder="あなたの名前"
                />
                @error('name')
                <p class="mt-2 text-sm text-red-500 flex items-center">
                    <span class="mr-1">⚠️</span>{{ $message }}
                </p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-bold text-pastel-purple-600 mb-2">
                    🔒 パスワード
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    class="w-full px-4 py-3 border-3 border-pastel-yellow-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-pink-200 focus:border-pastel-pink-300 transition-all @error('password') border-red-400 @enderror"
                    placeholder="パスワードを入力"
                />
                @error('password')
                <p class="mt-2 text-sm text-red-500 flex items-center">
                    <span class="mr-1">⚠️</span>{{ $message }}
                </p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-bold text-pastel-purple-600 mb-2">
                    🔒 パスワード（確認）
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    class="w-full px-4 py-3 border-3 border-pastel-yellow-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-pink-200 focus:border-pastel-pink-300 transition-all"
                    placeholder="もう一度入力"
                />
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-pastel-yellow-400 to-pastel-pink-400 text-red-600 px-6 py-4 rounded-2xl hover:shadow-2xl hover:scale-105 transition-all font-bold text-lg"
            >
                登録する 🌟
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-pastel-purple-500">
                すでにアカウントをお持ちですか？
                <a href="/login" class="font-bold underline decoration-wavy" style="color: #DC2626;">
                    ログイン
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
