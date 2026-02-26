<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'class_subject_id',
        'created_by',
        'title',
        'description',
        'time_limit',
        'is_published',
    ];

    // Exam belongs to a class_subject
    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    // Exam was created by a lecturer
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Exam has many questions
    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    // Exam has many submissions
    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }

    // Total marks possible for this exam
    public function totalMarks()
    {
        return $this->questions()->sum('marks');
    }
}
