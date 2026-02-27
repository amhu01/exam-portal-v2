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
            'options.*' => 'nullable|string',
            'correct_option' => 'required_if:type,mcq|integer',
        ],[
            'options.*.required_if' => 'Do not leave any options blank',
        ]);
        if ($request->type === 'mcq') {
            $filledOptions = array_filter($request->options, fn($o) => !empty(trim($o)));
            if (count($filledOptions) < 2) {
                return back()->with('error', 'Please provide at least 2 options.')->withInput();
            }
        }        
        
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
