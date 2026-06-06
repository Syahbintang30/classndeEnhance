<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\TopicProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TopicProgressController extends Controller
{
    public function show($topic)
    {
        // Saat player dibuka, frontend butuh tahu posisi tontonan terakhir dari user ini.
        $topicModel = Topic::find($topic);
        if (! $topicModel) {
            return response()->json(['completed' => false, 'watched_seconds' => 0], 404);
        }

        if (! Schema::hasTable('topic_progresses')) {
            return response()->json(['completed' => false, 'watched_seconds' => 0]);
        }

        $progress = TopicProgress::where('user_id', auth()->id())
            ->where('topic_id', $topicModel->id)
            ->first();

        return response()->json([
            'completed' => (bool) ($progress->completed ?? false),
            'watched_seconds' => (int) ($progress->watched_seconds ?? 0),
        ]);
    }

    public function update(Request $request, $topic)
    {
        // Endpoint ini menyimpan progress belajar tanpa reload halaman.
        $topicModel = Topic::find($topic);
        if (! $topicModel) {
            return response()->json(['ok' => false, 'error' => 'topic_not_found'], 404);
        }

        if (! Schema::hasTable('topic_progresses')) {
            return response()->json(['ok' => true, 'completed' => false, 'watched_seconds' => 0]);
        }

        $data = $request->validate([
            'watched_seconds' => ['nullable', 'integer', 'min:0'],
            'duration_seconds' => ['nullable', 'integer', 'min:0'],
            'completed' => ['nullable', 'boolean'],
        ]);

        $incomingSeconds = (int) ($data['watched_seconds'] ?? 0);
        $durationSeconds = (int) ($data['duration_seconds'] ?? 0);
        $incomingCompleted = (bool) ($data['completed'] ?? false);

        // Kalau user sudah menonton 95% durasi, sistem menandai topic sebagai selesai otomatis.
        if (! $incomingCompleted && $durationSeconds > 0) {
            $incomingCompleted = $incomingSeconds >= max(1, (int) floor($durationSeconds * 0.95));
        }

        $progress = TopicProgress::firstOrNew([
            'user_id' => auth()->id(),
            'topic_id' => $topicModel->id,
        ]);

        $currentSeconds = (int) ($progress->watched_seconds ?? 0);
        $progress->watched_seconds = max($currentSeconds, $incomingSeconds);
        $progress->completed = (bool) (($progress->completed ?? false) || $incomingCompleted);
        $progress->save();

        return response()->json([
            'ok' => true,
            'completed' => (bool) $progress->completed,
            'watched_seconds' => (int) $progress->watched_seconds,
        ]);
    }
}
