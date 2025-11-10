<?php

namespace App\Http\Controllers;

use App\Models\ReadingRecord;
use Illuminate\Http\Request;

class ReadingRecordController extends Controller
{
    public function index()
    {
        $records = auth()->user()->readingRecords()
            ->with('book')
            ->latest()
            ->paginate(12);

        return view('reading-records.index', compact('records'));
    }

    public function update(Request $request, ReadingRecord $record)
    {
        \Log::info('Update method called', [
            'user_id' => auth()->id(),
            'record_id' => $record->id,
            'record_user_id' => $record->user_id,
            'authenticated' => auth()->check(),
        ]);

        // 自分の記録かチェック
        if ($record->user_id !== auth()->id()) {
            \Log::warning('Authorization failed', [
                'record_user_id' => $record->user_id,
                'auth_user_id' => auth()->id(),
            ]);
            abort(403, '権限がありません。');
        }

        $request->validate([
            'current_page' => 'required|integer|min:0|max:' . $record->total_pages,
            'status' => 'required|in:未読,読中,完読',
        ]);

        $record->update([
            'current_page' => $request->input('current_page'),
            'status' => $request->input('status'),
        ]);

        \Log::info('Record updated successfully', ['record_id' => $record->id]);

        return redirect()->route('web.reading-records.index')
            ->with('success', '読書記録を更新しました！');
    }

    public function destroy(ReadingRecord $record)
    {
        // 自分の記録かチェック
        if ($record->user_id !== auth()->id()) {
            abort(403, '権限がありません。');
        }

        $record->delete();

        return redirect()->route('web.reading-records.index')
            ->with('success', '読書記録を削除しました。');
    }
}
