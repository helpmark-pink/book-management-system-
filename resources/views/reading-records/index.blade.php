@extends('layouts.app')

@section('title', '読書記録を管理 - 読書記録システム')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent">
            📊 読書記録を管理
        </h1>
        <p class="mt-2 text-pastel-purple-500 text-lg">読書の進捗を更新して、目標達成を目指しましょう！</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-2 border-green-200 text-green-700 rounded-2xl">
        {{ session('success') }}
    </div>
    @endif

    @if($records->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            @foreach($records as $record)
                <div class="bg-white/80 backdrop-blur-sm p-6 rounded-3xl shadow-xl border-3 border-pastel-pink-200 hover:shadow-2xl transition-all">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- 本の画像 -->
                        @if($record->book->cover_image)
                            <img src="{{ $record->book->cover_image }}" alt="{{ $record->book->title }}" class="w-full sm:w-32 h-40 object-contain rounded-2xl">
                        @else
                            <div class="w-full sm:w-32 h-40 bg-pastel-pink-100 flex items-center justify-center rounded-2xl">
                                <span class="text-5xl">📚</span>
                            </div>
                        @endif

                        <!-- 本の情報と編集フォーム -->
                        <div class="flex-1">
                            <h3 class="font-bold text-xl text-pastel-purple-700 mb-2">{{ $record->book->title }}</h3>
                            <p class="text-sm text-pastel-purple-500 mb-4">著者: {{ $record->book->author }}</p>

                            <form method="POST" action="{{ route('web.reading-records.update', $record) }}" class="space-y-4">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-sm font-bold text-pastel-purple-600 mb-2">
                                        📖 現在のページ
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input
                                            type="number"
                                            name="current_page"
                                            value="{{ $record->current_page }}"
                                            min="0"
                                            max="{{ $record->total_pages }}"
                                            class="flex-1 px-4 py-2 border-2 border-pastel-pink-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-purple-200 focus:border-pastel-purple-300 transition-all"
                                        />
                                        <span class="text-pastel-purple-600 font-bold">/ {{ $record->total_pages }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-pastel-purple-600 mb-2">
                                        📌 ステータス
                                    </label>
                                    <select
                                        name="status"
                                        class="w-full px-4 py-2 border-2 border-pastel-pink-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-purple-200 focus:border-pastel-purple-300 transition-all"
                                    >
                                        <option value="未読" {{ $record->status === '未読' ? 'selected' : '' }}>📝 未読</option>
                                        <option value="読中" {{ $record->status === '読中' ? 'selected' : '' }}>📖 読中</option>
                                        <option value="完読" {{ $record->status === '完読' ? 'selected' : '' }}>✅ 完読</option>
                                    </select>
                                </div>

                                <!-- 進捗バー -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-bold text-pastel-purple-600">進捗状況</span>
                                        <span class="text-sm font-bold text-pastel-purple-600">
                                            {{ number_format($record->progress_percentage, 1) }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-pastel-pink-100 rounded-full h-4 overflow-hidden">
                                        <div class="bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 h-full rounded-full transition-all"
                                             style="width: {{ $record->progress_percentage }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button
                                        type="submit"
                                        class="flex-1 bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 text-black px-4 py-3 rounded-2xl hover:shadow-xl hover:scale-105 transition-all font-bold"
                                    >
                                        💾 更新
                                    </button>

                                    <button
                                        type="button"
                                        onclick="if(confirm('本当に削除しますか？')) { document.getElementById('delete-form-{{ $record->id }}').submit(); }"
                                        class="flex-1 sm:flex-none bg-gradient-to-r from-red-300 to-red-400 text-white px-4 py-3 rounded-2xl hover:shadow-xl hover:scale-105 transition-all font-bold"
                                    >
                                        🗑️ 削除
                                    </button>
                                </div>
                            </form>

                            <form id="delete-form-{{ $record->id }}" method="POST" action="{{ route('web.reading-records.destroy', $record) }}" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ページネーション -->
        <div class="mt-8">
            {{ $records->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-3 border-pastel-purple-200">
            <div class="text-6xl mb-4 opacity-30">📖</div>
            <p class="text-pastel-purple-500 text-xl mb-2">まだ読書記録がありません</p>
            <p class="text-sm text-pastel-purple-300 mb-6">本を検索して読書記録を追加しましょう！</p>
            <a href="{{ route('books.search') }}" class="inline-block bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 text-black px-8 py-4 rounded-2xl hover:shadow-xl hover:scale-105 transition-all font-bold">
                📖 本を検索・追加
            </a>
        </div>
    @endif
</div>
@endsection
