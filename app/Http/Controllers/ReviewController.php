<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReadingRecord;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        // 完読した本のリストを取得
        $completedBooks = auth()->user()->readingRecords()
            ->with('book')
            ->where('status', '完読')
            ->get();

        // 既存のレビューを取得
        $reviews = auth()->user()->reviews()
            ->with('readingRecord.book')
            ->latest()
            ->paginate(10);

        return view('reviews.index', compact('completedBooks', 'reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reading_record_id' => 'required|exists:reading_records,id',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|min:10',
        ]);

        $readingRecord = ReadingRecord::findOrFail($request->input('reading_record_id'));

        // 自分の記録かチェック
        if ($readingRecord->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        // 既存のレビューをチェック
        $existingReview = Review::where('user_id', auth()->id())
            ->where('reading_record_id', $readingRecord->id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'この本のレビューは既に投稿されています。');
        }

        Review::create([
            'user_id' => auth()->id(),
            'reading_record_id' => $request->input('reading_record_id'),
            'rating' => $request->input('rating'),
            'review_text' => $request->input('review_text'),
        ]);

        return redirect()->route('web.reviews.index')
            ->with('success', 'レビューを投稿しました！');
    }

    public function destroy(Review $review)
    {
        // 自分のレビューかチェック
        if ($review->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        $review->delete();

        return redirect()->route('web.reviews.index')
            ->with('success', 'レビューを削除しました。');
    }
}
