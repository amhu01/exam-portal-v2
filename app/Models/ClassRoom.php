<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    // A class has many students
    public function students()
    {
        return $this->hasMany(User::class, 'class_id');
    }

    // A class has many subjects through class_subject
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject')
                    ->withPivot('lecturer_id')
                    ->withTimestamps();
    }

    // A class has many class_subject records
    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }
}