<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\EnrollmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    // Show available classes and student's request status
    public function index()
    {
        $student = auth()->user();

        // If already enrolled, redirect to dashboard
        if ($student->class_id) {
            return redirect()->route('student.dashboard')
                             ->with('info', 'You are already enrolled in a class!');
        }

        $classes = ClassRoom::withCount('students')->get();

        // Get student's pending/rejected requests
        $requests = EnrollmentRequest::where('student_id', $student->id)
                                     ->with('classRoom')
                                     ->latest()
                                     ->get();

        return view('student.enrollment.index', compact('classes', 'requests'));
    }

    // Show the request form
    public function create(ClassRoom $class)
    {
        $student = auth()->user();

        // Check if already enrolled
        if ($student->class_id) {
            return redirect()->route('student.dashboard')
                             ->with('info', 'You are already enrolled in a class!');
        }

        // Check if already has a pending request for this class
        $existingRequest = EnrollmentRequest::where('student_id', $student->id)
                                            ->where('class_id', $class->id)
                                            ->where('status', 'pending')
                                            ->first();

        if ($existingRequest) {
            return redirect()->route('student.enrollment.index')
                             ->with('info', 'You already have a pending request for this class!');
        }

        return view('student.enrollment.create', compact('class'));
    }

    // Submit the request
    public function store(Request $request, ClassRoom $class)
    {
        $student = auth()->user();

        if ($student->class_id) {
            return redirect()->route('student.dashboard');
        }

        $request->validate([
            'statement' => 'required|string|min:50',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'statement.min' => 'Please write at least 50 characters explaining why you want to join.',
            'document.mimes' => 'Document must be a PDF, DOC or DOCX file.',
            'document.max' => 'Document must not exceed 2MB.',
        ]);

        $documentPath = null;

        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('enrollment-documents', 'public');
        }
        
        DB::beginTransaction();
            EnrollmentRequest::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'statement' => $request->statement,
                'document_path' => $documentPath,
                'status' => 'pending',
            ]);
        DB::commit();
        return redirect()->route('student.enrollment.index')
                         ->with('success', 'Enrollment request submitted! Please wait for admin approval.');
    }
}