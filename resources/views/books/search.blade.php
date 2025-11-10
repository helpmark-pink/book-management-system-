@extends('layouts.app')

@section('title', '本を検索・追加 - 読書記録システム')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent">
            📖 本を検索・追加
        </h1>
        <p class="mt-2 text-pastel-purple-500 text-lg">読みたい本を検索して、読書記録に追加しましょう！</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-2 border-green-200 text-green-700 rounded-2xl">
        {{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="mb-6 p-4 bg-blue-50 border-2 border-blue-200 text-blue-700 rounded-2xl">
        {{ session('info') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 text-red-700 rounded-2xl">
        {{ session('error') }}
    </div>
    @endif

    <!-- 検索フォーム -->
    <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-xl border-3 border-pastel-pink-200 mb-8">
        <form method="GET" action="{{ route('books.search.results') }}" class="flex flex-col sm:flex-row gap-4">
            <input
                type="text"
                name="query"
                value="{{ $query ?? '' }}"
                placeholder="本のタイトル、著者名、キーワードで検索..."
                class="flex-1 px-6 py-4 border-3 border-pastel-pink-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-purple-200 focus:border-pastel-purple-300 transition-all"
                required
            />
            <button
                type="submit"
                class="bg-gradient-to-r from-pastel-pink-400 to-pastel-purple-400 text-black px-8 py-4 rounded-2xl hover:shadow-2xl hover:scale-105 transition-all font-bold text-lg"
            >
                🔍 検索
            </button>
        </form>
    </div>

    <!-- 検索結果 -->
    @if(isset($results))
        @if(count($results) > 0)
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-pastel-pink-600 mb-4">
                    検索結果: {{ count($results) }}件
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($results as $book)
                    @php
                        $volumeInfo = $book['volumeInfo'] ?? [];
                        $title = $volumeInfo['title'] ?? '不明';
                        $authors = isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : '著者不明';
                        $publisher = $volumeInfo['publisher'] ?? '出版社不明';
                        $publishedDate = $volumeInfo['publishedDate'] ?? '';
                        $pageCount = $volumeInfo['pageCount'] ?? 100;
                        $thumbnail = $volumeInfo['imageLinks']['thumbnail'] ?? null;
                        $description = $volumeInfo['description'] ?? '';
                        $googleBooksId = $book['id'] ?? '';
                        $isbn = '';

                        if (isset($volumeInfo['industryIdentifiers'])) {
                            foreach ($volumeInfo['industryIdentifiers'] as $identifier) {
                                if ($identifier['type'] === 'ISBN_13') {
                                    $isbn = $identifier['identifier'];
                                    break;
                                }
                            }
                        }

                        if (empty($isbn)) {
                            $isbn = $googleBooksId;
                        }
                    @endphp

                    <div class="bg-white/80 backdrop-blur-sm p-6 rounded-3xl shadow-xl border-3 border-pastel-yellow-200 hover:shadow-2xl hover:scale-105 transition-all">
                        @if($thumbnail)
                            <img src="{{ $thumbnail }}" alt="{{ $title }}" class="w-full h-48 object-contain mb-4 rounded-2xl">
                        @else
                            <div class="w-full h-48 bg-pastel-pink-100 flex items-center justify-center mb-4 rounded-2xl">
                                <span class="text-6xl">📚</span>
                            </div>
                        @endif

                        <h3 class="font-bold text-lg text-pastel-purple-700 mb-2 line-clamp-2">{{ $title }}</h3>
                        <p class="text-sm text-pastel-purple-500 mb-1">著者: {{ $authors }}</p>
                        <p class="text-sm text-pastel-purple-500 mb-1">出版社: {{ $publisher }}</p>
                        @if($pageCount)
                            <p class="text-sm text-pastel-purple-500 mb-3">ページ数: {{ $pageCount }}</p>
                        @endif

                        <form method="POST" action="{{ route('books.add') }}">
                            @csrf
                            <input type="hidden" name="google_books_id" value="{{ $googleBooksId }}">
                            <input type="hidden" name="isbn" value="{{ $isbn }}">
                            <input type="hidden" name="title" value="{{ $title }}">
                            <input type="hidden" name="author" value="{{ $authors }}">
                            <input type="hidden" name="publisher" value="{{ $publisher }}">
                            <input type="hidden" name="published_date" value="{{ $publishedDate }}">
                            <input type="hidden" name="page_count" value="{{ $pageCount }}">
                            <input type="hidden" name="thumbnail" value="{{ $thumbnail }}">
                            <input type="hidden" name="description" value="{{ $description }}">

                            <button
                                type="submit"
                                class="w-full bg-gradient-to-r from-pastel-yellow-400 to-pastel-pink-400 text-black px-4 py-3 rounded-2xl hover:shadow-xl hover:scale-105 transition-all font-bold"
                            >
                                ➕ 読書記録に追加
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-3 border-pastel-purple-200">
                <div class="text-6xl mb-4 opacity-30">😢</div>
                <p class="text-pastel-purple-500 text-xl">検索結果が見つかりませんでした</p>
                <p class="text-sm text-pastel-purple-300 mt-2">別のキーワードで検索してみてください</p>
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-white/80 backdrop-blur-sm rounded-3xl shadow-xl border-3 border-pastel-purple-200">
            <div class="text-6xl mb-4 opacity-50">🔍</div>
            <p class="text-pastel-purple-500 text-xl">本を検索してみましょう</p>
            <p class="text-sm text-pastel-purple-300 mt-2">タイトル、著者名、キーワードなどで検索できます</p>
        </div>
    @endif
</div>
@endsection
