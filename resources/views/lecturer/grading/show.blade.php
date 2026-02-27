<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Grading — {{ $submission->student->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('lecturer.grading.grade', [$exam, $submission]) }}">
                @csrf

                @foreach($submission->answers as $answer)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">
                    <p class="font-semibold mb-2">Q{{ $answer->question->order }}. {{ $answer->question->question_text }}</p>
                    <p class="text-gray-500 text-sm mb-3">Max marks: {{ $answer->question->marks }}</p>

                    @if($answer->question->isMCQ())
                        <div class="space-y-1 mb-3">
                            @foreach($answer->question->options as $option)
                            <div class="flex items-center gap-2 text-sm p-2 rounded
                                {{ $option->is_correct ? 'bg-green-50 text-green-700' : '' }}
                                {{ $answer->selected_option_id == $option->id && !$option->is_correct ? 'bg-red-50 text-red-700' : '' }}
                            ">
                                <span>
                                    @if($option->is_correct) ✓
                                    @elseif($answer->selected_option_id == $option->id) ✗
                                    @else ○
                                    @endif
                                </span>
                                {{ $option->option_text }}
                                @if($answer->selected_option_id == $option->id)
                                    <span class="text-xs">(Student's answer)</span>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <p class="text-sm font-semibold {{ $answer->marks_awarded > 0 ? 'text-green-600' : 'text-red-600' }}">
                            Auto graded: {{ $answer->marks_awarded }} / {{ $answer->question->marks }} marks
                        </p>
                        <input type="hidden" name="marks[{{ $answer->id }}]" value="{{ $answer->marks_awarded }}">
                    @else
                        <div class="bg-gray-50 rounded p-3 text-sm text-gray-700 mb-3">
                            {{ $answer->answer_text ?? 'No answer provided.' }}
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="font-semibold text-sm">Marks awarded:</label>
                            <input type="number"
                                name="marks[{{ $answer->id }}]"
                                value="{{ $answer->marks_awarded ?? 0 }}"
                                min="0"
                                max="{{ $answer->question->marks }}"
                                class="border rounded px-3 py-1 w-24">
                            <span class="text-gray-500 text-sm">/ {{ $answer->question->marks }}</span>
                        </div>
                    @endif
                </div>
                @endforeach

                @if($submission->isSubmitted())
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                        Submit Grades
                    </button>
                </div>
                @else
                <div class="bg-white shadow-sm sm:rounded-lg p-4 text-center text-gray-500">
                    Already graded. Total: {{ $submission->total_marks }} / {{ $exam->totalMarks() }} marks
                </div>
                @endif
            </form>

            <div class="mt-4 text-center">
                <a href="{{ route('lecturer.grading.exam', $exam) }}" class="text-blue-500 hover:underline">
                    ← Back to submissions
                </a>
            </div>
        </div>
    </div>
</x-app-layout>