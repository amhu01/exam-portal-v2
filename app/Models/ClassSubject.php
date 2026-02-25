<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $table = 'class_subject';

    protected $fillable = [
        'class_id',
        'subject_id',
        'lecturer_id',
    ];

    // Belongs to a class
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Belongs to a subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // Belongs to a lecturer
    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }
}