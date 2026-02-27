<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\Answer;
use Illuminate\Http\Request;

class GradingController extends Controller
{
    // Show all submissions for lecturer's exams
    public function index()
    {
        $lecturer = auth()->user();

        $exams = Exam::where('created_by', $lecturer->id)
                     ->with(['classSubject.subject', 'classSubject.classRoom'])
                     ->withCount(['submissions', 'submissions as pending_count' => function($q) {
                         $q->where('status', 'submitted');
                     }])
                     ->get();

        return view('lecturer.grading.index', compact('exams'));
    }

    // Show all submissions for a specific exam
    public function exam(Exam $exam)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $submissions = ExamSubmission::where('exam_id', $exam->id)
                                     ->with('student')
                                     ->whereIn('status', ['submitted', 'graded'])
                                     ->get();

        return view('lecturer.grading.exam', compact('exam', 'submissions'));
    }

    // Show a specific submission for grading
    public function show(Exam $exam, ExamSubmission $submission)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $submission->load(['answers.question.options', 'answers.selectedOption', 'student']);

        return view('lecturer.grading.show', compact('exam', 'submission'));
    }

    // Grade open text answers
    public function grade(Request $request, Exam $exam, ExamSubmission $submission)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'marks' => 'required|array',
            'marks.*' => 'required|integer|min:0',
        ]);

        $totalMarks = 0;

        foreach ($request->marks as $answerId => $marks) {
            $answer = Answer::find($answerId);
            if (!$answer) continue;

            // Make sure marks dont exceed question marks
            $maxMarks = $answer->question->marks;
            $awardedMarks = min($marks, $maxMarks);

            $answer->update(['marks_awarded' => $awardedMarks]);
            $totalMarks += $awardedMarks;
        }

        // Add MCQ marks too
        $mcqMarks = $submission->answers()
                               ->whereNotNull('selected_option_id')
                               ->sum('marks_awarded');

        $submission->update([
            'status' => 'graded',
            'total_marks' => $totalMarks + $mcqMarks,
        ]);

        return redirect()->route('lecturer.grading.exam', $exam)
                         ->with('success', 'Submission graded successfully!');
    }
}