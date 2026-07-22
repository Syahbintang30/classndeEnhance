<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongTab extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'bpm',
        'difficulty',
        'track_name',
        'tab_data',
        'audio_url',
        'gp_file_path',
        'is_published',
    ];

    protected $casts = [
        'tab_data' => 'array',
        'is_published' => 'boolean',
        'bpm' => 'integer',
    ];
}
