@extends('layouts.app')

@section('title', 'レビューを書く - 読書記録システム')

@section('content')
<div class="max-w-7xl mx-auto px-4">
    <div class="mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-pastel-pink-500 to-pastel-purple-500 bg-clip-text text-transparent">
            ⭐ レビューを書く
        </h1>
        <p class="mt-2 text-pastel-purple-500 text-lg">完読した本の感想を記録しましょう！</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border-2 border-green-200 text-green-700 rounded-2xl">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 text-red-700 rounded-2xl">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 新規レビュー作成 -->
        <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-xl border-3 border-pastel-yellow-200">
            <h2 class="text-2xl font-bold text-pastel-yellow-600 mb-6 flex items-center">
                <span class="mr-2">✍️</span> 新しいレビューを書く
            </h2>

            @if($completedBooks->count() > 0)
                <form method="POST" action="{{ route('web.reviews.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-pastel-purple-600 mb-2">
                            📚 本を選択
                        </label>
                        <select
                            name="reading_record_id"
                            required
                            class="w-full px-4 py-3 border-3 border-pastel-yellow-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-pink-200 focus:border-pastel-pink-300 transition-all @error('reading_record_id') border-red-400 @enderror"
                        >
                            <option value="">本を選択してください</option>
                            @foreach($completedBooks as $record)
                                <option value="{{ $record->id }}">{{ $record->book->title }} - {{ $record->book->author }}</option>
                            @endforeach
                        </select>
                        @error('reading_record_id')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-pastel-purple-600 mb-2">
                            ⭐ 評価 (星をクリックしてください)
                        </label>
                        <div class="flex gap-1" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="cursor-pointer star-label">
                                    <input
                                        type="radio"
                                        name="rating"
                                        value="{{ $i }}"
                                        required
                                        class="hidden"
                                        {{ old('rating') == $i ? 'checked' : '' }}
                                    />
                                    <span class="text-4xl transition-all hover:scale-110" data-star="{{ $i }}">☆</span>
                                </label>
                            @endfor
                        </div>
                        @error('rating')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const stars = document.querySelectorAll('#star-rating .star-label');
                            const inputs = document.querySelectorAll('#star-rating input[type="radio"]');

                            // 初期状態の設定
                            const checkedInput = document.querySelector('#star-rating input[type="radio"]:checked');
                            if (checkedInput) {
                                updateStars(checkedInput.value);
                            }

                            stars.forEach((label, index) => {
                                const input = label.querySelector('input');
                                const star = label.querySelector('span');

                                // クリック時
                                label.addEventListener('click', function() {
                                    const value = input.value;
                                    updateStars(value);
                                });

                                // ホバー時
                                label.addEventListener('mouseenter', function() {
                                    const value = input.value;
                                    highlightStars(value);
                                });
                            });

                            // マウスが星の外に出た時、選択された評価を表示
                            document.getElementById('star-rating').addEventListener('mouseleave', function() {
                                const checkedInput = document.querySelector('#star-rating input[type="radio"]:checked');
                                if (checkedInput) {
                                    updateStars(checkedInput.value);
                                } else {
                                    updateStars(0);
                                }
                            });

                            function updateStars(rating) {
                                stars.forEach((label, index) => {
                                    const star = label.querySelector('span');
                                    if (index < rating) {
                                        star.textContent = '⭐';
                                    } else {
                                        star.textContent = '☆';
                                    }
                                });
                            }

                            function highlightStars(rating) {
                                stars.forEach((label, index) => {
                                    const star = label.querySelector('span');
                                    if (index < rating) {
                                        star.textContent = '⭐';
                                    } else {
                                        star.textContent = '☆';
                                    }
                                });
                            }
                        });
                    </script>

                    <div>
                        <label class="block text-sm font-bold text-pastel-purple-600 mb-2">
                            📝 レビュー内容 (10文字以上)
                        </label>
                        <textarea
                            name="review_text"
                            rows="6"
                            required
                            minlength="10"
                            class="w-full px-4 py-3 border-3 border-pastel-yellow-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-pastel-pink-200 focus:border-pastel-pink-300 transition-all @error('review_text') border-red-400 @enderror"
                            placeholder="この本の感想を書いてください..."
                        >{{ old('review_text') }}</textarea>
                        @error('review_text')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-pastel-yellow-400 to-pastel-pink-400 text-black px-6 py-4 rounded-2xl hover:shadow-2xl hover:scale-105 transition-all font-bold text-lg"
                    >
                        📮 レビューを投稿
                    </button>
                </form>
            @else
                <div class="text-center py-8">
                    <div class="text-6xl mb-4 opacity-30">📖</div>
                    <p class="text-pastel-purple-500">まだ完読した本がありません</p>
                    <p class="text-sm text-pastel-purple-300 mt-2">本を読み終えたらレビューを書きましょう！</p>
                </div>
            @endif
        </div>

        <!-- 投稿済みレビュー一覧 -->
        <div class="bg-white/80 backdrop-blur-sm p-6 sm:p-8 rounded-3xl shadow-xl border-3 border-pastel-pink-200">
            <h2 class="text-2xl font-bold text-pastel-pink-600 mb-6 flex items-center">
                <span class="mr-2">📚</span> あなたのレビュー
            </h2>

            @if($reviews->count() > 0)
                <div class="space-y-4 max-h-[600px] overflow-y-auto pr-2">
                    @foreach($reviews as $review)
                        <div class="bg-gradient-to-r from-pastel-pink-50 to-pastel-purple-50 p-4 rounded-2xl border-2 border-pastel-pink-100">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-pastel-purple-700 flex-1">{{ $review->readingRecord->book->title }}</h4>
                                <form method="POST" action="{{ route('web.reviews.destroy', $review) }}" class="inline" onsubmit="return confirm('本当に削除しますか？');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-2">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                            <p class="text-sm text-pastel-purple-500 mb-2">著者: {{ $review->readingRecord->book->author }}</p>
                            <div class="flex items-center mb-2">
                                <span class="text-yellow-500 font-bold mr-2">
                                    @for($i = 0; $i < $review->rating; $i++)
                                        ⭐
                                    @endfor
                                </span>
                                <span class="text-sm text-pastel-purple-500">({{ $review->rating }}/5)</span>
                            </div>
                            <p class="text-sm text-pastel-purple-600 mt-2 leading-relaxed">{{ $review->review_text }}</p>
                            <p class="text-xs text-pastel-purple-400 mt-2">{{ $review->created_at->format('Y年m月d日') }}</p>
                        </div>
                    @endforeach
                </div>

                <!-- ページネーション -->
                @if($reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="text-6xl mb-4 opacity-30">⭐</div>
                    <p class="text-pastel-purple-500">まだレビューがありません</p>
                    <p class="text-sm text-pastel-purple-300 mt-2">完読した本のレビューを書いてみましょう！</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
