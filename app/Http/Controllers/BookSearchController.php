<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookSearchController extends Controller
{
    public function index()
    {
        return view('books.search');
    }

    public function list()
    {
        // すべての本を取得（読書記録数も取得）
        $books = Book::withCount('readingRecords')
            ->with('readingRecords.review')
            ->latest()
            ->paginate(12);

        return view('books.list', compact('books'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1',
        ]);

        $query = $request->input('query');

        try {
            // Google Books APIパラメータ
            $params = [
                'q' => $query,
                'maxResults' => 20,
                'langRestrict' => 'ja',
            ];

            // APIキーが設定されている場合は追加（レート制限対策）
            if ($apiKey = config('services.google_books.api_key')) {
                $params['key'] = $apiKey;
            }

            $response = Http::get('https://www.googleapis.com/books/v1/volumes', $params);

            if ($response->successful()) {
                $books = $response->json()['items'] ?? [];
                return view('books.search', ['results' => $books, 'query' => $query]);
            }

            return back()->with('error', '検索に失敗しました。');
        } catch (\Exception $e) {
            return back()->with('error', 'エラーが発生しました: ' . $e->getMessage());
        }
    }

    public function add(Request $request)
    {
        $request->validate([
            'google_books_id' => 'required|string',
            'title' => 'required|string',
            'author' => 'required|string',
            'publisher' => 'nullable|string',
            'published_date' => 'nullable|string',
            'page_count' => 'required|integer|min:1',
            'thumbnail' => 'nullable|url',
            'description' => 'nullable|string',
        ]);

        // ISBNがない場合はGoogle Books IDをISBNとして使用
        $isbn = $request->input('isbn', $request->input('google_books_id'));

        // 既存の本を確認
        $book = Book::where('isbn', $isbn)->first();

        if (!$book) {
            // 新しい本を作成
            $book = Book::create([
                'isbn' => $isbn,
                'title' => $request->input('title'),
                'author' => $request->input('author'),
                'publisher' => $request->input('publisher'),
                'published_date' => $request->input('published_date'),
                'page_count' => $request->input('page_count'),
                'cover_image' => $request->input('thumbnail'),
                'description' => $request->input('description'),
            ]);
        }

        // ユーザーの読書記録を確認
        $existingRecord = ReadingRecord::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->first();

        if ($existingRecord) {
            return redirect()->route('books.search')
                ->with('info', 'この本はすでに読書記録に追加されています。');
        }

        // 読書記録を作成
        ReadingRecord::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'status' => '未読',
            'current_page' => 0,
            'total_pages' => $book->page_count,
        ]);

        return redirect()->route('books.search')
            ->with('success', '本を読書記録に追加しました！');
    }
}
