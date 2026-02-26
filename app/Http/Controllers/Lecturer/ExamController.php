<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ClassSubject;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $lecturer = auth()->user();

        // Get all exams created by this lecturer
        $exams = Exam::where('created_by', $lecturer->id)
                     ->with(['classSubject.subject', 'classSubject.classRoom'])
                     ->withCount('questions')
                     ->get();

        return view('lecturer.exams.index', compact('exams'));
    }

    public function create()
    {
        $lecturer = auth()->user();

        // Only show class_subjects assigned to this lecturer
        $classSubjects = ClassSubject::where('lecturer_id', $lecturer->id)
                                     ->with(['subject', 'classRoom'])
                                     ->get();

        return view('lecturer.exams.create', compact('classSubjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_subject_id' => 'required|exists:class_subject,id',
            'time_limit' => 'required|integer|min:1|max:180',
        ]);

        // Make sure this lecturer is assigned to this class_subject
        $classSubject = ClassSubject::where('id', $request->class_subject_id)
                                    ->where('lecturer_id', auth()->id())
                                    ->firstOrFail();

        $exam = Exam::create([
            'title' => $request->title,
            'description' => $request->description,
            'class_subject_id' => $classSubject->id,
            'time_limit' => $request->time_limit,
            'created_by' => auth()->id(),
            'is_published' => false,
        ]);

        return redirect()->route('lecturer.exams.questions', $exam)
                         ->with('success', 'Exam created! Now add your questions.');
    }

    public function edit(Exam $exam)
    {
        // Make sure this lecturer owns this exam
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $lecturer = auth()->user();
        $classSubjects = ClassSubject::where('lecturer_id', $lecturer->id)
                                     ->with(['subject', 'classRoom'])
                                     ->get();

        return view('lecturer.exams.edit', compact('exam', 'classSubjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'required|integer|min:1|max:180',
        ]);

        $exam->update($request->only('title', 'description', 'time_limit'));

        return redirect()->route('lecturer.exams.index')->with('success', 'Exam updated successfully!');
    }

    public function destroy(Exam $exam)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $exam->delete();
        return redirect()->route('lecturer.exams.index')->with('success', 'Exam deleted successfully!');
    }

    public function togglePublish(Exam $exam)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $exam->update(['is_published' => !$exam->is_published]);

        $message = $exam->is_published ? 'Exam published!' : 'Exam unpublished!';
        return back()->with('success', $message);
    }
}
