<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $exam->load('questions.options');

        return view('lecturer.exams.questions', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }
        $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:mcq,open_text',
            'marks' => 'required|integer|min:1',
            'options' => 'required_if:type,mcq|array|min:2',
            'options.*' => 'required_if:type,mcq|string',
            'correct_option' => 'required_if:type,mcq|integer',
        ]);
        try {
            $order = $exam->questions()->count() + 1;

            $question = Question::create([
                'exam_id' => $exam->id,
                'question_text' => $request->question_text,
                'type' => $request->type,
                'marks' => $request->marks,
                'order' => $order,
            ]);

            if ($request->type === 'mcq') {
                foreach ($request->options as $index => $optionText) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => $optionText,
                        'is_correct' => ($index == $request->correct_option),
                    ]);
                }
            }

            return back()->with('success', 'Question added successfully!');

        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage())->withInput();
        }
    }
    public function destroy(Exam $exam, Question $question)
    {
        if ($exam->created_by !== auth()->id()) {
            abort(403);
        }

        $question->delete();
        return back()->with('success', 'Question deleted!');
    }
}
