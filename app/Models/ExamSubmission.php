<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSubmission extends Model
{
    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'total_marks',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    // Submission belongs to an exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Submission belongs to a student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Submission has many answers
    public function answers()
    {
        return $this->hasMany(Answer::class, 'submission_id');
    }

    // Helper methods
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isGraded(): bool
    {
        return $this->status === 'graded';
    }
}
