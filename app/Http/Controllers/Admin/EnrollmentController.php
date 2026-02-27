<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentRequest;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index()
    {
        $requests = EnrollmentRequest::with(['student', 'classRoom'])
                                     ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
                                     ->latest()
                                     ->get();

        return view('admin.enrollment.index', compact('requests'));
    }

    public function approve(EnrollmentRequest $enrollmentRequest)
    {
        // Update the request status
        $enrollmentRequest->update(['status' => 'approved']);

        // Assign student to the class
        $enrollmentRequest->student->update(['class_id' => $enrollmentRequest->class_id]);

        // Reject all other pending requests from this student
        EnrollmentRequest::where('student_id', $enrollmentRequest->student_id)
                         ->where('id', '!=', $enrollmentRequest->id)
                         ->where('status', 'pending')
                         ->update(['status' => 'rejected', 'admin_remarks' => 'Auto rejected — student enrolled in another class.']);

        return back()->with('success', 'Student enrolled successfully!');
    }

    public function reject(Request $request, EnrollmentRequest $enrollmentRequest)
    {
        $request->validate([
            'admin_remarks' => 'required|string|min:10',
        ], [
            'admin_remarks.required' => 'Please provide a reason for rejection.',
            'admin_remarks.min' => 'Reason must be at least 10 characters.',
        ]);

        $enrollmentRequest->update([
            'status' => 'rejected',
            'admin_remarks' => $request->admin_remarks,
        ]);

        return back()->with('success', 'Request rejected.');
    }
}