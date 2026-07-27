<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SongTab;
use Illuminate\Http\Request;

class SongTabController extends Controller
{
    public function index()
    {
        $songTabs = SongTab::latest()->paginate(15);
        return view('admin.song_tabs.index', compact('songTabs'));
    }

    public function create()
    {
        return view('admin.song_tabs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'bpm' => 'required|integer|min:30|max:300',
            'difficulty' => 'required|string',
            'track_name' => 'required|string',
            'tab_json' => 'nullable|string',
        ]);

        $tabData = null;
        if (!empty($validated['tab_json'])) {
            $tabData = json_decode($validated['tab_json'], true);
        }

        // Default fallback TAB measure if not provided
        if (!$tabData) {
            $tabData = [
                [
                    ['string' => 3, 'fret' => "12", 'note' => "D", 'freq' => 146.83, 'beat' => 0],
                    ['string' => 1, 'fret' => "15", 'note' => "D", 'freq' => 293.66, 'beat' => 1],
                    ['string' => 2, 'fret' => "14", 'note' => "A", 'freq' => 220.00, 'beat' => 2],
                    ['string' => 2, 'fret' => "12", 'note' => "G", 'freq' => 196.00, 'beat' => 3]
                ]
            ];
        }

        $audioUrl = null;
        if ($request->hasFile('audio_file')) {
            $path = $request->file('audio_file')->store('song_tabs', 'public');
            $audioUrl = asset('storage/' . $path);
        }

        SongTab::create([
            'title' => $validated['title'],
            'artist' => $validated['artist'],
            'bpm' => $validated['bpm'],
            'difficulty' => $validated['difficulty'],
            'track_name' => $validated['track_name'],
            'audio_url' => $audioUrl,
            'tab_data' => $tabData,
            'is_published' => true,
        ]);

        return redirect()->route('admin.song-tabs.index')->with('success', 'Song TAB added successfully!');
    }

    public function destroy(SongTab $songTab)
    {
        $songTab->delete();
        return redirect()->route('admin.song-tabs.index')->with('success', 'Song TAB deleted successfully.');
    }
}
