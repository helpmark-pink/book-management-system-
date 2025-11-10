@extends('layouts.app')

@section('title', '本一覧 - 読書記録システム')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent">
            📚 本一覧
        </h1>
        <p class="mt-2 text-pastel-purple-500 text-lg">登録されているすべての本を表示しています</p>
    </div>

    @if($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($books as $book)
                <div class="bg-white/80 backdrop-blur-sm p-4 rounded-3xl shadow-xl border-3 border-pastel-pink-200 hover:shadow-2xl hover:scale-105 transition-all">
                    <!-- 本の画像 -->
                    <div class="mb-4">
                        @if($book->cover_image)
                            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" class="w-full h-64 object-contain rounded-2xl bg-pastel-pink-50">
                        @else
                            <div class="w-full h-64 bg-pastel-pink-100 flex items-center justify-center rounded-2xl">
                                <span class="text-7xl">📖</span>
                            </div>
                        @endif
                    </div>

                    <!-- 本の情報 -->
                    <div class="space-y-2">
                        <h3 class="font-bold text-lg text-pastel-purple-700 line-clamp-2 min-h-[3.5rem]">{{ $book->title }}</h3>
                        <p class="text-sm text-pastel-purple-500">著者: {{ $book->author }}</p>

                        @if($book->publisher)
                            <p class="text-xs text-pastel-purple-400">出版社: {{ $book->publisher }}</p>
                        @endif

                        @if($book->published_date)
                            <p class="text-xs text-pastel-purple-400">出版日: {{ $book->published_date->format('Y年m月d日') }}</p>
                        @endif

                        <div class="flex items-center gap-2 text-xs text-pastel-purple-400">
                            <span>📄 {{ $book->page_count }}ページ</span>
                        </div>

                        <!-- 統計情報 -->
                        <div class="pt-3 border-t-2 border-pastel-pink-100">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-pastel-purple-500">
                                    👥 {{ $book->reading_records_count }}人が読書中
                                </span>

                                @php
                                    $ratings = $book->readingRecords->map->review->whereNotNull()->pluck('rating');
                                    $avgRating = $ratings->count() > 0 ? round($ratings->avg(), 1) : null;
                                @endphp

                                @if($avgRating)
                                    <span class="text-yellow-500 font-bold">
                                        ⭐ {{ $avgRating }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- 説明（省略表示） -->
                        @if($book->description)
                            <p class="text-xs text-pastel-purple-400 line-clamp-3 pt-2">
                                {{ $book->description }}
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ページネーション -->
        <div class="mt-8">
            {{ $books->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-3 border-pastel-purple-200">
            <div class="text-6xl mb-4 opacity-30">📚</div>
            <p class="text-pastel-purple-500 text-xl mb-2">まだ本が登録されていません</p>
            <p class="text-sm text-pastel-purple-300 mb-6">本を検索して読書記録に追加しましょう！</p>
            <a href="{{ route('books.search') }}" class="inline-block bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 text-black px-8 py-4 rounded-2xl hover:shadow-xl hover:scale-105 transition-all font-bold">
                📖 本を検索・追加
            </a>
        </div>
    @endif
</div>
@endsection
