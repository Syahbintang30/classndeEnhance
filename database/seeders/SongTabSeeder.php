<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SongTab;

class SongTabSeeder extends Seeder
{
    public function run(): void
    {
        SongTab::truncate();

        SongTab::create([
            'title' => "In This River (Live Lead Solo)",
            'artist' => 'Black Label Society',
            'bpm' => 72,
            'difficulty' => 'Medium',
            'track_name' => 'Electric Guitar Clean (Zakk Wylde Solo)',
            'is_published' => true,
            'tab_data' => [
                [
                    ['string' => 2, 'fret' => "9", 'note' => "E", 'freq' => 164.81, 'beat' => 0],
                    ['string' => 2, 'fret' => "11", 'note' => "F#", 'freq' => 185.00, 'beat' => 1],
                    ['string' => 1, 'fret' => "9", 'note' => "C#", 'freq' => 277.18, 'beat' => 2],
                    ['string' => 1, 'fret' => "12", 'note' => "E", 'freq' => 329.63, 'beat' => 3]
                ],
                [
                    ['string' => 0, 'fret' => "12", 'note' => "E", 'freq' => 329.63, 'beat' => 0],
                    ['string' => 0, 'fret' => "14", 'note' => "F#", 'freq' => 369.99, 'beat' => 1],
                    ['string' => 0, 'fret' => "16", 'note' => "G#", 'freq' => 415.30, 'beat' => 2],
                    ['string' => 0, 'fret' => "14", 'note' => "F#", 'freq' => 369.99, 'beat' => 3]
                ],
                [
                    ['string' => 1, 'fret' => "12", 'note' => "E", 'freq' => 329.63, 'beat' => 0],
                    ['string' => 2, 'fret' => "11", 'note' => "F#", 'freq' => 185.00, 'beat' => 1],
                    ['string' => 2, 'fret' => "9", 'note' => "E", 'freq' => 164.81, 'beat' => 2],
                    ['string' => 3, 'fret' => "11", 'note' => "C#", 'freq' => 138.59, 'beat' => 3]
                ],
                [
                    ['string' => 2, 'fret' => "9", 'note' => "E", 'freq' => 164.81, 'beat' => 0],
                    ['string' => 2, 'fret' => "11", 'note' => "F#", 'freq' => 185.00, 'beat' => 1],
                    ['string' => 2, 'fret' => "9", 'note' => "E", 'freq' => 164.81, 'beat' => 2],
                    ['string' => 3, 'fret' => "11", 'note' => "C#", 'freq' => 138.59, 'beat' => 3]
                ]
            ]
        ]);

        SongTab::create([
            'title' => "Sweet Child O' Mine (Authentic Intro Solo)",
            'artist' => "Guns N' Roses",
            'bpm' => 125,
            'difficulty' => 'Hard',
            'track_name' => 'Electric Guitar Lead (Slash Riff)',
            'is_published' => true,
            'tab_data' => [
                [
                    ['string' => 3, 'fret' => "12", 'note' => "D", 'freq' => 146.83, 'beat' => 0],
                    ['string' => 1, 'fret' => "15", 'note' => "D", 'freq' => 293.66, 'beat' => 0.5],
                    ['string' => 2, 'fret' => "14", 'note' => "A", 'freq' => 220.00, 'beat' => 1],
                    ['string' => 2, 'fret' => "12", 'note' => "G", 'freq' => 196.00, 'beat' => 1.5],
                    ['string' => 0, 'fret' => "15", 'note' => "G", 'freq' => 392.00, 'beat' => 2],
                    ['string' => 2, 'fret' => "14", 'note' => "A", 'freq' => 220.00, 'beat' => 2.5],
                    ['string' => 0, 'fret' => "14", 'note' => "F#", 'freq' => 369.99, 'beat' => 3],
                    ['string' => 2, 'fret' => "14", 'note' => "A", 'freq' => 220.00, 'beat' => 3.5]
                ],
                [
                    ['string' => 3, 'fret' => "14", 'note' => "E", 'freq' => 164.81, 'beat' => 0],
                    ['string' => 1, 'fret' => "15", 'note' => "D", 'freq' => 293.66, 'beat' => 0.5],
                    ['string' => 2, 'fret' => "14", 'note' => "A", 'freq' => 220.00, 'beat' => 1],
                    ['string' => 2, 'fret' => "12", 'note' => "G", 'freq' => 196.00, 'beat' => 1.5],
                    ['string' => 0, 'fret' => "15", 'note' => "G", 'freq' => 392.00, 'beat' => 2],
                    ['string' => 2, 'fret' => "14", 'note' => "A", 'freq' => 220.00, 'beat' => 2.5],
                    ['string' => 0, 'fret' => "14", 'note' => "F#", 'freq' => 369.99, 'beat' => 3],
                    ['string' => 2, 'fret' => "14", 'note' => "A", 'freq' => 220.00, 'beat' => 3.5]
                ]
            ]
        ]);

        SongTab::create([
            'title' => "Ada Titik (Iconic Lead Solo)",
            'artist' => 'Sal Priadi',
            'bpm' => 95,
            'difficulty' => 'Easy',
            'track_name' => 'Electric Guitar Melodic Lead',
            'is_published' => true,
            'tab_data' => [
                [
                    ['string' => 0, 'fret' => "5", 'note' => "A", 'freq' => 440.00, 'beat' => 0],
                    ['string' => 0, 'fret' => "7", 'note' => "B", 'freq' => 493.88, 'beat' => 1],
                    ['string' => 0, 'fret' => "8", 'note' => "C", 'freq' => 523.25, 'beat' => 2],
                    ['string' => 1, 'fret' => "5", 'note' => "E", 'freq' => 329.63, 'beat' => 3]
                ],
                [
                    ['string' => 1, 'fret' => "8", 'note' => "G", 'freq' => 392.00, 'beat' => 0],
                    ['string' => 0, 'fret' => "7", 'note' => "B", 'freq' => 493.88, 'beat' => 1],
                    ['string' => 0, 'fret' => "5", 'note' => "A", 'freq' => 440.00, 'beat' => 2],
                    ['string' => 1, 'fret' => "5", 'note' => "E", 'freq' => 329.63, 'beat' => 3]
                ]
            ]
        ]);
    }
}
