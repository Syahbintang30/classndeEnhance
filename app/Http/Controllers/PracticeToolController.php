<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PracticeToolController extends Controller
{
    /**
     * Enforce paid member requirement for advanced tools like Interactive TAB Player / Song Vault.
     */
    protected function checkAdvancedAccess()
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (($user->is_admin ?? false) || ($user->is_superadmin ?? false) || $user->isPaidMember()) {
            return null; // Access Granted
        }

        return response()->view('practice-tools.upgrade', [
            'isBeginner' => false,
            'upgradePrice' => 150000,
        ]);
    }

    public function index()
    {
        if (! auth()->check()) return redirect()->route('login');
        return view('practice-tools.index');
    }

    public function tuner()
    {
        if (! auth()->check()) return redirect()->route('login');
        return view('practice-tools.tuner');
    }

    public function metronome()
    {
        if (! auth()->check()) return redirect()->route('login');
        return view('practice-tools.metronome');
    }

    public function chords()
    {
        if ($access = $this->checkAdvancedAccess()) return $access;
        return view('practice-tools.chords');
    }

    public function scales()
    {
        if ($access = $this->checkAdvancedAccess()) return $access;
        return view('practice-tools.scales');
    }

    public function trainer()
    {
        if (! auth()->check()) return redirect()->route('login');
        return view('practice-tools.trainer');
    }

    public function quiz()
    {
        if (! auth()->check()) return redirect()->route('login');
        return view('practice-tools.quiz');
    }

    public function guitarHero()
    {
        if ($access = $this->checkAdvancedAccess()) return $access;
        $songTabs = \App\Models\SongTab::where('is_published', true)->get();
        return view('practice-tools.guitar-hero', compact('songTabs'));
    }

    public function claimXp(Request $request)
    {
        $user = auth()->user();
        if (!$user) return response()->json(['success' => false], 401);

        $amount = (int) $request->input('amount', 50);
        $amount = min(100, max(5, $amount)); // Cap at 100 XP per claim

        $user->increment('xp', $amount);

        return response()->json([
            'success' => true,
            'xp' => $user->fresh()->xp,
            'rank' => $user->fresh()->guitar_rank,
        ]);
    }
}
