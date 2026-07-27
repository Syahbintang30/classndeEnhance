<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'question_id',
        'answer_id',
        'question_en',
        'answer_en',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function getQuestionForLocale(string $lang = 'id'): string
    {
        if ($lang === 'en') {
            return $this->question_en ?: ($this->question ?: '');
        }
        return $this->question_id ?: ($this->question ?: '');
    }

    public function getAnswerForLocale(string $lang = 'id'): string
    {
        if ($lang === 'en') {
            return $this->answer_en ?: ($this->answer ?: '');
        }
        return $this->answer_id ?: ($this->answer ?: '');
    }
}
