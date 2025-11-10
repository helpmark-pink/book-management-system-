@extends('layouts.app')

@section('title', 'ログイン - 読書記録システム')

@section('content')
<div class="max-w-md mx-auto px-4">
    <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-10 rounded-3xl shadow-2xl border-4 border-pastel-pink-200">
        <div class="text-center mb-8">
            <div class="text-5xl sm:text-6xl mb-4">📖</div>
            <h2 class="text-2xl sm:text-3xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent">
                ログイン
            </h2>
        </div>
            <p class="text-pastel-purple-400 mt-2 text-sm sm:text-base">おかえりなさい！</p>
        </div>

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 text-red-600 rounded-2xl text-sm">
            {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                    class="w-full px-4 py-3 border-3 border-pastel-pink-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-purple-200 focus:border-pastel-purple-300 transition-all @error('name') border-red-400 @enderror"
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
                    class="w-full px-4 py-3 border-3 border-pastel-pink-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-purple-200 focus:border-pastel-purple-300 transition-all @error('password') border-red-400 @enderror"
                    placeholder="パスワード"
                />
                @error('password')
                <p class="mt-2 text-sm text-red-500 flex items-center">
                    <span class="mr-1">⚠️</span>{{ $message }}
                </p>
                @enderror
            </div>

            <div class="flex items-center">
                <input
                    type="checkbox"
                    id="remember"
                    name="remember"
                    class="w-5 h-5 border-3 border-pastel-pink-200 rounded focus:ring-4 focus:ring-pastel-purple-200"
                    checked
                />
                <label for="remember" class="ml-2 text-sm text-pastel-purple-600 font-medium">
                    ログイン状態を保持する
                </label>
            </div>

            <button
                type="submit"
                class="w-full bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 px-6 py-4 rounded-2xl hover:shadow-2xl hover:scale-105 transition-all font-bold text-lg"
                style="color: #DC2626;"
            >
                ログイン ✨
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-sm text-pastel-purple-500">
                アカウントをお持ちでないですか？
                <a href="/register" class="text-pastel-pink-500 hover:text-pastel-purple-600 font-bold underline decoration-wavy">
                    登録する
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
