<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Questions — {{ $exam->title }}
            </h2>
            <a href="{{ route('lecturer.exams.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                Back to Exams
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Exam Summary --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-600">Subject: <span class="font-semibold">{{ $exam->classSubject->subject->name }}</span></p>
                        <p class="text-gray-600">Class: <span class="font-semibold">{{ $exam->classSubject->classRoom->name }}</span></p>
                        <p class="text-gray-600">Time Limit: <span class="font-semibold">{{ $exam->time_limit }} minutes</span></p>
                        <p class="text-gray-600">Total Marks: <span class="font-semibold">{{ $exam->totalMarks() }}</span></p>
                    </div>
                    <span class="px-3 py-1 rounded font-semibold {{ $exam->is_published ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $exam->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>

            {{-- Existing Questions --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Questions ({{ $exam->questions->count() }})</h3>

                @forelse($exam->questions as $question)
                <div class="border rounded p-4 mb-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <p class="font-semibold">Q{{ $question->order }}. {{ $question->question_text }}</p>
                            <div class="flex gap-3 mt-1">
                                <span class="text-sm px-2 py-1 rounded {{ $question->isMCQ() ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                    {{ $question->isMCQ() ? 'MCQ' : 'Open Text' }}
                                </span>
                                <span class="text-sm text-gray-600">{{ $question->marks }} mark(s)</span>
                            </div>

                            {{-- Show options for MCQ --}}
                            @if($question->isMCQ())
                            <ul class="mt-3 space-y-1">
                                @foreach($question->options as $option)
                                <li class="flex items-center gap-2 text-sm">
                                    <span class="{{ $option->is_correct ? 'text-green-600 font-bold' : 'text-gray-600' }}">
                                        {{ $option->is_correct ? '✓' : '○' }} {{ $option->option_text }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('lecturer.exams.questions.destroy', [$exam, $question]) }}" onsubmit="return confirm('Delete this question?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm ml-4">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-gray-500">No questions yet. Add your first question below!</p>
                @endforelse
            </div>

            {{-- Add Question Form --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6" x-data="{
                type: 'mcq',
                options: ['', '', '', ''],
                addOption() { this.options.push('') },
                removeOption(index) {
                    if(this.options.length > 2) this.options.splice(index, 1)
                }
            }">
                <h3 class="font-bold text-lg mb-4">Add Question</h3>

                <form method="POST" action="{{ route('lecturer.exams.questions.store', $exam) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Question Type</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="mcq" x-model="type" checked>
                                <span>Multiple Choice (MCQ)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="type" value="open_text" x-model="type">
                                <span>Open Text</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Question Text</label>
                        <textarea name="question_text" rows="3"
                            class="w-full border rounded px-3 py-2 @error('question_text') border-red-500 @enderror">{{ old('question_text') }}</textarea>
                        @error('question_text') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Marks</label>
                        <input type="number" name="marks" value="{{ old('marks', 1) }}" min="1"
                            class="w-full border rounded px-3 py-2 @error('marks') border-red-500 @enderror">
                        @error('marks') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- MCQ Options --}}
                    <div x-show="type === 'mcq'" class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Options</label>
                        <p class="text-gray-500 text-sm mb-3">Select the radio button next to the correct answer.</p>

                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center gap-3 mb-2">
                                <input type="radio" name="correct_option" :value="index" :checked="index === 0">
                                <input type="text" :name="'options[' + index + ']'" x-model="options[index]"
                                    placeholder="Option text"
                                    class="flex-1 border rounded px-3 py-2">
                                <button type="button" @click="removeOption(index)"
                                    class="text-red-500 font-bold text-lg"
                                    x-show="options.length > 2">✕</button>
                            </div>
                        </template>
                        @error('correct_option')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        @error('options')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <button type="button" @click="addOption"
                            class="mt-2 text-blue-500 hover:text-blue-700 font-semibold text-sm">
                            + Add Option
                        </button>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Add Question
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
