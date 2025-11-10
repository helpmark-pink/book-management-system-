@extends('layouts.app')

@section('title', 'ダッシュボード - 読書記録システム')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent">
            ダッシュボード
        </h1>
        <p class="mt-2 text-pastel-purple-500 text-lg">ようこそ、<span class="font-bold text-pastel-pink-500">{{ Auth::user()->name }}</span> さん 🌸</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
        <div class="bg-gradient-to-br from-pastel-pink-100 to-white p-6 rounded-3xl shadow-xl hover:shadow-2xl transition-all border-3 border-pastel-pink-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-pastel-pink-500 mb-2">📚 総読書記録数</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent">
                        {{ Auth::user()->readingRecords()->count() }}
                    </p>
                </div>
                <div class="text-5xl sm:text-6xl opacity-50">📚</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-pastel-purple-100 to-white p-6 rounded-3xl shadow-xl hover:shadow-2xl transition-all border-3 border-pastel-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-pastel-purple-500 mb-2">✅ 完読した本</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-pastel-purple-500 to-pastel-pink-500 bg-clip-text text-transparent">
                        {{ Auth::user()->readingRecords()->where('status', '完読')->count() }}
                    </p>
                </div>
                <div class="text-5xl sm:text-6xl opacity-50">✓</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-pastel-yellow-100 to-white p-6 rounded-3xl shadow-xl hover:shadow-2xl transition-all border-3 border-pastel-yellow-200 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-bold text-pastel-yellow-600 mb-2">⭐ レビュー数</p>
                    <p class="text-4xl font-bold bg-gradient-to-r from-pastel-yellow-500 to-pastel-pink-500 bg-clip-text text-transparent">
                        {{ Auth::user()->reviews()->count() }}
                    </p>
                </div>
                <div class="text-5xl sm:text-6xl opacity-50">⭐</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-xl border-3 border-pastel-pink-200">
            <h2 class="text-2xl font-bold text-pastel-pink-600 mb-6 flex items-center">
                <span class="mr-2">📖</span> 最近の読書記録
            </h2>
            @php
                $recentRecords = Auth::user()->readingRecords()->with('book')->latest()->take(5)->get();
            @endphp

            @if($recentRecords->count() > 0)
                <div class="space-y-4">
                    @foreach($recentRecords as $record)
                        <div class="bg-gradient-to-r from-pastel-pink-50 to-pastel-purple-50 p-4 rounded-2xl border-2 border-pastel-pink-100 hover:shadow-lg transition-all">
                            <h3 class="font-bold text-pastel-purple-700">{{ $record->book->title }}</h3>
                            <p class="text-sm text-pastel-purple-500 mt-1">{{ $record->book->author }}</p>
                            <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                                <span class="text-xs px-3 py-1 rounded-full font-bold
                                    @if($record->status === '完読') bg-gradient-to-r from-green-200 to-green-300 text-green-800
                                    @elseif($record->status === '読中') bg-gradient-to-r from-blue-200 to-blue-300 text-blue-800
                                    @else bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800
                                    @endif">
                                    {{ $record->status }}
                                </span>
                                <div class="flex items-center">
                                    <div class="w-24 sm:w-32 bg-pastel-pink-100 rounded-full h-3 mr-2 overflow-hidden">
                                        <div class="bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 h-full rounded-full transition-all"
                                             style="width: {{ $record->progress_percentage }}%">
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-pastel-purple-600">
                                        {{ number_format($record->progress_percentage, 1) }}%
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4 opacity-30">📖</div>
                    <p class="text-pastel-purple-400">まだ読書記録がありません</p>
                    <p class="text-sm text-pastel-purple-300 mt-2">本を追加して読書を始めましょう！</p>
                </div>
            @endif
        </div>

        <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-xl border-3 border-pastel-yellow-200">
            <h2 class="text-2xl font-bold text-pastel-yellow-600 mb-6 flex items-center">
                <span class="mr-2">⚡</span> クイックアクション
            </h2>
            <div class="space-y-4">
                <a href="{{ route('web.books.list') }}" class="block w-full bg-gradient-to-r from-pastel-blue-300 to-pastel-blue-400 text-black px-6 py-4 rounded-2xl hover:shadow-xl hover:scale-105 transition-all text-center font-bold">
                    <span class="text-2xl mr-2">📚</span> 本一覧
                </a>
                <a href="/books/search" class="block w-full bg-gradient-to-r from-pastel-pink-300 to-pastel-pink-400 text-black px-6 py-4 rounded-2xl hover:shadow-xl hover:scale-105 transition-all text-center font-bold">
                    <span class="text-2xl mr-2">📖</span> 本を検索・追加
                </a>
                <a href="{{ route('web.reading-records.index') }}" class="block w-full bg-gradient-to-r from-pastel-purple-300 to-pastel-purple-400 text-black px-6 py-4 rounded-2xl hover:shadow-xl hover:scale-105 transition-all text-center font-bold">
                    <span class="text-2xl mr-2">📊</span> 読書記録を管理
                </a>
                <a href="{{ route('web.reviews.index') }}" class="block w-full bg-gradient-to-r from-pastel-yellow-300 to-pastel-yellow-400 text-black px-6 py-4 rounded-2xl hover:shadow-xl hover:scale-105 transition-all text-center font-bold">
                    <span class="text-2xl mr-2">⭐</span> レビューを書く
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
