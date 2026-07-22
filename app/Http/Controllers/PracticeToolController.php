<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PracticeToolController extends Controller
{
    /**
     * Enforce Intermediate tier access requirement for Practice Tools.
     */
    protected function checkAccess()
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (($user->is_admin ?? false) || ($user->is_superadmin ?? false) || $user->isIntermediateMember()) {
            return null; // Access Granted
        }

        return response()->view('practice-tools.upgrade', [
            'isBeginner' => $user->isBeginnerMember(),
            'upgradePrice' => 150000,
        ]);
    }

    public function index()
    {
        if ($access = $this->checkAccess()) return $access;
        return view('practice-tools.index');
    }

    public function tuner()
    {
        if ($access = $this->checkAccess()) return $access;
        return view('practice-tools.tuner');
    }

    public function metronome()
    {
        if ($access = $this->checkAccess()) return $access;
        return view('practice-tools.metronome');
    }

    public function chords()
    {
        if ($access = $this->checkAccess()) return $access;
        return view('practice-tools.chords');
    }

    public function scales()
    {
        if ($access = $this->checkAccess()) return $access;
        return view('practice-tools.scales');
    }

    public function trainer()
    {
        if ($access = $this->checkAccess()) return $access;
        return view('practice-tools.trainer');
    }
}
