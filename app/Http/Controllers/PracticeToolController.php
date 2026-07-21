<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PracticeToolController extends Controller
{
    public function index()
    {
        return view('practice-tools.index');
    }

    public function tuner()
    {
        return view('practice-tools.tuner');
    }

    public function metronome()
    {
        return view('practice-tools.metronome');
    }

    public function chords()
    {
        return view('practice-tools.chords');
    }
}
