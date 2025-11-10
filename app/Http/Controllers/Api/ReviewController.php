<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reviews = $request->user()
            ->reviews()
            ->with(['readingRecord.book'])
            ->latest()
            ->paginate(15);

        return response()->json($reviews);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reading_record_id' => ['required', 'exists:reading_records,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review_text' => ['required', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if reading record belongs to user
        $readingRecord = \App\Models\ReadingRecord::find($request->reading_record_id);
        if ($readingRecord->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if review already exists for this reading record
        if ($readingRecord->review()->exists()) {
            return response()->json([
                'message' => 'A review already exists for this reading record'
            ], 422);
        }

        $review = $request->user()->reviews()->create($validator->validated());

        return response()->json($review->load('readingRecord.book'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Review $review)
    {
        // Authorize user can only view their own reviews
        if ($review->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($review->load('readingRecord.book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Authorize user can only update their own reviews
        if ($review->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => ['sometimes', 'integer', 'between:1,5'],
            'review_text' => ['sometimes', 'string', 'max:1000'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review->update($validator->validated());

        return response()->json($review->load('readingRecord.book'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Review $review)
    {
        // Authorize user can only delete their own reviews
        if ($review->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
}
