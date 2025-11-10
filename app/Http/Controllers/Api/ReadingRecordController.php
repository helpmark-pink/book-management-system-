<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReadingRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReadingRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->readingRecords()->with(['book', 'review']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest()->paginate(15);

        return response()->json($records);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_id' => ['required', 'exists:books,id'],
            'status' => ['required', 'in:未読,読中,完読'],
            'current_page' => ['required', 'integer', 'min:0'],
            'total_pages' => ['required', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Custom validation: current_page <= total_pages
        if ($request->current_page > $request->total_pages) {
            return response()->json([
                'errors' => ['current_page' => ['Current page cannot be greater than total pages']]
            ], 422);
        }

        $record = $request->user()->readingRecords()->create($validator->validated());

        return response()->json($record->load(['book', 'review']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ReadingRecord $readingRecord)
    {
        // Authorize user can only view their own records
        if ($readingRecord->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($readingRecord->load(['book', 'review']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReadingRecord $readingRecord)
    {
        // Authorize user can only update their own records
        if ($readingRecord->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'book_id' => ['sometimes', 'exists:books,id'],
            'status' => ['sometimes', 'in:未読,読中,完読'],
            'current_page' => ['sometimes', 'integer', 'min:0'],
            'total_pages' => ['sometimes', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Custom validation: current_page <= total_pages
        $currentPage = $request->current_page ?? $readingRecord->current_page;
        $totalPages = $request->total_pages ?? $readingRecord->total_pages;

        if ($currentPage > $totalPages) {
            return response()->json([
                'errors' => ['current_page' => ['Current page cannot be greater than total pages']]
            ], 422);
        }

        $readingRecord->update($validator->validated());

        return response()->json($readingRecord->load(['book', 'review']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, ReadingRecord $readingRecord)
    {
        // Authorize user can only delete their own records
        if ($readingRecord->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $readingRecord->delete();

        return response()->json(['message' => 'Reading record deleted successfully']);
    }
}
