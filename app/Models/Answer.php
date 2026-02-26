<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = [
        'submission_id',
        'question_id',
        'selected_option_id',
        'answer_text',
        'marks_awarded',
    ];

    // Answer belongs to a submission
    public function submission()
    {
        return $this->belongsTo(ExamSubmission::class, 'submission_id');
    }

    // Answer belongs to a question
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    // Answer belongs to a selected option (MCQ only)
    public function selectedOption()
    {
        return $this->belongsTo(Option::class, 'selected_option_id');
    }

    // Check if MCQ answer is correct
    public function isCorrect(): bool
    {
        if ($this->question->isMCQ()) {
            return $this->selected_option_id === $this->question->correctOption?->id;
        }

        return false; // open text can't be auto checked
    }
}
