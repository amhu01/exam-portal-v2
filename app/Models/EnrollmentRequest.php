<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentRequest extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'statement',
        'document_path',
        'status',
        'admin_remarks',
    ];

    // Request belongs to a student
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Request belongs to a class
    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
