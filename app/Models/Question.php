<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'question_text',
        'type',
        'order',
        'marks',
    ];

    // Question belongs to an exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Question has many options (MCQ only)
    public function options()
    {
        return $this->hasMany(Option::class);
    }

    // Get the correct option (MCQ only)
    public function correctOption()
    {
        return $this->hasOne(Option::class)->where('is_correct', true);
    }

    // Helper to check if question is MCQ
    public function isMCQ(): bool
    {
        return $this->type === 'mcq';
    }

    // Helper to check if question is open text
    public function isOpenText(): bool
    {
        return $this->type === 'open_text';
    }
}
