<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Search by title or author
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        $books = $query->latest()->paginate(15);

        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => ['required', 'string', 'unique:books,isbn'],
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'published_date' => ['nullable', 'date'],
            'cover_image_url' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book = Book::create($validator->validated());

        return response()->json($book, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => ['sometimes', 'string', 'unique:books,isbn,' . $book->id],
            'title' => ['sometimes', 'string', 'max:255'],
            'author' => ['sometimes', 'string', 'max:255'],
            'publisher' => ['nullable', 'string', 'max:255'],
            'published_date' => ['nullable', 'date'],
            'cover_image_url' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $book->update($validator->validated());

        return response()->json($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }

    /**
     * Search books using Google Books API
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => ['required_without:query', 'string'],
            'query' => ['required_without:isbn', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $searchTerm = $request->isbn ? 'isbn:' . $request->isbn : $request->query;

        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $searchTerm,
            'maxResults' => 10,
        ]);

        if ($response->failed()) {
            return response()->json(['message' => 'Failed to fetch data from Google Books API'], 500);
        }

        $data = $response->json();

        if (!isset($data['items'])) {
            return response()->json(['items' => []]);
        }

        $books = collect($data['items'])->map(function ($item) {
            $volumeInfo = $item['volumeInfo'] ?? [];
            $industryIdentifiers = $volumeInfo['industryIdentifiers'] ?? [];

            $isbn = collect($industryIdentifiers)->firstWhere('type', 'ISBN_13')['identifier'] ??
                    collect($industryIdentifiers)->firstWhere('type', 'ISBN_10')['identifier'] ?? null;

            return [
                'isbn' => $isbn,
                'title' => $volumeInfo['title'] ?? 'Unknown',
                'author' => isset($volumeInfo['authors']) ? implode(', ', $volumeInfo['authors']) : 'Unknown',
                'publisher' => $volumeInfo['publisher'] ?? null,
                'published_date' => $volumeInfo['publishedDate'] ?? null,
                'cover_image_url' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
                'description' => $volumeInfo['description'] ?? null,
            ];
        });

        return response()->json(['items' => $books]);
    }
}
