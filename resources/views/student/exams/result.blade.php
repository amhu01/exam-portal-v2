<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Results — {{ $exam->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Score Summary --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="text-center">
                    @if($submission->isGraded())
                        <p class="text-5xl font-bold text-green-600">{{ $submission->total_marks }}</p>
                        <p class="text-gray-500 mt-1">out of {{ $exam->totalMarks() }} marks</p>
                        <p class="text-lg mt-2 font-semibold">
                            {{ round(($submission->total_marks / $exam->totalMarks()) * 100) }}%
                        </p>
                    @else
                        <p class="text-2xl font-bold text-blue-600">Pending Grading</p>
                        <p class="text-gray-500 mt-1">Your lecturer is grading your open text answers.</p>
                    @endif
                    <p class="text-gray-500 text-sm mt-2">Submitted: {{ $submission->submitted_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>

            {{-- Answer Review --}}
            @foreach($submission->answers as $answer)
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <p class="font-semibold mb-2">Q{{ $answer->question->order }}. {{ $answer->question->question_text }}</p>

                @if($answer->question->isMCQ())
                    <div class="space-y-1">
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
                            <span>{{ $option->option_text }}</span>
                            @if($answer->selected_option_id == $option->id)
                                <span class="text-xs ml-2">(Your answer)</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-gray-50 rounded p-3 text-sm text-gray-700 mb-2">
                        {{ $answer->answer_text ?? 'No answer provided.' }}
                    </div>
                @endif

                <div class="text-right text-sm mt-2">
                    @if($answer->marks_awarded !== null)
                        <span class="font-semibold {{ $answer->marks_awarded > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $answer->marks_awarded }} / {{ $answer->question->marks }} marks
                        </span>
                    @else
                        <span class="text-gray-500">Pending grading</span>
                    @endif
                </div>
            </div>
            @endforeach

            <div class="text-center">
                <a href="{{ route('student.exams.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                    Back to Exams
                </a>
            </div>

        </div>
    </div>
</x-app-layout>