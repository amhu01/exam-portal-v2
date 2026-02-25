<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    // A subject belongs to many classes
    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_subject')
                    ->withPivot('lecturer_id')
                    ->withTimestamps();
    }

    // A subject has many class_subject records
    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'subject_id');
    }
}