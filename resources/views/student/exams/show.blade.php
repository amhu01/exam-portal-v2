<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $exam->title }}
            </h2>
            <div id="timer-display" class="font-bold text-lg text-gray-800">
                ⏱ Loading...
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('student.exams.submit', $exam) }}" id="examForm"
                x-data="examTimer({{ $timeRemaining }})">
                @csrf

                @foreach($exam->questions as $question)
                <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-4">
                    <p class="font-semibold mb-1">Q{{ $question->order }}. {{ $question->question_text }}</p>
                    <p class="text-gray-500 text-sm mb-4">{{ $question->marks }} mark(s)</p>

                    @if($question->isMCQ())
                        @foreach($question->options as $option)
                        <label class="flex items-center gap-3 mb-2 cursor-pointer">
                            <input type="radio"
                                name="answers[{{ $question->id }}][option]"
                                value="{{ $option->id }}"
                                {{ isset($answers[$question->id]) && $answers[$question->id]->selected_option_id == $option->id ? 'checked' : '' }}>
                            <span>{{ $option->option_text }}</span>
                        </label>
                        @endforeach
                    @else
                        <textarea
                            name="answers[{{ $question->id }}][text]"
                            rows="4"
                            placeholder="Type your answer here..."
                            class="w-full border rounded px-3 py-2">{{ isset($answers[$question->id]) ? $answers[$question->id]->answer_text : '' }}</textarea>
                    @endif
                </div>
                @endforeach

                <div class="flex gap-3 justify-end">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded"
                        onclick="return confirm('Are you sure you want to submit? You cannot change your answers after submitting.')">
                        Submit Exam
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function examTimer(seconds) {
            return {
                timeLeft: seconds,
                formatted: '',
                init() {
                    this.timeLeft = Math.floor(this.timeLeft);                    
                    this.updateFormatted();
                    this.syncDisplay();

                    const interval = setInterval(() => {
                        this.timeLeft--;
                        this.updateFormatted();
                        this.syncDisplay();

                        if (this.timeLeft <= 0) {
                            clearInterval(interval);
                            document.getElementById('examForm').submit();
                        }
                    }, 1000);
                },
                updateFormatted() {
                    const mins = Math.floor(this.timeLeft / 60);
                    const secs = this.timeLeft % 60;
                    this.formatted = `${mins}:${secs.toString().padStart(2, '0')}`;
                },
                syncDisplay() {
                    const display = document.getElementById('timer-display');
                    if (display) {
                        display.textContent = '⏱ ' + this.formatted;
                        if (this.timeLeft < 60) {
                            display.classList.add('text-red-600');
                            display.classList.remove('text-gray-800');
                        }
                    }
                }
            }
        }    </script>
</x-app-layout>