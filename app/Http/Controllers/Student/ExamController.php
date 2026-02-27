<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    // Show all available exams for the student
    public function index()
    {
        $student = auth()->user();

        if (!$student->class_id) {
            return redirect()->route('student.enrollment.index')
                             ->with('info', 'You need to be enrolled in a class first!');
        }

        // Get all class_subject ids for this student's class
        $classSubjectIds = $student->classRoom->classSubjects->pluck('id');

        // Get all published exams for those class_subjects
        $exams = Exam::whereIn('class_subject_id', $classSubjectIds)
                     ->where('is_published', true)
                     ->with(['classSubject.subject', 'classSubject.classRoom'])
                     ->withCount('questions')
                     ->get();

        // Get student's submissions
        $submissions = ExamSubmission::where('student_id', $student->id)
                                     ->get()
                                     ->keyBy('exam_id');

        return view('student.exams.index', compact('exams', 'submissions'));
    }

    // Start or continue an exam
    public function show(Exam $exam)
    {
        $student = auth()->user();

        // Make sure this exam belongs to student's class
        $classSubjectIds = $student->classRoom->classSubjects->pluck('id');
        if (!$classSubjectIds->contains($exam->class_subject_id)) {
            abort(403);
        }

        // Check if already submitted
        $submission = ExamSubmission::where('exam_id', $exam->id)
                                    ->where('student_id', $student->id)
                                    ->first();

        if ($submission && $submission->isSubmitted() || $submission && $submission->isGraded()) {
            return redirect()->route('student.exams.result', $exam)
                             ->with('info', 'You have already submitted this exam!');
        }

        // Create submission if not started yet
        if (!$submission) {
            $submission = ExamSubmission::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        // Check if time is up
        $timeElapsed = $submission->started_at->diffInMinutes(now());
        if ($timeElapsed >= $exam->time_limit) {
            $this->submitExam($submission);
            return redirect()->route('student.exams.result', $exam)
                             ->with('info', 'Time is up! Your exam has been auto submitted.');
        }

        $timeRemaining = round(($exam->time_limit * 60) - $submission->started_at->diffInSeconds(now()));

        $exam->load('questions.options');

        // Get existing answers
        $answers = Answer::where('submission_id', $submission->id)
                         ->get()
                         ->keyBy('question_id');

        return view('student.exams.show', compact('exam', 'submission', 'timeRemaining', 'answers'));
    }

    // Save answer (called via form submission)
    public function saveAnswer(Request $request, Exam $exam)
    {
        $student = auth()->user();

        $submission = ExamSubmission::where('exam_id', $exam->id)
                                    ->where('student_id', $student->id)
                                    ->where('status', 'in_progress')
                                    ->firstOrFail();

        // Check time
        $timeElapsed = $submission->started_at->diffInMinutes(now());
        if ($timeElapsed >= $exam->time_limit) {
            $this->submitExam($submission);
            return redirect()->route('student.exams.result', $exam)
                             ->with('info', 'Time is up! Your exam has been auto submitted.');
        }

        $request->validate([
            'answers' => 'required|array',
        ]);

        foreach ($request->answers as $questionId => $answerData) {
            $question = Question::find($questionId);
            if (!$question) continue;

            $existingAnswer = Answer::where('submission_id', $submission->id)
                                    ->where('question_id', $questionId)
                                    ->first();

            $answerPayload = [
                'submission_id' => $submission->id,
                'question_id' => $questionId,
                'selected_option_id' => $question->isMCQ() ? ($answerData['option'] ?? null) : null,
                'answer_text' => $question->isOpenText() ? ($answerData['text'] ?? null) : null,
            ];

            if ($existingAnswer) {
                $existingAnswer->update($answerPayload);
            } else {
                Answer::create($answerPayload);
            }
        }

        return back()->with('success', 'Answers saved!');
    }

    // Submit the exam
    public function submit(Request $request, Exam $exam)
    {
        $student = auth()->user();

        $submission = ExamSubmission::where('exam_id', $exam->id)
                                    ->where('student_id', $student->id)
                                    ->where('status', 'in_progress')
                                    ->firstOrFail();

        // Save answers first
        if ($request->has('answers')) {
            foreach ($request->answers as $questionId => $answerData) {
                $question = Question::find($questionId);
                if (!$question) continue;

                $existingAnswer = Answer::where('submission_id', $submission->id)
                                        ->where('question_id', $questionId)
                                        ->first();

                $answerPayload = [
                    'submission_id' => $submission->id,
                    'question_id' => $questionId,
                    'selected_option_id' => $question->isMCQ() ? ($answerData['option'] ?? null) : null,
                    'answer_text' => $question->isOpenText() ? ($answerData['text'] ?? null) : null,
                ];

                if ($existingAnswer) {
                    $existingAnswer->update($answerPayload);
                } else {
                    Answer::create($answerPayload);
                }
            }
        }

        $this->submitExam($submission);

        return redirect()->route('student.exams.result', $exam)
                         ->with('success', 'Exam submitted successfully!');
    }

    // Show results
    public function result(Exam $exam)
    {
        $student = auth()->user();

        $submission = ExamSubmission::where('exam_id', $exam->id)
                                    ->where('student_id', $student->id)
                                    ->with(['answers.question.options', 'answers.selectedOption'])
                                    ->firstOrFail();

        if ($submission->isInProgress()) {
            return redirect()->route('student.exams.show', $exam);
        }

        return view('student.exams.result', compact('exam', 'submission'));
    }

    // Private helper to grade and submit
    private function submitExam(ExamSubmission $submission)
    {
        $exam = $submission->exam;
        $totalMarks = 0;
        $hasOpenText = false;

        foreach ($exam->questions as $question) {
            $answer = Answer::where('submission_id', $submission->id)
                            ->where('question_id', $question->id)
                            ->first();

            if (!$answer) continue;

            if ($question->isMCQ()) {
                // Auto grade MCQ
                if ($answer->selected_option_id && $answer->isCorrect()) {
                    $answer->update(['marks_awarded' => $question->marks]);
                    $totalMarks += $question->marks;
                } else {
                    $answer->update(['marks_awarded' => 0]);
                }
            } else {
                // Open text needs manual grading
                $hasOpenText = true;
            }
        }

        $submission->update([
            'submitted_at' => now(),
            'status' => $hasOpenText ? 'submitted' : 'graded',
            'total_marks' => $hasOpenText ? null : $totalMarks,
        ]);
    }
}